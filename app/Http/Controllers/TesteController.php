<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;



class TesteController
{
    public function __construct(
    ) {}

   
    public function teste(Request $request)
    {
        dd($request->all());
    }
}
