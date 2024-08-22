<?php

namespace App\Http\Controllers\Api;

use App\Models\Film;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\FilmResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class FilmApiController extends Controller
{
    public function create(Request $request){
        $validator = Validator::make(request()->all(),[
            'title' => 'required|max:255',
            'description' => 'required|max:1100',
            'director' => 'required|max:255',
            'release_year' => 'required|integer',
            'genre' => 'required|array',
            'genre.*' => 'string',
            'price'=>'required|integer',
            'duration'=>'required|integer',
            'video' => 'required',
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 'error',
                'message' => 'gagal post film',
                'data' => null,
            ]);
        }
        
        $videoFileName = $this->saveVideo($request);
        $request['video_url'] = env('APP_URL').$videoFileName;

        $cover = null;
        if($request->cover_image){
            $coverFileName = $this->saveCover($request);
            $cover = env('APP_URL').$coverFileName;
        }
        $request['cover_image_url'] = $cover;
        $request['genres'] = implode(',', $request->genre);
        $film = Film::create($request->except(['video', 'cover_image','genre']));
        return response()->json([
            'status' => 'success',
            'message' => 'film created',
            'data' => new FilmResource($film),
        ]);
    }

    public function getFilms(Request $request){
        if ((!$request->has('q'))||$request->query('q')=="") {
            $films = Film::all();
            return response()->json([
                'status' => 'success',
                'message' => 'berhasil get film',
                'data' => FilmResource::collection($films),
            ]); 
        }

        $title = $request->query('q');
        $director = $request->query('q');
        $film = Film::where('title','LIKE', "%{$title}%")->orWhere('director','LIKE', "%{$director}%")->get();
        if ($film) {
            return response()->json([
                'status' => 'success',
                'message' => 'berhasil get film',
                'data' => FilmResource::collection($film),
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'gagal get film',
            'data' => null,
        ], 404);
    }

    public function getFilmById($id){
        if ($film = Film::find($id)) {
            $filmArray = $film -> toArray();
            $filmArray['genre'] = explode(',', $filmArray['genres']);
            unset($filmArray['genres']);
            return response()->json([
                'status' => 'success',
                'message' => 'film found',
                'data' => $filmArray,
            ]);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Film not found',
            'data' => null,
        ], 404);
    }

    public function update(Request $request,$id){
        $validator = Validator::make(request()->all(),[
            'title' => 'required|max:255',
            'description' => 'required|max:1100',
            'director' => 'required|max:255',
            'release_year' => 'required|integer',
            'genre' => 'required|array',
            'genre.*' => 'string',
            'price'=>'required|integer',
            'duration'=>'required|integer',
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 'error',
                'message' => 'gagal put film',
                'data' => null,
            ]);
        }

        if ($film = Film::find($id)){
            $video_url = $film -> video_url;
            $cover_url = $film -> cover_image_url;
            if($request->video){
                $videoFileName = $this->saveVideo($request);
                $video_url = env('APP_URL').$videoFileName;
            }
            if($request->cover_image){
                $coverFileName = $this->saveCover($request);
                $cover_url = env('APP_URL').$coverFileName;
            }
            $request['video_url'] = $video_url;
            $request['cover_image_url'] = $cover_url;
            $request['genres'] = implode(',', $request->genre);
            
            $film->update($request->except(['video', 'cover_image','genre']));
            $filmArray = $film -> toArray();
            $filmArray['genre'] = explode(',', $filmArray['genres']);
            unset($filmArray['genres']);

            return response()->json([
                'status' => 'success',
                'message' => 'film updated',
                'data' => $filmArray,
            ]);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Film not found',
            'data' => null,
        ], 404);
    }

    public function delete($id){
        if ($film = Film::find($id)) {
            $filmArray = $film -> toArray();
            $filmArray['genre'] = explode(',', $filmArray['genres']);
            unset($filmArray['genres']);
            $film->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Film deleted successfully',
                'data' => $filmArray,
            ]);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Film not found',
            'data' => null,
        ], 404);
    }

    public function saveVideo($request)
    {
        $videoName = $this->generateRandomString();
        $extvideo = $request->video->extension();
        $videoFileName = '/uploads/'.$videoName.'.'.$extvideo;
        $videoFile = $request->video;
        $videoFile -> move('uploads',$videoFileName);

        return $videoFileName;
    }

    public function saveCover($request)
    {
        $coverName = $this->generateRandomString();
        $extcover = $request->cover_image->extension();
        $cover = '/uploads/'.$coverName.'.'.$extcover;
        $coverFile = $request->cover_image;
        $coverFile -> move('uploads',$cover);
        return $cover;
    }

    function generateRandomString($length = 40) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';

    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[random_int(0, $charactersLength - 1)];
    }

    return $randomString;
}
}
