<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Session;

class DistributerController extends Controller
{
    public function __construct()
    {
        $this->middleware('CheckAuth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        // done/ Use new \MongoDB\BSON\ObjectID this for search with Object Id
        //ok

        // $distributers = User::where('referralId',new \MongoDB\BSON\ObjectID('603388bb7d20e50a81217277'))->get();
        // $distributer = User::where('isLogin',false)->get();
        // // echo "<pre>";print_r($distributer->toArray());
        // echo "<pre>";print_r($distributers->toArray());die;
        if (Session::get('role') == "Admin") {
            if (Session::get('username') == 10001) {
                $user = User::where('role', 'distributer')->orderBy('createdAt', 'DESC')->get();
                foreach ($user as $key => $value) {
                    $refer = User::where('_id', new \MongoDB\BSON\ObjectID($value['referralId']))->first();
                    $user[$key]['refer'] = $refer['userName'];
                }
            } else {
                $superdistributer = User::where('referralId', new \MongoDB\BSON\ObjectID(Session::get('id')))->where('role', 'superDistributer')->orderBy('createdAt', 'DESC')->get();
                foreach ($superdistributer as $super) {
                    $user = User::where('role', 'distributer')->orderBy('createdAt', 'DESC')->where('referralId', new \MongoDB\BSON\ObjectID($super['_id']))->get();
                    // echo "<pre>";
                    // print_r($distributer->toArray());die;
                    foreach ($user as $key => $dis) {
                        $refer = User::where('_id', new \MongoDB\BSON\ObjectID($dis['referralId']))->first();
                        $user[$key]['refer'] = $refer['userName'];
                    }
                }
            }
        } elseif (Session::get('role') == "superDistributer") {
            $user = User::where('referralId', new \MongoDB\BSON\ObjectID(Session::get('id')))->where('role', 'distributer')->orderBy('createdAt', 'DESC')->get();
            // echo "<pre>";print_r($user->toArray());die;
            foreach ($user as $key => $value) {
                $refer = User::where('_id', new \MongoDB\BSON\ObjectID($value['referralId']))->first();
                $user[$key]['refer'] = $refer['userName'];
            }
        }
        // echo "<pre>";print_r($user->toArray());die;
        return view('distributer.index', ['data' => $user]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
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
        //
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
