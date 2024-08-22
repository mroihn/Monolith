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
/**
 * @OA\Post(
 *     path="/films",
 *     summary="Upload a new film",
 *     tags={"Film"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             @OA\Schema(
 *                 type="object",
 *                 required={"title", "description", "director", "release_year", "genre", "price", "duration", "video"},
 *                 @OA\Property(property="title", type="string", example="Inception", description="Title of the film"),
 *                 @OA\Property(property="description", type="string", example="A mind-bending thriller", description="Description of the film"),
 *                 @OA\Property(property="director", type="string", example="Christopher Nolan", description="Director of the film"),
 *                 @OA\Property(property="release_year", type="integer", example=2010, description="Release year of the film"),
 *                 @OA\Property(property="genre", type="array", @OA\Items(type="string"), example={"Sci-Fi", "Thriller"}, description="Genres of the film"),
 *                 @OA\Property(property="price", type="number", format="float", example=19.99, description="Price of the film"),
 *                 @OA\Property(property="duration", type="integer", example=8880, description="Duration of the film in seconds"),
 *                 @OA\Property(property="video", type="string", format="binary", description="Video file of the film"),
 *                 @OA\Property(property="cover_image", type="string", format="binary", nullable=true, description="Cover image file of the film")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successfully uploaded the film",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="success"),
 *             @OA\Property(property="message", type="string", example="Film uploaded successfully"),
 *             @OA\Property(property="data", type="object",
 *                 @OA\Property(property="id", type="string", example="1"),
 *                 @OA\Property(property="title", type="string", example="Inception"),
 *                 @OA\Property(property="description", type="string", example="A mind-bending thriller"),
 *                 @OA\Property(property="director", type="string", example="Christopher Nolan"),
 *                 @OA\Property(property="release_year", type="integer", example=2010),
 *                 @OA\Property(property="genre", type="array", @OA\Items(type="string"), example={"Sci-Fi", "Thriller"}),
 *                 @OA\Property(property="price", type="number", format="float", example=19.99),
 *                 @OA\Property(property="duration", type="integer", example=8880),
 *                 @OA\Property(property="video_url", type="string", example="http://example.com/videos/inception.mp4"),
 *                 @OA\Property(property="cover_image_url", type="string", nullable=true, example="http://example.com/images/inception.jpg"),
 *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-08-22T00:00:00Z"),
 *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-08-22T00:00:00Z")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid input data",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="error"),
 *             @OA\Property(property="message", type="string", example="Invalid input data"),
 *             @OA\Property(property="data", type="null", example=null)
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="error"),
 *             @OA\Property(property="message", type="string", example="Unauthorized"),
 *             @OA\Property(property="data", type="null", example=null)
 *         )
 *     ),
 *     security={{"bearerAuth": {}}}
 * )
 */
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
                'message' => 'Invalid input data',
                'data' => null,
            ], 400);
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
            'message' => 'Successfully uploaded the film',
            'data' => new FilmResource($film),
        ]);
    }

/**
 * @OA\Get(
 *     path="/films",
 *     summary="Get a list of films",
 *     tags={"Film"},
 *     @OA\Parameter(
 *         name="q",
 *         in="query",
 *         required=false,
 *         description="Search filter for title and director",
 *         @OA\Schema(
 *             type="string",
 *             example="Inception"
 *         )
 *     ),
 *     @OA\RequestBody(
 *         required=false
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successfully retrieved list of films",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="success"),
 *             @OA\Property(property="message", type="string", example="Successfully retrieved films"),
 *             @OA\Property(property="data", type="array", @OA\Items(
 *                 @OA\Property(property="id", type="string", example="1"),
 *                 @OA\Property(property="title", type="string", example="Inception"),
 *                 @OA\Property(property="director", type="string", example="Christopher Nolan"),
 *                 @OA\Property(property="release_year", type="integer", example=2010),
 *                 @OA\Property(property="genre", type="array", @OA\Items(type="string"), example={"Sci-Fi", "Thriller"}),
 *                 @OA\Property(property="price", type="number", format="float", example=19.99),
 *                 @OA\Property(property="duration", type="integer", example=8880),
 *                 @OA\Property(property="cover_image_url", type="string", nullable=true, example="http://example.com/images/inception.jpg"),
 *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-08-22T00:00:00Z"),
 *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-08-22T00:00:00Z")
 *             ), nullable=true),
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="error"),
 *             @OA\Property(property="message", type="string", example="Unauthorized"),
 *             @OA\Property(property="data", type="null", example=null)
 *         )
 *     ),
 *     security={{"bearerAuth": {}}}
 * )
 */

    public function getFilms(Request $request){
        if ((!$request->has('q'))||$request->query('q')=="") {
            $films = Film::all();
            return response()->json([
                'status' => 'success',
                'message' => 'Successfully retrieved films',
                'data' => FilmResource::collection($films),
            ]); 
        }

        $title = $request->query('q');
        $director = $request->query('q');
        $film = Film::where('title','LIKE', "%{$title}%")->orWhere('director','LIKE', "%{$director}%")->get();
        if ($film) {
            return response()->json([
                'status' => 'success',
                'message' => 'bSuccessfully retrieved films',
                'data' => FilmResource::collection($film),
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'gagal get film',
            'data' => null,
        ], 404);
    }

/**
 * @OA\Get(
 *     path="/films/{id}",
 *     summary="Get details of a specific film",
 *     tags={"Film"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the film to retrieve",
 *         @OA\Schema(
 *             type="string",
 *             example="1"
 *         )
 *     ),
 *     @OA\RequestBody(
 *         required=false
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successfully retrieved film details",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="success"),
 *             @OA\Property(property="message", type="string", example="Successfully retrieved film details"),
 *             @OA\Property(property="data", type="object",
 *                 @OA\Property(property="id", type="string", example="1"),
 *                 @OA\Property(property="title", type="string", example="Inception"),
 *                 @OA\Property(property="description", type="string", example="A mind-bending thriller"),
 *                 @OA\Property(property="director", type="string", example="Christopher Nolan"),
 *                 @OA\Property(property="release_year", type="integer", example=2010),
 *                 @OA\Property(property="genre", type="array", @OA\Items(type="string"), example={"Sci-Fi", "Thriller"}),
 *                 @OA\Property(property="price", type="number", format="float", example=19.99),
 *                 @OA\Property(property="duration", type="integer", example=8880),
 *                 @OA\Property(property="video_url", type="string", example="http://example.com/videos/inception.mp4"),
 *                 @OA\Property(property="cover_image_url", type="string", nullable=true, example="http://example.com/images/inception.jpg"),
 *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-08-22T00:00:00Z"),
 *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-08-22T00:00:00Z")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Film not found",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="error"),
 *             @OA\Property(property="message", type="string", example="Film not found"),
 *             @OA\Property(property="data", type="null", example=null)
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="error"),
 *             @OA\Property(property="message", type="string", example="Unauthorized"),
 *             @OA\Property(property="data", type="null", example=null)
 *         )
 *     ),
 *     security={{"bearerAuth": {}}}
 * )
 */

    public function getFilmById($id){
        if ($film = Film::find($id)) {
            $filmArray = $film -> toArray();
            $filmArray['genre'] = explode(',', $filmArray['genres']);
            unset($filmArray['genres']);
            return response()->json([
                'status' => 'success',
                'message' => 'Successfully retrieved film details',
                'data' => $filmArray,
            ]);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Film not found',
            'data' => null,
        ], 404);
    }

/**
 * @OA\Put(
 *     path="/films/{id}",
 *     summary="Update details of a specific film",
 *     tags={"Film"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the film to update",
 *         @OA\Schema(
 *             type="string",
 *             example="1"
 *         )
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             @OA\Schema(
 *                 type="object",
 *                 @OA\Property(property="title", type="string", example="Inception"),
 *                 @OA\Property(property="description", type="string", example="A mind-bending thriller"),
 *                 @OA\Property(property="director", type="string", example="Christopher Nolan"),
 *                 @OA\Property(property="release_year", type="integer", example=2010),
 *                 @OA\Property(property="genre", type="array", @OA\Items(type="string"), example={"Sci-Fi", "Thriller"}),
 *                 @OA\Property(property="price", type="number", format="float", example=19.99),
 *                 @OA\Property(property="duration", type="integer", example=8880),
 *                 @OA\Property(property="video", type="string", format="binary", nullable=true),
 *                 @OA\Property(property="cover_image", type="string", format="binary", nullable=true)
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successfully updated film details",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="success"),
 *             @OA\Property(property="message", type="string", example="Successfully updated film details"),
 *             @OA\Property(property="data", type="object",
 *                 @OA\Property(property="id", type="string", example="1"),
 *                 @OA\Property(property="title", type="string", example="Inception"),
 *                 @OA\Property(property="description", type="string", example="A mind-bending thriller"),
 *                 @OA\Property(property="director", type="string", example="Christopher Nolan"),
 *                 @OA\Property(property="release_year", type="integer", example=2010),
 *                 @OA\Property(property="genre", type="array", @OA\Items(type="string"), example={"Sci-Fi", "Thriller"}),
 *                 @OA\Property(property="price", type="number", format="float", example=19.99),
 *                 @OA\Property(property="duration", type="integer", example=8880),
 *                 @OA\Property(property="video_url", type="string", example="http://example.com/videos/inception.mp4"),
 *                 @OA\Property(property="cover_image_url", type="string", nullable=true, example="http://example.com/images/inception.jpg"),
 *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-08-22T00:00:00Z"),
 *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-08-22T00:00:00Z")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Film not found",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="error"),
 *             @OA\Property(property="message", type="string", example="Film not found"),
 *             @OA\Property(property="data", type="null", example=null)
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="error"),
 *             @OA\Property(property="message", type="string", example="Unauthorized"),
 *             @OA\Property(property="data", type="null", example=null)
 *         )
 *     ),
 *     security={{"bearerAuth": {}}}
 * )
 */

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
                'message' => 'Successfully updated film details',
                'data' => $filmArray,
            ]);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Film not found',
            'data' => null,
        ], 404);
    }

/**
 * @OA\Delete(
 *     path="/films/{id}",
 *     summary="Delete a specific film",
 *     tags={"Film"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the film to delete",
 *         @OA\Schema(
 *             type="string",
 *             example="1"
 *         )
 *     ),
 *     @OA\RequestBody(
 *         required=false
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successfully deleted film",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="success"),
 *             @OA\Property(property="message", type="string", example="Successfully deleted film"),
 *             @OA\Property(property="data", type="object",
 *                 @OA\Property(property="id", type="string", example="1"),
 *                 @OA\Property(property="title", type="string", example="Inception"),
 *                 @OA\Property(property="description", type="string", example="A mind-bending thriller"),
 *                 @OA\Property(property="director", type="string", example="Christopher Nolan"),
 *                 @OA\Property(property="release_year", type="integer", example=2010),
 *                 @OA\Property(property="genre", type="array", @OA\Items(type="string"), example={"Sci-Fi", "Thriller"}),
 *                 @OA\Property(property="video_url", type="string", example="http://example.com/videos/inception.mp4"),
 *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-08-22T00:00:00Z"),
 *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-08-22T00:00:00Z")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Film not found",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="error"),
 *             @OA\Property(property="message", type="string", example="Film not found"),
 *             @OA\Property(property="data", type="null", example=null)
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="error"),
 *             @OA\Property(property="message", type="string", example="Unauthorized"),
 *             @OA\Property(property="data", type="null", example=null)
 *         )
 *     ),
 *     security={{"bearerAuth": {}}}
 * )
 */

    public function delete($id){
        if ($film = Film::find($id)) {
            $filmArray = $film -> toArray();
            $filmArray['genre'] = explode(',', $filmArray['genres']);
            unset($filmArray['genres']);
            $film->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Successfully deleted film',
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
