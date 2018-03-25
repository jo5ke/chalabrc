<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Club as Club;
use App\Match as Match;
use App\Article as Article;
use App\League as League;
use App\Round as Round;
use App\Player as Player;
use App\Season as Season;
use App\User as User;
use App\PlayerStats as PlayerStats;
use Faker\Factory;


class AdminController extends Controller
{
    // *** DO NOT FORGET TO ADD RELATIONS TO QUERIES ONCE EVERYTHING IS DEFINED ***
    
    //Club CRUD section
   	public function getClubs(Request $request)
    {
        $results = Club::where('league_id',$request->l_id)->get();
        if ($results === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

   	public function getClub(Request $request)
    {
        $results = Club::where('id', $request->id)->with('players')->first();
        if ($results === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    public function postClub(Request $request)
    {
        $club = new Club;
        $club->name = $request->name;
        $club->league_id = $request->l_id;
        $club->save();

        $results = Club::where('id', $club->id)->get();
        if ($results === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    public function removeClub(Request $request)
    {
        $club = Club::where('id',$request->id)->first();
        $match = Match::where('id', $request->m_id)->first();
        
        if ($club === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        $club->delete();
        $match->delete();
        return $this->json($club);
    }

    public function updateClub(Request $request)
    {
        $club = Club::where('id',$request->id)->first();
        $club->name = $request->name;
        $club->league_id = $request->l_id;
        $club->save();

        if ($club === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($club);
    }
    

    /////////////////////////////////Club ends

    //Player CRUD section
    public function getPlayers()
    {
        $results = Player::all();
        if ($results === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    public function getPlayersByClub(Request $request)
    {
        $results = Club::where('league_id', $request->l_id)->with('players')->get();
      //  $results = Player::where('club_id',$request->c_id,'league_id',$request->l_id);
        if ($results === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    public function getPlayer(Request $request)
    {
        $results = Player::where('id', $request->id)->get();
        if ($results === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    public function postPlayer(Request $request)
    {
        $player = new Player;
        $player->first_name = $request->first_name;
        $player->last_name = $request->last_name;
        $player->position = $request->position;
        $player->number = $request->number;
        $player->price = $request->price;
        $player->league_id = $request->l_id;
        $club = Club::where('name',$request->club_name)->first();
        $player->club_id = $club->id;
        $player->save();

        $results = Player::where('id', $player->id)->get();
        if ($results === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    public function removePlayer(Request $request)
    {
        $player = Player::where('id',$request->id)->first();
        
        if ($player === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        $player->delete();
        return $this->json($player);
    }

    public function updatePlayer(Request $request)
    {
        $player = Player::where('id',$request->id)->first();
        $player->first_name = $request->first_name;
        $player->last_name = $request->last_name;
        $player->position = $request->position;
        $player->number = $request->number;
        $player->price = $request->price;
        $player->league_id = $request->l_id;
        $club=Club::where('name',$request->club_name)->first();
        $player->club_id = $club->id;
        $player->save();

        if ($player === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($player);
    }

    /////////////////////////////////Club ends

    //League CRUD

   	public function getLeagues()
    {
        $results = League::all();
        if ($results === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

   	public function getLeague(Request $request)
    {
        $results = League::where('id', $request->id)->get();
        if ($results === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    public function postLeague(Request $request)
    {
    	$league = new League();
    	$league->name = $request->name;
    	$league->number_of_rounds = $request->number_of_rounds;
    	$league->save();

        $results = League::where('id', $league->id)->get();
        if ($results === null) {
            $response = 'There was a problem saving your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    public function removeLeague(Request $request)
    {
        $league = League::where('id',$request->id)->first();
        
        if ($league === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        $league->delete();
        return $this->json($league);
    }

    public function updateLeague(Request $request)
    {
        $league = League::where('id',$request->id)->first();
        $league->name = $request->name;
        $league->number_of_rounds = $request->number_of_rounds;
        $league->save();

        if ($league === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($league);
    }

    /////////////////////////////////League ends

    //Match CRUD

  	public function getMatches(Request $request)
    {
        $results = Match::where('round_id',$request->r_id)->where('league_id',$request->l_id)->get();
        if ($results === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    public function getMatchesByRounds(Request $request)
    {
      //  $results = League::where('id', $request->l_id)->with('rounds.matches')->get();
      $results = Round::where('league_id',$request->l_id)->with('matches')->get();
     
      
        if ($results === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

   	public function getMatch(Request $request)
    {
        $results = Match::where('id', $request->id)->first();
        if ($results === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

   	public function postMatch(Request $request)
    {
    	$match = new Match();
    	$match->club1_name = $request->c1_name;
        $match->club2_name = $request->c2_name;
        $round = Round::where('league_id',$request->l_id)->where('round_no',$request->r_no)->first();
        //return $round;
        $match->round_id = $round->id;
        $match->league_id = $request->l_id;

        $club1 = Club::where('name',$match->club1_name)->first();
        $club2 = Club::where('name',$match->club2_name)->first();

        $players1 = $club1->players;
        foreach($players1 as $player){
            $round->players()->attach($player);     
          //  $stats = new PlayerStats;
                   
        }

        $players2 = $club2->players;
        foreach($players2 as $player){
              $round->players()->attach($player);     
            // $stats = new PlayerStats;
            // $stats->first_name = $player->first_name;       
            // $stats->last_name = $player->last_name;  
            // $stats->save();     
        }
       
    	$match->save();

        if ($match === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($match);
    }

    public function removeMatch(Request $request)
    {
        $match = Match::where('id',$request->id)->first();
        
        if ($match === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        $match->delete();
        return $this->json($match);
    }

    public function updateMatch(Request $request)
    {
        $round = Round::where('round_no',$request->r_no)->where('league_id',$request->l_id)->first();
        // return $round->id;
        $match = Match::where('id',$request->id)->first();

        $match->delete();

        $newMatch = new Match;
    	$newMatch->club1_name = $request->c1_name;
        $newMatch->club2_name = $request->c2_name;
        
       
        $newMatch->round_id = $round->id;
        $newMatch->league_id = $request->l_id;
        
        $newMatch->save();
        return $newMatch;
        

        if ($newMatch === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($newMatch);
    }

    /////////////////////////////////Match ends

    //Round CRUD

   	public function getRounds(Request $request)
    {
        $results = Round::where('league_id',$request->l_id)->get();
        if ($results === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

   	public function getRound(Request $request)
    {
        $results = Round::where('id', $request->id)->get();
        if ($results === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    public function postRound(Request $request)
    {
        $round = new Round();
        $round->round_no = $request->round_no;
        $round->league_id = $request->l_id;
    	$round->save();

        $results = Round::where('id', $round->id)->get();
        if ($results === null) {
            $response = 'There was a problem saving your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    public function removeRound(Request $request)
    {
        $round = Round::where('id',$request->id)->first();
        
        if ($round === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        $round->delete();
        return $this->json($round);
    }

    public function updateRound(Request $request)
    {
        $round->round_no = $request->round_no;
        $round->league_id = $request->l_id;
    	$round->save();

        $round = Round::where('id', $round->id)->get();
        if ($round === null) {
            $response = 'There was a problem saving your data.';
            return $this->json($response, 404);
        }
        return $this->json($round);
    }

    /////////////////////////////////Round ends

    //Season CRUD

   	public function getSeasons()
    {
        $results = Season::all();
        if ($results === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

   	public function getSeason(Request $request)
    {
        $results = Season::where('id', $request->id)->get();
        if ($results === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    public function postSeason(Request $request)
    {
        $season = new Season();
        $season->name = $request->name;
    	$season->save();

        $results = Season::where('id', $season->id)->get();
        if ($results === null) {
            $response = 'There was a problem saving your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    public function removeSeason(Request $request)
    {
        $season = Season::where('id',$request->id)->first();
        
        if ($season === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        $season->delete();
        return $this->json($season);
    }

    public function updateSeason(Request $request)
    {
        $season = Season::where('id',$request->id)->first();
        $season->name = $request->name;
    	$season->save();

        $season = Season::where('id', $season->id)->get();
        if ($season === null) {
            $response = 'There was a problem saving your data.';
            return $this->json($response, 404);
        }
        return $this->json($season);
    }

    /////////////////////////////////Season ends

    //User CRUD

   	public function getUsers()
    {
        $results = User::all();
        if ($results === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

   	public function getUser(Request $request)
    {
        $results = User::where('id', $request->id)->get();
        if ($results === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    public function postUser(Request $request)
    {
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->uuid = Factory::create()->uuid;
    	$user->save();

        $results = Season::where('id', $user->id)->get();
        if ($results === null) {
            $response = 'There was a problem saving your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    public function removeUser(Request $request)
    {
        $user = User::where('uuid',$request->uuid)->first();
        
        if ($user === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        $user->delete();
        return $this->json($user);
    }

    public function updateUser(Request $request)
    {
        $user = User::where('uuid',$request->id)->first();
        $user->name = $request->name;
        $user->email = $request->email;

    	$user->save();

        $user = Season::where('id', $user->id)->get();
        if ($user === null) {
            $response = 'There was a problem saving your data.';
            return $this->json($response, 404);
        }
        return $this->json($user);
    }

    /////////////////////////////////User ends

    //News CRUD

  	public function getArticles()
      {
          $results = Article::all();
          if ($results === null) {
              $response = 'There was a problem fetching your data.';
              return $this->json($response, 404);
          }
          return $this->json($results);
      }
  
    public function getArticle(Request $request)
    {
        $results = Article::where('id', $request->id)->get();
        if ($results === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }
  
    public function postArticle(Request $request)
    {
        $article = new Article();
        $article->title = $request->title;
        $article->body = $request->body;
        $article->league_id = $request->l_id;
        $article->save();

        if ($article === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($article);
    }
  
    public function removeArticle(Request $request)
    {
        $article = Article::where('id',$request->id)->first();
        
        if ($article === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        $article->delete();
        return $this->json($article);
    }

    public function updateArticle(Request $request)
    {
        $article = Article::where('id',$request->id)->first();
        $article->title = $request->title;
        $article->body = $request->body;
        $article->league_id = $request->l_id;
        $article->save();

        if ($article === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($article);
    }
  
    /////////////////////////////////News ends

    //Post match stats

    public function getClubsByMatch(Request $request)
    {
        $match = Match::where('id', $request->m_id)->first();
        $club1 = Club::where('league_id',$request->l_id)->where('name', $match->club1_name)->first();
        $club2 = Club::where('league_id',$request->l_id)->where('name', $match->club2_name)->first();

        $round = Round::where('round_no',$request->r_id)->first();
        
        $players = $round->players;

        $club1 = $club1->players()->with('rounds')->get();
        $club2 = $club2->players()->with('rounds')->get();
        
        // return $club1->players;
        
        $i = 0;
        foreach($players as $player){
            $stats[$i] = $player->pivot;
            $i++;
        }

        
        $results = [
            "club1" => $club1,
            "club2" => $club2
        ];

        if ($results === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    public function getPlayersStats(Request $request)
    {  
        // $round = Round::where('round_no',$request->r_no)->where('league_id',$request->l_id)->first();
        
        // return $round->matches->where('round_id',$request->r_no);
        // $players = $round->players;
        
        // $i = 0;
        // foreach($players as $player){
        //     $results[$i] = $player->pivot;
        //     $i++;
        // }

        ////////////////////////////////

        $match = Match::where('id', $request->m_id)->first();
        $club1 = Club::where('league_id',$request->l_id)->where('name', $match->club1_name)->first();
        $club2 = Club::where('league_id',$request->l_id)->where('name', $match->club2_name)->first();


        $c1 = DB::table('players')
                    ->join('round_player','players.id','=','round_player.player_id')
                    ->select('players.first_name','players.last_name','players.id','players.number','players.position','players.price','players.club_id',
                            'round_player.assist','round_player.captain','round_player.clean','round_player.kd_3strike','k_save','round_player.miss',
                            'round_player.own_goal','round_player.player_id','round_player.red','round_player.yellow','round_player.round_id','round_player.score','round_player.start','round_player.sub','round_player.total')
                    ->where([
                                ['round_player.round_id','=',$request->r_id],
                                ['players.club_id', '=', $club1->id],
                            ])
                    ->get();

        $c2 = DB::table('players')
                    ->join('round_player','players.id','=','round_player.player_id')
                    ->select('players.first_name','players.last_name','players.id','players.number','players.position','players.price','players.club_id',
                            'round_player.assist','round_player.captain','round_player.clean','round_player.kd_3strike','k_save','round_player.miss',
                            'round_player.own_goal','round_player.player_id','round_player.red','round_player.yellow','round_player.round_id','round_player.score','round_player.start','round_player.sub','round_player.total')
                    ->where([
                                ['round_player.round_id','=',$request->r_id],
                                ['players.club_id', '=', $club2->id],
                            ])
                    ->get();

        $results = [
            "club1" => $c1,
            "club2" => $c2,
        ];
                    
        // $c1 = DB::table('players')
        //             ->join('round_player', function($join){
        //                 $join->on('round_player','players.id','=','round_player.player_id')
        //                         ->select('players.first_name','players.last_name','players.id','players.number','players.position','players.price','players.club_id',
        //                         'round_player.assist','round_player.captain','round_player.clean','round_player.kd_3strike','k_save','round_player.miss',
        //                         'round_player.own_goal','round_player.player_id','round_player.red','round_player.yellow','round_player.round_id','round_player.score','round_player.start','round_player.sub')
        //                         ->where('players.club_id', '=', $club1->id);
        //             })
        //             ->get();



        if ($results === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    public function postPlayerStats(Request $request)
    {
        $round = Round::where('round_no',$request->input('data.round_id'))->where('league_id',$request->l_id)->first();

        $player = $round->players->where('pivot.player_id',65)->where('pivot.round_id',$round->round_no)->first()->pivot;
        $player->start = $request->input('data.start');
        $player->sub = $request->input('data.sub');
        $player->assist = $request->input('data.assist');
        $player->miss = $request->input('data.miss');
        $player->score = $request->input('data.score');
        $player->clean = $request->input('data.clean');
        $player->k_save = $request->input('data.k_save');
        $player->kd_3strike = $request->input('data.kd_3strike');
        $player->yellow = $request->input('data.yellow');
        $player->red = $request->input('data.red');
        $player->own_goal = $request->input('data.own_goal');
        $player->captain = $request->input('data.captain');
        $player->save();

        if ($player === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($player);
    }


    /////////////////////////////////Post match stats ends



}
