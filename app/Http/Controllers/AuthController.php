<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //$token = '5|Mnm1AxIaf61aI814CkiHOWCtHkv0dbhteH7cxzaEb1b7453a'; -> Invoice
    //$token = '6|VRoHezaLpwNRJ3VsIOms2yO7RNKL8m45zCQih7Ei5a453afa'; -> User
    use HttpResponses;
    public function login(Request $request){


        if(Auth::attempt($request->only('email','password' ))){

            return $this->response('Autorized', 200,[
                // 'token' => $request->user()->createToken('invoice',['user-store'])->plainTextToken,
                'token' => $request->user()->createToken('invoice')->plainTextToken,
            ]);
        }
        
        return $this->response('Not Autorized', 403);
    }


    public function logout(Request $request){

        $request->user()->currentAccessToken()->delete();
        
        return $this->response('Token Revoked', 200);

    }
}
