<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Collection;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Collection::macro('mapToCASL', function ($userId = null) {
            return $this->map(function ($permission) use ($userId) {

                $splitPermission = explode(' ', $permission->name);
                \Log::info($splitPermission);
                $action = $splitPermission[0];
                $subject = $splitPermission[1];
                $ownership = $splitPermission[2] ?? null;

                $rule = [
                'action' => $action,
                'subject' => $subject,
            ];

                if ($ownership === 'own' && $userId !== null) {
                    $rule['conditions'] = ['user_id' => $userId];
                }

                return $rule;
            });
        });
    }
}
