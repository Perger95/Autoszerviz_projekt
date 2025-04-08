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
        Schema::create('cars', function (Blueprint $table) {
            $table->id(); // autó id
            $table->unsignedBigInteger('client_id');
            $table->unsignedInteger('car_id'); // ügyfélenként egyedi autóazonosító
            $table->string('type'); // autó típus
            $table->dateTime('registered'); // regisztrálás időpontja
            $table->boolean('ownbrand'); // saját márkás-e
            $table->unsignedInteger('accident'); // balesetek száma
            $table->timestamps();
    
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
