<?php

namespace App\Http\Controllers;

use App\Models\ouvrier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Api_ouvrier_controller extends Controller
{
    // louvrier connecté
    public function index(){
        return response([
            'ouvrier'=>ouvrier::orderBy('created_at','desc')->where('id',auth()->user()->id)->get(),
        ]);
    }
    // tout les ouvriers
    public function all(){
        return response([
            'ouvrier'=>ouvrier::all(),
        ]);
    }
    // login pour l'ouvrier 
    public function login(Request $request){
        $credentials=$request->validate([
            'telephone'=>'required|numeric|digits:8',
            'password'=>'required|string|min:4'
        ]);
        $user=Auth::guard('ouvrier')->user();
        if(Auth::guard('ouvrier')->attempt($credentials)){
       
            return response([
               'message'=> 'ouvrier connecté avec succes',
                'token'=>Auth::guard('ouvrier')->user()->createToken('secret')->plainTextToken,
            ],200);
        }
        return response([
            'message'=>'login ou password invalid',
        ],401);

    }
    //registre de creation d'un ouvrier
    public function Register(Request $request)
    {
        $atts = $request->validate([
            'nom' => 'required|string',
            'prenom' => 'required|string',
            'age' => 'required|integer',
            'telephone' => 'required|numeric|digits:8',
            'adresse' => 'required|string',
            'metier' => 'required|string',
            'ville' => 'required|string',
            'password' => 'required|string|min:4|confirmed'

        ]);
        $ouvrier = ouvrier::create([
            'nom' => $atts['nom'],
            'prenom' => $atts['prenom'],
            'age' => $atts['age'],
            'telephone' => $atts['telephone'],
            'adresse' => $atts['adresse'],
            'metier' => $atts['metier'],
            'ville' => $atts['ville'],
            'password' => bcrypt($atts['password'])
        ]);
        return response([
            'message' => 'ouvier creer avec succé',
            'ouvrier' => $ouvrier,
            'token' => $ouvrier->createToken('secret')->plainTextToken,


        ]);
    }
    // modification les donné un ouvrier
       public function update(Request $request){
        $ouvrier=ouvrier::where('id',auth()->user()->id);
        if(!$ouvrier){
            return response([
                'message'=>'ouvier introuvable'
            ]);
        }
        $atts = $request->validate([
            'telephone' => 'numeric|digits:8',
            'adresse' => 'string',
            'metier' => 'string',
            'ville' => 'string',
            'password' => 'string|min:4|confirmed'

        ]);
        $ouvrier->update([
            'telephone' => $atts['telephone'],
            'adresse' => $atts['adresse'],
            'metier' => $atts['metier'],
            'ville' => $atts['ville'],
            'password' => bcrypt($atts['password'])
        ]);
        return response([
            'message' => 'ouvier modifié avec succé',
        ],200);
    }

    // logout pour un ouvrier
    public function logout(){
        session()->flush();
        Auth::guard('ouvrier')->logout();
        return response([
            'message'=>'déconnecté'
        ],200);
    }
}
