<?php
namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;

class TenantService
{
    public function approveTenant($tenant)
    {
        try {
            $databaseName = $this->generateUniqueDatabaseName($tenant->id);
            DB::statement("CREATE DATABASE `$databaseName`");
            $tenant->update(['tenancy_db_name' => $databaseName]);
            
            // Connect to the new tenant database
            DB::statement("USE `$databaseName`");
            
            // Create the employees table
            Schema::create('employees', function ($table) {
                $table->id();
                $table->string('name');
                $table->string('job_title')->nullable();
                $table->string('role');
                $table->string('email')->unique();
                $table->string('phone');
                $table->string('address')->nullable();
                $table->enum('gender', ['Male', 'Female'])->nullable();
                $table->date('birthdate')->nullable();
                $table->date('start_date')->nullable();
                $table->enum('employment_status', ['Active', 'On Leave', 'Resigned'])->default('Active');
                $table->enum('shift', ['Morning', 'Afternoon', 'Night'])->nullable();
                $table->string('photo')->nullable(); // stores path to uploaded image
                $table->timestamps();
            });
            
            // Create the workers table (alias for employees)
            Schema::create('workers', function ($table) {
                $table->id();
                $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
                $table->timestamps();
            });
            
            // Create the customers table
            Schema::create('customers', function ($table) {
                $table->id();
                $table->string('name');
                $table->string('phone')->nullable();
                $table->string('email')->nullable();
                $table->text('address')->nullable();
                $table->timestamps();
            });
            
            // Create the worker_logs table
            Schema::create('worker_logs', function ($table) {
                $table->id();
                $table->foreignId('worker_id')->constrained('workers');
                $table->foreignId('customer_id')->nullable()->constrained('customers');
                $table->string('item_name');
                $table->integer('quantity');
                $table->decimal('weight_kg', 5, 2);
                $table->string('detergent_type');
                $table->decimal('detergent_used', 5, 2);
                $table->enum('detergent_unit', ['g', 'kg']);
                $table->date('washed_at');
                $table->decimal('payment', 8, 2);
                $table->text('remarks')->nullable();
                $table->timestamps();
            });
            
            // Switch back to the central database
            DB::statement("USE " . config('database.connections.mysql.database'));
            
        } catch (\Exception $e) {
            throw new \Exception("Something went wrong during approval! Error: " . $e->getMessage());
        }
    }

    private function generateUniqueDatabaseName($tenantId)
    {
        do {
            $databaseName = 'tenant_' . $tenantId;
            $exists = DB::select("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?", [$databaseName]);
        } while (!empty($exists));

        return $databaseName;
    }
}