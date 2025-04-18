<?php
namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TenantService
{
    public function approveTenant($tenant)
    {
        try {
            $databaseName = $this->generateUniqueDatabaseName($tenant->id);
            DB::statement("CREATE DATABASE `$databaseName`");
            $tenant->update(['tenancy_db_name' => $databaseName]);
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