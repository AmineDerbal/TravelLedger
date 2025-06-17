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
        Collection::macro('mapToCasl', function () {
            return $this->map(function ($permission) {
                return match ($permission->name) {
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
                        'conditions' => ['user_id' => $this->id]
                    ],
                    'transactions.destroy' => [
                        'action' => 'delete',
                        'subject' => 'Transaction'
                    ],
                    'transactions.destroy.own' => [
                        'action' => 'delete',
                        'subject' => 'Transaction',
                        'conditions' => ['user_id' => $this->id]
                    ],
                    'ledgers.view' => [
                        'action' => 'view',
                        'subject' => 'Ledger'
                    ],
                    'ledgers.manage' => [
                        'action' => 'manage',
                        'subject' => 'Ledger'
                    ],
                     default => [
                    'action' => explode('.', $permission->name)[0],
                    'subject' => explode('.', $permission->name)[1] ?? 'all'
                ]
                };
            });
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        
    }
}