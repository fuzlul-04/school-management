<?php

namespace App\Providers;

use App\Models\Permission;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->registerGates();
    }

    protected function registerGates(): void
    {
        try {
            $permissions = Permission::where('status', 'active')->pluck('slug')->toArray();

            foreach ($permissions as $permission) {
                Gate::define($permission, function ($user) use ($permission) {
                    return $user->hasPermission($permission);
                });
            }
        } catch (\Exception $e) {
            //
        }
    }
}