<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Session;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
     */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/User';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function Login_custom(Request $request)
    {
        // validate the info, create rules for the inputs
        // echo "<pre>";
        // print_r($request->toArray());die;
        $validator = $request->validate([
            'userName' => 'required', // make sure the email is an actual email
            'password' => 'required',
        ]);

        $userName = $request->userName;
        $password = $request->password;

        if ($userName != "" && $password != "") {
            $user = User::where([
                'userName' => $userName,
                'password' => $password,
                'isActive' => true,
            ])->first();

            if ($user) {
                $users = User::where('userName', $userName)->first();
                // echo "<pre>";
                // print_r($users->toArray());
                // die();
                if (!empty($users)) {
                    if ($users['userName'] == 'superadminA'){
                        session()->flash('msg', 'You not Login in to This Panel');
                        return redirect()->route('login');
                    }
                    if ($users['role'] == 'Admin') {
                        // Auth::login($users)
                        Session::put('username', $users['userName']);
                        Session::put('name', $users['name']);
                        Session::put('creditPoint', $users['creditPoint']);
                        Session::put('role', $users['role']);
                        Session::put('id', $users['_id']);
                        Session::put('transactionPin', $users['transactionPin']);
                        Session::put('permissions', $users['permissions']);
                        Session::put('referralId', $users['referralId']);
                        Session::put('is_f', ($user['is_franchise'] == false) ? "false" : "true");
                        Session::put('password', $users['password']);
                        return redirect()->intended('/dashboard');
                    } elseif ($users['role'] == "agent") {
                        Session::put('username', $users['userName']);
                        Session::put('name', $users['name']);
                        Session::put('creditPoint', $users['creditPoint']);
                        Session::put('role', $users['role']);
                        Session::put('id', $users['_id']);
                        Session::put('referralId', $users['referralId']);
                        Session::put('transactionPin', $users['transactionPin']);
                        Session::put('permissions', $users['permissions']);
                        Session::put('is_f', ($user['is_franchise'] == false) ? "false" : "true");
                        return redirect()->intended('/dashboard');
                    } elseif ($users['role'] == "premium") {
                        Session::put('username', $users['userName']);
                        Session::put('name', $users['name']);
                        Session::put('creditPoint', $users['creditPoint']);
                        Session::put('role', $users['role']);

                        Session::put('referralId', $users['referralId']);
                        Session::put('id', $users['_id']);
                        Session::put('transactionPin', $users['transactionPin']);
                        Session::put('permissions', $users['permissions']);
                        Session::put('is_f', ($user['is_franchise'] == false) ? "false" : "true");
                        return redirect()->intended('/dashboard');
                    } elseif ($users['role'] == "executive") {
                        Session::put('username', $users['userName']);
                        Session::put('name', $users['name']);
                        Session::put('creditPoint', $users['creditPoint']);
                        Session::put('role', $users['role']);

                        Session::put('referralId', $users['referralId']);
                        Session::put('id', $users['_id']);
                        Session::put('transactionPin', $users['transactionPin']);
                        Session::put('permissions', $users['permissions']);
                        Session::put('is_f', ($user['is_franchise'] == false) ? "false" : "true");
                        return redirect()->intended('/dashboard');
                    } elseif ($users['role'] == "classic") {
                        Session::put('username', $users['userName']);
                        Session::put('name', $users['name']);
                        Session::put('creditPoint', $users['creditPoint']);
                        Session::put('role', $users['role']);

                        Session::put('referralId', $users['referralId']);
                        Session::put('id', $users['_id']);
                        Session::put('transactionPin', $users['transactionPin']);
                        Session::put('permissions', $users['permissions']);
                        Session::put('is_f', ($user['is_franchise'] == false) ? "false" : "true");
                        return redirect()->intended('/dashboard');
                    } elseif ($users['role'] == "subadmin") {
                        Session::put('username', $users['userName']);
                        Session::put('name', $users['name']);
                        Session::put('creditPoint', $users['creditPoint']);
                        Session::put('role', $users['role']);
                        Session::put('is_f', ($user['is_franchise'] == false) ? "false" : "true");
                        Session::put('referralId', $users['referralId']);
                        Session::put('id', $users['_id']);
                        Session::put('transactionPin', $users['transactionPin']);
                        Session::put('permissions', $users['permissions']);
                        return redirect()->intended('/dashboard');
                    } else {
                        session()->flash('msg', 'You not Login in to This Panel');
                        return redirect()->route('login');
                    }
                }
            } else {
                session()->flash('msg', 'These credentials do not match our records.');
                return redirect()->route('login');
            }
        } else {
            session()->flash('msg', 'Username And Password is Required.');
            return redirect()->route('login');
        }
    }

    public function doLogout()
    {
        Auth::logout(); // log the user out of our application
        Session::flush(); //
        return Redirect::to('login'); // redirect the user to the login screen
    }
}
