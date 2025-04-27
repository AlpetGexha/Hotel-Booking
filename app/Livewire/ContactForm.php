<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Contact;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class ContactForm extends Component
{
    use WithFileUploads;

    #[Rule('required|email:filter')]
    public string $email = '';

    #[Rule('required|min:3|max:100')]
    public string $subject = '';

    #[Rule('required|min:10|max:2000')]
    public string $message = '';

    #[Rule('nullable|array|max:5')]
    public $attachments = [];

    #[Rule('nullable|file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png')]
    public $attachment = [];

    public bool $success = false;

    /**
     * Submit the contact form with rate limiting
     */
    public function submit(): void
    {
        // Apply rate limiting - 5 submissions per hour
        $ipAddress = request()->ip();
        $key = "contact_form_{$ipAddress}";

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $secondsUntilAvailable = RateLimiter::availableIn($key);
            $this->addError('rate_limit', "Too many contact attempts. Please try again in {$secondsUntilAvailable} seconds.");

            return;
        }

        // Validate form data
        $this->validate([
            'email' => 'required|email:filter',
            'subject' => 'required|min:3|max:100',
            'message' => 'required|min:10|max:2000',
            'attachments.*' => 'nullable|file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png',
        ]);

        // Create new contact record
        $contact = Contact::create([
            'email' => $this->email,
            'subject' => $this->subject,
            'message' => $this->message,
            'ip_address' => $ipAddress,
        ]);

        // Handle file uploads if present
        if (! empty($this->attachments)) {
            foreach ($this->attachments as $attachment) {
                $contact->addMedia($attachment->getRealPath())
                    ->usingName($attachment->getClientOriginalName())
                    ->usingFileName($attachment->getClientOriginalName())
                    ->toMediaCollection('attachments');
            }
        }

        // Increment the rate limiter
        RateLimiter::hit($key, 3600); // Keep the rate limit for 1 hour

        // Reset form and show success message
        $this->reset(['email', 'subject', 'message', 'attachments']);
        $this->success = true;
    }

    public function render()
    {
        return view('livewire.contact-form');
    }
}
