namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TenantService
{
    // ...existing code...

    public function approveTenant($tenant)
    {
        try {
            // Generate a unique database name
            $databaseName = $this->generateUniqueDatabaseName($tenant->id);

            // Create the database
            DB::statement("CREATE DATABASE `$databaseName`");

            // Update tenant record with the database name
            $tenant->update([
                'tenancy_db_name' => $databaseName,
            ]);

        } catch (\Exception $e) {
            // Handle errors
            throw new \Exception("Something went wrong during approval! Error: " . $e->getMessage());
        }
    }

    private function generateUniqueDatabaseName($tenantId)
    {
        do {
            $databaseName = 'tenant_' . $tenantId . '_' . Str::random(5);
            $exists = DB::select("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?", [$databaseName]);
        } while (!empty($exists));

        return $databaseName;
    }

    /**
     * Generate a unique database name for the tenant.
     *
     * @param string $baseName
     * @return string
     */
    public function generateUniqueDbName(string $baseName): string
    {
        $dbName = 'tenant_' . $baseName;
        $counter = 1;

        while (DB::table('tenants')->where('tenancy_db_name', $dbName)->exists()) {
            $dbName = 'tenant_' . $baseName . '_' . $counter;
            $counter++;
        }

        return $dbName;
    }

    /**
     * Register a new tenant.
     *
     * @param array $tenantData
     * @return void
     */
    public function registerTenant(array $tenantData): void
    {
        $tenantData['tenancy_db_name'] = $this->generateUniqueDbName($tenantData['company_name']);

        DB::table('tenants')->insert([
            'id' => $tenantData['id'],
            'data' => json_encode($tenantData),
            'updated_at' => now(),
            'created_at' => now(),
        ]);
    }
}