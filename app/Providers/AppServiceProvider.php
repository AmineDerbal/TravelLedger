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
                $mapping = [
                    'transactions.create' => [
                        'action' => 'create',
                        'subject' => 'Transaction'
                    ],
                    'transactions.edit' => [
                        'action' => 'update',
                        'subject' => 'Transaction'
                    ],
                    'transactions.edit.own' => [
                        'action' => 'update',
                        'subject' => 'Transaction',
                        'conditions' => ['user_id' => $userId]
                    ],
                    'transactions.destroy' => [
                        'action' => 'destroy',
                        'subject' => 'Transaction'
                    ],
                    'transactions.destroy.own' => [
                        'action' => 'destroy',
                        'subject' => 'Transaction',
                        'conditions' => ['user_id' => $userId]
                    ],
                    'ledgers.view' => [
                        'action' => 'view',
                        'subject' => 'Ledger'
                    ],
                    'ledgers.manage' => [
                        'action' => 'manage',
                        'subject' => 'Ledger'
                    ],
                    'users.manage' => [
                        'action' => 'manage',
                        'subject' => 'User'
                    ],

                ];

                return $mapping[$permission->name] ?? [
                    'action' => explode('.', $permission->name)[0],
                    'subject' => explode('.', $permission->name)[1] ?? 'all'
                ];
            });
        });
    }
}
