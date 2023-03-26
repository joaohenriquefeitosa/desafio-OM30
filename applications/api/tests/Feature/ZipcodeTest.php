<?php

namespace Tests\Feature;

use App\Business\PatientBusiness;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\Patient;
use App\Models\Address;

class ZipcodeTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_get_address_from_zipcode_success()
    {
        $data = [
            'zipcode' => '83065060',
        ];

        $response = $this->getJson(route('zipcode.get', $data));

        $response->assertStatus(200);
    }
}
