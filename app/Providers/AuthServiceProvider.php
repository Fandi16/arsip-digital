<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        // Tidak pakai policy, pakai Gate langsung.
    ];

    public function boot()
    {
        $this->registerPolicies();

        // Hanya admin yang boleh kelola user
        Gate::define('manage-users', function ($user) {
            return $user->role === 'admin';
        });

        // Semua role kecuali admin saja yang boleh manage arsip
        Gate::define('manage-archives', function ($user) {
            return in_array($user->role, ['admin', 'admin_marketing', 'marketing']);
        });
    }
}
