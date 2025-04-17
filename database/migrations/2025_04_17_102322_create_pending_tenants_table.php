<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDetailsToDomainsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('pending_tenants', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Company name
            $table->string('email');    
            $table->string('location');
            $table->string('domain');
            $table->string('contact_number');
            $table->boolean('approved')->default(false);
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
