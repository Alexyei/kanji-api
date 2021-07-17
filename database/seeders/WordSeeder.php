<?php

namespace Database\Seeders;

use App\Models\Tag;
use App\Models\Word;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class WordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('words')->truncate();
        $files = ["N5.json","N4.json","N3.json","N2.json"];

        foreach ($files as $filename) {
            $json = File::get("database/japan words/" . $filename);
            $data = json_decode($json);
            foreach ($data as $obj){
                $new_word = Word::create([
                    "word" => $obj->word,
                    "translate" => $obj->translate,
                    "lang" => $obj->lang,
                    "type" => $obj->type,
                ]);

                $tags_ids = [];
                foreach ($obj->tags as $tag){
                    $tags_ids[] = Tag::where("name",'=',$tag)->first()->id;
                }
                $new_word->tags()->sync($tags_ids);
                $new_word->save();
            }
        }
    }
}
