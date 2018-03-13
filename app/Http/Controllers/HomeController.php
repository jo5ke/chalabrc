<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\News as News;


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
            return $response = 'There are no news yet.';
        }
        return $results;
    }

    public function getLatestNews()
    {
        $results = News::all()->orderBy('created_at', 'desc')->first();
        if (isEmpty($results)) {
            return $response = 'There are no news yet.';
        }
        return $results;
    }

    public function topFivePlayers()
    {
        $results = User::all()->take(5)->orderBy('points', 'desc')->get();
        if (isEmpty($results)) {
            return $response = 'There was a problem fetching players.';
        }
        return $results;
    }

    public function topFivePlayersDivision(Request $request)
    {
        $results = User::with('leagues')->take(5)->where('id', $request->id)->get();
        if (isEmpty($results)) {
            return $response = 'There was a problem fetching players.'
        }
        return $results;
    }
}
