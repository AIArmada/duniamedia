<?php

declare(strict_types=1);

namespace App\Features\Sales\EntryPoints\Livewire;

use App\Features\Identity\Api\ManagesParties;
use App\Features\Sales\Api\RecordsEnquiries;
use App\Shared\Contracts\VerifiesTurnstile;
use Livewire\Attributes\Locked;
use Livewire\Component;

class ContactForm extends Component
{
    public string $name = '';

    public string $email = '';

    public string $phone = '';

    public string $company = '';

    public string $message = '';

    public string $website = '';

    public bool $consent = false;

    public string $turnstileToken = '';

    #[Locked]
    public bool $submitted = false;

    public function submit(ManagesParties $parties, RecordsEnquiries $enquiries, VerifiesTurnstile $turnstile): void
    {
        if ($this->submitted) {
            return;
        }

        $data = $this->validate([
            'name' => 'required|string|max:120',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:40',
            'company' => 'nullable|string|max:160',
            'message' => 'required|string|min:10|max:4000',
            'consent' => 'accepted',
            'website' => 'max:0',
            'turnstileToken' => 'required|string|max:2048',
        ], [
            'turnstileToken.required' => 'Please complete the security check before sending your enquiry.',
            'turnstileToken.max' => 'Please retry the security check.',
        ]);

        $token = $data['turnstileToken'];
        unset($data['turnstileToken']);
        $this->reset('turnstileToken');

        if (! $turnstile->verify($token, 'contact', request()->getHost(), request()->ip())) {
            $this->addError('turnstileToken', 'The security check could not be verified. Please try again.');
            $this->dispatch('contact-turnstile-reset');

            return;
        }

        $party = $parties->createWithContact($data);

        $enquiries->record($party->value, [
            'source' => 'contact',
            'subject' => 'Website contact',
            'brief' => $data['message'],
            'consent_at' => now()->toIso8601String(),
        ]);

        $this->submitted = true;
    }

    public function render()
    {
        return view('livewire.contact-form', [
            'turnstileSiteKey' => app(VerifiesTurnstile::class)->siteKey(),
        ])->layout('layouts.app', ['pageTitle' => 'Contact | Dunia Media', 'metaDescription' => 'Tell us what you have in mind. Connect with Dunia Media about media, creative, technology, creators or training.']);
    }
}
