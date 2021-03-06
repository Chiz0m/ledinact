<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Handles Registration Request
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'user_type' => 1
        ]);

        // $token = $user->createToken('TutsForWeb')->accessToken;

        return response()->json(['data' => ['messsage' => 'User has been created!']], 200);
    }

    public function registerOther(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'user_type' => $request->user_type,
        ]);

        // $token = $user->createToken('TutsForWeb')->accessToken;

        return response()->json(['data' => ['messsage' => 'User has been created!']], 200);
    }



    public function updatePassword(Request $request)
    {
        $this->validate($request, [
            'code' => 'required',
            'password' => 'required|min:6',
        ]);

        // $user = User::create([
        //     'name' => $request->name,
        //     'email' => $request->email,
        //     'password' => bcrypt($request->password),
        //     'user_type' => 1
        // ]);
        $user = User::where('geo_location', $request->code)->firstOrFail()->update(['password' => bcrypt($request->password)]);
        $user =  User::where('geo_location', $request->code)->update(['geo_location' => NULL]);

        // $token = $user->createToken('TutsForWeb')->accessToken;

        return response()->json(['data' => ['messsage' => 'Password is updated']], 200);
    }



    public function forgotPassword(Request $request)
    {

        // the message
        $this->validate($request, [
            'email' => 'required',
        ]);

        $userEmail = $request->email;
        $randomCode = rand(1000, 10000);
        $user =  User::where('email', $userEmail)->update(['geo_location' => $randomCode]);
        $msg = "Your code is " . $randomCode . "\nPlease copy this code and use it in the app section!";
        $msg = wordwrap($msg, 70);
        mail($userEmail, "Reset Password Code - LedInAction", $msg);

        return response()->json(['data' => ['messsage' => 'Token has been sent to your email!']], 200);
    }


    /**
     * Handles Login Request
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (auth()->attempt($credentials)) {
            $token = auth()->user()->createToken('Ledinaction')->accessToken;
            return response()->json(['token' => $token], 200);
        } else {
            return response()->json(['error' => 'UnAuthorised'], 401);
        }
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            $request->user()->token()->delete();
        }
        return response()->json(['message' => 'User logged out', 200]);
    }

    public function userList()
    {
        // $users = User::where(['user_type' => 1]);
        $users = new User;
        $users = $users::all();

        return response()->json(['data' => $users]);
    }

    public function userDelete($id)
    {
        // $users = User::where(['user_type' => 1]);
        $users = User::findOrFail($id)->delete($id);

        return response()->json(['data' => ['message' => 'User Deleted']]);
    }
    public function sayHello()
    {
        // $users = User::where(['user_type' => 1]);

        return response()->json(['data' => ['message' => 'Hi']]);
    }

    /**
     * Returns Authenticated User Details
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function details()
    {
        return response()->json(['user' => auth()->user()], 200);
    }
}
