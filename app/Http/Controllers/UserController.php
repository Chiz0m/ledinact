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


    public function forgotPassword(Request $request)
    {

        // the message
        $randomCode = rand(1000, 10000);
        $msg = "Your code is" . $randomCode . "\nPlease copy this code and use it in the app section!";

        // use wordwrap() if lines are longer than 70 characters
        $msg = wordwrap($msg, 70);

        // send email
        // mail("kevin@ledinaction.com", "Warranty cliam notice", $msg);
        mail("chizomreal@gmail.com", "Warranty cliam notice", $msg);
        // $this->validate($request, [
        //     'name' => 'required|min:3',
        //     'email' => 'required|email|unique:users',
        //     'password' => 'required|min:6',
        // ]);

        // $user = User::create([
        //     'name' => $request->name,
        //     'email' => $request->email,
        //     'password' => bcrypt($request->password),
        //     'user_type' => $request->user_type,
        // ]);

        // $token = $user->createToken('TutsForWeb')->accessToken;

        // return response()->json(['data' => ['messsage' => 'User has been created!']], 200);
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
