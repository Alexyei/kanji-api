<?php

namespace Database\Seeders;

use App\Models\Kanji;
use App\Models\Tag;
use App\Models\Word;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class KanjiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('kanji')->truncate();
        $files = ["N5.json","N4.json","N3.json","N2.json"];

        foreach ($files as $filename) {
            $json = File::get("database/japan kanji/" . $filename);
            $data = json_decode($json);
            foreach ($data as $obj){
                $new_kanji = Kanji::create([
                    "word" => $obj->word,
                    "translate" => $obj->translate,
                    "lang" => $obj->lang,
                ]);

                $tags_ids = [];
                foreach ($obj->tags as $tag){
                    $tags_ids[] = Tag::where("name",'=',$tag)->first()->id;
                }
                $new_kanji->tags()->sync($tags_ids);
                $new_kanji->save();
            }
        }
    }
}
