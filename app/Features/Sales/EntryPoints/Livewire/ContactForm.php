<?php

declare(strict_types=1);

namespace App\Features\Sales\EntryPoints\Livewire;

use App\Features\Identity\Api\ManagesParties;
use App\Features\Sales\Api\RecordsEnquiries;
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

    public bool $submitted = false;

    public function submit(ManagesParties $parties, RecordsEnquiries $enquiries): void
    {
        $data = $this->validate([
            'name' => 'required|string|max:120',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:40',
            'company' => 'nullable|string|max:160',
            'message' => 'required|string|min:10|max:4000',
            'consent' => 'accepted',
            'website' => 'max:0',
        ]);

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
        return view('livewire.contact-form')->layout('layouts.app', ['pageTitle' => 'Contact | Dunia Media', 'metaDescription' => 'Tell us what you have in mind. Connect with Dunia Media about media, creative, technology, creators or training.']);
    }
}
