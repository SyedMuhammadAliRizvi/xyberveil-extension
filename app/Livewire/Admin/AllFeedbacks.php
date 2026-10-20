<?php

namespace App\Livewire\Admin;

use App\Models\UserFeedback;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class AllFeedbacks extends Component
{
    use WithPagination;

    public $search = '';

    public $perPage = 5;

    #[Url]
    public $dateFrom = '';

    #[Url]
    public $dateTo = '';

    public $feedbackId;

    public $subject;

    public $email;

    public $message;

    public function updated($value)
    {
        if (in_array($value, ['dateFrom', 'dateTo'])) {
            $this->resetPage();
        }
    }

    public function resetFilters()
    {
        $this->reset([
            'dateFrom',
            'dateTo',
        ]);
    }

    public function viewFeedback($feedbackId)
    {
        $this->feedbackId = $feedbackId;

        $feedback = UserFeedback::findOrFail($feedbackId);
        $this->subject = $feedback->subject;
        $this->email = $feedback->email;
        $this->message = $feedback->message;
    }

    public function closeModel()
    {
        $this->reset([
            'feedbackId',
            'subject',
            'email',
            'message',
        ]);

        $this->dispatch('closeModel');
    }

    public function deleteFeedback($feedbackId)
    {
        $feedback = UserFeedback::findOrFail($feedbackId);
        $feedback->delete();

        $this->dispatch('sweetAlert', title: 'Success!', message: 'Feedback deleted successfully.', type: 'success');
        $this->resetPage();
    }

    public function render()
    {
        $feedbacks = UserFeedback::query()
            ->when($this->search, fn ($query) => $query->where(function ($query) {
                $query->where('subject', 'like', '%'.$this->search.'%')
                    ->orWhere('email', 'like', '%'.$this->search.'%');
            }))
            ->when($this->dateFrom, fn ($query) => $query->whereDate('created_at', '>=', $this->dateFrom))
            ->when($this->dateTo, fn ($query) => $query->whereDate('created_at', '<=', $this->dateTo))
            ->latest()
            ->paginate($this->perPage);

        /** @disregard @phpstan-ignore-line */
        return view('livewire.admin.all-feedbacks', ['feedbacks' => $feedbacks])
            ->extends('layouts.tailadmin')
            ->section('content');
    }
}
