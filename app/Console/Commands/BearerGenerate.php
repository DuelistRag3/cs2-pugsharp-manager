<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class BearerGenerate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bearer:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a new bearer token for API authentication';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $token = bin2hex(random_bytes(32));
        $this->info("Generated Bearer Token: {$token}");

        // Add Bearer token to .env file or replace existing one
        $envPath = base_path('.env');
        if (file_exists($envPath)) {
            $envContent = file_get_contents($envPath);
            if (preg_match('/^API_BEARER_TOKEN=.*$/m', $envContent)) {
                // Replace the existing line
                $envContent = preg_replace('/^API_BEARER_TOKEN=.*$/m', "API_BEARER_TOKEN={$token}", $envContent);
            } else {
                // Append the new token
                $envContent .= "\nAPI_BEARER_TOKEN={$token}\n";
            }
            file_put_contents($envPath, $envContent);
            $this->info('Bearer token saved to .env file.');
        } else {
            $this->error('.env file not found. Please create one or ensure it exists in the root directory of your application.');
        }
    }
}
