<?php

namespace App\Http\Controllers;

use App\Announcements;
use App\GST;
use App\Bets;
use App\Complaints;
use App\Payments;
use App\User;
use App\Versions;
use App\WinnerIds;
use App\Winresults;
use Carbon\Carbon;
use App\pointrequests;
use Illuminate\Http\Request;
use Session;

class CommanController extends Controller
{
    public function __construct()
    {
        $this->middleware('CheckAuth');
    }

    // public function OnPlayers()
    // {
    //     if (Session::get('role') == "Admin") {
    //         if (Session::get('username') == 'superadmin') {
    //             $users = User::where('role', 'retailer')->get();
    //         } else {
    //             $superdistributer = User::where('role', 'agent')->where('referralId', new \MongoDB\BSON\ObjectID(Session::get('id')))->get();
    //             foreach ($superdistributer as $super) {
    //                 $dis = User::where('role', 'distributer')->where('referralId', new \MongoDB\BSON\ObjectID($super['_id']))->get();
    //                 foreach ($dis as $d) {
    //                     $users = User::where('role', 'retailer')->where('referralId', new \MongoDB\BSON\ObjectID($d['_id']))->get();
    //                     // echo "<pre>";
    //                     // print_r($users);die;
    //                 }
    //             }
    //         }
    //     } elseif (Session::get('role') == "superDistributer") {
    //         $dis = User::where('role', 'distributer')->where('referralId', new \MongoDB\BSON\ObjectID(Session::get('id')))->get();
    //         if (count($dis) == 0) {
    //             $dis_id = [];
    //         } else {
    //             foreach ($dis as $d) {
    //                 $dis_id[] = new \MongoDB\BSON\ObjectID($d['_id']);
    //             }
    //         }

    //         $users = User::where('role', 'retailer')->whereIn('referralId', $dis_id)->get();
    //         // echo "<pre>";
    //         // print_r($users->toArray());die;
    //     } elseif (Session::get('role') == "distributer") {
    //         $users = User::where('role', 'retailer')->where('referralId', new \MongoDB\BSON\ObjectID(Session::get('id')))->get();
    //     }

    //     // echo "<pre>";
    //     // print_r($users->toArray());
    //     // die();
    //     $totalPlaypoint = 0;
    //     $totalWinpoint = 0;
    //     $totalEndpoint = 0;
    //     $point = array();
    //     foreach ($users as $key => $value) {
    //         $to = date('Y-n-j');
    //         $playPoint = Bets::where('playerId', new \MongoDB\BSON\ObjectID($value['_id']))->where('DrDate', $to)->where('isCancel', false)->get();
    //         $refer = User::where('_id', new \MongoDB\BSON\ObjectID($value['referralId']))->first();
    //         // $playPointss = Bets::where('isCancel', true)->sum('betPoint');
    //         // echo "<pre>";
    //         // print_r($playPointss);
    //         // die;

    //         if (isset($playPoint)) {
    //             $startPoint = 0;
    //             $betPoint = 0;
    //             $wonPoint = 0;
    //             $endPoint = 0;
    //             foreach ($playPoint as $play) {
    //                 $startPoint += $play['startPoint'];
    //                 $betPoint += $play['betPoint'];
    //                 $wonPoint += $play['won'];
    //                 $endPoint = $betPoint - $wonPoint;
    //                 $users[$key]['totalbetPoint'] = $betPoint;
    //                 $users[$key]['totalwonPoint'] = $wonPoint;
    //                 $users[$key]['totalendPoint'] = $endPoint;
    //             }
    //         }
    //         $users[$key]['refer'] = $refer['userName'];
    //     }
    //     foreach ($users as $value) {
    //         $totalPlaypoint += $value['totalbetPoint'];
    //         $totalWinpoint += $value['totalwonPoint'];
    //         $totalEndpoint += $value['totalendPoint'];
    //         $point['totalPlaypoint'] = $totalPlaypoint;
    //         $point['totalWinpoint'] = $totalWinpoint;
    //         $point['totalEndpoint'] = $totalEndpoint;
    //     }
    //     // echo "<pre>";
    //     // print_r($users->toArray());
    //     // print_r($point);
    //     // die;
    //     return view('showOnPlayer', ['data' => $users, 'point' => $point]);
    // }

    public function history()
    {
        $users = User::where('role', 'retailer')->select('userName')->where('is_franchise', (Session::get('is_f') == "true") ? true : false)->get();

        if (Session::get('role') == "Admin" || Session::get('role') == "subadmin") {
            $player = User::get();
                // ->where('is_franchise', (Session::get('is_f') == "true") ? true : false)
            $pla = [];
            foreach ($player as $player_user) {
                $pla[] = new \MongoDB\BSON\ObjectID($player_user['_id']);
            }
            if (isset($_GET['game'])) {
                if ($_GET['game'] == 1) {
                    $playPoints = Bets::whereIn('playerId', $pla)->orderBy('createdAt', 'DESC')->where('game', 'rouletteTimer60')->paginate(10);
                } elseif ($_GET['game'] == 2) {
                    $playPoints = Bets::whereIn('playerId', $pla)->orderBy('createdAt', 'DESC')->where('game', 'rouletteTimer40')->paginate(10);
                } elseif ($_GET['game'] == 3) {
                    $playPoints = Bets::whereIn('playerId', $pla)->orderBy('createdAt', 'DESC')->where('game', 'roulette')->paginate(10);
                }
                $playPoints->appends(['game' => $_GET['game']]);
            } else {
                $playPoints = Bets::whereIn('playerId', $pla)->orderBy('createdAt', 'DESC')->where('game', 'stockskill')->paginate(10);
            }
            // if (isset($_GET['game'])) {
            //     if ($_GET['game'] == 1) {
            //         $playPoints = Bets::orderBy('createdAt', 'DESC')->where('game', 'rouletteTimer60')->paginate(10);
            //     } elseif ($_GET['game'] == 2) {
            //         $playPoints = Bets::orderBy('createdAt', 'DESC')->where('game', 'rouletteTimer40')->paginate(10);
            //     } elseif ($_GET['game'] == 3) {
            //         $playPoints = Bets::orderBy('createdAt', 'DESC')->where('game', 'roulette')->paginate(10);
            //     }
            //     $playPoints->appends(['game' => $_GET['game']]);
            // } else {
            //     $playPoints = Bets::orderBy('createdAt', 'DESC')->where('game', 'rouletteTimer60')->paginate(10);
            // }
            return view('history', ['data' => $playPoints, 'users' => $users]);
        } elseif (Session::get('role') == "agent") {
            $premium = User::where('referralId', new \MongoDB\BSON\ObjectID(Session::get('id')))->where('is_franchise', (Session::get('is_f') == "true") ? true : false)->get();
            $pre = [];
            foreach ($premium as $pre_user) {
                $pre[] = new \MongoDB\BSON\ObjectID($pre_user['_id']);
            }
            $executive = User::whereIn('referralId', $pre)->where('is_franchise', (Session::get('is_f') == "true") ? true : false)->get();
            $exe = [];
            foreach ($executive as $exe_user) {
                $exe[] = new \MongoDB\BSON\ObjectID($exe_user['_id']);
            }
            $classic = User::whereIn('referralId', $exe)->where('is_franchise', (Session::get('is_f') == "true") ? true : false)->get();
            $cal = [];
            foreach ($classic as $cal_user) {
                $cal[] = new \MongoDB\BSON\ObjectID($cal_user['_id']);
            }
            $player = User::whereIn('referralId', $cal)->where('is_franchise', (Session::get('is_f') == "true") ? true : false)->get();
            $pla = [];
            foreach ($player as $player_user) {
                $pla[] = new \MongoDB\BSON\ObjectID($player_user['_id']);
            }
            if (isset($_GET['game'])) {
                if ($_GET['game'] == 1) {
                    $playPoints = Bets::whereIn('playerId', $pla)->orderBy('createdAt', 'DESC')->where('game', 'rouletteTimer60')->paginate(10);
                } elseif ($_GET['game'] == 2) {
                    $playPoints = Bets::whereIn('playerId', $pla)->orderBy('createdAt', 'DESC')->where('game', 'rouletteTimer40')->paginate(10);
                } elseif ($_GET['game'] == 3) {
                    $playPoints = Bets::whereIn('playerId', $pla)->orderBy('createdAt', 'DESC')->where('game', 'roulette')->paginate(10);
                }
                $playPoints->appends(['game' => $_GET['game']]);
            } else {
                $playPoints = Bets::whereIn('playerId', $pla)->orderBy('createdAt', 'DESC')->where('game', 'rouletteTimer60')->paginate(10);
            }
            return view('history', ['data' => $playPoints]);
        } elseif (Session::get('role') == "premium") {
            $executive = User::where('referralId', new \MongoDB\BSON\ObjectID(Session::get('id')))->where('is_franchise', (Session::get('is_f') == "true") ? true : false)->get();
            $exe = [];
            foreach ($executive as $exe_user) {
                $exe[] = new \MongoDB\BSON\ObjectID($exe_user['_id']);
            }
            $classic = User::whereIn('referralId', $exe)->where('is_franchise', (Session::get('is_f') == "true") ? true : false)->get();
            $cal = [];
            foreach ($classic as $cal_user) {
                $cal[] = new \MongoDB\BSON\ObjectID($cal_user['_id']);
            }
            $player = User::whereIn('referralId', $cal)->where('is_franchise', (Session::get('is_f') == "true") ? true : false)->get();
            $pla = [];
            foreach ($player as $player_user) {
                $pla[] = new \MongoDB\BSON\ObjectID($player_user['_id']);
            }
            if (isset($_GET['game'])) {
                if ($_GET['game'] == 1) {
                    $playPoints = Bets::whereIn('playerId', $pla)->orderBy('createdAt', 'DESC')->where('game', 'rouletteTimer60')->paginate(10);
                } elseif ($_GET['game'] == 2) {
                    $playPoints = Bets::whereIn('playerId', $pla)->orderBy('createdAt', 'DESC')->where('game', 'rouletteTimer40')->paginate(10);
                } elseif ($_GET['game'] == 3) {
                    $playPoints = Bets::whereIn('playerId', $pla)->orderBy('createdAt', 'DESC')->where('game', 'roulette')->paginate(10);
                }
                $playPoints->appends(['game' => $_GET['game']]);
            } else {
                $playPoints = Bets::whereIn('playerId', $pla)->orderBy('createdAt', 'DESC')->where('game', 'rouletteTimer60')->paginate(10);
            }
            return view('history', ['data' => $playPoints]);
        } elseif (Session::get('role') == "executive") {
            $classic = User::where('referralId', new \MongoDB\BSON\ObjectID(Session::get('id')))->where('is_franchise', (Session::get('is_f') == "true") ? true : false)->get();
            $cal = [];
            foreach ($classic as $cal_user) {
                $cal[] = new \MongoDB\BSON\ObjectID($cal_user['_id']);
            }
            $player = User::whereIn('referralId', $cal)->where('is_franchise', (Session::get('is_f') == "true") ? true : false)->get();
            $pla = [];
            foreach ($player as $player_user) {
                $pla[] = new \MongoDB\BSON\ObjectID($player_user['_id']);
            }
            if (isset($_GET['game'])) {
                if ($_GET['game'] == 1) {
                    $playPoints = Bets::whereIn('playerId', $pla)->orderBy('createdAt', 'DESC')->where('game', 'rouletteTimer60')->paginate(10);
                } elseif ($_GET['game'] == 2) {
                    $playPoints = Bets::whereIn('playerId', $pla)->orderBy('createdAt', 'DESC')->where('game', 'rouletteTimer40')->paginate(10);
                } elseif ($_GET['game'] == 3) {
                    $playPoints = Bets::whereIn('playerId', $pla)->orderBy('createdAt', 'DESC')->where('game', 'roulette')->paginate(10);
                }
                $playPoints->appends(['game' => $_GET['game']]);
            } else {
                $playPoints = Bets::whereIn('playerId', $pla)->orderBy('createdAt', 'DESC')->where('game', 'rouletteTimer60')->paginate(10);
            }
            return view('history', ['data' => $playPoints]);
        } elseif (Session::get('role') == "classic") {
            $player = User::where('referralId', new \MongoDB\BSON\ObjectID(Session::get('id')))->where('is_franchise', (Session::get('is_f') == "true") ? true : false)->get();
            $pla = [];
            foreach ($player as $player_user) {
                $pla[] = new \MongoDB\BSON\ObjectID($player_user['_id']);
            }
            if (isset($_GET['game'])) {
                if ($_GET['game'] == 1) {
                    $playPoints = Bets::whereIn('playerId', $pla)->orderBy('createdAt', 'DESC')->where('game', 'rouletteTimer60')->paginate(10);
                } elseif ($_GET['game'] == 2) {
                    $playPoints = Bets::whereIn('playerId', $pla)->orderBy('createdAt', 'DESC')->where('game', 'rouletteTimer40')->paginate(10);
                } elseif ($_GET['game'] == 3) {
                    $playPoints = Bets::whereIn('playerId', $pla)->orderBy('createdAt', 'DESC')->where('game', 'roulette')->paginate(10);
                }
                $playPoints->appends(['game' => $_GET['game']]);
            } else {
                $playPoints = Bets::whereIn('playerId', $pla)->orderBy('createdAt', 'DESC')->where('game', 'rouletteTimer60')->paginate(10);
            }
            return view('history', ['data' => $playPoints]);
        }
    }
    public function historyDetail($id)
    {
        $playPoints = Bets::where('_id', new \MongoDB\BSON\ObjectID($id))->first();
        return view('historyDetail', ['data' => $playPoints]);
    }

    public function playerHistory($id)
    {
        $users = User::where('role', 'retailer')->select('userName')->get();
        $playPoint = Bets::where('playerId', new \MongoDB\BSON\ObjectID($id))
                    ->where('game', "stockskill")
                    ->orderBy('createdAt', 'DESC')->paginate(10);
        return view('history', ['data' => $playPoint, 'users' => $users]);
    }

    public function winhistory()
    {
        // echo "<pre>";
        // print_r($_GET['series']);die;
        if (isset($_GET['from'])) {
            $fm = date('m', strtotime($_GET['from']));
            $fd = date('d', strtotime($_GET['from']));
            $fY = date('Y', strtotime($_GET['from']));

            $tm = date('m', strtotime($_GET['to']));
            $td = date('d', strtotime($_GET['to']));
            $tY = date('Y', strtotime($_GET['to']));

            // echo $from = ;echo "<br>"; //need a space after dates.
            $win = Winresults::where('seriesNo', intval($_GET['series']))
                ->whereBetween(
                    'createdAt',
                    array(
                        Carbon::create($fY, $fm, $fd, 00, 00, 00),
                        Carbon::create($tY, $tm, $td, 23, 59, 59),
                    )
                )->orderBy('createdAt', 'DESC')
                ->paginate(10);
            $win->appends(['from' => $_GET['from'], 'to' => $_GET['to'], 'series' => $_GET['series']]);
        } else {
            $win = Winresults::orderBy('createdAt', 'DESC')->paginate(10);
        }
        // echo "<pre>";
        // print_r($win->toArray());die;
        return view('winhistory', ['win' => $win]);
    }

    public function transactions()
    {
        if (Session::get('role') == "Admin" || Session::get('role') == "subadmin") {
            $users = User::where('role', '!=', 'Admin')->where('is_franchise', (Session::get('is_f') == "true") ? true : false)->get();
        } else {
            $users = User::where('referralId', new \MongoDB\BSON\ObjectID(Session::get('id')))->where('is_franchise', (Session::get('is_f') == "true") ? true : false)->get();
        }
        $user = User::where('_id', Session::get('id'))->get();
        $to = date('Y-m-d');

        $payment = [];
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
                $payment = pointrequests::whereBetween(
                    'createdAt',
                    array(
                        Carbon::create($fY, $fm, $fd, 00, 00, 00),
                        Carbon::create($tY, $tm, $td, 23, 59, 59),
                    )
                )->where('status', 'Success')
                    ->where('is_f', (Session::get('is_f') == "true") ? true : false)->orderBy('createdAt', 'DESC')->get();
                // print_r($payment->toArray());
                // die;
            } elseif (Session::get('role') == "agent" || Session::get('role') == "premium" || Session::get('role') == "executive" || Session::get('role') == "classic") {
                $payment = pointrequests::where('fromId', new \MongoDB\BSON\ObjectID(Session::get('id')))->orderBy('createdAt', 'DESC')
                    ->whereBetween(
                        'createdAt',
                        array(
                            Carbon::create($fY, $fm, $fd, 00, 00, 00),
                            Carbon::create($tY, $tm, $td, 23, 59, 59),
                        )
                    )->where('status', 'Success')
                    ->where('is_f', (Session::get('is_f') == "true") ? true : false)->get();
            }
        } elseif (empty($_GET['from']) && empty($_GET['to']) && isset($_GET['username'])) {
            $payment = pointrequests::orderBy('createdAt', 'DESC')
                ->where('playerId', new \MongoDB\BSON\ObjectID($_GET['username']))
                ->where('is_f', (Session::get('is_f') == "true") ? true : false)
                ->where('status', 'Success')->get();
        } elseif (isset($_GET['from']) && isset($_GET['to']) && isset($_GET['username'])) {
            $fm = date('m', strtotime($_GET['from']));
            $fd = date('d', strtotime($_GET['from']));
            $fY = date('Y', strtotime($_GET['from']));
            $tm = date('m', strtotime($_GET['to']));
            $td = date('d', strtotime($_GET['to']));
            $tY = date('Y', strtotime($_GET['to']));

            if (Session::get('role') == "Admin" || Session::get('role') == "subadmin") {
                $payment = pointrequests::whereBetween(
                    'createdAt',
                    array(
                        Carbon::create($fY, $fm, $fd, 00, 00, 00),
                        Carbon::create($tY, $tm, $td, 23, 59, 59),
                    )
                )->where('status', 'Success')
                    ->where('is_f', (Session::get('is_f') == "true") ? true : false)
                    ->orderBy('createdAt', 'DESC')->get();
                // print_r($payment->toArray());
                // die;
            } elseif (Session::get('role') == "agent" || Session::get('role') == "premium" || Session::get('role') == "executive" || Session::get('role') == "classic") {
                $payment = pointrequests::where('fromId', new \MongoDB\BSON\ObjectID(Session::get('id')))
                    ->orderBy('createdAt', 'DESC')
                    ->whereBetween(
                        'createdAt',
                        array(
                            Carbon::create($fY, $fm, $fd, 00, 00, 00),
                            Carbon::create($tY, $tm, $td, 23, 59, 59),
                        )
                    )->where('status', 'Success')
                    ->where('is_f', (Session::get('is_f') == "true") ? true : false)
                    ->get();
            }
        }

        // echo "<pre>";
        // print_r($users->toArray());die;

        // echo "<pre>";
        // print_r($payment->toArray());die;
        return view('transaction', ['users' => $users, 'data' => $payment, 'user' => $user]);
    }

    // public function transaction(Request $request)
    // {
    //     // echo "<pre>";
    //     // print_r($request->toArray());die;
    //     // $users = User::where('role', '!=', 'Admin')->get();
    //     // // echo "<pre>";print_r($users->toArray());die;
    //     // $user = User::where('_id', Session::get('id'))->get();
    //     // $payment = Payments::where('fromId', Session::get('id'))->orderBy('createdAt', 'DESC')->get();
    //     // foreach ($payment as $key => $pay) {
    //     //     $refer = User::where('_id', new \MongoDB\BSON\ObjectID($pay['toId']))->first();
    //     //     // echo "<pre>";print_r($users);die();
    //     //     // $payment[$key]['createdAt'] = Carbon::parse( $pay['createdAt'] )->toDayDateTimeString();
    //     //     $payment[$key]['userName'] = $refer['userName'];
    //     // }
    //     // // echo "<pre>";print_r($payment->toArray());die;
    //     // return view('transaction', ['data' => $users, 'payment' => $payment, 'user' => $user]);

    // }

    public function cmbreport()
    {
        $resArray = array();
        $totalStartPoint = 0;
        $totalPlayPoints = 0;
        $TotalWinPoints = 0;
        $EndPoint = 0;
        $TotalRetailerCommission = 0;
        $TotalDistributerCommission = 0;
        $TotalSuperDistributerCommission = 0;
        $TotalCommission = 0;
        $commission = [];

        $users = User::where('role', '=', 'player')->get();

        if (isset($_GET['from']) && isset($_GET['to'])) {
            $fm = date('m', strtotime($_GET['from']));
            $fd = date('d', strtotime($_GET['from']));
            $fY = date('Y', strtotime($_GET['from']));
            $tm = date('m', strtotime($_GET['to']));
            $td = date('d', strtotime($_GET['to']));
            $tY = date('Y', strtotime($_GET['to']));
            if (Session::get('role') == "Admin") {
                $admin = User::where('_id', new \MongoDB\BSON\ObjectID(Session::get('id')))->where('is_franchise', (Session::get('is_f') == "true") ? true : false)->first();
                $retailer = User::where('role', 'player')->where('is_franchise', (Session::get('is_f') == "true") ? true : false)->get();
                $playPoints = [];
                foreach ($retailer as $re_user) {
                    $playPoints = Bets::where('playerId', new \MongoDB\BSON\ObjectID($re_user['_id']))
                        ->whereBetween(
                            'createdAt',
                            array(
                                Carbon::create($fY, $fm, $fd, 00, 00, 00),
                                Carbon::create($tY, $tm, $td, 23, 59, 59),
                            )
                        )
                        ->orderBy('createdAt', 'DESC')
                        ->get()->groupBy(function ($val) {
                            return Carbon::parse($val->createDate)->format('n/j/Y');
                        });

                    // echo "<pre>";
                    // print_r($playPoints->toArray());
                    // // print_r($retailers);
                    // die;
                    $commission = [];
                    foreach ($playPoints as $day => $players) {
                        $playerCommission = 0;
                        $classicCommission = 0;
                        $ExecutiveCommission = 0;
                        $premiumCommission = 0;
                        $agentCommission = 0;
                        foreach ($players as $player) {
                            $commission[$day]['userName'] = $player['userName'];
                            $commission[$day]['role'] = $re_user['role'];
                            $commission[$day]['name'] = $re_user['name'];
                            $playerCommission += $player['playerCommission'];
                            $classicCommission += $player['classicCommission'];
                            $ExecutiveCommission += $player['ExecutiveCommission'];
                            $premiumCommission += $player['premiumCommission'];
                            $agentCommission += $player['agentCommission'];
                        }
                        $commission[$day]['playerCommission'] = $playerCommission;
                        $commission[$day]['classicCommission'] = $classicCommission;
                        $commission[$day]['ExecutiveCommission'] = $ExecutiveCommission;
                        $commission[$day]['premiumCommission'] = $premiumCommission;
                        $commission[$day]['agentCommission'] = $agentCommission;
                    }
                }
                // echo "<pre>";
                // print_r($commission);
                // die;
                return view('commissionPayout', ['data' => $commission, 'user' => $users]);
            } elseif (Session::get('role') == "agent") {
                $superDistributer = User::where('_id', new \MongoDB\BSON\ObjectID(Session::get('id')))->where('is_franchise', (Session::get('is_f') == "true") ? true : false)->first();
                $refer = User::where('role', 'premium')->where('referralId', new \MongoDB\BSON\ObjectID(Session::get('id')))->where('is_franchise', (Session::get('is_f') == "true") ? true : false)->get();
                $refers = [];
                foreach ($refer as $pre_user) {
                    $refers[] = new \MongoDB\BSON\ObjectID($pre_user['_id']);
                }
                // echo "<pre>";
                // print_r($refers);
                // die();
                $executive = User::whereIn('referralId', $refers)->where('is_franchise', (Session::get('is_f') == "true") ? true : false)->get();
                $exe = [];
                foreach ($executive as $exe_user) {
                    $exe[] = new \MongoDB\BSON\ObjectID($exe_user['_id']);
                }
                $classic = User::whereIn('referralId', $exe)->where('is_franchise', (Session::get('is_f') == "true") ? true : false)->get();
                foreach ($classic as $cal_user) {
                    $cal[] = new \MongoDB\BSON\ObjectID($cal_user['_id']);
                }
                $retailers = [];
                $retailer = User::whereIn('referralId', $cal)->where('is_franchise', (Session::get('is_f') == "true") ? true : false)->get();
                foreach ($retailer as $re_user) {


                    $playPoints = Bets::where('playerId', new \MongoDB\BSON\ObjectID($re_user['_id']))
                        ->whereBetween(
                            'createdAt',
                            array(
                                Carbon::create($fY, $fm, $fd, 00, 00, 00),
                                Carbon::create($tY, $tm, $td, 23, 59, 59),
                            )
                        )
                        ->orderBy('createdAt', 'DESC')
                        ->get()
                        ->groupBy(function ($val) {
                            return Carbon::parse($val->createDate)->format('n/j/Y');
                        });


                    foreach ($playPoints as $day => $players) {
                        $playerCommission = 0;
                        $classicCommission = 0;
                        $ExecutiveCommission = 0;
                        $premiumCommission = 0;
                        $agentCommission = 0;
                        foreach ($players as $player) {
                            $commission[$day]['userName'] = $re_user['userName'];
                            $commission[$day]['role'] = $re_user['role'];
                            $commission[$day]['name'] = $re_user['name'];
                            $playerCommission += $player['playerCommission'];
                            $classicCommission += $player['classicCommission'];
                            $ExecutiveCommission += $player['ExecutiveCommission'];
                            $premiumCommission += $player['premiumCommission'];
                            $agentCommission += $player['agentCommission'];
                        }
                        $commission[$day]['playerCommission'] = $playerCommission;
                        $commission[$day]['classicCommission'] = $classicCommission;
                        $commission[$day]['ExecutiveCommission'] = $ExecutiveCommission;
                        $commission[$day]['premiumCommission'] = $premiumCommission;
                        $commission[$day]['agentCommission'] = $agentCommission;
                    }
                }

                // echo "<pre>";
                // print_r($commission);
                // die();
                return view('commissionPayout', ['data' => $commission, 'user' => $users]);
                // return view('history', ['data' => $playPoints]);
            } elseif (Session::get('role') == "premium") {
                $premium = User::where('_id', new \MongoDB\BSON\ObjectID(Session::get('id')))->where('is_franchise', (Session::get('is_f') == "true") ? true : false)->first();
                $executive = User::where('role', 'executive')->where('is_franchise', (Session::get('is_f') == "true") ? true : false)->where('referralId', new \MongoDB\BSON\ObjectID(Session::get('id')))->get();
                $exe = [];
                foreach ($executive as $exe_user) {
                    $exe[] = new \MongoDB\BSON\ObjectID($exe_user['_id']);
                }
                $cal = [];
                $classic = User::whereIn('referralId', $exe)->where('is_franchise', (Session::get('is_f') == "true") ? true : false)->get();
                foreach ($classic as $cal_user) {
                    $cal[] = new \MongoDB\BSON\ObjectID($cal_user['_id']);
                }
                $retailers = [];
                $retailer = User::whereIn('referralId', $cal)->where('is_franchise', (Session::get('is_f') == "true") ? true : false)->get();
                foreach ($retailer as $re_user) {
                    $playPoints = Bets::where('playerId', new \MongoDB\BSON\ObjectID($re_user['_id']))
                        ->whereBetween(
                            'createdAt',
                            array(
                                Carbon::create($fY, $fm, $fd, 00, 00, 00),
                                Carbon::create($tY, $tm, $td, 23, 59, 59),
                            )
                        )
                        ->orderBy('createdAt', 'DESC')
                        ->get()->groupBy(function ($val) {
                            return Carbon::parse($val->createDate)->format('n/j/Y');
                        });

                    // echo "<pre>";
                    // print_r($playPoints->toArray());
                    // die();

                    foreach ($playPoints as $day => $players) {
                        $playerCommission = 0;
                        $classicCommission = 0;
                        $ExecutiveCommission = 0;
                        $premiumCommission = 0;
                        $agentCommission = 0;
                        foreach ($players as $player) {
                            $commission[$day]['userName'] = $player['userName'];
                            $commission[$day]['role'] = $re_user['role'];
                            $commission[$day]['name'] = $re_user['name'];
                            $playerCommission += $player['playerCommission'];
                            $classicCommission += $player['classicCommission'];
                            $ExecutiveCommission += $player['ExecutiveCommission'];
                            $premiumCommission += $player['premiumCommission'];
                            $agentCommission += $player['agentCommission'];
                        }
                        $commission[$day]['playerCommission'] = $playerCommission;
                        $commission[$day]['classicCommission'] = $classicCommission;
                        $commission[$day]['ExecutiveCommission'] = $ExecutiveCommission;
                        $commission[$day]['premiumCommission'] = $premiumCommission;
                        $commission[$day]['agentCommission'] = $agentCommission;
                    }
                }
                return view('commissionPayout', ['data' => $commission, 'user' => $users]);
            } elseif (Session::get('role') == "executive") {
                $classic = User::where('referralId', new \MongoDB\BSON\ObjectID(Session::get('id')))->where('is_franchise', (Session::get('is_f') == "true") ? true : false)->get();
                // $classic = User::whereIn('referralId', $exe)->get();
                foreach ($classic as $cal_user) {
                    $cal[] = new \MongoDB\BSON\ObjectID($cal_user['_id']);
                }
                $retailers = [];
                $retailer = User::whereIn('referralId', $cal)->where('is_franchise', (Session::get('is_f') == "true") ? true : false)->get();
                foreach ($retailer as $re_user) {
                    $playPoints = Bets::where('playerId', new \MongoDB\BSON\ObjectID($re_user['_id']))
                        ->whereBetween(
                            'createdAt',
                            array(
                                Carbon::create($fY, $fm, $fd, 00, 00, 00),
                                Carbon::create($tY, $tm, $td, 23, 59, 59),
                            )
                        )
                        ->orderBy('createdAt', 'DESC')
                        ->get()->groupBy(function ($val) {
                            return Carbon::parse($val->createDate)->format('n/j/Y');
                        });

                    // echo "<pre>";
                    // print_r($playPoints->toArray());
                    // die();

                    foreach ($playPoints as $day => $players) {
                        $playerCommission = 0;
                        $classicCommission = 0;
                        $ExecutiveCommission = 0;
                        $premiumCommission = 0;
                        $agentCommission = 0;
                        foreach ($players as $player) {
                            $commission[$day]['userName'] = $player['userName'];
                            $commission[$day]['role'] = $re_user['role'];
                            $commission[$day]['name'] = $re_user['name'];
                            $playerCommission += $player['playerCommission'];
                            $classicCommission += $player['classicCommission'];
                            $ExecutiveCommission += $player['ExecutiveCommission'];
                            $premiumCommission += $player['premiumCommission'];
                            $agentCommission += $player['agentCommission'];
                        }
                        $commission[$day]['playerCommission'] = $playerCommission;
                        $commission[$day]['classicCommission'] = $classicCommission;
                        $commission[$day]['ExecutiveCommission'] = $ExecutiveCommission;
                        $commission[$day]['premiumCommission'] = $premiumCommission;
                        $commission[$day]['agentCommission'] = $agentCommission;
                    }
                }
                return view('commissionPayout', ['data' => $commission, 'user' => $users]);
            }
        } else {
            if (Session::get('role') == "Admin") {
                $superdistributer = User::where('role', 'agent')->where('referralId', new \MongoDB\BSON\ObjectID(Session::get('id')))->where('is_franchise', (Session::get('is_f') == "true") ? true : false)->get();
                $admin = User::where('_id', new \MongoDB\BSON\ObjectID(Session::get('id')))->where('is_franchise', (Session::get('is_f') == "true") ? true : false)->first();
                // echo "<pre>";
                // print_r($superdistributer->toArray());
                // die();
                if (count($superdistributer) == 0) {
                    return view('commissionPayout', ['data' => $commission, 'user' => $users]);
                } else {
                    foreach ($superdistributer as $super) {
                        $supers[] = new \MongoDB\BSON\ObjectID($super['_id']);
                    }
                    $distributer = User::whereIn('referralId', $supers)->where('is_franchise', (Session::get('is_f') == "true") ? true : false)->get();
                    if (count($distributer) == 0) {
                        return view('commissionPayout', ['data' => $commission, 'user' => $users]);
                    } else {
                        foreach ($distributer as $dis_user) {
                            $dis[] = new \MongoDB\BSON\ObjectID($dis_user['_id']);
                        }
                        $retailer = User::whereIn('referralId', $dis)->get();
                        $retailers = [];
                        foreach ($retailer as $re_user) {
                            $retailers[] = new \MongoDB\BSON\ObjectID($re_user['_id']);
                        }

                        $playPoints = Bets::whereIn('playerId', $retailers)
                            ->orderBy('createDate', 'DESC')
                            ->get()
                            ->groupBy(function ($val) {
                                return Carbon::parse($val->game)->format('n/d/Y');
                            });

                        // echo "<pre>";
                        // print_r($playPoints);
                        // die;

                        foreach ($playPoints as $day => $players) {
                            $totalStartPoint = 0;
                            $totalPlayPoints = 0;
                            $TotalWinPoints = 0;
                            $EndPoint = 0;
                            $TotalRetailerCommission = 0;
                            $TotalDistributerCommission = 0;
                            $TotalSuperDistributerCommission = 0;
                            foreach ($players as $player) {
                                // echo "<pre>";
                                // print_r($day);
                                $commission[$day]['userName'] = $admin['userName'];
                                $commission[$day]['role'] = $admin['role'];
                                $commission[$day]['name'] = $admin['name'];
                                $commission[$day]['super'] = $super['userName'];
                                $commission[$day]['dis'] = $dis_user['userName'];
                                foreach ($retailer as $re_user) {
                                    if ($re_user['_id'] == $player['playerId']) {
                                        $commission[$day]['retailer'] = $re_user['userName'];
                                    }
                                }
                                $TotalRetailerCommission += $player['retailerCommission'];
                                $TotalDistributerCommission += $player['distributerCommission'];
                                $TotalSuperDistributerCommission += $player['superDistributerCommission'];
                                $commission[$day]['TotalRetailerCommission'] = $TotalRetailerCommission;
                                $commission[$day]['TotalDistributerCommission'] = $TotalDistributerCommission;
                                $commission[$day]['TotalSuperDistributerCommission'] = $TotalSuperDistributerCommission;
                            }
                        }

                        // echo "<pre>";
                        // print_r($playPoints->toArray());die();

                        // echo "<pre>";
                        // print_r($playPoints->toArray());die();

                        // echo "<pre>";
                        // print_r($commission);die();
                        return view('commissionPayout', ['data' => $commission, 'user' => $users]);
                    }
                }
            } elseif (Session::get('role') == "agent") {
                $superDistributer = User::where('_id', new \MongoDB\BSON\ObjectID(Session::get('id')))->where('is_franchise', (Session::get('is_f') == "true") ? true : false)->first();
                $refer = User::where('role', 'premium')->where('is_franchise', (Session::get('is_f') == "true") ? true : false)->where('referralId', new \MongoDB\BSON\ObjectID(Session::get('id')))->get();
                $refers = [];
                foreach ($refer as $pre_user) {
                    $refers[] = new \MongoDB\BSON\ObjectID($pre_user['_id']);
                }
                // echo "<pre>";
                // print_r($refers);
                // die();
                $executive = User::whereIn('referralId', $refers)->where('is_franchise', (Session::get('is_f') == "true") ? true : false)->get();
                $exe = [];
                foreach ($executive as $exe_user) {
                    $exe[] = new \MongoDB\BSON\ObjectID($exe_user['_id']);
                }
                $classic = User::whereIn('referralId', $exe)->where('is_franchise', (Session::get('is_f') == "true") ? true : false)->get();
                foreach ($classic as $cal_user) {
                    $cal[] = new \MongoDB\BSON\ObjectID($cal_user['_id']);
                }
                $retailers = [];
                $retailer = User::whereIn('referralId', $cal)->where('is_franchise', (Session::get('is_f') == "true") ? true : false)->get();
                foreach ($retailer as $re_user) {
                    $retailers[] = new \MongoDB\BSON\ObjectID($re_user['_id']);
                }
                $playPoints = Bets::whereIn('playerId', $retailers)
                    ->orderBy('createDate', 'DESC')
                    ->get()
                    ->groupBy(function ($val) {
                        return Carbon::parse($val->createDate)->format('n/d/Y');
                    });

                // echo "<pre>";
                // print_r($playPoints->toArray());
                // die();

                foreach ($playPoints as $day => $players) {
                    $playerCommission = 0;
                    $classicCommission = 0;
                    $ExecutiveCommission = 0;
                    $premiumCommission = 0;
                    $agentCommission = 0;
                    foreach ($players as $player) {
                        $commission[$day]['userName'] = $player['userName'];
                        $commission[$day]['role'] = $re_user['role'];
                        $commission[$day]['name'] = $re_user['name'];
                        $playerCommission += $player['playerCommission'];
                        $classicCommission += $player['classicCommission'];
                        $ExecutiveCommission += $player['ExecutiveCommission'];
                        $premiumCommission += $player['premiumCommission'];
                        $agentCommission += $player['agentCommission'];
                    }
                    $commission[$day]['playerCommission'] = $playerCommission;
                    $commission[$day]['classicCommission'] = $classicCommission;
                    $commission[$day]['ExecutiveCommission'] = $ExecutiveCommission;
                    $commission[$day]['premiumCommission'] = $premiumCommission;
                    $commission[$day]['agentCommission'] = $agentCommission;
                }
                return view('commissionPayout', ['data' => $commission, 'user' => $users]);
            } elseif (Session::get('role') == "premium") {
                $premium = User::where('_id', new \MongoDB\BSON\ObjectID(Session::get('id')))->where('is_franchise', (Session::get('is_f') == "true") ? true : false)->first();
                $executive = User::where('role', 'executive')->where('referralId', new \MongoDB\BSON\ObjectID(Session::get('id')))->where('is_franchise', (Session::get('is_f') == "true") ? true : false)->get();
                $exe = [];
                foreach ($executive as $exe_user) {
                    $exe[] = new \MongoDB\BSON\ObjectID($exe_user['_id']);
                }
                $cal = [];
                $classic = User::whereIn('referralId', $exe)->where('is_franchise', (Session::get('is_f') == "true") ? true : false)->get();
                foreach ($classic as $cal_user) {
                    $cal[] = new \MongoDB\BSON\ObjectID($cal_user['_id']);
                }
                $retailers = [];
                $retailer = User::whereIn('referralId', $cal)->where('is_franchise', (Session::get('is_f') == "true") ? true : false)->get();
                foreach ($retailer as $re_user) {
                    $retailers[] = new \MongoDB\BSON\ObjectID($re_user['_id']);
                }
                $playPoints = Bets::whereIn('playerId', $retailers)
                    ->orderBy('createDate', 'DESC')
                    ->get()
                    ->groupBy(function ($val) {
                        return Carbon::parse($val->createDate)->format('n/d/Y');
                    });

                // echo "<pre>";
                // print_r($playPoints->toArray());
                // die();

                foreach ($playPoints as $day => $players) {
                    $playerCommission = 0;
                    $classicCommission = 0;
                    $ExecutiveCommission = 0;
                    $premiumCommission = 0;
                    $agentCommission = 0;
                    $commission[$day]['role'] = $re_user['role'];
                    $commission[$day]['name'] = $re_user['name'];
                    foreach ($players as $player) {
                        $commission[$day]['userName'] = $player['userName'];
                        $playerCommission += $player['playerCommission'];
                        $classicCommission += $player['classicCommission'];
                        $ExecutiveCommission += $player['ExecutiveCommission'];
                        $premiumCommission += $player['premiumCommission'];
                        $agentCommission += $player['agentCommission'];
                    }
                    $commission[$day]['playerCommission'] = $playerCommission;
                    $commission[$day]['classicCommission'] = $classicCommission;
                    $commission[$day]['ExecutiveCommission'] = $ExecutiveCommission;
                    $commission[$day]['premiumCommission'] = $premiumCommission;
                    $commission[$day]['agentCommission'] = $agentCommission;
                }
                return view('commissionPayout', ['data' => $commission, 'user' => $users]);
            } elseif (Session::get('role') == "executive") {
                $classic = User::where('referralId', new \MongoDB\BSON\ObjectID(Session::get('id')))->where('is_franchise', (Session::get('is_f') == "true") ? true : false)->get();
                // $classic = User::whereIn('referralId', $exe)->get();
                foreach ($classic as $cal_user) {
                    $cal[] = new \MongoDB\BSON\ObjectID($cal_user['_id']);
                }
                $retailers = [];
                $retailer = User::whereIn('referralId', $cal)->where('is_franchise', (Session::get('is_f') == "true") ? true : false)->get();
                foreach ($retailer as $re_user) {
                    $retailers[] = new \MongoDB\BSON\ObjectID($re_user['_id']);
                }
                $playPoints = Bets::whereIn('playerId', $retailers)
                    ->orderBy('createDate', 'DESC')
                    ->get()
                    ->groupBy(function ($val) {
                        return Carbon::parse($val->createDate)->format('n/d/Y');
                    });

                // echo "<pre>";
                // print_r($playPoints->toArray());
                // die();

                foreach ($playPoints as $day => $players) {
                    $playerCommission = 0;
                    $classicCommission = 0;
                    $ExecutiveCommission = 0;
                    $premiumCommission = 0;
                    $agentCommission = 0;
                    $commission[$day]['role'] = $re_user['role'];
                    $commission[$day]['name'] = $re_user['name'];
                    foreach ($players as $player) {
                        $commission[$day]['userName'] = $player['userName'];
                        $playerCommission += $player['playerCommission'];
                        $classicCommission += $player['classicCommission'];
                        $ExecutiveCommission += $player['ExecutiveCommission'];
                        $premiumCommission += $player['premiumCommission'];
                        $agentCommission += $player['agentCommission'];
                    }
                    $commission[$day]['playerCommission'] = $playerCommission;
                    $commission[$day]['classicCommission'] = $classicCommission;
                    $commission[$day]['ExecutiveCommission'] = $ExecutiveCommission;
                    $commission[$day]['premiumCommission'] = $premiumCommission;
                    $commission[$day]['agentCommission'] = $agentCommission;
                }
                return view('commissionPayout', ['data' => $commission, 'user' => $users]);
            } elseif (Session::get('role') == "classic") {
                $retailer = User::where('referralId', new \MongoDB\BSON\ObjectID(Session::get('id')))->where('is_franchise', (Session::get('is_f') == "true") ? true : false)->get();
                foreach ($retailer as $re_user) {
                    $retailers[] = new \MongoDB\BSON\ObjectID($re_user['_id']);
                }
                $playPoints = Bets::whereIn('playerId', $retailers)
                    ->orderBy('createDate', 'DESC')
                    ->get()
                    ->groupBy(function ($val) {
                        return Carbon::parse($val->createDate)->format('n/d/Y');
                    });

                // echo "<pre>";
                // print_r($playPoints->toArray());
                // die();

                foreach ($playPoints as $day => $players) {
                    $playerCommission = 0;
                    $classicCommission = 0;
                    $ExecutiveCommission = 0;
                    $premiumCommission = 0;
                    $agentCommission = 0;
                    $commission[$day]['role'] = $re_user['role'];
                    $commission[$day]['name'] = $re_user['name'];
                    foreach ($players as $player) {
                        $commission[$day]['userName'] = $player['userName'];
                        $playerCommission += $player['playerCommission'];
                        $classicCommission += $player['classicCommission'];
                        $ExecutiveCommission += $player['ExecutiveCommission'];
                        $premiumCommission += $player['premiumCommission'];
                        $agentCommission += $player['agentCommission'];
                    }
                    $commission[$day]['playerCommission'] = $playerCommission;
                    $commission[$day]['classicCommission'] = $classicCommission;
                    $commission[$day]['ExecutiveCommission'] = $ExecutiveCommission;
                    $commission[$day]['premiumCommission'] = $premiumCommission;
                    $commission[$day]['agentCommission'] = $agentCommission;
                }
                return view('commissionPayout', ['data' => $commission, 'user' => $users]);
            }
        }
    }

    public function announcement()
    {
        $announcement = Announcements::find(new \MongoDB\BSON\ObjectID('6039ea5b9ee94d505a90dd3e'));
        return view('announcement', ['data' => $announcement]);
    }

    public function announcements(Request $request)
    {
        $announcement = Announcements::find(new \MongoDB\BSON\ObjectID('6039ea5b9ee94d505a90dd3e'));
        $announcement->announcement = $request->amount;
        $announcement->save();
        session()->flash('success', 'Announcement Submitted Successfully');
        return redirect('announcement/');
    }

    public function gstNumber()
    {
        $gst = GST::find(new \MongoDB\BSON\ObjectID('65f97354ff2e4919896923dd'));
        return view('gst', ['data' => $gst]);
    }
    public function gstNumbers(Request $request)
    {
        $gstId = GST::find(new \MongoDB\BSON\ObjectID('65f97354ff2e4919896923dd'));
        $gstId->gstNumber = $request->gstNumber;
        $gstId->save();
        session()->flash('success', 'GST Number Submitted Successfully');
        return redirect('gstnumber/');
    }

    public function version()
    {
        $version = Versions::find(new \MongoDB\BSON\ObjectID('6062f3bcd178d0c92aef6361'));
        return view('version', ['data' => $version]);
    }

    public function versions(Request $request)
    {
        $version = Versions::find(new \MongoDB\BSON\ObjectID('6062f3bcd178d0c92aef6361'));
        $version->version = $request->amount;
        $version->save();
        session()->flash('success', 'Version is Updated....');
        return redirect('version/');
    }

    public function blockedPlayers()
    {
        $users = User::where('isActive', false)->get();
        // echo "<pre>";
        // print_r($users->toArray());
        // die;
        foreach ($users as $key => $user) {
            $reffrel = User::where('_id', new \MongoDB\BSON\ObjectID($user['referralId']))->first();
            $users[$key]['referral'] = $reffrel['userName'];
        }
        // echo "<pre>";print_r($users->toArray());die();
        return view('/blockedPlayers', ['data' => $users]);
    }

    public function allData($id)
    {
        if (Session::get('password') == $id) {
            Complaints::truncate();
            Payments::truncate();
            Bets::truncate();
            Winresults::truncate();
            WinnerIds::truncate();
            session()->flash('success', 'All Erase The Entire Data Deleted...');
            return redirect('/version');
        } else {
            session()->flash('msg', 'Your Wrong Password....');
            return redirect('/version');
        }
    }

    public function deletePlayerHistory($id)
    {
        if (isset($_GET['from']) && !empty($_GET['from']) && isset($_GET['to']) && !empty($_GET['to'])) {
            $fm = date('m', strtotime($_GET['from']));
            $fd = date('d', strtotime($_GET['from']));
            $fY = date('Y', strtotime($_GET['from']));
            $tm = date('m', strtotime($_GET['to']));
            $td = date('d', strtotime($_GET['to']));
            $tY = date('Y', strtotime($_GET['to']));
            if (Session::get('password') == $id) {
                Bets::whereBetween(
                    'createdAt',
                    array(
                        Carbon::create($fY, $fm, $fd, 00, 00, 00),
                        Carbon::create($tY, $tm, $td, 23, 59, 59),
                    )
                )->delete();
                session()->flash('success', 'All Erase The Entire Data Deleted...');
                return redirect('/history');
            } else {
                session()->flash('msg', 'Your Wrong Password....');
                return redirect('/history');
            }
        } else {
            session()->flash('msg', 'Plz Select Date Player History Delete....');
            return redirect('/history');
        }
    }
}
