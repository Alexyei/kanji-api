<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\WordResource;
use App\Models\Word;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KanjiController extends Controller
{
    public function index(Request $request){
        $rules=[
            'chars' => 'required|string|between:1,2000',
            'lang' => 'required|in:ja,zh-hant,zh-hans',
//            'type' => 'required|in:kanji,katakana,hiragana',
            'minLength' => 'required|numeric|between:1,10',
            'maxLength' => 'required|numeric|gte:minLength',
            'count' => 'required|numeric|between:1,100',
        ];
        $messages=[
            'required' => 'Please enter a :attribute.',
            'between' => 'The :attribute value :input is not between :min - :max.',
            'in' => 'The :attribute must be one of the following types: :values',
            'numeric' => 'The :attribute must be an integer',
            'string' => 'The :attribute must be a string',
            'gte' => 'maxLength must be gretter or equal then minLength'
        ];
        $validator=Validator::make($request->all(),$rules,$messages);
        if($validator->fails())
        {
            $messages=$validator->messages();
            $errors=$messages->all();
            //return $this->respondWithError($errors,500);
            return response()->json(['errors'=>$errors], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
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


        $hiragana = 'あいうええかきくけこさしすせそたちつてとなにぬねのはひふへほまみむめもやゆよらりるれろわゐゑをんがぎぐげござじずぜぞだぢづでどばびぶべぼぱぴぷぺぽ';
        $katakana = 'アイウエオカキクケコサシスセソタチツテトナニヌネノハヒフヘホマミムメモヤユヨラリルレロワヲンガギグゲゴザジズゼゾダヂヅデドバビブベボパピプペポ';
        $chars=$request->chars;
        if ($request->type == 'kanji')
            $chars .= $hiragana.$katakana;
        $sections = ['N5','N4','N3','N2'];
        $words = [];
        foreach ($sections as $tagname){
            $words[] = WordResource::collection(Word::

            with('tags')
                ->whereHas('tags', function($q) use ($tagname){
                    $q->where('name', '=', $tagname);
                })
//                ->groupBy(tags)

                ->where('type','=',$request->type)
                ->where('word', 'regexp', '^['.$chars.']+$')
                ->whereRaw('CHAR_LENGTH(word) < ?', [$request->maxLength])
                ->whereRaw('CHAR_LENGTH(word) >= ?', [$request->minLength])
                ->inRandomOrder()->limit($request->count)
                //->orderBy('id','desc')
                ->get());

//        $arr = [];
//            foreach($words as $word)
//                $arr[] = $word->word;
//        return array_unique($arr);

        }
        return $words;
    }
}
