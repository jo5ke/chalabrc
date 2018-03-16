<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Article as Article;
use App\User as User;
use App\League as League;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function getNews()
    {
        $results = Article::all();
        if ($results->isEmpty()) {
            $response = 'There was a problem fetching players.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    public function getLatestNews()
    {
        $results = Article::orderBy('created_at', 'desc')->latest();
        if ($results->isEmpty()) {
            $response = 'There was a problem fetching players.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    public function getTopFivePlayers()
    {
        $results = User::orderBy('points', 'desc')->take(5)->get();
        if ($results->isEmpty()) {
            $response = 'There was a problem fetching players.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    // "id" = id lige (1 i 2)
    public function topFivePlayersDivision(Request $request)
    {
        $results = User::with('leagues')->where('id', $request->id)->take(5)->get();
        if ($results->isEmpty()) {
            $response = 'There was a problem fetching players.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }
}
