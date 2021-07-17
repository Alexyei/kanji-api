<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('word_tag')->truncate();
        DB::table('tags')->truncate();

        $tags = ["N5","N4","N3","N2","N1"];
        foreach ($tags as $value) {
            Tag::create(["name"=>$value]);
        }
    }
}
