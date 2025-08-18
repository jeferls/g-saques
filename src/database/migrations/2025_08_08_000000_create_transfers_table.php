<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transfers', function (Blueprint $table) {
            $table->id();

            $table->string('transfer_id')->unique('uniq_transfer_id')->nullable();
            $table->enum('gateway', ['PAGARME']);
            $table->enum('status', ['created', 'pending_transfer', 'processing', 'transferred', 'failed', 'canceled'])->default('created');
            $table->unsignedBigInteger('amount')->comment('Valor em centavos (ex: 10000 = R$ 100,00)');
            $table->unsignedBigInteger('fee')->nullable();
            $table->string('source_type', 50)->nullable();
            $table->string('source_id')->nullable();
            $table->string('type', 50)->nullable();
            $table->string('target_type', 50)->nullable();
            $table->string('target_id')->nullable();
            $table->string('idempotency_key')->nullable();
            $table->text('reason')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transfers');
    }
};
