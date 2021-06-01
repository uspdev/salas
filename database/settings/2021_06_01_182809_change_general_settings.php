<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

class ChangeGeneralSettings extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->rename('general.color', 'general.cor');
    }
}
