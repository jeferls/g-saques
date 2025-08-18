<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebhooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('webhooks', function (Blueprint $table) {
            $table->id();
            $table->string('entity')->nullable();
            $table->bigInteger('entity_id')->nullable();
            $table->string('event_type')->nullable();
            $table->string('origin');
            $table->text('url');
            $table->text('raw');
            $table->string('gateway')->nullable();
            $table->enum('status', ['pending', 'sent', 'failed'])->default('pending');
            $table->integer('attempts')->default(5);
            $table->timestamp('last_attempt')->nullable();
            $table->string('response_status', 10)->nullable();
            $table->text('response_raw')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('webhooks');
    }
}
