<?php

namespace App\Http\Controllers\mocks;

use Illuminate\Http\Request;





class MockTransferController
{
    public function __construct() {}

    public function postTransferPagarme(Request $request, $transferId = null)
    {
        $transfer = [
            "object" => "transfer",
            "id" => $transferId  ?? random_int(1,9999999999),
            "amount" => $request->input('amount') ?? 100,
            "type" => "ted",
            "status" => "pending_transfer",
            "source_type" => "recipient",
            "source_id" => $request->input('recipient_id') ?? 're_1234567891011',
            "target_type" => "bank_account",
            "target_id" => "17346045",
            "fee" => 367,
            "funding_date" => null,
            "funding_estimated_date" => "2017-02-18T02:00:00.000Z",
            "transaction_id" => null,
            "date_created" => "2017-02-17T16:24:20.933Z",
            "bank_account" => [
                "object" => "bank_account",
                "id" => 17346045,
                "bank_code" => "000",
                "agencia" => "00000",
                "agencia_dv" => "2",
                "conta" => "00000",
                "conta_dv" => "00",
                "type" => "conta_corrente",
                "document_type" => "cpf",
                "document_number" => "03602396681",
                "legal_name" => "nome2",
                "charge_transfer_fees" => true,
                "date_created" => "2016-12-27T22:08:10.536Z",
            ],
            "metadata" => $request->input('metadata') ?? [],
        ];

        return $transfer;
    }
}
