<?php

namespace App\Business;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class AddressBusiness
{
    private $urlService;
    private $retryWaitDefault;
    private $retryAttempsDefault;
    private $timeoutDefault;

    public function __construct()
    {
        $this->urlService = 'https://viacep.com.br/ws/%s/json';
        $this->retryWaitDefault = 30000;
        $this->retryAttempsDefault = 3;
        $this->timeoutDefault = 5;
    }

    public function getAddressFromZipcode($zipcode): JsonResponse
    {
        $uri = sprintf($this->urlService, $zipcode);

        try{
            $response = \Http::timeout($this->timeoutDefault)
                ->retry($this->retryAttempsDefault, $this->retryWaitDefault, function ($exception) {
                    return $exception instanceof ConnectionException;
                })
                ->get($uri);

            return response()->json(['status' => true, 'data' => $response->json()]);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Error getting the zipcode.'], status: Response::HTTP_NOT_FOUND);
        }
    }
}
