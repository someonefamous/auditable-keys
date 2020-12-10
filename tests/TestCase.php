<?php

namespace SomeoneFamous\AuditableKeys\Tests;

use SomeoneFamous\AuditableKeys\AuditableKeysServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            AuditableKeysServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        include_once __DIR__ . '/../database/migrations/create_test_models_table.php.stub';
        include_once __DIR__ . '/../database/migrations/create_auditable_values_tables.php.stub';

        (new \CreateTestModelsTable())->up();
        (new \CreateAuditableValuesTables)->up();
    }
}
