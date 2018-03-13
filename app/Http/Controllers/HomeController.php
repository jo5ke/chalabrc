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
    public function index()
    {
        return view('home');
    }

    public function getNews()
    {
        $news = News::all()->get();
        if (isEmpty($news)) {
            return $response = 'There are no news yet.';
        }
        return $news;
    }

    public function getLatestNews()
    {
        $news = News::all()->orderBy('created_at', 'desc')->first();
        if (isEmpty($news)) {
            return $response = 'There are no news yet.';
        }
        return $news;
    }

    public function topFivePlayers()
    {
        $users = User::all()->take(5)->orderBy('points', 'desc')->get();
        if (isEmpty($users)) {
            return $response = 'There was a problem fetching these players.';
        }
        return $users;
    }

    public function topFiveDivision(Request $request)
    {
        $results = User::where('id', $request->id)->with('players');
    }
}
