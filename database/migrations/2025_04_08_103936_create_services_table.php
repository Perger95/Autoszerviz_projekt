<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->unsignedInteger('car_id');
            $table->unsignedInteger('lognumber'); // bejegyzés sorszáma (azonos ügyfél-autóhoz)
            $table->string('event'); // esemény leírása
            $table->dateTime('eventtime')->nullable(); 
            $table->unsignedBigInteger('document_id');
            $table->timestamps();

            // Idegen kulcs kapcsolat (csak a client_id-ra biztosan tudjuk megadni)
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->foreign('car_id')->references('id')->on('cars')->onDelete('cascade'); // car_id idegen kulcs kapcsolódás
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
