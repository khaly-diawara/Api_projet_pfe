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
            $verificationCode = random_int(100000, 999999); 
            $ouvrier=ouvrier::where('telephone',$credentials['telephone']);
            
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
            'password' => 'required|string|min:4|confirmed',    
        ]);
        $image=$this->SaveImage($request->image,'profiles');
        $ouvrier = ouvrier::create([
            'nom' => $atts['nom'],
            'prenom' => $atts['prenom'],
            'age' => $atts['age'],
            'telephone' => $atts['telephone'],
            'adresse' => $atts['adresse'],
            'metier' => $atts['metier'],
            'ville' => $atts['ville'],
            'image'=>$image,
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
            'adresse' => 'string',
            'metier' => 'string',
            'ville' => 'string',
            'password' => 'string|min:4|confirmed'

        ]);
        $ouvrier->update([
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

// ///////////////////////////////////////////////////



// public function verifyLogin(Request $request)
// {
//     $request->validate([
//         'numeric_login' => 'required|numeric',
//         'verification_code' => 'required|numeric',
//     ]);

//     $user = User::where('numeric_login', $request->numeric_login)
//                 ->where('verification_code', $request->verification_code)
//                 ->first();

//     if ($user) {
//         // Générer un token JWT ou un token d'accès
//         $token = $user->createToken('authToken')->plainTextToken;

//         return response()->json(['token' => $token], 200);
//     } else {
//         return response()->json(['error' => 'Code de vérification invalide'], 401);
//     }


// /////////////////////////////////////////

// public function sendVerificationCode(Request $request)
// {
//     $request->validate(['phone_number' => 'required|numeric']);

//     // Génération du code
//     $verificationCode = rand(100000, 999999);
//     // Stockage dans la base de données (ex: table users)
//     $user = ouvrier::where('phone_number', $request->phone_number)->first();
//     $user->verification_code = $verificationCode;
//     $user->save();

//     // Envoi du code par SMS (ex: via Twilio)
//     // Twilio::sendSMS($request->phone_number, "Votre code de vérification: $verificationCode");

//     return response()->json(['message' => 'Code envoyé avec succès']);
// }