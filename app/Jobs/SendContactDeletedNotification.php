<?php

namespace App\Jobs;

use App\Mail\ContactDeleted;
use App\Models\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendContactDeletedNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private Contact $contact
    ) {}

    public function handle(): void
    {
        Mail::to($this->contact->email)
            ->send(new ContactDeleted($this->contact));
    }
} 