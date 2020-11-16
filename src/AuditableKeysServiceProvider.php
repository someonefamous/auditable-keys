<?php

namespace SomeoneFamous\AuditableKeys;

use Illuminate\Support\ServiceProvider;

class AuditableKeysServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            if (!class_exists('SetUpAuditableKeyTables')) {
                $this->publishes([
                    __DIR__ . '/../database/migrations/set_up_auditable_key_tables.php.stub' => database_path(
                        'migrations/' . date('Y_m_d_His') . '_set_up_auditable_key_tables.php'
                    ),
                ], 'migrations');
            }
        }
    }
}
