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
        Schema::create('pending_tenants', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Company name
            $table->string('email')->unique();    
            $table->string('location');
            $table->string('domain')->unique();
            $table->string('contact_number');
            $table->boolean('approved')->default(false);
            $table->string('database_name')->nullable(); // New column for database name
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pending_tenants');
    }
};
