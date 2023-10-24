<?php

namespace app\Http\Controllers\Api;

use app\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Session;
use Illuminate\Support\Facades\Hash;



use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    /**
     * login user to our application
     */
    public function loginUserExample(Request $request){
        // 
        // $hash = Hash::make('Manav@123');dd($hash);


        $validator = Validator::make($request->all(), [
            'userName' => 'required',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails())
        {
            return response(['status' => false, 'errors'=>$validator->errors()->all()], 422);
        }
        $user = User::where('userName', $request->userName)->first();
        if ($user) {//dd($data);

            $data = [
                'userName' => $request->userName,
                'password' => $request->password
            ];

            if (auth()->attempt($data)) {
                $token = auth()->user()->createToken('Laravel8PassportAuth')->accessToken;
                dd($token);
                return response()->json(['token' => $token], 200);
            } else {
                return response()->json(['error' => 'Unauthorised'], 401);
            }
            
            // if ($request->password == $user->password) {
            //     User::where('userName', $request->userName)->update(['isLogin' => true]);
            //     $response = ['hasError' => false, 'token' => $user->_id];
            //     return response($response, 200);
            // } else {
            //     $response = ['hasError' => true, "message" => "Password mismatch"];
            //     return response($response, 422);
            // }
        } else {
            $response = ['hasError' => true, "message" =>'User does not exist'];
            return response($response, 422);
        }
    }

    public function getUserDetails(Request $request){
        $userDetails = User::where('_id', $request->user_api_id)->where('isLogin', true)->first();
        if($userDetails){
            $response = ['hasError' => false, 'data' => $userDetails->toArray()];
            
        }else{
            $response = ['hasError' => true, 'data' => (object) []];
        }
        return $response;
    }

    public function logOut(Request $request){
        $userDetails = User::where('_id', $request->user_api_id)->where('isLogin', true);
        if($userDetails->count() > 0){
            $userDetails->update(['isLogin' => false]) ;
            $response = ['hasError' => false, "message" =>'User logged out'];
        }else{
            $response = ['hasError' => true, "message" =>'Something went wrong'];
        }
        return $response;
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'userName';
    }
}
