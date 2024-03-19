<?php

namespace App\Http\Controllers;

use App\Bets;
use App\User;
use Carbon\Carbon;
use Session;
use MongoDB\BSON\UTCDateTime;
use MongoDB\BSON\ObjectID;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PlayerReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if (Session::get('role') == "Admin" || Session::get('role') == "subadmin") {
            $users = User::where('role', '!=', 'Admin')->where('role', '!=', 'super_distributor')->where('role', '!=', 'distributor')->where('is_franchise', (Session::get('is_f') == "true") ? true : false)->get();
        } else {
            $users = User::where('referralId', new \MongoDB\BSON\ObjectID(Session::get('id')))->where('is_franchise', (Session::get('is_f') == "true") ? true : false)->get();
        }
        $user = User::where('_id', Session::get('id'))->get();
        $to = date('Y-m-d');

        $payment = [];

        if (isset($_GET['from']) && isset($_GET['to']) && empty($_GET['username']) && empty($_GET['from']) && !empty($_GET['to'])) {
            // echo "not user";
            // die;
            $fm = date('m', strtotime($_GET['from']));
            $fd = date('d', strtotime($_GET['from']));
            $fY = date('Y', strtotime($_GET['from']));

            $tm = date('m', strtotime($_GET['to']));
            $td = date('d', strtotime($_GET['to']));
            $tY = date('Y', strtotime($_GET['to']));
            if (Session::get('role') == "Admin" || Session::get('role') == "subadmin") {
                //->select('bet', 'won','endPoint' , 'retailerId' , 'createdAt','DrDate', 'userName')
                $groupBy = 'DrDate';
                $payments = Bets::raw(function ($collection) use ($fY, $fm, $fd, $tY, $tm, $td) {
                    return $collection->aggregate([
                        [
                            '$match' => [
                                'createdAt' => [
                                    '$gte' => new UTCDateTime(Carbon::create($fY, $fm, $fd, 00, 00, 00)->timestamp * 1000),
                                    '$lte' => new UTCDateTime(Carbon::create($tY, $tm, $td, 23, 59, 59)->timestamp * 1000),
                                ],
                            ],
                        ],
                        [
                            '$group' => [
                                '_id' => '$playerId',
                                'userName' => ['$first' => '$userName'],
                                'createdAt' => ['$first' => '$createdAt'],
                                'totalBet' => ['$sum' => '$bet'],
                                'totalWon' => ['$sum' => '$won'],
                                'totalEndPoint' => ['$sum' => '$endPoint'],
                            ],
                        ],
                        [
                            '$project' => [
                                '_id' => 0,
                                'playerId' => '$_id',
                                'userName' => 1,
                                'createdAt' => 1,
                                'totalBet' => 1,
                                'totalWon' => 1,
                                'totalEndPoint' => 1,
                            ],
                        ],
                    ]);
                })->toArray();
                // ->groupBy( $groupBy);

                // $totalProfit = [];
                // $totalEndPoint = [];
                // foreach ($payments as $key => $sum) {
                //     $totalProfit[] = $sum['won'] - $sum['bet'];
                //     $totalEndPoint[] = $sum['endPoint'];

                // }
                // $totalProfit = array_sum($totalProfit);
                // $totalEndPoint = array_sum($totalEndPoint);
                // $payments = [
                //     'sum' => $payments,
                //     'user' => $user,
                //     'totalProfit' => $totalProfit,
                //     'totalEndPoint' => $totalEndPoint,
                // ];


                    // dd($users['_id']);
                    $userGroup = $payments;
                            // dd($userGroup);
                } elseif (Session::get('role') == "agent" || Session::get('role') == "super_distributor" || Session::get('role') == "distributor" || Session::get('role') == "retailer") {

                    // $user = User::where('referralId', new \MongoDB\BSON\ObjectID(Session::get('id')))
                    // ->select('_id', 'name', 'userName')
                    // ->get()->toArray();

                    // $payments = Bets::whereBetween(
                    //     'createdAt',
                    //     [
                    //         Carbon::create($fY, $fm, $fd, 0, 0, 0),
                    //         Carbon::create($tY, $tm, $td, 23, 59, 59),
                    //     ]
                    // )->whereIn('retailerId', array_map(function($user) {
                    //     return new \MongoDB\BSON\ObjectID($user['_id']);
                    // }, $user))
                    // ->select('bet', 'won','endPoint' , 'retailerId' , 'createdAt','DrDate', 'userName')->groupBy('DrDate')->get();

                    $user = User::where('referralId', new ObjectID(Session::get('id')))
                    ->select('_id', 'name', 'userName')
                    ->get()
                    ->toArray();

                // Extract user IDs to use in the aggregation
                $userIds = array_map(function ($user) {
                    return new ObjectID($user['_id']);
                }, $user);

                // MongoDB aggregation query
                $payments = Bets::raw(function ($collection) use ($fY, $fm, $fd, $tY, $tm, $td, $userIds) {
                    return $collection->aggregate([
                        [
                            '$match' => [
                                'createdAt' => [
                                    '$gte' => new UTCDateTime(Carbon::create($fY, $fm, $fd, 0, 0, 0)->timestamp * 1000),
                                    '$lte' => new UTCDateTime(Carbon::create($tY, $tm, $td, 23, 59, 59)->timestamp * 1000),
                                ],
                                'playerId' => ['$in' => $userIds],
                            ],
                        ],
                        [
                            '$group' => [
                                '_id' => ['$dateToString' => ['format' => '%Y-%m-%d', 'date' => '$createdAt']],
                                'totalBet' => ['$sum' => '$bet'],
                                'totalWon' => ['$sum' => '$won'],
                                'totalEndPoint' => ['$first' => '$endPoint'],
                                'playerId' => ['$first' => '$playerId'],
                                'userName' => ['$first' => '$userName'],
                                'createdAt' => ['$first' => '$createdAt'],
                                'DrDate' => ['$first' => '$DrDate'],
                            ],
                        ],
                        [
                            '$project' => [
                                '_id' => 0,
                                'DrDate' => '$_id',
                                'totalBet' => 1,
                                'totalWon' => 1,
                                'totalEndPoint' => 1,
                                'playerId' => 1,
                                'userName' => 1,
                                'createdAt' => 1,
                            ],
                        ],
                    ]);
                })->toArray();










                    // $user = User::where('referralId',  new \MongoDB\BSON\ObjectID(Session::get('id')))->select('userName', '_id', 'name')->get()->toArray();
                    // dd('hello',$user);
                    // echo ""

                    // $userData = [];

                    // foreach ($classic as $cal_user) {
                    //     $players[] = new \MongoDB\BSON\ObjectID($cal_user['_id']);
                    // }
                        // $i=0;
                        //                     foreach ($user as $usr) {
                        //                         // $bets = Bets::where('retailerId', new \MongoDB\BSON\ObjectID($usr->_id))->get();
                        //                     //    echo new \MongoDB\BSON\ObjectID($usr['_id']);
                        //                        // $usersData =new \MongoDB\BSON\ObjectID($usr['_id']);
                        //                           $userData[$i]['userdata']=$usr;
                        //                           $userData[$i]['bets'] = Bets::select('bet', 'won','endPoint' , 'retailerId' , 'createdAt','DrDate', 'userName')
                        //                                 ->where('retailerId', new \MongoDB\BSON\ObjectID($usr['_id']))
                        //                                 ->groupBy(['retailerId','DrDate'])->get()->toArray();


                        //      $i++;                       }

                    // $payments = Bets::whereBetween(
                    //     'createdAt',
                    //     array(
                    //         Carbon::create($fY, $fm, $fd, 00, 00, 00),
                    //         Carbon::create($tY, $tm, $td, 23, 59, 59),
                    //     )
                    // )->select('bet', 'won','endPoint' , 'retailerId' , 'createdAt','DrDate', 'userName')
                    // ->groupBy(['retailerId','DrDate'])->get();

                    // echo "<pre>";
                    // print_r($payments);
                    // die;
                    // dd($userData);
                    //   dd($usersData);
                    // echo "<pre>";
                    // print_r($user);
                    // echo "After For loop <br>";
                    // print_r($usr);
                    // die;
                    // $ObjId = new \MongoDB\BSON\ObjectID($usr['_id']);
                    // dd($ObjId);

                    // dd($payments);



                    // $payments = Bets::whereBetween(
                    //     'createdAt',
                    //         [
                    //         Carbon::create($fY, $fm, $fd, 0, 0, 0),
                    //         Carbon::create($tY, $tm, $td, 23, 59, 59),
                    //         ]
                    //         )->select('bet', 'won','endPoint' , 'retailerId' , 'createdAt','DrDate', 'userName')
                    //         ->where('retailerId', $usr)
                    //         ->groupBy(['retailerId','DrDate'])->get();
                            $userGroup = $payments;

                            // dd($userGroup, Session::get('id'));
            }
        } elseif (empty($_GET['from']) && empty($_GET['to']) && isset($_GET['username'])) {
            $payment = Bets::orderBy('createdAt', 'DESC')
                ->where('playerId', new \MongoDB\BSON\ObjectID($_GET['username']))
                ->where('is_f', (Session::get('is_f') == "true") ? true : false)
                ->get();
            foreach ($payment as $key => $pay) {
                $refer = User::where('_id', new \MongoDB\BSON\ObjectID($pay['fromId']))->first();
                // echo "<pre>";print_r($users);die();
                // $payment[$key]['createdAt'] = Carbon::parse( $pay['createdAt'] )->toDayDateTimeString();
                $payment[$key]['userName'] = $refer['userName'];

            }
                 // dd($payment);

        } elseif (isset($_GET['from']) && isset($_GET['to']) && isset($_GET['username'])) {
            $fm = date('m', strtotime($_GET['from']));
            $fd = date('d', strtotime($_GET['from']));
            $fY = date('Y', strtotime($_GET['from']));
            $tm = date('m', strtotime($_GET['to']));
            $td = date('d', strtotime($_GET['to']));
            $tY = date('Y', strtotime($_GET['to']));

            if (Session::get('role') == "Admin" || Session::get('role') == "subadmin" ) {
                if(!$_GET['username'] == "") {
                    $user = User::where('_id', new \MongoDB\BSON\ObjectID($_GET['username']))->get()->toArray();

                    // dd($user);
                    $Sum = Bets::whereBetween(
                        'createdAt',
                        [
                            Carbon::create($fY, $fm, $fd, 0, 0, 0),
                            Carbon::create($tY, $tm, $td, 23, 59, 59),
                        ]
                    )->where('playerId', new \MongoDB\BSON\ObjectID($_GET['username']))
                        ->get()
                        ->groupBy('DrDate')
                        ->map(function ($group) {
                            return [
                                'DrDate' => $group->first()->DrDate,
                                'won' => $group->sum('won'),
                                'bet' => $group->sum('bet'),
                                'endPoint' => $group->first()->endPoint,
                                'DrTime' => $group->first()->DrTime,
                                'createdAt' => $group->first()->createdAt,
                            ];
                        })
                        ->values();

                        $totalProfit = [];
                        $totalEndPoint = [];
                        foreach ($Sum as $key => $sum) {
                            $totalProfit[] = $sum['won'] - $sum['bet'];
                            $totalEndPoint[] = $sum['endPoint'];

                        }
                        $totalProfit = array_sum($totalProfit);
                        $totalEndPoint = array_sum($totalEndPoint);

                        // dd( $totalProfit);
                        // dd(array_sum($totalProfit), $totalProfit);
                        $payment = [
                            'sum' => $Sum,
                            'user' => $user,
                            'totalProfit' => $totalProfit,
                            'totalEndPoint' => $totalEndPoint,
                        ];

                } else {

                    $payments = Bets::raw(function ($collection) use ($fY, $fm, $fd, $tY, $tm, $td) {
                        return $collection->aggregate([
                            [
                                '$match' => [
                                    'createdAt' => [
                                        '$gte' => new UTCDateTime(Carbon::create($fY, $fm, $fd, 00, 00, 00)->timestamp * 1000),
                                        '$lte' => new UTCDateTime(Carbon::create($tY, $tm, $td, 23, 59, 59)->timestamp * 1000),
                                    ],
                                ],
                            ],
                            [
                                '$group' => [
                                    '_id' => '$playerId',
                                    'userName' => ['$first' => '$userName'],
                                    'createdAt' => ['$first' => '$createdAt'],
                                    'totalBet' => ['$sum' => '$bet'],
                                    'totalWon' => ['$sum' => '$won'],
                                    'totalEndPoint' => ['$sum' => '$endPoint'],
                                ],
                            ],
                            [
                                '$project' => [
                                    '_id' => 0,
                                    'playerId' => '$_id',
                                    'userName' => 1,
                                    'createdAt' => 1,
                                    'totalBet' => 1,
                                    'totalWon' => 1,
                                    'totalEndPoint' => 1,
                                ],
                            ],
                        ]);
                    })->toArray();
                    $userGroup = $payments;
                }


                // dd($payment);
                // print_r($payment->toArray());
                // die;
            } elseif (Session::get('role') == "super_distributor" || Session::get('role') == "distributor") {
                if(!$_GET['username'] == "") {
                    $user = User::where('_id', new \MongoDB\BSON\ObjectID($_GET['username']))->get()->toArray();
                    // dd($user);
                    $Sum = Bets::whereBetween(
                        'createdAt',
                        [
                            Carbon::create($fY, $fm, $fd, 0, 0, 0),
                            Carbon::create($tY, $tm, $td, 23, 59, 59),
                        ]
                    )->where('playerId', new \MongoDB\BSON\ObjectID($_GET['username']))
                        ->get()
                        ->groupBy('DrDate')
                        ->map(function ($group) {
                            return [
                                'DrDate' => $group->first()->DrDate,
                                'won' => $group->sum('won'),
                                'bet' => $group->sum('bet'),
                                'endPoint' => $group->first()->endPoint,
                                'DrTime' => $group->first()->DrTime,
                                'createdAt' => $group->first()->createdAt,
                            ];
                        })
                        ->values();

                        $totalProfit = [];
                        $totalEndPoint = [];
                        foreach ($Sum as $key => $sum) {
                            $totalProfit[] = $sum['won'] - $sum['bet'];
                            $totalEndPoint[] = $sum['endPoint'];

                        }
                        $totalProfit = array_sum($totalProfit);
                        $totalEndPoint = array_sum($totalEndPoint);

                        // dd( $totalProfit);
                        // dd(array_sum($totalProfit), $totalProfit);
                        $payment = [
                            'sum' => $Sum,
                            'user' => $user,
                            'totalProfit' => $totalProfit,
                            'totalEndPoint' => $totalEndPoint,
                        ];
                } else {
                    $user = User::where('referralId', new ObjectID(Session::get('id')))
                    ->select('_id', 'name', 'userName')
                    ->get()
                    ->toArray();

                // Extract user IDs to use in the aggregation
                $userIds = array_map(function ($user) {
                    return new ObjectID($user['_id']);
                }, $user);

                // MongoDB aggregation query
                $payments = Bets::raw(function ($collection) use ($fY, $fm, $fd, $tY, $tm, $td, $userIds) {
                    return $collection->aggregate([
                        [
                            '$match' => [
                                'createdAt' => [
                                    '$gte' => new UTCDateTime(Carbon::create($fY, $fm, $fd, 0, 0, 0)->timestamp * 1000),
                                    '$lte' => new UTCDateTime(Carbon::create($tY, $tm, $td, 23, 59, 59)->timestamp * 1000),
                                ],
                                'playerId' => ['$in' => $userIds],
                            ],
                        ],
                        [
                            '$group' => [
                                '_id' => ['$dateToString' => ['format' => '%Y-%m-%d', 'date' => '$createdAt']],
                                'totalBet' => ['$sum' => '$bet'],
                                'totalWon' => ['$sum' => '$won'],
                                'totalEndPoint' => ['$first' => '$endPoint'],
                                'playerId' => ['$first' => '$playerId'],
                                'userName' => ['$first' => '$userName'],
                                'createdAt' => ['$first' => '$createdAt'],
                                'DrDate' => ['$first' => '$DrDate'],
                            ],
                        ],
                        [
                            '$project' => [
                                '_id' => 0,
                                'DrDate' => '$_id',
                                'totalBet' => 1,
                                'totalWon' => 1,
                                'totalEndPoint' => 1,
                                'playerId' => 1,
                                'userName' => 1,
                                'createdAt' => 1,
                            ],
                        ],
                    ]);
                })->toArray();
                        // dd($payments);
                    // $payment = []; // Set an empty array if username is not set
                    $userGroup = $payments;
                }
            }
        }

        return view('admin.playerReport', ['data' => $users, 'payment' => $payment, 'userGroup'=>$userGroup ?? [],  'user' => $user, ]); //'won'=>$won?? 0, 'bet'=>$bet?? 0, 'endPoint' =>$endPoint?? 0
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
        $user = $id;

        //  $to = date('Y-m-d');
        // // dd($user)
        // $fm = date('m', strtotime($_GET['from']));
        //     $fd = date('d', strtotime($_GET['from']));
        //     $fY = date('Y', strtotime($_GET['from']));
        //     $tm = date('m', strtotime($_GET['to']));
        //     $td = date('d', strtotime($_GET['to']));
        //     $tY = date('Y', strtotime($_GET['to']));

        // MongoDB aggregation query
        $payments = Bets::raw(function ($collection) use ($user) {
            return $collection->aggregate([
                [
                    '$match' => [
                        // 'createdAt' => [
                        //     '$gte' => new UTCDateTime(Carbon::create($fY, $fm, $fd, 0, 0, 0)->timestamp * 1000),
                        //     '$lte' => new UTCDateTime(Carbon::create($tY, $tm, $td, 23, 59, 59)->timestamp * 1000),
                        // ],
                        'playerId' => new ObjectID($user),
                    ],
                ],
                [
                    '$group' => [
                        '_id' => ['$dateToString' => ['format' => '%Y-%m-%d', 'date' => '$createdAt']],
                        'totalBet' => ['$sum' => '$bet'],
                        'totalWon' => ['$sum' => '$won'],
                        'endPoint' => ['$first' => '$endPoint'],
                        'playerId' => ['$first' => '$playerId'],
                        'userName' => ['$first' => '$userName'],
                        'createdAt' => ['$first' => '$createdAt'],
                        'DrDate' => ['$first' => '$DrDate'],
                    ],
                ],
                [
                    '$project' => [
                        '_id' => 0,
                        'DrDate' => '$_id',
                        'totalBet' => 1,
                        'totalWon' => 1,
                        'endPoint' => 1,
                        'playerId' => 1,
                        'userName' => 1,
                        'createdAt' => 1,
                    ],
                ],
            ]);
        })->toArray();
        $userGroup = $payments;
        // dd($payments);
        return view('Genreport', ['userGroup'=>$userGroup ?? [],  'user' => $user, ]); //'won'=>$won?? 0, 'bet'=>$bet?? 0, 'endPoint' =>$endPoint?? 0
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



    public function Bets()
    {
        if (Session::get('role') == "Admin" || Session::get('role') == "subadmin") {
            $users = User::where('role', 'retailer')->get();
        } else {
            if(Session::get('role') == "agent" || Session::get('role') == "super_distributor" || Session::get('role') == "distributor"){
                $users = User::where('referralId', new \MongoDB\BSON\ObjectID(Session::get('id')))->get();
            }else{
                $users = User::where('_id', new \MongoDB\BSON\ObjectID(Session::get('id')))->get();
            }
        }
        $user = User::where('_id', Session::get('id'))->get();
        $to = date('Y-m-d');
        // dd($users);
        $payment = [];
        if (isset($_GET['from']) && isset($_GET['to']) && empty($_GET['username']) && empty($_GET['from']) && !empty($_GET['to'])) {
            // echo "not user";
            // die;
            $fm = date('m', strtotime($_GET['from']));
            $fd = date('d', strtotime($_GET['from']));
            $fY = date('Y', strtotime($_GET['from']));
            $tm = date('m', strtotime($_GET['to']));
            $td = date('d', strtotime($_GET['to']));
            $tY = date('Y', strtotime($_GET['to']));
            if (Session::get('role') == "Admin" || Session::get('role') == "subadmin") {

                $numBets = Bets::raw(function ($collection) use ($fY, $fm, $fd, $tY, $tm, $td) { //$retailerId
                    $fromDate = new UTCDateTime(Carbon::create($fY, $fm, $fd, 0, 0, 0)->timestamp * 1000);
                    $toDate = new UTCDateTime(Carbon::create($tY, $tm, $td, 23, 59, 59)->timestamp * 1000);

                    return $collection->aggregate([
                        [
                            '$match' => [
                                'createdAt' => [
                                    '$gte' => $fromDate,
                                    '$lt' => $toDate,
                                ],
                                // 'retailerId' => $retailerId, // Add this line to match retailerId
                            ],
                        ],
                        [
                            '$addFields' => [
                                'positionsArray' => ['$objectToArray' => '$position'],
                            ],
                        ],
                        [
                            '$unwind' => '$positionsArray',
                        ],
                        [
                            '$group' => [
                                '_id' => [
                                    'userName' => '$userName',
                                    'DrDate' => '$DrDate',
                                    'position' => '$positionsArray.k',
                                ],
                                'total' => ['$sum' => ['$toInt' => '$positionsArray.v']],
                                'createdAt' => ['$first' => '$createdAt'],
                                'bet' => ['$sum' => ['$toInt' => '$bet']],
                                'won' => ['$sum' => ['$toInt' => '$won']], // Add this line to include 'bet' field
                            ],
                        ],
                        [
                            '$group' => [
                                '_id' => [
                                    'userName' => '$_id.userName',
                                    'DrDate' => '$_id.DrDate',
                                ],
                                'createdAt' => ['$first' => '$createdAt'],
                                'positions' => [
                                    '$push' => [
                                        'k' => '$_id.position',
                                        'v' => '$total',
                                    ],
                                ],
                                'bet' => ['$first' => '$bet'],
                                'won' => ['$first' => '$won'], // Add this line to include 'bet' field
                            ],
                        ],
                        [
                            '$sort' => ['_id.userName' => 1, '_id.DrDate' => 1],
                        ],
                        [
                            '$project' => [
                                '_id' => 0,
                                'userName' => '$_id.userName',
                                'DrDate' => '$_id.DrDate',
                                'createdAt' => [
                                    '$dateToString' => [
                                        'format' => '%Y-%m-%d', // Adjust the format as needed
                                        'date' => '$createdAt',
                                    ],
                                ],
                                'positions' => [
                                    '$arrayToObject' => '$positions',
                                ],
                                'bet' => 1,
                                'won' => 1, // Add this line to include 'bet' field
                            ],
                        ],
                    ]);
                })->toArray();
                // dd($numBets);
                // Convert BSON types to regular PHP arrays
                $numBets = json_decode(json_encode($numBets), true);
                foreach ($numBets as &$bet) {
                    if (isset($bet['positions'])) {
                        ksort($bet['positions']);
                    }
                }
                // dd($numBets);

            } elseif (Session::get('role') == "super_distributor" || Session::get('role') == "distributor") {
                // dd('hello');

                $usersBets = User::where('referralId', new \MongoDB\BSON\ObjectID(Session::get('id')))->get()->toArray();
                // dd($usersBets);
                $retailerIds = [];

                foreach ($usersBets as $key => $value) {
                    $retailerIds[] = new \MongoDB\BSON\ObjectID($value['_id']);
                }

                $numBets = Bets::raw(function ($collection) use ($fY, $fm, $fd, $tY, $tm, $td, $retailerIds) { //, $retailerId
                    $fromDate = new UTCDateTime(Carbon::create($fY, $fm, $fd, 0, 0, 0)->timestamp * 1000);
                    $toDate = new UTCDateTime(Carbon::create($tY, $tm, $td, 23, 59, 59)->timestamp * 1000);

                    return $collection->aggregate([
                        [
                            '$match' => [
                                'createdAt' => [
                                    '$gte' => $fromDate,
                                    '$lt' => $toDate,
                                ],
                                'playerId' => ['$in' => $retailerIds],
                            ],
                        ],
                        [
                            '$addFields' => [
                                'positionsArray' => ['$objectToArray' => '$position'],
                            ],
                        ],
                        [
                            '$unwind' => '$positionsArray',
                        ],
                        [
                            '$group' => [
                                '_id' => [
                                    'userName' => '$userName',
                                    'DrDate' => '$DrDate',
                                    'position' => '$positionsArray.k',
                                ],
                                'total' => ['$sum' => ['$toInt' => '$positionsArray.v']],
                                'createdAt' => ['$first' => '$createdAt'],
                                'bet' => ['$sum' => ['$toInt' => '$bet']],
                                'won' => ['$sum' => ['$toInt' => '$won']], // Add this line to include 'bet' field
                            ],
                        ],
                        [
                            '$group' => [
                                '_id' => [
                                    'userName' => '$_id.userName',
                                    'DrDate' => '$_id.DrDate',
                                ],
                                'createdAt' => ['$first' => '$createdAt'],
                                'positions' => [
                                    '$push' => [
                                        'k' => '$_id.position',
                                        'v' => '$total',
                                    ],
                                ],
                                'bet' => ['$first' => '$bet'],
                                'won' => ['$first' => '$won'], // Add this line to include 'bet' field
                            ],
                        ],
                        [
                            '$sort' => ['_id.userName' => 1, '_id.DrDate' => 1],
                        ],
                        [
                            '$project' => [
                                '_id' => 0,
                                'userName' => '$_id.userName',
                                'DrDate' => '$_id.DrDate',
                                'createdAt' => [
                                    '$dateToString' => [
                                        'format' => '%Y-%m-%d', // Adjust the format as needed
                                        'date' => '$createdAt',
                                    ],
                                ],
                                'positions' => [
                                    '$arrayToObject' => '$positions',
                                ],
                                'bet' => 1,
                                'won' => 1, // Add this line to include 'bet' field
                            ],
                        ],
                    ]);
                })->toArray();
                // dd($numBets);
                // Convert BSON types to regular PHP arrays
                $numBets = json_decode(json_encode($numBets), true);
                foreach ($numBets as &$bet) {
                    if (isset($bet['positions'])) {
                        ksort($bet['positions']);
                    }
                }
            } elseif (Session::get('role') == "retailer") {
                // dd('hello');

                $usersBets = User::where('_id', new \MongoDB\BSON\ObjectID(Session::get('id')))->get()->toArray();
                // dd($usersBets);
                $retailerIds = [];

                foreach ($usersBets as $key => $value) {
                    $retailerIds[] = new \MongoDB\BSON\ObjectID($value['_id']);
                }

                $numBets = Bets::raw(function ($collection) use ($fY, $fm, $fd, $tY, $tm, $td, $retailerIds) { //, $retailerId
                    $fromDate = new UTCDateTime(Carbon::create($fY, $fm, $fd, 0, 0, 0)->timestamp * 1000);
                    $toDate = new UTCDateTime(Carbon::create($tY, $tm, $td, 23, 59, 59)->timestamp * 1000);

                    return $collection->aggregate([
                        [
                            '$match' => [
                                'createdAt' => [
                                    '$gte' => $fromDate,
                                    '$lt' => $toDate,
                                ],
                                'playerId' => ['$in' => $retailerIds],
                            ],
                        ],
                        [
                            '$addFields' => [
                                'positionsArray' => ['$objectToArray' => '$position'],
                            ],
                        ],
                        [
                            '$unwind' => '$positionsArray',
                        ],
                        [
                            '$group' => [
                                '_id' => [
                                    'userName' => '$userName',
                                    'DrDate' => '$DrDate',
                                    'position' => '$positionsArray.k',
                                ],
                                'total' => ['$sum' => ['$toInt' => '$positionsArray.v']],
                                'createdAt' => ['$first' => '$createdAt'],
                                'bet' => ['$sum' => ['$toInt' => '$bet']],
                                'won' => ['$sum' => ['$toInt' => '$won']], // Add this line to include 'bet' field
                            ],
                        ],
                        [
                            '$group' => [
                                '_id' => [
                                    'userName' => '$_id.userName',
                                    'DrDate' => '$_id.DrDate',
                                ],
                                'createdAt' => ['$first' => '$createdAt'],
                                'positions' => [
                                    '$push' => [
                                        'k' => '$_id.position',
                                        'v' => '$total',
                                    ],
                                ],
                                'bet' => ['$first' => '$bet'],
                                'won' => ['$first' => '$won'], // Add this line to include 'bet' field
                            ],
                        ],
                        [
                            '$sort' => ['_id.userName' => 1, '_id.DrDate' => 1],
                        ],
                        [
                            '$project' => [
                                '_id' => 0,
                                'userName' => '$_id.userName',
                                'DrDate' => '$_id.DrDate',
                                'createdAt' => [
                                    '$dateToString' => [
                                        'format' => '%Y-%m-%d', // Adjust the format as needed
                                        'date' => '$createdAt',
                                    ],
                                ],
                                'positions' => [
                                    '$arrayToObject' => '$positions',
                                ],
                                'bet' => 1,
                                'won' => 1, // Add this line to include 'bet' field
                            ],
                        ],
                    ]);
                })->toArray();
                // dd($numBets);
                // Convert BSON types to regular PHP arrays
                $numBets = json_decode(json_encode($numBets), true);
                foreach ($numBets as &$bet) {
                    if (isset($bet['positions'])) {
                        ksort($bet['positions']);
                    }
                }
                // dd($numBets);
            }
        } elseif (empty($_GET['from']) && empty($_GET['to']) && isset($_GET['username'])) {
            $payment = Payments::orderBy('createdAt', 'DESC')
                ->where('toId', $_GET['username'])
                ->where('is_f', (Session::get('is_f') == "true") ? true : false)
                ->get();
            // dd('hello');
            foreach ($payment as $key => $pay) {
                $refer = User::where('_id', new \MongoDB\BSON\ObjectID($pay['fromId']))->first();
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
                // dd('hello');
                // $retailerId = $_GET['username'];
                // dd($retailerId, $_GET['username']);

                $retailerId = new \MongoDB\BSON\ObjectID($_GET['username']);

                $numBetsUser = Bets::raw(function ($collection) use ($fY, $fm, $fd, $tY, $tm, $td, $retailerId) { //, $retailerId
                    $fromDate = new UTCDateTime(Carbon::create($fY, $fm, $fd, 0, 0, 0)->timestamp * 1000);
                    $toDate = new UTCDateTime(Carbon::create($tY, $tm, $td, 23, 59, 59)->timestamp * 1000);

                    return $collection->aggregate([
                        [
                            '$match' => [
                                'createdAt' => [
                                    '$gte' => $fromDate,
                                    '$lt' => $toDate,
                                ],
                                'playerId' => $retailerId,
                            ],
                        ],
                        // [
                        //     '$addFields' => [
                        //         'positionsArray' => ['$objectToArray' => '$position'],
                        //     ],
                        // ],
                        // [
                        //     '$unwind' => '$positionsArray',
                        // ],
                        // [
                        //     '$group' => [
                        //         '_id' => [
                        //             'userName' => '$userName',
                        //             'DrDate' => '$DrDate',
                        //             'position' => '$positionsArray.k',
                        //         ],
                        //         'total' => ['$sum' => ['$toInt' => '$positionsArray.v']],
                        //         'createdAt' => ['$first' => '$createdAt'],
                        //         'bet' => ['$sum' => ['$toInt' => '$bet']],
                        //         'won' => ['$sum' => ['$toInt' => '$won']], // Add this line to include 'bet' field
                        //     ],
                        // ],

                        [
                            '$sort' => ['_id.userName' => 1, '_id.DrDate' => 1],
                        ],
                        [
                            '$project' => [
                                '_id' => 0,
                                'userName' => '$userName',
                                'DrDate' => '$DrDate',
                                'createdAt' => [
                                    '$dateToString' => [
                                        'format' => '%Y-%m-%d', // Adjust the format as needed
                                        'date' => '$createdAt',
                                    ],
                                ],
                                // 'positions' => [
                                //     '$arrayToObject' => '$positions',
                                // ],
                                'bet' => 1,
                                'won' => 1, // Add this line to include 'bet' field
                            ],
                        ],
                    ]);
                })->toArray();
                // dd($numBets);
                // Convert BSON types to regular PHP arrays
                $numBetsUser = json_decode(json_encode($numBetsUser), true);
                foreach ($numBetsUser as &$bet) {
                    if (isset($bet['positions'])) {
                        ksort($bet['positions']);
                    }
                }                // dd($payment);


            } elseif (Session::get('role') == "agent" || Session::get('role') == "super_distributor" || Session::get('role') == "distributor" || Session::get('role') == "retailer") {

                // dd('hello');
                if ($_GET['username'] == "" ) {
                    $usersBets = User::where('referralId', new \MongoDB\BSON\ObjectID(Session::get('id')))->get()->toArray();

                    $retailerIds = [];

                    foreach ($usersBets as $key => $value) {
                        $retailerIds[] = new \MongoDB\BSON\ObjectID($value['_id']);
                    }


                    $numBets = Bets::raw(function ($collection) use ($fY, $fm, $fd, $tY, $tm, $td, $retailerIds) { //, $retailerId
                        $fromDate = new UTCDateTime(Carbon::create($fY, $fm, $fd, 0, 0, 0)->timestamp * 1000);
                        $toDate = new UTCDateTime(Carbon::create($tY, $tm, $td, 23, 59, 59)->timestamp * 1000);

                        return $collection->aggregate([
                            [
                                '$match' => [
                                    'createdAt' => [
                                        '$gte' => $fromDate,
                                        '$lt' => $toDate,
                                    ],
                                    'playerId' => ['$in' => $retailerIds],
                                ],
                            ],
                            [
                                '$addFields' => [
                                    'positionsArray' => ['$objectToArray' => '$position'],
                                ],
                            ],
                            [
                                '$unwind' => '$positionsArray',
                            ],
                            [
                                '$group' => [
                                    '_id' => [
                                        'userName' => '$userName',
                                        'DrDate' => '$DrDate',
                                        'position' => '$positionsArray.k',
                                    ],
                                    'total' => ['$sum' => ['$toInt' => '$positionsArray.v']],
                                    'createdAt' => ['$first' => '$createdAt'],
                                    'bet' => ['$sum' => ['$toInt' => '$bet']],
                                    'won' => ['$sum' => ['$toInt' => '$won']], // Add this line to include 'bet' field
                                ],
                            ],
                            [
                                '$group' => [
                                    '_id' => [
                                        'userName' => '$_id.userName',
                                        'DrDate' => '$_id.DrDate',
                                    ],
                                    'createdAt' => ['$first' => '$createdAt'],
                                    'positions' => [
                                        '$push' => [
                                            'k' => '$_id.position',
                                            'v' => '$total',
                                        ],
                                    ],
                                    'bet' => ['$first' => '$bet'],
                                    'won' => ['$first' => '$won'], // Add this line to include 'bet' field
                                ],
                            ],
                            [
                                '$sort' => ['_id.userName' => 1, '_id.DrDate' => 1],
                            ],
                            [
                                '$project' => [
                                    '_id' => 0,
                                    'userName' => '$_id.userName',
                                    'DrDate' => '$_id.DrDate',
                                    'createdAt' => [
                                        '$dateToString' => [
                                            'format' => '%Y-%m-%d', // Adjust the format as needed
                                            'date' => '$createdAt',
                                        ],
                                    ],
                                    'positions' => [
                                        '$arrayToObject' => '$positions',
                                    ],
                                    'bet' => 1,
                                    'won' => 1, // Add this line to include 'bet' field
                                ],
                            ],
                        ]);
                    })->toArray();
                    // dd($numBets);
                    // Convert BSON types to regular PHP arrays
                    $numBets = json_decode(json_encode($numBets), true);
                    foreach ($numBets as &$bet) {
                        if (isset($bet['positions'])) {
                            ksort($bet['positions']);
                        }
                    }
                } else {
                    // dd('hello');
                    // $usersBets = User::where('referralId', new \MongoDB\BSON\ObjectID(Session::get('id')))->get()->toArray();

                    // $retailerIds = [];

                    // foreach ($usersBets as $key => $value) {
                    //     $retailerIds[] = new \MongoDB\BSON\ObjectID($value['_id']);
                    // }

                    $retailerId = new \MongoDB\BSON\ObjectID($_GET['username']);

                    $numBetsUser = Bets::raw(function ($collection) use ($fY, $fm, $fd, $tY, $tm, $td, $retailerId) {
                        $fromDate = new UTCDateTime(Carbon::create($fY, $fm, $fd, 0, 0, 0)->timestamp * 1000);
                        $toDate = new UTCDateTime(Carbon::create($tY, $tm, $td, 23, 59, 59)->timestamp * 1000);

                        return $collection->aggregate([
                            [
                                '$match' => [
                                    'createdAt' => [
                                        '$gte' => $fromDate,
                                        '$lt' => $toDate,
                                    ],
                                    'playerId' => $retailerId,
                                ],
                            ],
                            [
                                '$addFields' => [
                                    'positionsArray' => ['$objectToArray' => '$position'],
                                ],
                            ],
                            [
                                '$unwind' => '$positionsArray',
                            ],
                            [
                                '$group' => [
                                    '_id' => [
                                        'userName' => '$userName',
                                        'position' => '$positionsArray.k',
                                        'DrDate' => '$DrDate',
                                    ],
                                    'winPosition' => ['$first' => '$winPosition'],
                                    'total' => ['$sum' => ['$toInt' => '$positionsArray.v']],
                                    'createdAt' => ['$first' => '$createdAt'],
                                    'bet' => ['$sum' => ['$toInt' => '$bet']],
                                    'won' => ['$sum' => ['$toInt' => '$won']],
                                ],
                            ],
                            [
                                '$group' => [
                                    '_id' => [
                                        'DrDate' => '$_id.DrDate',
                                    ],
                                    'createdAt' => ['$first' => '$createdAt'],
                                    'positions' => [
                                        '$push' => [
                                            'k' => '$_id.position',
                                            'v' => '$total',
                                        ],
                                    ],
                                    'winPosition' => ['$first' => '$winPosition'],
                                    // 'bet' => ['$sum' => '$bet'], // Adjust if needed
                                    // 'won' => ['$sum' => '$won'], // Adjust if needed
                                ],
                            ],
                            [
                                '$sort' => ['_id.DrDate' => 1],
                            ],
                            [
                                '$project' => [
                                    '_id' => 0,
                                    'DrDate' => '$_id.DrDate',
                                    'createdAt' => [
                                        '$dateToString' => [
                                            'format' => '%Y-%m-%d',
                                            'date' => '$createdAt',
                                        ],
                                    ],
                                    'positions' => [
                                        '$arrayToObject' => '$positions',
                                    ],
                                    'winPosition' => 1,
                                    'bet' => 1,
                                    'won' => 1,
                                ],
                            ],
                        ]);
                    })->toArray();

                    // dd($numBetsUser);
                    // Convert BSON types to regular PHP arrays
                    $numBetsUser = json_decode(json_encode($numBetsUser), true);
                    foreach ($numBetsUser as &$bet) {
                        if (isset($bet['positions'])) {
                            ksort($bet['positions']);
                        }
                    }
                    // dd($numBetsUser);
                }
                // dd($payment, $_GET['username']);


                // dd($payment, Session::get('id'), $_GET['username'] );
            }
        }

        // dd('hello');
        return view('BetReports', ['data' => $users, 'numBets' => $numBets ?? [], 'betUser' => $numBetsUser ?? [], 'user' => $user]);
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
