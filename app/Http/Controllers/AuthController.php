<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //Registers Employee
    public function register(Request $request){

        $validData = $request->validate([
            'firstname' => 'required|string|max:255',
            'middlename' => 'required|string|max:255',
            'lastname'  => 'required|string|max:255',
            'dob' => 'date_format:Y-m-d|before:today|nullable',
            'email' => 'required|string|email|max:255|unique:users',
            'street_address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'phone_number' => 'required|string|max:255',
            'gender' => 'required|string|max:255',
            'job_title' => 'required|string|max:255',
            'department' => 'required|string|max:255',
        ]);

        $password = Hash::make(strtolower($validData['lastname']));

        //Create User
        $user = User::create([
            'firstname' => $validData['firstname'],
            'middlename' => $validData['middlename'],
            'lastname'  => $validData['lastname'],
            'dob' => $validData['dob'],
            'email' => $validData['email'],
            'street_address' => $validData['street_address'],
            'city' => $validData['city'],
            'state' => $validData['state'],
            'country' => $validData['country'],
            'phone_number' => $validData['phone_number'],
            'gender' => $validData['gender'],
            'job_title' => $validData['job_title'],
            'department' => $validData['department'],
            'password' => $password
        ]);

        //Create Wallet for user 
        $wallet = Wallet::create([
            'user_id' => $user->id
        ]);

        $response = [
            'status' => 'Success',
            'message' => "Employee Account and Virtual Wallet Created Successfully Created Successfully. Password is User's last name"
        ];

        return response()->json($response, 201);

    }

    public function login(Request $request){
        $validData = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(
            [
            'status' => 'Failed',
            'message' => 'Invalid login details'
            ], 401);
        }

        $user = User::where('email', $request['email'])->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = [
            'status' => 'Success',
            'message' => "Login Successful",
            'access_token' => $token,
            'token_type' => 'Bearer',
        ];

        return response()->json($response, 200);
    }

    public function me(Request $request){
    return $request->user();
    }

    public function edit(Request $request){
        $validData = $request->validate([
            'firstname' => 'required|string|max:255',
            'middlename' => 'required|string|max:255',
            'lastname'  => 'required|string|max:255',
            'dob' => 'date_format:Y-m-d|before:today|nullable',
            'street_address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'phone_number' => 'required|string|max:255',
            'gender' => 'required|string|max:255',
            'job_title' => 'required|string|max:255',
            'department' => 'required|string|max:255',
        ]);
        
        //Find User ID in DB
        $id = $request->user()->id;
        $user = User::find($id);

        if($user){
            $user->firstname = $validData['firstname'];
            $user->middlename = $validData['middlename'];
            $user->lastname  = $validData['lastname'];
            $user->dob = $validData['dob'];
            $user->street_address = $validData['street_address'];
            $user->city = $validData['city'];
            $user->state = $validData['state'];
            $user->country = $validData['country'];
            $user->phone_number = $validData['phone_number'];
            $user->gender = $validData['gender'];
            $user->job_title = $validData['job_title'];
            $user->department = $validData['department'];

            $saved = $user->save();

            if($saved) {
                $user = User::find($id);

                $response = array(
                    "status" => "success",
                    "message" => "Profile Updated Successfully",
                    "user" => $user
                );

                return response()->json($response, 200);
            }
        }

        //User isnt found
         $jsonRes = array(
            "status" => "failed",
            "message" => "User Not Found",
            "user" => []
        );
        return response()->json($jsonRes, 404);

    }
    
}
