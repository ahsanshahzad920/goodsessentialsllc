<?php

namespace App\Jobs;

use App\Mail\AdminContactFormMail;
use App\Mail\ContactFormMail;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendContactFormMail implements ShouldQueue
{
    use Queueable;

    protected $data;

    /**
     * Create a new job instance.
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        
        Mail::to($this->data['email'])->send(new ContactFormMail($this->data));
        $superAdmins = User::role('admin')->get();
        foreach ($superAdmins as $admin) {
            Mail::to($admin->email)->send(new AdminContactFormMail($this->data));
        }
    }
}
