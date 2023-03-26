<?php

namespace App\Http\Controllers\Api;

use App\Business\AddressBusiness;
use App\Http\Controllers\Controller;
use App\Http\Requests\Address\ZipcodeFormRequest;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function getAddressFromZipcode(ZipcodeFormRequest $zipcodeFormRequest, AddressBusiness $addressBusiness)
    {
        $data = $zipcodeFormRequest->validated();
        return $addressBusiness->getAddressFromZipcode($data['zipcode']);
    }
}
