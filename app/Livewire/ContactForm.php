<?php

namespace App\Livewire;

use App\Actions\CreatePartyWithContact;
use App\Models\Enquiry;
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

    public function submit(): void
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

        $party = app(CreatePartyWithContact::class)->handle($data);

        Enquiry::create([
            'requester_party_id' => $party->id,
            'source' => 'contact',
            'subject' => 'Website contact',
            'brief' => $data['message'],
            'status' => 'new',
            'consent_at' => now(),
        ]);

        $this->submitted = true;
    }

    public function render()
    {
        return view('livewire.contact-form')->layout('layouts.app', ['pageTitle' => 'Contact | Dunia Media', 'metaDescription' => 'Tell us what you have in mind. Connect with Dunia Media about media, creative, technology, creators or training.']);
    }
}
