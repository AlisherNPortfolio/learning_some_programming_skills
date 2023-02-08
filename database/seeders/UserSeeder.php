<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\Traits\DisableForeignKeys;
use Database\Seeders\Traits\TruncateTable;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    use TruncateTable, DisableForeignKeys;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->disableForeignKeys(); //<== MySQLda
        $this->truncate('users'); // <== shu qator qo'shiladi
        $users = User::factory(10)->create();
        // $this->enableForeignKeys(); //<== MySQLda
    }
}
