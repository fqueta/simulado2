<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UploadFile extends Controller
{
    public function upload(Request $request){
       $request->file('arquivo')->store('teste');
       //$dados
       //var_dump($dados);
    }
}
