<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class MovieController extends Controller
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => config('services.omdb.url'),
            'timeout'  => 10,
        ]);
    }

    public function indexPage()
    {
        $favorites = DB::table('favorites')
            ->where('user_session', session()->getId())
            ->get();

        return view('movies.index', compact('favorites'));
    }

    public function detailPage($id)
    {
        return view('movies.detail', compact('id'));
    }

    public function favoritesPage()
    {
        $favorites = DB::table('favorites')
            ->where('user_session', session()->getId())
            ->get();

        return view('movies.favorites', compact('favorites'));
    }

    public function index(Request $request)
    {
        $page = $request->get('page', 1);
        $search = $request->get('s', 'avengers');

        if (empty($search)) {
            $search = 'avengers';
        }

        try {
            $response = $this->client->get('', [
                'query' => [
                    'apikey' => config('services.omdb.key'),
                    's' => $search,
                    'page' => $page,
                ]
            ]);

            $data = json_decode($response->getBody(), true);

            if (!isset($data['Search'])) {
                return response()->json(['Search' => []]);
            }

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['Search' => [], 'error' => $e->getMessage()]);
        }
    }

    public function detail($imdbID)
    {
        try {
            $response = $this->client->get('', [
                'query' => [
                    'apikey' => config('services.omdb.key'),
                    'i' => $imdbID,
                    'plot' => 'full',
                ]
            ]);

            $data = json_decode($response->getBody(), true);

            if (!isset($data['Title'])) {
                return response()->json(['error' => 'Movie not found']);
            }

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function addFavorite(Request $request)
    {
        $exists = DB::table('favorites')
            ->where('user_session', session()->getId())
            ->where('imdbID', $request->imdbID)
            ->first();

        if ($exists) {
            return response()->json([
                'message' => 'Movie already in favorites'
            ], 409);
        }

        DB::table('favorites')->insert([
            'user_session' => session()->getId(),
            'imdbID' => $request->imdbID,
            'title' => $request->title,
            'poster' => $request->poster,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['message' => 'Added to favorite']);
    }

    public function removeFavorite($id)
    {
        DB::table('favorites')
            ->where('id', $id)
            ->where('user_session', session()->getId())
            ->delete();

        return response()->json(['status' => 'ok']);
    }
}
