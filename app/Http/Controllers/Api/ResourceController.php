<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PlanResource;
use App\Models\Notification;
use App\Models\Option;
use App\Models\Plan;
use App\Models\UserFeedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ResourceController extends Controller
{
    public function plans()
    {
        $type = request()->query('type', 'regular');

        if (! in_array($type, ['regular', 'dedicated'], true)) {
            return response()->json([
                'status' => false,
                'message' => ['The selected type is invalid.'],
            ], 400);
        }

        $plans = Plan::active()->where('type', $type)->get();

        return response()->json([
            'status' => true,
            'type' => $type,
            'plans' => PlanResource::collection($plans),
        ]);
    }

    public function options()
    {
        $options = [
            'tos' => Option::where('key', 'tos')->first()->value ?? '',
            'privacy_policy' => Option::where('key', 'privacy_policy')->first()->value ?? '',
        ];

        return response()->json([
            'tos' => $options['tos'],
            'privacy_policy' => $options['privacy_policy'],
        ]);
    }

    public function notifications()
    {
        $notifications = Notification::query()
            ->orderByDesc('created_at')
            ->limit(100)
            ->get(['id', 'title', 'message', 'created_at']);

        return response()->json([
            'status' => true,
            'notifications' => $notifications->map(fn (Notification $notification) => [
                'id' => $notification->id,
                'title' => $notification->title,
                'message' => $notification->message,
                'created_at' => $notification->created_at?->toIso8601String(),
            ])->values(),
        ]);
    }

    public function addFeedback(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subject' => 'required|string|max:255',
            'email' => 'required|email',
            'message' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'error' => $validator->errors()->all(),
            ], 400);
        }

        $feedback = UserFeedback::create([
            'subject' => $request->subject,
            'email' => $request->email,
            'message' => $request->message,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Feedback added successfully',
            'feedback' => $feedback,
        ], 201);
    }
}
