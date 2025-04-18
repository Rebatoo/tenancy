<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('role');
            $table->string('email')->unique();
            $table->string('phone');
            $table->text('address');
            $table->enum('gender', ['Male', 'Female', 'Other'])->nullable();
            $table->date('birthdate')->nullable();
            $table->date('start_date');
            $table->enum('status', ['Active', 'On Leave', 'Resigned'])->default('Active');
            $table->enum('shift', ['Morning', 'Evening', 'Night', 'Custom'])->default('Morning');
            $table->string('photo')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
}; 