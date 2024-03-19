<?php

namespace App\Http\Controllers;

use App\User;
use App\Bets;
use Carbon\Carbon;
use session;

use Illuminate\Http\Request;

class playerWeeklyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if (Session::get('role') == "Admin" || Session::get('role') == "subadmin") {
            $users = User::where('role', '!=', 'Admin')->where('role', '!=', 'super_distributor')->where('role', '!=', 'distributor')->where('is_franchise', (Session::get('is_f') == "true") ? true : false)->get();
        } else {
            $users = User::where('referralId', new \MongoDB\BSON\ObjectID(Session::get('id')))->where('is_franchise', (Session::get('is_f') == "true") ? true : false)->get();
        }
        $user = User::where('_id', Session::get('id'))->get();
        $to = date('Y-m-d');

        $payment = [];
        $groupedData =[];
        $totalBet = 0;
        $totalWon = 0;
        $totalEndPoint =  0;

        if (isset($_GET['from']) && isset($_GET['to']) && empty($_GET['username']) && !empty($_GET['from']) && !empty($_GET['to'])) {
            // echo "not user";
            // die;
            $fm = date('m', strtotime($_GET['from']));
            $fd = date('d', strtotime($_GET['from']));
            $fY = date('Y', strtotime($_GET['from']));

            $tm = date('m', strtotime($_GET['to']));
            $td = date('d', strtotime($_GET['to']));
            $tY = date('Y', strtotime($_GET['to']));
            if (Session::get('role') == "Admin" || Session::get('role') == "subadmin") {
                $payment = Bets::whereBetween(
                    'createdAt',
                    array(
                        Carbon::create($fY, $fm, $fd, 00, 00, 00),
                        Carbon::create($tY, $tm, $td, 23, 59, 59),
                    )
                )->where('is_f', (Session::get('is_f') == "true") ? true : false)->orderBy('createdAt', 'DESC')->get();
                foreach ($payment as $key => $pay) {
                    $refer = User::where('_id', new \MongoDB\BSON\ObjectID($pay['fromId']))->first();
                    // echo "<pre>";
                    // $payment[$key]['createdAt'] = Carbon::parse( $pay['createdAt'] )->toDayDateTimeString();
                    $payment[$key]['userName'] = $refer['userName'];
                }
            } elseif (Session::get('role') == "agent" || Session::get('role') == "super_distributor" || Session::get('role') == "distributor" || Session::get('role') == "retailer") {
                $payment = Bets::where('toId', Session::get('id'))->orderBy('createdAt', 'DESC')
                    ->whereBetween(
                        'createdAt',
                        array(
                            Carbon::create($fY, $fm, $fd, 00, 00, 00),
                            Carbon::create($tY, $tm, $td, 23, 59, 59),
                        )
                    )->where('is_f', (Session::get('is_f') == "true") ? true : false)->get();
                foreach ($payment as $key => $pay) {
                    $refer = User::where('_id', new \MongoDB\BSON\ObjectID($pay['fromId']))->first();
                    // echo "<pre>";print_r($users);die();
                    // $payment[$key]['createdAt'] = Carbon::parse( $pay['createdAt'] )->toDayDateTimeString();
                    $payment[$key]['userName'] = $refer['userName'];
                }
            }


        } elseif (empty($_GET['from']) && empty($_GET['to']) && isset($_GET['username'])) {
            $payment = Bets::orderBy('createdAt', 'DESC')
                ->where('retailerId', new \MongoDB\BSON\ObjectID($_GET['username']))
                ->where('is_f', (Session::get('is_f') == "true") ? true : false)
                ->get();
            foreach ($payment as $key => $pay) {
                $refer = User::where('_id', new \MongoDB\BSON\ObjectID($pay['fromId']))->first();
                // echo "<pre>";print_r($users);die();
                // $payment[$key]['createdAt'] = Carbon::parse( $pay['createdAt'] )->toDayDateTimeString();
                $payment[$key]['userName'] = $refer['userName'];

            }

        } elseif (isset($_GET['from']) && isset($_GET['to']) && isset($_GET['username'])) {
            $fm = date('m', strtotime($_GET['from']));
            $fd = date('d', strtotime($_GET['from']));
            $fY = date('Y', strtotime($_GET['from']));
            $tm = date('m', strtotime($_GET['to']));
            $td = date('d', strtotime($_GET['to']));
            $tY = date('Y', strtotime($_GET['to']));

            if (Session::get('role') == "Admin" || Session::get('role') == "subadmin") {
                if (!$_GET['username'] == "") {
                    $user = User::where('_id', new \MongoDB\BSON\ObjectID($_GET['username']))->get()->toArray();
                    // dd($user);
                    $retailerId = new \MongoDB\BSON\ObjectID($_GET['username']);

                    $groupedData = Bets::whereBetween(
                        'createdAt',
                        [
                            Carbon::create($fY, $fm, $fd, 00, 00, 00),
                            Carbon::create($tY, $tm, $td, 23, 59, 59),
                        ]
                    )->where('retailerId', $retailerId)
                        ->select('won', 'bet', 'endPoint', 'DrDate', 'DrTime', 'createdAt', 'endPoint')
                        ->get()
                        ->map(function ($item) {
                            // Convert the createdAt timestamp to a valid datetime format
                            $item['createdAt'] = Carbon::createFromTimestampMs($item['createdAt'])->toDateString();
                            return $item;
                        })
                        ->groupBy('createdAt')
                        ->map(function ($group) {
                            return [
                                'totalBet' => collect($group)->sum('bet'),
                                'totalWon' => collect($group)->sum('won'),
                                'totalEndPoint' => collect($group)->sum('endPoint'),
                            ];
                        });

                    // Initialize total sums
                    $totalBet = 0;
                    $totalWon = 0;
                    $totalEndPoint = 0;

                    // Loop through the grouped data and accumulate the sums
                    foreach ($groupedData as $data) {
                        $totalBet += $data['totalBet'];
                        $totalWon += $data['totalWon'];
                        $totalEndPoint += $data['totalEndPoint'];
                    }
                } else {
                    $groupedData = [];
                }
            } elseif (Session::get('role') == "super_distributor" || Session::get('role') == "distributor") {
                // dd('hello');
                if (!$_GET['username'] == "") {
                    $user = User::where('_id', new \MongoDB\BSON\ObjectID($_GET['username']))->get()->toArray();
                    $retailerId = new \MongoDB\BSON\ObjectID($_GET['username']);
                    // dd($user);
                    $groupedData = Bets::whereBetween(
                        'createdAt',
                        [
                            Carbon::create($fY, $fm, $fd, 00, 00, 00),
                            Carbon::create($tY, $tm, $td, 23, 59, 59),
                        ]
                    )->where('retailerId', $retailerId)
                        ->select('won', 'bet', 'endPoint', 'DrDate', 'DrTime', 'createdAt', 'endPoint')
                        ->get()
                        ->map(function ($item) {
                            // Convert the createdAt timestamp to a valid datetime format
                            $item['createdAt'] = Carbon::createFromTimestampMs($item['createdAt'])->toDateString();
                            return $item;
                        })
                        ->groupBy('createdAt')
                        ->map(function ($group) {
                            return [
                                'totalBet' => collect($group)->sum('bet'),
                                'totalWon' => collect($group)->sum('won'),
                                'totalEndPoint' => collect($group)->sum('endPoint'),
                            ];
                        });


                    // Initialize total sums
                    $totalBet = 0;
                    $totalWon = 0;
                    $totalEndPoint = 0;

                    // Loop through the grouped data and accumulate the sums
                    foreach ($groupedData as $data) {
                        $totalBet += $data['totalBet'];
                        $totalWon += $data['totalWon'];
                        $totalEndPoint += $data['totalEndPoint'];
                    }
                } else {
                    $groupedData = [];
                }
            }
        }
        return view('admin.weekly', ['data' => $users, 'payment' => $payment, 'groupedData' => $groupedData, 'totalBet' => $totalBet, 'totalWon' => $totalWon, 'totalEndPoint' => $totalEndPoint]); //
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
