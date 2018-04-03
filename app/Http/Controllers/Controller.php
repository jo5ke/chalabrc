<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Image;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function json($data, $code = 200){
        $obj = [
            "data" => $data,
            "status" => $code
        ];
        
        return response()->json($obj);
    }

    //this is left to be fixed
    public function saveImageStorage(Request $request,$name,$driver='public')
    {
        $png_url = $name . "-" . time() . ".png";
      //  $png_url = $name . "-" . time().".png";
      //  $path = public_path() . "/images" . " $png_url;
        $img = Image::make(file_get_contents($request->image))->save($path) ;
        Storage::disk($driver)->put($png_url,$img);
    }

    public function makeTransfer(Request $request)
    {
        // $buy = json_encode($request->buy);
        // $sell = json_encode($request->sell);
        $buy = $request->buy;
        $sell = $request->sell;

        $user = JWTAuth::authenticate();
        // $user = User::where('id',1)->first();
        $meta = $user->oneLeague($request->l_id)->first();
        
        $team = Squad::where('user_id',$user->id)->where('league_id',$request->l_id)->first();
        $selected_team = json_decode($team->selected_team);
        $substitutions = json_decode($team->substitutions);
  
        $transfer = new Transfer;
        $transfer->user_id = $user->id;
        $transfer->squad_id = $team->id;
        $transfer->league_id = $request->l_id;
        $transfer->buy = json_encode($request->buy);
        $transfer->sell = json_encode($request->sell);
        $transfers_left = $meta->pivot->transfers;

        $buyed = array();
        $selled = array();
        $amm_buy =0;
        $amm_sell =0;

        for($i=0;$i<count($buy);$i++)
        {
            $buyed[$i] = Player::where('id',$buy[$i])->first();
            $amm_buy += $buyed[$i]->price;
        }
        for($i=0;$i<count($sell);$i++)
        {
            $selled[$i] = Player::where('id',$sell[$i])->first();
            $amm_sell += $selled[$i]->price;                
        }
        $transfer->ammount_buy = $amm_buy;   
        $transfer->ammount_sell = $amm_sell;
        $meta->pivot->money -= $amm_buy;           
        $meta->pivot->money += $amm_sell; 

        $selac = array();
        foreach($sell as $s){
            if(in_array($s,$selected_team)){
                array_push($selac,$s);
                $selected_team = array_diff($selected_team, $selac);
                $selected_team = array_values(json_decode(json_encode($selected_team), true));   
                array_push($selected_team,$s);   
            }else{
                array_push($selac,$s);
                $substitutions = array_diff($substitutions, $selac);
                $substitutions = array_values(json_decode(json_encode($substitutions), true));   
                array_push($selected_team,$s); 
            }
        }

        if(count($buy) <= $transfers_left){
                $meta->pivot->transfers = $transfers_left - count($buy);
               // $meta->pivot->save();
        }else{
            $meta->pivot->transfers--;
           // $meta->pivot->save();     
        }
        $meta->pivot->save();     
        $transfer->save();
        // $meta->pivot->money = $request->money;
        // $meta->pivot->save();  
        $team->selected_team = json_encode($selected_team);
        $team->substitutions = json_encode($substitutions);
        $team->save();

        // $transfer = Transfer::where('user_id',$user->id)->where('squad_id',$team->id)->where('league_id',$request->l_id)->get();
        // $no_of_transfers = $transfer->count();
        if ($team === null) {
            $response = 'There was a problem fetching players.';
            return $this->json($response, 404);
        }
        return $this->json($team);
    }

}
