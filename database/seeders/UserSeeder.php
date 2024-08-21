<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {   
        Schema::disableForeignKeyConstraints();
        User::truncate();
        Schema::enableForeignKeyConstraints();

        User::insert([
            'first_name'=>'admin',
            'last_name'=>'admin',
            'username'=>'admin',
            'email'=>'admin@g.com',
            'password'=>Hash::make('admin123'),
            'created_at'=>Carbon::now(),
            'updated_at'=>Carbon::now(),
        ]);

        User::factory()->count(50)->create();
    }
}
