<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CheckUserRole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:check-role {email?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check user role and fix if needed';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email') ?? 'admin@sekawan.com';
        
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("User with email '{$email}' not found!");
            return 1;
        }
        
        $this->info("User Found:");
        $this->line("  ID: {$user->id}");
        $this->line("  Name: {$user->name}");
        $this->line("  Email: {$user->email}");
        $this->line("  Role: " . ($user->role ?? 'NULL') . " (type: " . gettype($user->role) . ")");
        $this->line("  isAdmin(): " . ($user->isAdmin() ? 'TRUE' : 'FALSE'));
        $this->line("  Role === 'admin': " . (($user->role === 'admin') ? 'TRUE' : 'FALSE'));
        
        if ($user->role !== 'admin') {
            $this->warn("\nUser role is not 'admin'!");
            
            if ($this->confirm('Do you want to set this user\'s role to "admin"?', true)) {
                $user->role = 'admin';
                $user->save();
                $this->info("✓ User role updated to 'admin'");
                $this->line("  isAdmin() now returns: " . ($user->isAdmin() ? 'TRUE' : 'FALSE'));
            }
        } else {
            $this->info("\n✓ User has admin role!");
        }
        
        return 0;
    }
}
