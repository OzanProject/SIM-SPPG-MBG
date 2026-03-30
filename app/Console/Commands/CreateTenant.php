<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tenant;

class CreateTenant extends Command
{
    protected $signature = 'tenant:create {name} {domain?}';
    protected $description = 'Create a new tenant with a domain';

    public function handle()
    {
        $name = $this->argument('name');
        $domain = $this->argument('domain') ?? strtolower($name) . '.app.test';

        $this->info("Creating tenant: {$name}...");

        $tenant = Tenant::create([
            'id' => strtolower($name)
        ]);
        
        $tenant->domains()->create([
            'domain' => $domain
        ]);

        $this->info("Tenant {$name} created successfully with domain {$domain}.");
        $this->info("Database and schema have been automatically set up and seeded.");
    }
}
