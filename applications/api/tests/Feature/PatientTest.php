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

class PatientTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_create_patient_success()
    {
        // Clear the table before the test
        Patient::truncate();

        $data = [
            'name' => $this->faker->name,
            'birth_date' => $this->faker->date,
            'cpf' => $this->generateCPF(),
            'cns' => $this->generateCNS(),
            'mother_name' => $this->faker->name,
            'zipcode' => $this->faker->numerify('########'),
            'address' => $this->faker->address,
            'number' => $this->faker->buildingNumber,
            'complement' => $this->faker->secondaryAddress,
            'neighborhood' => $this->faker->citySuffix,
            'city' => $this->faker->city,
            'state' => $this->faker->stateAbbr,
        ];

        $response = $this->postJson(route('patient.store'), $data);

        $response->assertStatus(200);
    }

    public function test_create_patient_with_error_without_name()
    {
        // Clear the table before the test
        Patient::truncate();

        $data = [
            'birth_date' => $this->faker->date,
            'cpf' => $this->generateCPF(),
            'cns' => $this->generateCNS(),
            'mother_name' => $this->faker->name,
            'zipcode' => $this->faker->numerify('########'),
            'address' => $this->faker->address,
            'number' => $this->faker->buildingNumber,
            'complement' => $this->faker->secondaryAddress,
            'neighborhood' => $this->faker->citySuffix,
            'city' => $this->faker->city,
            'state' => $this->faker->stateAbbr,
        ];

        $response = $this->postJson(route('patient.store'), $data);

        $response->assertStatus(422);
    }

    public function test_create_patient_with_error_without_duplicate_cpf()
    {
        // Clear the table before the test
        Patient::truncate();

        $cpf = '84859219090';
        $data = [
            'name' => $this->faker->name,
            'birth_date' => $this->faker->date,
            'cpf' => $cpf,
            'cns' => $this->generateCNS(),
            'mother_name' => $this->faker->name,
            'zipcode' => $this->faker->numerify('########'),
            'address' => $this->faker->address,
            'number' => $this->faker->buildingNumber,
            'complement' => $this->faker->secondaryAddress,
            'neighborhood' => $this->faker->citySuffix,
            'city' => $this->faker->city,
            'state' => $this->faker->stateAbbr,
        ];

        $patientBusiness = new PatientBusiness();
        $patientBusiness->store($data);

        $response = $this->postJson(route('patient.store'), $data);

        $response->assertStatus(422);
    }

    public function test_update_patient_with_success()
    {
        // Clear the table before the test
        Patient::truncate();

        $data = [
            'name' => $this->faker->name,
            'birth_date' => $this->faker->date,
            'cpf' => $this->generateCPF(),
            'cns' => $this->generateCNS(),
            'mother_name' => $this->faker->name,
            'zipcode' => $this->faker->numerify('########'),
            'address' => $this->faker->address,
            'number' => $this->faker->buildingNumber,
            'complement' => $this->faker->secondaryAddress,
            'neighborhood' => $this->faker->citySuffix,
            'city' => $this->faker->city,
            'state' => $this->faker->stateAbbr,
        ];

        $patientBusiness = new PatientBusiness();
        $responseBusiness = $patientBusiness->store($data);
        $responseBusiness = json_decode($responseBusiness->content(), true);

        $dataToUpdate = [
            'name' => $this->faker->name,
        ];

        $response = $this->putJson(route('patient.update', $responseBusiness['data']['id']), $dataToUpdate);

        $response->assertStatus(200);
    }

    public function test_update_patient_with_error_non_existent()
    {
        // Clear the table before the test
        Patient::truncate();

        $dataToUpdate = [
            'name' => $this->faker->name,
        ];

        $response = $this->putJson(route('patient.update', '98c85983-06da-44e2-8ba3-5c7257cb9d2c'), $dataToUpdate);

        $response->assertStatus(404);
    }

    public function test_show_patient_with_success()
    {
        // Clear the table before the test
        Patient::truncate();

        $data = [
            'name' => $this->faker->name,
            'birth_date' => $this->faker->date,
            'cpf' => $this->generateCPF(),
            'cns' => $this->generateCNS(),
            'mother_name' => $this->faker->name,
            'zipcode' => $this->faker->numerify('########'),
            'address' => $this->faker->address,
            'number' => $this->faker->buildingNumber,
            'complement' => $this->faker->secondaryAddress,
            'neighborhood' => $this->faker->citySuffix,
            'city' => $this->faker->city,
            'state' => $this->faker->stateAbbr,
        ];

        $patientBusiness = new PatientBusiness();
        $responseBusiness = $patientBusiness->store($data);
        $responseBusiness = json_decode($responseBusiness->content(), true);

        $response = $this->get(route('patient.show', $responseBusiness['data']['id']));

        $response->assertStatus(200);
    }

    public function test_show_patient_with_error_non_existent()
    {
        // Clear the table before the test
        Patient::truncate();

        $response = $this->get(route('patient.show', '98c85983-06da-44e2-8ba3-5c7257cb9d2c'));

        $response->assertStatus(404);
    }

    public function test_list_patient_with_success()
    {
        // Clear the table before the test
        Patient::truncate();

        $response = $this->get(route('patient.index'));

        $response->assertStatus(200);
    }

    public function test_delete_patient_with_success()
    {
        // Clear the table before the test
        Patient::truncate();

        $data = [
            'name' => $this->faker->name,
            'birth_date' => $this->faker->date,
            'cpf' => $this->generateCPF(),
            'cns' => $this->generateCNS(),
            'mother_name' => $this->faker->name,
            'zipcode' => $this->faker->numerify('########'),
            'address' => $this->faker->address,
            'number' => $this->faker->buildingNumber,
            'complement' => $this->faker->secondaryAddress,
            'neighborhood' => $this->faker->citySuffix,
            'city' => $this->faker->city,
            'state' => $this->faker->stateAbbr,
        ];

        $patientBusiness = new PatientBusiness();
        $responseBusiness = $patientBusiness->store($data);
        $responseBusiness = json_decode($responseBusiness->content(), true);

        $response = $this->delete(route('patient.destroy', $responseBusiness['data']['id']));

        $response->assertStatus(200);
    }

    public function test_delete_patient_with_error_non_existent()
    {
        // Clear the table before the test
        Patient::truncate();

        $response = $this->delete(route('patient.destroy', '98c85983-06da-44e2-8ba3-5c7257cb9d2c'));

        $response->assertStatus(404);
    }

    private function generateCPF()
    {
        return '33279450047';
    }

    private function generateCNS()
    {
        return '229661101960006';
    }
}
