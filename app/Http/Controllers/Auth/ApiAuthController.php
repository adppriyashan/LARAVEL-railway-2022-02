<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\APIStructure;
use App\Models\Booking;
use App\Models\Location;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ApiAuthController extends Controller
{
    protected $tokenPrefix = 'railway';

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
        if ($validator->fails()) {
            return APIStructure::getResponse([], $validator->errors()->all(), 422);
        }
        $request['password'] = Hash::make($request['password']);
        $request['remember_token'] = Str::random(10);
        User::create($request->toArray())->createToken($this->tokenPrefix);
        return APIStructure::getResponse();
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            return APIStructure::getResponse([], $validator->errors()->all(), 422);
        }
        $user = User::where('email', $request->email)->first();
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $tickets = Booking::where('user', $user->id)->get();
                return APIStructure::getResponse(['token' =>  $user->createToken($this->tokenPrefix)->accessToken, 'user' => $user, 'tickets' => $tickets, 'locations' => Location::select('id','location')->orderBy('location', 'ASC')->get()], []);
            } else {
                return APIStructure::getResponse([], [], 422);
            }
        } else {
            return APIStructure::getResponse([], [], 422);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return APIStructure::getResponse();
    }
}
