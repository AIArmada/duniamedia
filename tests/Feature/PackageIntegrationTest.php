<?php

namespace Tests\Feature;

use AIArmada\Addressing\Models\Address;
use App\Actions\CreatePartyWithContact;
use App\Models\Location;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class PackageIntegrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_party_identity_uses_persons_and_contacting(): void
    {
        $party = app(CreatePartyWithContact::class)->handle([
            'name' => 'Integration Person',
            'email' => 'integration@example.com',
            'phone' => '+60123456789',
        ]);

        $this->assertNotNull($party->person_id);
        $this->assertDatabaseHas('persons', ['name' => 'Integration Person']);
        $this->assertDatabaseHas('contact_methods', [
            'contactable_type' => 'party',
            'contactable_id' => $party->id,
            'type' => 'email',
        ]);
        $this->assertSame('integration@example.com', $party->primaryEmail());
    }

    public function test_location_uses_addressing(): void
    {
        $location = Location::create([
            'location_type' => 'site',
            'name' => 'Integration Site',
            'status' => 'active',
        ]);

        $address = Address::create([
            'label' => 'Kuala Lumpur',
            'city' => 'Kuala Lumpur',
            'country_code' => 'MY',
        ]);

        $location->attachAddress($address, 'primary', true);

        $this->assertDatabaseHas('addressables', [
            'addressable_type' => 'location',
            'addressable_id' => $location->id,
            'address_id' => $address->id,
        ]);
        $this->assertSame($address->id, $location->primaryAddress()?->id);
    }

    public function test_replaced_erd_tables_are_gone(): void
    {
        $this->assertFalse(Schema::hasTable('party_contacts'));
        $this->assertFalse(Schema::hasTable('party_addresses'));
        $this->assertFalse(Schema::hasTable('people'));
        $this->assertFalse(Schema::hasTable('files'));
        $this->assertFalse(Schema::hasTable('portfolio_media'));
    }
}
