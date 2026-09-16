<?php

namespace App\Console\Commands;

use Database\Seeders\InsertRolesCommand;
use Illuminate\Console\Command;

class InsertRoles extends Command
{
    protected $signature = 'insert:roles';
    protected $description = 'Insertar roles y usuarios de prueba';

    public function handle()
    {
        $result = InsertRolesCommand::insert();
        $this->info($result);
    }
}
