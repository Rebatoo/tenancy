<?php

namespace App\Console\Commands;

use App\Models\Tenant;
use Illuminate\Console\Command;

class CreateTenant extends Command
{
    protected $signature = 'tenant:create {id} {--domain=}';
    protected $description = 'Create a new tenant with an optional domain';

    public function handle()
    {
        $id = $this->argument('id');
        $domain = $this->option('domain');

        $tenant = new Tenant([
            'id' => $id,
            'data' => [
                'company_name' => $id,
            ],
        ]);

        $tenant->save();

        if ($domain) {
            $tenant->domains()->create([
                'domain' => $domain,
            ]);
        }

        $this->info("Tenant created successfully with ID: {$id}");
        if ($domain) {
            $this->info("Domain added: {$domain}");
        }
    }
} 