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
      //  $path = public_path() . "/images" . "/" . $png_url;
        $img = Image::make(file_get_contents($request->image))->save($path) ;
        Storage::disk($driver)->put($png_url,$img);
    }

}
