<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Session;

class subAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::where('role', "subadmin")->where('is_franchise', (Session::get('is_f') == "true") ? true : false)->orderBy('createdAt', 'DESC')->get();
        return view('subAdmin', ['data' => $user]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('subAdmin_create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // echo "<pre>";
        // print_r($request->toArray());
        // die;

        $request->validate([
            'name' => 'required',
            'username' => 'required|unique:users,userName',
            'password' => 'required',
            'transactionPin' => 'required',
        ]);

        $referral = new \MongoDB\BSON\ObjectID(Session::get('id'));
        $role = "subadmin";
        $password = $request->password;
        $commissionPercentage = intval(trim($request->commissionPercentage, '"'));
        $transactionPin = intval(trim($request->transactionPin, '"'));
        // echo "<pre>";
        // print_r($userName);die();
        $user = new User();
        $user->name = $request->name;
        $user->userName =  $request->username;
        $user->password = $password;
        $user->role = $role;
        $user->isActive = true;
        $user->creditPoint = 0;
        $user->transactionPin = $transactionPin;
        $user->is_franchise = (Session::get('is_f') == "true") ? true : false;
        $user->referralId = $referral;
        $user->save();
        return redirect('/subAdmin');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        return view('subAdmin_edit', ['edata' => $user]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $referral = new \MongoDB\BSON\ObjectID(Session::get('id'));
        $role = "subadmin";
        $password = $request->password;
        $transactionPin = intval(trim($request->transactionPin, '"'));
        // echo "<pre>";
        // print_r($userName);die();
        $user = User::find($id);
        $user->name = $request->name;
        $user->userName =  $request->username;
        $user->password = $password;
        $user->role = $role;
        $user->isActive = true;
        $user->creditPoint = 0;
        $user->transactionPin = $transactionPin;
        $user->referralId = $referral;
        $user->save();
        return redirect('/subAdmin');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
