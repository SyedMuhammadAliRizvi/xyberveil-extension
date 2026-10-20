<?php

namespace App\Listeners;

use App\Events\AppStoreWebhookReceived;
use App\Jobs\SendBillingFailedEmailJob;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Readdle\AppStoreServerAPI\RenewalInfo;
use Readdle\AppStoreServerAPI\TransactionInfo;

class HandleAppStoreWebhook
{
    public $logger;

    public function __construct()
    {
        $this->logger = Log::channel('subscriptions');
    }

    /**
     * Handle the event.
     */
    public function handle(AppStoreWebhookReceived $event): void
    {
        $this->logger->info('App Store Webhook Processing', [
            'notification_type' => $event->notificationType,
            'subtype' => $event->subtype,
            'notification_uuid' => $event->appleNotification->notification_uuid,
        ]);

        $notificationType = $event->notificationType;
        $subtype = $event->subtype;
        $transactionInfo = $event->transactionInfo;
        $renewalInfo = $event->renewalInfo;

        match ($notificationType) {
            'SUBSCRIBED' => $this->handleSubscribed($transactionInfo, $renewalInfo, $subtype),
            'DID_RENEW' => $this->handleDidRenew($transactionInfo, $renewalInfo, $subtype),
            'DID_CHANGE_RENEWAL_PREF' => $this->handleDidChangeRenewalPref($transactionInfo, $renewalInfo, $subtype),
            'DID_CHANGE_RENEWAL_STATUS' => $this->handleDidChangeRenewalStatus($transactionInfo, $renewalInfo, $subtype),
            'DID_FAIL_TO_RENEW' => $this->handleDidFailToRenew($transactionInfo, $renewalInfo, $subtype),
            'EXPIRED' => $this->handleExpired($transactionInfo, $renewalInfo, $subtype),
            'REFUND' => $this->handleRefund($transactionInfo, $renewalInfo, $subtype),
            'GRACE_PERIOD_EXPIRED' => $this->handleGracePeriodExpired($transactionInfo, $renewalInfo, $subtype),
            'OFFER_REDEEMED' => $this->handleOfferRedeemed($transactionInfo, $renewalInfo, $subtype),
            'REFUND_DECLINED' => $this->handleRefundDeclined($transactionInfo, $renewalInfo, $subtype),
            'REFUND_REVERSED' => $this->handleRefundReversed($transactionInfo, $renewalInfo, $subtype),
            'CONSUMPTION_REQUEST' => $this->handleConsumptionRequest($transactionInfo, $renewalInfo, $subtype),
            'RENEWAL_EXTENDED' => $this->handleRenewalExtended($transactionInfo, $renewalInfo, $subtype),
            'REVOKE' => $this->handleRevoke($transactionInfo, $renewalInfo, $subtype),
            'TEST' => $this->handleTest($transactionInfo, $renewalInfo, $subtype),
            default => $this->logger->info('App Store: Unhandled notification type', [
                'type' => $notificationType,
                'subtype' => $subtype,
            ]),
        };

        // Mark notification as processed
        $event->appleNotification->update([
            'processed' => true,
            'processed_at' => now(),
        ]);
    }

    /**
     * Handle SUBSCRIBED notifications (INITIAL_BUY, RESUBSCRIBE)
     */
    protected function handleSubscribed(?TransactionInfo $transactionInfo, ?RenewalInfo $renewalInfo, ?string $subtype): void
    {
        if (! $transactionInfo) {
            $this->logger->warning('App Store: SUBSCRIBED - No transaction info provided');

            return;
        }

        $user = $this->findUserByAppAccountToken($transactionInfo->getAppAccountToken());
        if (! $user) {
            $this->logger->warning('App Store: SUBSCRIBED - No user found', [
                'app_account_token' => $transactionInfo->getAppAccountToken(),
            ]);

            return;
        }

        $plan = $this->findPlanByProductId($transactionInfo->getProductId());
        if (! $plan) {
            $this->logger->warning('App Store: SUBSCRIBED - No plan found', [
                'product_id' => $transactionInfo->getProductId(),
            ]);

            return;
        }

        $originalTransactionId = $transactionInfo->getOriginalTransactionId();
        $expiresAt = $this->convertAppleTimestamp($transactionInfo->getExpiresDate());
        $purchaseDate = $this->convertAppleTimestamp($transactionInfo->getPurchaseDate());
        $graceEndsAt = $plan->getGraceEndsAt($expiresAt);

        // Determine initial status based on offer type
        $initialStatus = $this->determineSubscriptionStatus($transactionInfo, $renewalInfo);
        if ($transactionInfo->getOfferDiscountType() === 'FREE_TRIAL') {
            $initialStatus = 'trialing';
        }

        if ($subtype === 'INITIAL_BUY') {
            // Check if subscription with this original transaction ID already exists
            $existingSubscription = $this->findLocalSubscription($user, $originalTransactionId);

            if ($existingSubscription) {
                // Update existing subscription
                $existingSubscription->update([
                    'plan_id' => $plan->id,
                    'starts_at' => $purchaseDate,
                    'ends_at' => $expiresAt,
                    'grace_ends_at' => $graceEndsAt,
                    'amount_paid' => $transactionInfo->getPrice() ?
                        $this->convertMilliunitsToAmount(
                            $transactionInfo->getPrice(),
                            $transactionInfo->getCurrency() ?? 'USD'
                        ) : null,
                    'currency' => $transactionInfo->getCurrency() ?? 'USD',
                    'status' => $initialStatus,
                    'is_recurring' => $this->isAutoRenewEnabled($renewalInfo),
                    'cancelled_by' => null,
                    'cancelled_reason' => null,
                    'appstore_transaction_id' => $transactionInfo->getTransactionId(),
                    'appstore_product_id' => $transactionInfo->getProductId(),
                ]);

                $this->logger->info('App Store: Existing subscription updated for INITIAL_BUY', [
                    'user_id' => $user->id,
                    'plan_id' => $plan->id,
                    'subscription_id' => $existingSubscription->id,
                    'original_transaction_id' => $originalTransactionId,
                    'status' => $initialStatus,
                    'offer_discount_type' => $transactionInfo->getOfferDiscountType(),
                ]);

                return;
            } else {
                // Cancel all previous subscriptions and create new one
                $user->cancelSubscription(null, 'system', 'App Store: New subscription created', true);
            }
        } elseif ($subtype === 'RESUBSCRIBE') {
            // User resubscribes after subscription expired
            $existingSubscription = $this->findLocalSubscription($user, $originalTransactionId);

            if ($existingSubscription) {
                // Update the existing expired subscription
                $existingSubscription->update([
                    'plan_id' => $plan->id,
                    'starts_at' => $purchaseDate,
                    'ends_at' => $expiresAt,
                    'grace_ends_at' => $graceEndsAt,
                    'amount_paid' => $transactionInfo->getPrice() ? $transactionInfo->getPrice() / 1000000 : 0,
                    'currency' => $transactionInfo->getCurrency() ?? 'USD',
                    'status' => $initialStatus,
                    'is_recurring' => $this->isAutoRenewEnabled($renewalInfo),
                    'cancelled_by' => null,
                    'cancelled_reason' => null,
                    'appstore_transaction_id' => $transactionInfo->getTransactionId(),
                    'appstore_product_id' => $transactionInfo->getProductId(),
                ]);

                $this->logger->info('App Store: Existing subscription reactivated for RESUBSCRIBE', [
                    'user_id' => $user->id,
                    'plan_id' => $plan->id,
                    'subscription_id' => $existingSubscription->id,
                    'original_transaction_id' => $originalTransactionId,
                    'status' => $initialStatus,
                    'offer_discount_type' => $transactionInfo->getOfferDiscountType(),
                ]);

                return;
            }
            // If no existing subscription found, create new one (fallback)
        }

        // Create new subscription
        $subscription = $user->localSubscriptions()->create([
            'plan_id' => $plan->id,
            'starts_at' => $purchaseDate,
            'trial_ends_at' => $transactionInfo->getOfferDiscountType() === 'FREE_TRIAL' ? $expiresAt : null,
            'ends_at' => $expiresAt,
            'grace_ends_at' => $graceEndsAt,
            'amount_paid' => $transactionInfo->getPrice() ? $transactionInfo->getPrice() / 1000000 : 0, // Convert from micros
            'currency' => $transactionInfo->getCurrency() ?? 'USD',
            'status' => $initialStatus,
            'is_recurring' => $this->isAutoRenewEnabled($renewalInfo),
            'provider' => 'appstore',
            'proration_meta' => null,
            'cancelled_by' => null,
            'cancelled_reason' => null,
            'appstore_original_transaction_id' => $originalTransactionId,
            'appstore_transaction_id' => $transactionInfo->getTransactionId(),
            'appstore_product_id' => $transactionInfo->getProductId(),
        ]);

        $this->logger->info('App Store: New subscription created', [
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'subscription_id' => $subscription->id,
            'subtype' => $subtype,
            'original_transaction_id' => $originalTransactionId,
            'price' => $transactionInfo->getPrice() ? $transactionInfo->getPrice() / 1000000 : 0,
            'status' => $initialStatus,
            'offer_discount_type' => $transactionInfo->getOfferDiscountType(),
            'is_free_trial' => $transactionInfo->getOfferDiscountType() === 'FREE_TRIAL',
        ]);
    }

    /**
     * Handle DID_RENEW notifications (auto-renewal, BILLING_RECOVERY)
     */
    protected function handleDidRenew(?TransactionInfo $transactionInfo, ?RenewalInfo $renewalInfo, ?string $subtype): void
    {
        if (! $transactionInfo) {
            $this->logger->warning('App Store: DID_RENEW - No transaction info provided');

            return;
        }

        $user = $this->findUserByAppAccountToken($transactionInfo->getAppAccountToken());
        if (! $user) {
            $this->logger->warning('App Store: DID_RENEW - No user found', [
                'app_account_token' => $transactionInfo->getAppAccountToken(),
            ]);

            return;
        }

        $subscription = $this->findLocalSubscription($user, $transactionInfo->getOriginalTransactionId());
        if (! $subscription) {
            $this->logger->warning('App Store: DID_RENEW - No local subscription found', [
                'user_id' => $user->id,
                'original_transaction_id' => $transactionInfo->getOriginalTransactionId(),
            ]);

            return;
        }

        $expiresAt = $this->convertAppleTimestamp($transactionInfo->getExpiresDate());

        $updateData = [
            'status' => 'active',
            'ends_at' => $expiresAt,
            'grace_ends_at' => $subscription->plan->getGraceEndsAt($expiresAt),
            'appstore_transaction_id' => $transactionInfo->getTransactionId(),
            'is_recurring' => $this->isAutoRenewEnabled($renewalInfo),
            'amount_paid' => $transactionInfo->getPrice() ?
                $this->convertMilliunitsToAmount(
                    $transactionInfo->getPrice(),
                    $transactionInfo->getCurrency() ?? 'USD'
                ) : null,
            'currency' => $transactionInfo->getCurrency() ?? 'USD',
        ];

        // If this was a trial conversion, clear trial_ends_at
        if ($subscription->status === 'trialing') {
            $updateData['trial_ends_at'] = null;
        }

        if ($subtype === 'BILLING_RECOVERY') {
            // Billing recovery: clear cancellation info and grace period status
            $updateData['cancelled_by'] = null;
            $updateData['cancelled_reason'] = null;
            $updateData['grace_ends_at'] = null;

            $this->logger->info('App Store: Subscription recovered from billing failure', [
                'user_id' => $user->id,
                'subscription_id' => $subscription->id,
                'previous_status' => $subscription->status,
                'expires_at' => $expiresAt,
                'amount_paid' => $transactionInfo->getPrice() ?
                    $this->convertMilliunitsToAmount(
                        $transactionInfo->getPrice(),
                        $transactionInfo->getCurrency() ?? 'USD'
                    ) : null,
                'currency' => $transactionInfo->getCurrency() ?? 'USD',
            ]);
        } else {
            // Normal auto-renewal (including trial conversion)
            $logMessage = $subscription->status === 'trialing'
                ? 'App Store: Trial converted to active subscription'
                : 'App Store: Subscription auto-renewed';

            $this->logger->info($logMessage, [
                'user_id' => $user->id,
                'subscription_id' => $subscription->id,
                'previous_status' => $subscription->status,
                'expires_at' => $expiresAt,
                'trial_conversion' => $subscription->status === 'trialing',
                'amount_paid' => $transactionInfo->getPrice() ?
                    $this->convertMilliunitsToAmount(
                        $transactionInfo->getPrice(),
                        $transactionInfo->getCurrency() ?? 'USD'
                    ) : null,
                'currency' => $transactionInfo->getCurrency() ?? 'USD',
            ]);
        }

        $subscription->update($updateData);
    }

    /**
     * Handle DID_CHANGE_RENEWAL_PREF notifications (UPGRADE, DOWNGRADE, revert)
     */
    protected function handleDidChangeRenewalPref(?TransactionInfo $transactionInfo, ?RenewalInfo $renewalInfo, ?string $subtype): void
    {
        if (! $transactionInfo || ! $renewalInfo) {
            $this->logger->warning('App Store: DID_CHANGE_RENEWAL_PREF - Missing transaction or renewal info');

            return;
        }

        $user = $this->findUserByAppAccountToken($transactionInfo->getAppAccountToken());
        if (! $user) {
            $this->logger->warning('App Store: DID_CHANGE_RENEWAL_PREF - No user found', [
                'app_account_token' => $transactionInfo->getAppAccountToken(),
            ]);

            return;
        }

        $subscription = $this->findLocalSubscription($user, $transactionInfo->getOriginalTransactionId());
        if (! $subscription) {
            $this->logger->warning('App Store: DID_CHANGE_RENEWAL_PREF - No local subscription found', [
                'user_id' => $user->id,
                'original_transaction_id' => $transactionInfo->getOriginalTransactionId(),
            ]);

            return;
        }

        if ($subtype === 'UPGRADE') {
            // Upgrade takes effect immediately, starting new billing period
            $newPlan = $this->findPlanByProductId($renewalInfo->getAutoRenewProductId());
            if ($newPlan) {
                $expiresAt = $this->convertAppleTimestamp($transactionInfo->getExpiresDate());

                $subscription->update([
                    'plan_id' => $newPlan->id,
                    'status' => 'active',
                    'ends_at' => $expiresAt,
                    'grace_ends_at' => $newPlan->getGraceEndsAt($expiresAt),
                    'appstore_product_id' => $renewalInfo->getAutoRenewProductId(),
                    'appstore_transaction_id' => $transactionInfo->getTransactionId(),
                    'amount_paid' => $transactionInfo->getPrice() ?
                        $this->convertMilliunitsToAmount(
                            $transactionInfo->getPrice(),
                            $transactionInfo->getCurrency() ?? 'USD'
                        ) : null,
                ]);

                $this->logger->info('App Store: Subscription upgraded immediately', [
                    'user_id' => $user->id,
                    'subscription_id' => $subscription->id,
                    'old_plan_id' => $subscription->plan_id,
                    'new_plan_id' => $newPlan->id,
                    'expires_at' => $expiresAt,
                ]);
            } else {
                $this->logger->warning('App Store: DID_CHANGE_RENEWAL_PREF UPGRADE - New plan not found', [
                    'product_id' => $renewalInfo->getAutoRenewProductId(),
                ]);
            }
        } elseif ($subtype === 'DOWNGRADE') {
            // Downgrade takes effect at next renewal - just log for now
            // The actual change will happen when the subscription renews with the new product
            $newProduct = $renewalInfo->getAutoRenewProductId();

            $this->logger->info('App Store: Subscription downgrade scheduled for next renewal', [
                'user_id' => $user->id,
                'subscription_id' => $subscription->id,
                'current_product_id' => $subscription->appstore_product_id,
                'scheduled_product_id' => $newProduct,
                'next_renewal' => $subscription->ends_at,
            ]);

            // Note: We don't update the subscription immediately for downgrades
            // The change will be applied when DID_RENEW is received with the new product
        } else {
            // No subtype means user reverted to current subscription (canceled downgrade)
            $this->logger->info('App Store: Subscription preference reverted (downgrade cancelled)', [
                'user_id' => $user->id,
                'subscription_id' => $subscription->id,
                'current_product_id' => $subscription->appstore_product_id,
            ]);

            // No action needed - user is staying with current subscription
        }
    }

    /**
     * Handle DID_CHANGE_RENEWAL_STATUS notifications (AUTO_RENEW_ENABLED, AUTO_RENEW_DISABLED)
     */
    protected function handleDidChangeRenewalStatus(?TransactionInfo $transactionInfo, ?RenewalInfo $renewalInfo, ?string $subtype): void
    {
        if (! $transactionInfo || ! $renewalInfo) {
            $this->logger->warning('App Store: DID_CHANGE_RENEWAL_STATUS - Missing transaction or renewal info');

            return;
        }

        $user = $this->findUserByAppAccountToken($transactionInfo->getAppAccountToken());
        if (! $user) {
            $this->logger->warning('App Store: DID_CHANGE_RENEWAL_STATUS - No user found', [
                'app_account_token' => $transactionInfo->getAppAccountToken(),
            ]);

            return;
        }

        $subscription = $this->findLocalSubscription($user, $transactionInfo->getOriginalTransactionId());
        if (! $subscription) {
            $this->logger->warning('App Store: DID_CHANGE_RENEWAL_STATUS - No local subscription found', [
                'user_id' => $user->id,
                'original_transaction_id' => $transactionInfo->getOriginalTransactionId(),
            ]);

            return;
        }

        $isAutoRenewEnabled = $this->isAutoRenewEnabled($renewalInfo);

        $updateData = [
            'is_recurring' => $isAutoRenewEnabled,
        ];

        if ($subtype === 'AUTO_RENEW_DISABLED') {
            // Customer turned off auto-renewal or App Store turned it off after refund
            $updateData['cancelled_by'] = 'user';
            $updateData['cancelled_reason'] = 'Auto-renewal disabled by user from App Store';
            // Keep subscription active until current period ends
            $updateData['status'] = 'cancelled'; // Will expire at ends_at

            $this->logger->info('App Store: Auto-renewal disabled', [
                'user_id' => $user->id,
                'subscription_id' => $subscription->id,
                'expires_at' => $subscription->ends_at,
            ]);
        } elseif ($subtype === 'AUTO_RENEW_ENABLED') {
            // Customer re-enabled auto-renewal
            $updateData['cancelled_by'] = null;
            $updateData['cancelled_reason'] = null;
            // Reactivate subscription if it was previously cancelled
            if ($subscription->status === 'cancelled' && now()->lt($subscription->ends_at)) {
                $updateData['status'] = 'active';
            }

            $this->logger->info('App Store: Auto-renewal re-enabled', [
                'user_id' => $user->id,
                'subscription_id' => $subscription->id,
                'current_status' => $subscription->status,
                'expires_at' => $subscription->ends_at,
            ]);
        } else {
            // No subtype - general renewal status change
            $this->logger->info('App Store: Renewal status changed', [
                'user_id' => $user->id,
                'subscription_id' => $subscription->id,
                'auto_renew_enabled' => $isAutoRenewEnabled,
            ]);
        }

        $subscription->update($updateData);
    }

    /**
     * Handle DID_FAIL_TO_RENEW notifications (with optional GRACE_PERIOD)
     */
    protected function handleDidFailToRenew(?TransactionInfo $transactionInfo, ?RenewalInfo $renewalInfo, ?string $subtype): void
    {
        if (! $transactionInfo) {
            $this->logger->warning('App Store: DID_FAIL_TO_RENEW - No transaction info provided');

            return;
        }

        $user = $this->findUserByAppAccountToken($transactionInfo->getAppAccountToken());
        if (! $user) {
            $this->logger->warning('App Store: DID_FAIL_TO_RENEW - No user found', [
                'app_account_token' => $transactionInfo->getAppAccountToken(),
            ]);

            return;
        }

        $subscription = $this->findLocalSubscription($user, $transactionInfo->getOriginalTransactionId());
        if (! $subscription) {
            $this->logger->warning('App Store: DID_FAIL_TO_RENEW - No local subscription found', [
                'user_id' => $user->id,
                'original_transaction_id' => $transactionInfo->getOriginalTransactionId(),
            ]);

            return;
        }

        // Initialize variables for email dispatch
        $hasGracePeriod = false;
        $graceEndsAt = null;
        $emailMessage = 'Payment failed - please update your payment method';

        if ($subtype === 'GRACE_PERIOD') {
            if ($renewalInfo->getGracePeriodExpiresDate()) {
                $graceEndsAt = $this->convertAppleTimestamp($renewalInfo->getGracePeriodExpiresDate());
            } elseif ($subscription->grace_ends_at) {
                $graceEndsAt = $subscription->grace_ends_at;
            } else {
                // Calculate grace period from plan settings using current ends_at as base
                $graceEndsAt = $subscription->plan->getGraceEndsAt($subscription->ends_at);
            }

            $updateData = [
                'status' => 'grace',
                'grace_ends_at' => $graceEndsAt,
                'cancelled_by' => 'system',
                'cancelled_reason' => 'Billing failed - entered grace period, service continues',
                'is_recurring' => true,
            ];

            $hasGracePeriod = true;
            $emailMessage = 'Payment failed - service continues during grace period';

            $this->logger->info('App Store: Subscription entered grace period', [
                'user_id' => $user->id,
                'subscription_id' => $subscription->id,
                'grace_ends_at' => $graceEndsAt,
                'grace_source' => $renewalInfo->getGracePeriodExpiresDate() ? 'apple_renewal_info' : ($subscription->grace_ends_at ? 'existing_subscription' : 'plan_calculation'),
            ]);
        } else {
            // No grace period - but maintain access until current period ends
            $updateData = [
                'status' => 'cancelled',
                'cancelled_by' => 'system',
                'cancelled_reason' => 'Billing failed - in retry period, please update payment method',
                'is_recurring' => false,
            ];

            $this->logger->info('App Store: Subscription failed renewal without grace period', [
                'user_id' => $user->id,
                'subscription_id' => $subscription->id,
                'access_continues_until' => $subscription->ends_at,
                'billing_retry_period' => '60 days',
                'action_needed' => 'Send billing failure notification to user',
            ]);
        }

        $subscription->update($updateData);

        // Dispatch email job with appropriate details
        SendBillingFailedEmailJob::dispatch(
            $user,
            $subscription,
            $emailMessage,
            $hasGracePeriod,
            $graceEndsAt
        );
    }

    /**
     * Handle EXPIRED notifications (VOLUNTARY, BILLING_RETRY, PRODUCT_NOT_FOR_SALE, PRICE_INCREASE)
     */
    protected function handleExpired(?TransactionInfo $transactionInfo, ?RenewalInfo $renewalInfo, ?string $subtype): void
    {
        if (! $transactionInfo) {
            $this->logger->warning('App Store: EXPIRED - No transaction info provided');

            return;
        }

        $user = $this->findUserByAppAccountToken($transactionInfo->getAppAccountToken());
        if (! $user) {
            $this->logger->warning('App Store: EXPIRED - No user found', [
                'app_account_token' => $transactionInfo->getAppAccountToken(),
            ]);

            return;
        }

        $subscription = $this->findLocalSubscription($user, $transactionInfo->getOriginalTransactionId());
        if (! $subscription) {
            $this->logger->warning('App Store: EXPIRED - No local subscription found', [
                'user_id' => $user->id,
                'original_transaction_id' => $transactionInfo->getOriginalTransactionId(),
            ]);

            return;
        }

        $expirationReason = match ($subtype) {
            'VOLUNTARY' => 'User voluntarily cancelled subscription',
            'BILLING_RETRY' => 'Billing retry period ended without successful payment',
            'PRODUCT_NOT_FOR_SALE' => 'Product no longer available for purchase',
            'PRICE_INCREASE' => 'Customer did not consent to price increase',
            default => 'Subscription expired for unknown reason',
        };

        $cancelledBy = match ($subtype) {
            'VOLUNTARY' => 'user',
            'PRICE_INCREASE' => 'user', // User chose not to consent
            default => 'system',
        };

        $expiresAt = $this->convertAppleTimestamp($transactionInfo->getExpiresDate());

        $subscription->update([
            'status' => 'expired',
            'ends_at' => $expiresAt ?: now(), // Use actual expiration timestamp or now as fallback
            'cancelled_by' => $cancelledBy,
            'cancelled_reason' => $expirationReason,
            'is_recurring' => false,
        ]);

        $this->logger->info('App Store: Subscription expired', [
            'user_id' => $user->id,
            'subscription_id' => $subscription->id,
            'subtype' => $subtype,
            'reason' => $expirationReason,
            'cancelled_by' => $cancelledBy,
            'expires_at' => $expiresAt,
        ]);
    }

    /**
     * Handle REFUND notifications
     */
    protected function handleRefund(?TransactionInfo $transactionInfo, ?RenewalInfo $renewalInfo, ?string $subtype): void
    {
        if (! $transactionInfo) {
            $this->logger->warning('App Store: REFUND - No transaction info provided');

            return;
        }

        $user = $this->findUserByAppAccountToken($transactionInfo->getAppAccountToken());
        if (! $user) {
            $this->logger->warning('App Store: REFUND - No user found', [
                'app_account_token' => $transactionInfo->getAppAccountToken(),
            ]);

            return;
        }

        $subscription = $this->findLocalSubscription($user, $transactionInfo->getOriginalTransactionId());
        if (! $subscription) {
            $this->logger->warning('App Store: REFUND - No local subscription found', [
                'user_id' => $user->id,
                'original_transaction_id' => $transactionInfo->getOriginalTransactionId(),
            ]);

            return;
        }

        $revocationDate = $this->convertAppleTimestamp($transactionInfo->getRevocationDate());
        $revocationReason = $transactionInfo->getRevocationReason();

        // Map Apple's revocation reasons to human-readable text
        $reasonText = match ($revocationReason) {
            0 => 'No specific reason provided',
            1 => 'Customer cancelled for other reason',
            default => "Apple refund reason code: {$revocationReason}",
        };

        $subscription->update([
            'status' => 'cancelled',
            'ends_at' => $revocationDate ?: now(),
            'cancelled_by' => 'system',
            'cancelled_reason' => "Apple refund - {$reasonText}",
            'is_recurring' => false,
        ]);

        $this->logger->info('App Store: Subscription refunded and cancelled', [
            'user_id' => $user->id,
            'subscription_id' => $subscription->id,
            'revocation_date' => $revocationDate,
            'revocation_reason' => $revocationReason,
            'reason_text' => $reasonText,
        ]);
    }

    /**
     * Handle GRACE_PERIOD_EXPIRED notifications
     */
    protected function handleGracePeriodExpired(?TransactionInfo $transactionInfo, ?RenewalInfo $renewalInfo, ?string $subtype): void
    {
        if (! $transactionInfo) {
            $this->logger->warning('App Store: GRACE_PERIOD_EXPIRED - No transaction info provided');

            return;
        }

        $user = $this->findUserByAppAccountToken($transactionInfo->getAppAccountToken());
        if (! $user) {
            $this->logger->warning('App Store: GRACE_PERIOD_EXPIRED - No user found', [
                'app_account_token' => $transactionInfo->getAppAccountToken(),
            ]);

            return;
        }

        $subscription = $this->findLocalSubscription($user, $transactionInfo->getOriginalTransactionId());
        if (! $subscription) {
            $this->logger->warning('App Store: GRACE_PERIOD_EXPIRED - No local subscription found', [
                'user_id' => $user->id,
                'original_transaction_id' => $transactionInfo->getOriginalTransactionId(),
            ]);

            return;
        }

        // Grace period expired - turn off access to service
        // Apple continues billing retry for up to 60 days
        $subscription->update([
            'status' => 'expired',
            'ends_at' => now(), // Cut off access immediately
            'cancelled_by' => 'system',
            'cancelled_reason' => 'Grace period expired - billing retry continues but access revoked',
            'is_recurring' => false, // Mark as non-recurring since grace period ended
        ]);

        $this->logger->info('App Store: Grace period expired, access revoked', [
            'user_id' => $user->id,
            'subscription_id' => $subscription->id,
            'access_cut_off_at' => now(),
            'note' => 'Apple continues billing retry for up to 60 days',
        ]);
    }

    /**
     * Handle OFFER_REDEEMED notifications (promotional offers, upgrade/downgrade offers)
     */
    protected function handleOfferRedeemed(?TransactionInfo $transactionInfo, ?RenewalInfo $renewalInfo, ?string $subtype): void
    {
        if (! $transactionInfo) {
            $this->logger->warning('App Store: OFFER_REDEEMED - No transaction info provided');

            return;
        }

        $user = $this->findUserByAppAccountToken($transactionInfo->getAppAccountToken());
        if (! $user) {
            $this->logger->warning('App Store: OFFER_REDEEMED - No user found', [
                'app_account_token' => $transactionInfo->getAppAccountToken(),
            ]);

            return;
        }

        $this->logger->info('App Store: Offer redeemed', [
            'user_id' => $user->id,
            'subtype' => $subtype,
            'product_id' => $transactionInfo->getProductId(),
            'original_transaction_id' => $transactionInfo->getOriginalTransactionId(),
        ]);

        // Handle based on subtype
        if ($subtype === 'UPGRADE') {
            // Treat as immediate upgrade
            $this->handleDidChangeRenewalPref($transactionInfo, $renewalInfo, 'UPGRADE');
        } elseif ($subtype === 'DOWNGRADE') {
            // Treat as scheduled downgrade
            $this->handleDidChangeRenewalPref($transactionInfo, $renewalInfo, 'DOWNGRADE');
        } else {
            // Regular offer redemption for current subscription - just log
            $this->logger->info('App Store: Regular promotional offer redeemed for active subscription', [
                'user_id' => $user->id,
                'product_id' => $transactionInfo->getProductId(),
            ]);
        }
    }

    /**
     * Handle REFUND_DECLINED notifications
     */
    protected function handleRefundDeclined(?TransactionInfo $transactionInfo, ?RenewalInfo $renewalInfo, ?string $subtype): void
    {
        if (! $transactionInfo) {
            $this->logger->warning('App Store: REFUND_DECLINED - No transaction info provided');

            return;
        }

        $user = $this->findUserByAppAccountToken($transactionInfo->getAppAccountToken());
        if (! $user) {
            $this->logger->warning('App Store: REFUND_DECLINED - No user found', [
                'app_account_token' => $transactionInfo->getAppAccountToken(),
            ]);

            return;
        }

        $this->logger->info('App Store: Refund request declined', [
            'user_id' => $user->id,
            'product_id' => $transactionInfo->getProductId(),
            'original_transaction_id' => $transactionInfo->getOriginalTransactionId(),
        ]);
    }

    /**
     * Handle REFUND_REVERSED notifications
     */
    protected function handleRefundReversed(?TransactionInfo $transactionInfo, ?RenewalInfo $renewalInfo, ?string $subtype): void
    {
        if (! $transactionInfo) {
            $this->logger->warning('App Store: REFUND_REVERSED - No transaction info provided');

            return;
        }

        $user = $this->findUserByAppAccountToken($transactionInfo->getAppAccountToken());
        if (! $user) {
            $this->logger->warning('App Store: REFUND_REVERSED - No user found', [
                'app_account_token' => $transactionInfo->getAppAccountToken(),
            ]);

            return;
        }

        $subscription = $this->findLocalSubscription($user, $transactionInfo->getOriginalTransactionId());
        if ($subscription && $subscription->status === 'cancelled') {
            // Reinstate the subscription if it was cancelled due to refund
            $expiresAt = $this->convertAppleTimestamp($transactionInfo->getExpiresDate());

            $subscription->update([
                'status' => 'active',
                'ends_at' => $expiresAt ?: $subscription->ends_at,
                'cancelled_by' => null,
                'cancelled_reason' => null,
                'is_recurring' => $this->isAutoRenewEnabled($renewalInfo),
            ]);

            $this->logger->info('App Store: Subscription reinstated after refund reversal', [
                'user_id' => $user->id,
                'subscription_id' => $subscription->id,
                'expires_at' => $expiresAt,
            ]);
        } else {
            $this->logger->info('App Store: Refund reversed but no applicable subscription found', [
                'user_id' => $user->id,
                'original_transaction_id' => $transactionInfo->getOriginalTransactionId(),
            ]);
        }
    }

    /**
     * Handle CONSUMPTION_REQUEST notifications
     */
    protected function handleConsumptionRequest(?TransactionInfo $transactionInfo, ?RenewalInfo $renewalInfo, ?string $subtype): void
    {
        if (! $transactionInfo) {
            $this->logger->warning('App Store: CONSUMPTION_REQUEST - No transaction info provided');

            return;
        }

        $user = $this->findUserByAppAccountToken($transactionInfo->getAppAccountToken());
        if (! $user) {
            $this->logger->warning('App Store: CONSUMPTION_REQUEST - No user found', [
                'app_account_token' => $transactionInfo->getAppAccountToken(),
            ]);

            return;
        }

        $this->logger->info('App Store: Consumption data requested for refund', [
            'user_id' => $user->id,
            'product_id' => $transactionInfo->getProductId(),
            'original_transaction_id' => $transactionInfo->getOriginalTransactionId(),
            'note' => 'You should provide consumption data to Apple using the App Store Server API',
        ]);
    }

    /**
     * Handle RENEWAL_EXTENDED notifications
     */
    protected function handleRenewalExtended(?TransactionInfo $transactionInfo, ?RenewalInfo $renewalInfo, ?string $subtype): void
    {
        if (! $transactionInfo) {
            $this->logger->warning('App Store: RENEWAL_EXTENDED - No transaction info provided');

            return;
        }

        $user = $this->findUserByAppAccountToken($transactionInfo->getAppAccountToken());
        if (! $user) {
            $this->logger->warning('App Store: RENEWAL_EXTENDED - No user found', [
                'app_account_token' => $transactionInfo->getAppAccountToken(),
            ]);

            return;
        }

        $subscription = $this->findLocalSubscription($user, $transactionInfo->getOriginalTransactionId());
        if ($subscription) {
            $newExpiresAt = $this->convertAppleTimestamp($transactionInfo->getExpiresDate());

            $subscription->update([
                'ends_at' => $newExpiresAt,
                'grace_ends_at' => $subscription->plan->getGraceEndsAt($newExpiresAt),
            ]);

            $this->logger->info('App Store: Subscription renewal date extended', [
                'user_id' => $user->id,
                'subscription_id' => $subscription->id,
                'new_expires_at' => $newExpiresAt,
            ]);
        }
    }

    /**
     * Handle REVOKE notifications (Family Sharing revoked)
     */
    protected function handleRevoke(?TransactionInfo $transactionInfo, ?RenewalInfo $renewalInfo, ?string $subtype): void
    {
        if (! $transactionInfo) {
            $this->logger->warning('App Store: REVOKE - No transaction info provided');

            return;
        }

        $user = $this->findUserByAppAccountToken($transactionInfo->getAppAccountToken());
        if (! $user) {
            $this->logger->warning('App Store: REVOKE - No user found', [
                'app_account_token' => $transactionInfo->getAppAccountToken(),
            ]);

            return;
        }

        $subscription = $this->findLocalSubscription($user, $transactionInfo->getOriginalTransactionId());
        if ($subscription) {
            $subscription->update([
                'status' => 'cancelled',
                'ends_at' => now(),
                'cancelled_by' => 'system',
                'cancelled_reason' => 'Family Sharing access revoked',
                'is_recurring' => false,
            ]);

            $this->logger->info('App Store: Family Sharing access revoked', [
                'user_id' => $user->id,
                'subscription_id' => $subscription->id,
            ]);
        }
    }

    /**
     * Handle TEST notifications
     */
    protected function handleTest(?TransactionInfo $transactionInfo, ?RenewalInfo $renewalInfo, ?string $subtype): void
    {
        $this->logger->info('App Store: Test notification received', [
            'subtype' => $subtype,
            'timestamp' => now(),
        ]);
    }

    /**
     * Find user by app account token
     */
    protected function findUserByAppAccountToken(?string $appAccountToken): ?User
    {
        if (! $appAccountToken) {
            return null;
        }

        return User::where('app_account_token', $appAccountToken)->first();
    }

    /**
     * Find plan by App Store product ID
     */
    protected function findPlanByProductId(?string $productId): ?Plan
    {
        if (! $productId) {
            return null;
        }

        return Plan::where('appstore_product_id', $productId)->first();
    }

    /**
     * Find local subscription by user and original transaction ID
     */
    protected function findLocalSubscription(User $user, ?string $originalTransactionId)
    {
        if (! $originalTransactionId) {
            return null;
        }

        return $user->localSubscriptions()
            ->where('appstore_original_transaction_id', $originalTransactionId)
            ->first();
    }

    /**
     * Convert Apple timestamp (milliseconds) to Carbon instance
     */
    protected function convertAppleTimestamp($timestamp): ?Carbon
    {
        if (! $timestamp) {
            return null;
        }

        try {
            // Apple timestamps are in milliseconds
            return Carbon::createFromTimestampMs($timestamp);
        } catch (\Exception $e) {
            Log::error('Failed to convert Apple timestamp', [
                'timestamp' => $timestamp,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Check if auto-renewal is enabled
     */
    protected function isAutoRenewEnabled(?RenewalInfo $renewalInfo): bool
    {
        if (! $renewalInfo) {
            return false;
        }

        return $renewalInfo->getAutoRenewStatus() === 1;
    }

    /**
     * Determine subscription status based on transaction and renewal info
     */
    protected function determineSubscriptionStatus(?TransactionInfo $transactionInfo, ?RenewalInfo $renewalInfo): string
    {
        if (! $transactionInfo) {
            return 'failed';
        }

        $now = now();
        $expiresAt = $this->convertAppleTimestamp($transactionInfo->getExpiresDate());

        if (! $expiresAt) {
            return 'active'; // Non-expiring purchase
        }

        if ($expiresAt->isPast()) {
            return 'expired';
        }

        return 'active';
    }

    /**
     * Convert Apple milliunits to proper currency amount
     */
    private function convertMilliunitsToAmount(int $milliunits, string $currency): float|int
    {
        // Complete list of zero-decimal currencies (minor unit = 0)
        $zeroDecimalCurrencies = [
            'BIF',
            'BYR',
            'CLF',
            'DJF',
            'GNF',
            'ISK',
            'JPY',
            'KMF',
            'KRW',
            'PYG',
            'RWF',
            'UGX',
            'UYI',
            'VND',
            'VUV',
            'XAF',
            'XOF',
            'XPF',
        ];

        $amount = $milliunits / 1000000;

        if (in_array($currency, $zeroDecimalCurrencies)) {
            return (int) round($amount); // Return whole number
        }

        return round($amount, 2); // Return with 2 decimal places
    }
}
