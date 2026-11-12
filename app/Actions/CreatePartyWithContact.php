<?php

namespace App\Actions;

use AIArmada\Contacting\Actions\CreateContactMethodAction;
use AIArmada\Persons\Models\Person;
use App\Models\Organization;
use App\Models\Party;
use App\Models\PartyRelationship;
use Illuminate\Support\Str;

class CreatePartyWithContact
{
    /**
     * @param  array{name: string, email?: string|null, phone?: string|null, company?: string|null}  $data
     */
    public function handle(array $data): Party
    {
        $name = trim($data['name']);
        $company = trim((string) ($data['company'] ?? ''));
        $displayName = $company !== '' ? $company : $name;

        $person = Person::create([
            'name' => $name,
            'status' => 'active',
        ]);

        $party = Party::create([
            'person_id' => $company !== '' ? null : $person->id,
            'party_type' => $company !== '' ? 'organization' : 'individual',
            'display_name' => $displayName,
            'slug' => $this->uniqueSlug($displayName),
            'status' => 'active',
        ]);

        if ($company !== '') {
            Organization::create([
                'party_id' => $party->id,
                'organization_type' => 'client',
            ]);

            $personParty = Party::create([
                'person_id' => $person->id,
                'party_type' => 'individual',
                'display_name' => $name,
                'slug' => $this->uniqueSlug($name),
                'status' => 'active',
            ]);

            PartyRelationship::create([
                'from_party_id' => $personParty->id,
                'to_party_id' => $party->id,
                'relationship_type' => 'contact_of',
                'start_date' => now()->toDateString(),
            ]);
        }

        $contactAction = app(CreateContactMethodAction::class);

        foreach (['email' => $data['email'] ?? null, 'phone' => $data['phone'] ?? null] as $type => $value) {
            if (is_string($value) && trim($value) !== '') {
                $contactAction->execute($party, [
                    'type' => $type,
                    'purpose' => 'general',
                    'value' => trim($value),
                    'label' => 'Primary',
                    'is_primary' => true,
                    'is_public' => false,
                ]);
            }
        }

        return $party;
    }

    private function uniqueSlug(string $name): string
    {
        $base = Str::slug($name) ?: 'party';
        $slug = $base;
        $suffix = 2;

        while (Party::where('slug', $slug)->exists()) {
            $slug = $base.'-'.$suffix;
            $suffix++;
        }

        return $slug;
    }
}
