<?php

namespace App\Http\Controllers;

use Domain\Shared\Builders\ServiceGatewayBuilder;
use Illuminate\Http\Request;



class TesteController
{
    public function __construct(
    ) {}

   
    public function teste(Request $request)
    {
        try {
            $service = ServiceGatewayBuilder::fromRequest($request->all())->build();
            $result  = $service->tokenizeCard($request->all());
            return response()->json($result);
        } catch (InvalidArgumentException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

}
