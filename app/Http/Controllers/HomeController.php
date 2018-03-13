<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\News as News;
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
        $results = News::all()->get();
        if (isEmpty($results)) {
            $response = 'There was a problem fetching players.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    public function getLatestNews()
    {
        $results = News::orderBy('created_at', 'desc')->latest();
        if (isEmpty($results)) {
            $response = 'There was a problem fetching players.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    public function getTopFivePlayers()
    {//RADI
        $results = User::orderBy('points', 'desc')->take(5)->get();
        if (!empty($results)) {
            $response = 'There was a problem fetching players.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    public function topFivePlayersDivision(Request $request)
    {
        $results = User::with('leagues')->where('id', $request->id)->take(5)->get();
        if (!empty($results)) {
            $response = 'There was a problem fetching players.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }
}
