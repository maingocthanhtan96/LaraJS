<?php

use Illuminate\Database\Seeder;

class GeneratorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $model = [
            "name" => "User",
            "name_trans" => "User",
            "limit" => 25,
            "options" => ["Soft Deletes"]
        ];

        $fields[] = [
            "id" => 1,
            "field_name" => "id",
            "field_name_trans" => "ID",
            "db_type" => "Increments",
            "enum" => [],
            "default_value" => "None",
            "as_define" => null,
            "search" => false,
            "sort" => true,
            "show" => true
        ];
        $fields[] = [
            "id" => 2,
            "field_name" => "name",
            "field_name_trans" => "Name",
            "db_type" => "VARCHAR",
            "enum" => [],
            "length_varchar" => 191,
            "default_value" => "None",
            "as_define" => null,
            "search" => true,
            "sort" => false,
            "show" => true
        ];
        $fields[] = [
            "id" => 3,
            "field_name" => "email",
            "field_name_trans" => "Email",
            "db_type" => "VARCHAR",
            "enum" => [],
            "length_varchar" => 191,
            "default_value" => "None",
            "as_define" => null,
            "search" => true,
            "sort" => false,
            "show" => true
        ];

        \App\Models\Generator::create([
            'field' => json_encode($fields),
            'model' => json_encode($model),
            'table' => \Str::snake(\Str::plural('user'))
        ]);
    }
}
