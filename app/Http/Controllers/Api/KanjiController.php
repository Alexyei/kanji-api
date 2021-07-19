<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\KanjiResource;
use App\Http\Resources\WordResource;
use App\Models\Kanji;
use App\Models\Word;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Expr\Cast\Object_;

class KanjiController extends Controller
{
    public function index(Request $request)
    {
        $rules = [
//            'chars' => 'required|string|between:1,2000',
            'lang' => 'required|in:ja,zh-hant,zh-hans',
//            'type' => 'required|in:kanji,katakana,hiragana',
//            'minLength' => 'required|numeric|between:1,10',
//            'maxLength' => 'required|numeric|gte:minLength',
//            'count' => 'required|numeric|between:1,100',
        ];
        $messages = [
            'required' => 'Please enter a :attribute.',
            'between' => 'The :attribute value :input is not between :min - :max.',
            'in' => 'The :attribute must be one of the following types: :values',
            'numeric' => 'The :attribute must be an integer',
            'string' => 'The :attribute must be a string',
            'gte' => 'maxLength must be gretter or equal then minLength'
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $messages = $validator->messages();
            $errors = $messages->all();
            //return $this->respondWithError($errors,500);
            return response()->json(['errors' => $errors], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

//dd($request->all());

//        $hiragana = 'あいうええかきくけこさしすせそたちつてとなにぬねのはひふへほまみむめもやゆよらりるれろわゐゑをんがぎぐげござじずぜぞだぢづでどばびぶべぼぱぴぷぺぽ';
//        $katakana = 'アイウエオカキクケコサシスセソタチツテトナニヌネノハヒフヘホマミムメモヤユヨラリルレロワヲンガギグゲゴザジズゼゾダヂヅデドバビブベボパピプペポ';
//        $chars='嫌朝御飯あなた';
//        $words = WordResource::collection(Word::
//        with('tags')
////            ->whereHas('tags', function($q){
////            $q->where('name', '=', 'N5');
////        })
//            ->where('type','=','katakana')
//            ->where('word', 'regexp', '^['.$chars.$hiragana.$katakana.']+$')
//            ->whereRaw('CHAR_LENGTH(word) <= ?', [10])
//            ->whereRaw('CHAR_LENGTH(word) >= ?', [1])
//            ->inRandomOrder()->limit(10)
//            ->orderBy('id','desc')->get());


//        $hiragana = 'あいうええかきくけこさしすせそたちつてとなにぬねのはひふへほまみむめもやゆよらりるれろわゐゑをんがぎぐげござじずぜぞだぢづでどばびぶべぼぱぴぷぺぽ';
//        $katakana = 'アイウエオカキクケコサシスセソタチツテトナニヌネノハヒフヘホマミムメモヤユヨラリルレロワヲンガギグゲゴザジズゼゾダヂヅデドバビブベボパピプペポ';
//        $chars=$request->chars;
        //    if ($request->type == 'kanji')
        //        $chars .= $hiragana.$katakana;
//        $tags = ['N5', 'N4', 'N3', 'N2'];
////        $kanji = [];
////        foreach ($sections as $tagname){
//        $kanji = KanjiResource::collection(Kanji::
//
//        with('tags')
////            ->whereHas('tags', function ($query) use ($tags) {
////                $query->whereIn('name', $tags);
////            })
////                ->groupBy(tags)
//
//            //      ->where('type','=',$request->type)
//            //     ->where('word', 'regexp', '^['.$chars.']+$')
//            //      ->whereRaw('CHAR_LENGTH(word) < ?', [$request->maxLength])
//            //     ->whereRaw('CHAR_LENGTH(word) >= ?', [$request->minLength])
//            //      ->inRandomOrder()->limit($request->count)
//            //->orderBy('id','desc')
//            ->get());
////dd($kanji->getIterator());
//        $results = [];
//        foreach ($tags as $tag) {
//            $results[] = $kanji->filter(function ($value, $key) use ($tag) {
//                    return $value->tags->contains(function ($tvalue, $tkey) use($tag) {
//                        return $tvalue->name == $tag;
//                    });
//                });
//////            $results[] = $kanji->whereHas('tags', function ($q) use($tag) {
//////                $q->where('name','=',$tag);
//////            });
////            $results[] = array_filter($kanji->toArray(), function ($item) use ($tag) {
////                return count(array_filter($item->tags, function ($tag_obg) use ($tag) {
////                    return $tag_obg->name == $tag;
////                })) !== 0;
////            });
//        }
//
//
////        foreach ($kanji as $item) {
////            if (in_array("N5", $item->tags))
////                $results[0][] = $item;
////            elseif (in_array("N4", $item->tags))
////                $results[1][] = $item;
////            elseif (in_array("N3", $item->tags))
////                $results[2][] = $item;
////            elseif (in_array("N2", $item->tags))
////                $results[3][] = $item;
////        }
////        $arr = [];
////        foreach ($words as $word)
////            $arr[] = $word->word;
////        return array_unique($arr);
//
////        }
//        return $kanji;
        $sections = ['N5','N4','N3','N2'];
        $kanji = [];
        foreach ($sections as $tagname){
            $kanji[] = KanjiResource::collection(Cache::rememberForever($tagname, function() use ($tagname) {
                return Kanji::with('tags')
                ->whereHas('tags', function($q) use ($tagname){
                    $q->where('name', '=', $tagname);
                })
                ->get();}));
            }

//        $arr = [];
//            foreach($words as $word)
//                $arr[] = $word->word;
//        return array_unique($arr);

     //   }

        return $kanji;
    }
}
