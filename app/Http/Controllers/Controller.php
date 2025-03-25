<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

abstract class Controller
{
    public function SaveImage($image,$path='public'){
        if(!$image){
            return null;
        }
        $FileName=time().'.png';
        Storage::disk($path)->put($FileName,base64_decode($image));
        return URL::to('/').'/storage'.$path.'/'.$FileName;    }
}
