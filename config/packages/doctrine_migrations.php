<?php

use Symfony\Config\DoctrineMigrationsConfig;

return static function (DoctrineMigrationsConfig $doctrineMigrationsConfig): void {
    $doctrineMigrationsConfig->migrationsPath('DoctrineMigrations', '%kernel.project_dir%/migrations');
};