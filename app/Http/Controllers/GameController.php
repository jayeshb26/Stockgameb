<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Winresults;
use App\User;

use Carbon\Carbon;
use Session;
use App\Bets;

class GameController extends Controller
{
    public function index($id)
    {
        // rouletteMini
        // funRoulette
        // roulette
        // funTarget
        // tripleChance

        if ($id == 1) {
            $bets = Winresults::where('gameName', "rouletteTimer60")->orderBy('createdAt', 'DESC')->paginate(10);
            $game = "RouletteTimer60";
        } elseif ($id == 2) {
            $bets = Winresults::where('gameName', "rouletteTimer40")->orderBy('createdAt', 'DESC')->paginate(10);
            $game = "RouletteTimer40";
        } elseif ($id == 3) {
            $bets = Winresults::where('gameName', "roulette")->orderBy('createdAt', 'DESC')->paginate(10);
            $game = "Roulette";
        }
        return view('gameDraw', ['data' => $bets, 'game' => $game]);
    }

    public function gameProfit()
    {
        $type = $_GET['type'];
        $resArray = array();
        $totalStartPoint = 0;
        $totalPlayPoints = 0;
        $TotalWinPoints = 0;
        $EndPoint = 0;
        $TotalCommission = 0;
        $today = date_create(date("Y-m-d"));
        $users = User::where('role', '=', 'retailer')->get();

        $fm = date('m', strtotime($_GET['from']));
        $fd = date('j', strtotime($_GET['from']));
        $fY = date('Y', strtotime($_GET['from']));
        $tm = date('m', strtotime($_GET['to']));
        $tY = date('Y', strtotime($_GET['to']));

        if (Session::get('role') == "Admin") {
            if (Session::get('is_f') == "false") {
                $agent = User::where('role', 'agent')->where('referralId', new \MongoDB\BSON\ObjectID(Session::get('id')))->get();
                // echo "<pre>";
                // print_r($superdistributer->toArray());die;
                $admin = User::where('_id', new \MongoDB\BSON\ObjectID(Session::get('id')))->get();
                if (count($agent) == 0) {
                    $game = array();
                    $game['rouletteTimer40'] = 0;
                    $game['rouletteTimer60'] = 0;
                    $game['roulette'] = 0;
                    return view('gameProfit', ['data' => $admin,  'total' => $game]);
                } else {
                    foreach ($agent as $super) {
                        $agents[] = new \MongoDB\BSON\ObjectID($super['_id']);
                    }
                    $premium = User::whereIn('referralId', $agents)->get();
                    if (count($premium) == 0) {

                        $game = array();
                        $game['rouletteTimer40'] = 0;
                        $game['rouletteTimer60'] = 0;
                        $game['roulette'] = 0;
                        return view('gameProfit', ['data' => $admin,  'total' => $game]);
                    } else {
                        foreach ($premium as $pre_user) {
                            $pre[] = new \MongoDB\BSON\ObjectID($pre_user['_id']);
                        }
                        $executive = User::whereIn('referralId', $pre)->get();
                        if (count($executive) == 0) {

                            $game = array();
                            $game['rouletteTimer40'] = 0;
                            $game['rouletteTimer60'] = 0;
                            $game['roulette'] = 0;
                            return view('gameProfit', ['data' => $admin,  'total' => $game]);
                        } else {
                            foreach ($executive as $exe_user) {
                                $exe[] = new \MongoDB\BSON\ObjectID($exe_user['_id']);
                            }
                            $classic = User::whereIn('referralId', $exe)->get();
                            if (count($classic) == 0) {

                                $game = array();
                                $game['rouletteTimer40'] = 0;
                                $game['rouletteTimer60'] = 0;
                                $game['roulette'] = 0;
                                return view('gameProfit', ['data' => $admin,  'total' => $game]);
                            } else {
                                foreach ($classic as $cal_user) {
                                    $cal[] = new \MongoDB\BSON\ObjectID($cal_user['_id']);
                                }
                                $player = User::whereIn('referralId', $cal)->get();
                                foreach ($player as $player_user) {
                                    $players[] = new \MongoDB\BSON\ObjectID($player_user['_id']);
                                }
                                if (count($players) == 0) {

                                    $game = array();
                                    $game['rouletteTimer40'] = 0;
                                    $game['rouletteTimer60'] = 0;
                                    $game['roulette'] = 0;
                                    return view('gameProfit', ['data' => $admin,  'total' => $game]);
                                } else {
                                    if ($type == 1 || $type == 2 || $type == 3 || $type == 4 || $type == 5 || $type == 8) {
                                        $td = date('j', strtotime($_GET['to']));
                                        $playPoints = Bets::whereIn('playerId', $players)
                                            ->whereBetween(
                                                'createdAt',
                                                array(
                                                    Carbon::create($fY, $fm, $fd, 00, 00, 00),
                                                    Carbon::create($tY, $tm, $td, 23, 59, 59),
                                                )
                                            )->get()
                                            ->groupBy(function ($val) {
                                                return $val->game;
                                            });
                                    } elseif ($type == 7 || $type == 6) {
                                        $to = date('n-j-Y', strtotime($_GET['to']));
                                        $playPoints = Bets::whereIn('playerId', $players)
                                            ->where('DrDate', $to)->get()->groupBy(function ($val) {
                                                return $val->game;
                                            });;
                                    }

                                    // echo "<pre>";
                                    // print_r($playPoints->toArray());
                                    // die;
                                    $game = array();

                                    $game['rouletteTimer40'] = 0;
                                    $game['rouletteTimer60'] = 0;
                                    $game['roulette'] = 0;

                                    // echo "<pre>";
                                    // print_r($game);
                                    // die;

                                    $total = [];
                                    foreach ($playPoints as $key => $play) {
                                        $totalPlayPoints = 0;
                                        $TotalWinPoints = 0;
                                        foreach ($play as $pl) {
                                            $totalPlayPoints += $pl['bet'];
                                            $TotalWinPoints += $pl['won'];
                                        }
                                        $total[$key]['totalPlayPoints'] = $totalPlayPoints;
                                        $total[$key]['TotalWinPoints'] = $TotalWinPoints;
                                    }
                                    // echo "<pre>";
                                    // print_r($total);
                                    // die;
                                    // $profit = [];
                                    foreach ($total as $key => $to) {
                                        if (array_key_exists($key, $game)) {
                                            $game[$key] = intval($to['totalPlayPoints'] - $to['TotalWinPoints']);
                                        }
                                    }

                                    // $total['EndPoint'] = 
                                    // echo "<pre>";
                                    // print_r($game);
                                    // die;
                                    return view('gameProfit', ['data' => $admin, 'total' => $game]);
                                }
                            }
                        }
                    }
                }
            } else {
                $premium = User::where('role', 'premium')->where('referralId', new \MongoDB\BSON\ObjectID(Session::get('id')))->get();
                // echo "<pre>";
                // print_r($premium->toArray());
                // die;
                $admin = User::where('_id', new \MongoDB\BSON\ObjectID(Session::get('id')))->get();
                if (count($premium) == 0) {
                    $game = array();
                    $game['rouletteTimer40'] = 0;
                    $game['rouletteTimer60'] = 0;
                    $game['roulette'] = 0;
                    return view('gameProfit', ['data' => $admin,  'total' => $game]);
                } else {
                    foreach ($premium as $pre_user) {
                        $pre[] = new \MongoDB\BSON\ObjectID($pre_user['_id']);
                    }
                    $executive = User::whereIn('referralId', $pre)->get();
                    if (count($executive) == 0) {

                        $game = array();
                        $game['rouletteTimer40'] = 0;
                        $game['rouletteTimer60'] = 0;
                        $game['roulette'] = 0;
                        return view('gameProfit', ['data' => $admin,  'total' => $game]);
                    } else {
                        foreach ($executive as $exe_user) {
                            $exe[] = new \MongoDB\BSON\ObjectID($exe_user['_id']);
                        }
                        $classic = User::whereIn('referralId', $exe)->get();
                        if (count($classic) == 0) {

                            $game = array();
                            $game['rouletteTimer40'] = 0;
                            $game['rouletteTimer60'] = 0;
                            $game['roulette'] = 0;
                            return view('gameProfit', ['data' => $admin,  'total' => $game]);
                        } else {
                            foreach ($classic as $cal_user) {
                                $cal[] = new \MongoDB\BSON\ObjectID($cal_user['_id']);
                            }
                            $player = User::whereIn('referralId', $cal)->get();
                            foreach ($player as $player_user) {
                                $players[] = new \MongoDB\BSON\ObjectID($player_user['_id']);
                            }
                            if (count($players) == 0) {

                                $game = array();
                                $game['rouletteTimer40'] = 0;
                                $game['rouletteTimer60'] = 0;
                                $game['roulette'] = 0;
                                return view('gameProfit', ['data' => $admin,  'total' => $game]);
                            } else {
                                if ($type == 1 || $type == 2 || $type == 3 || $type == 4 || $type == 5 || $type == 8) {
                                    $td = date('j', strtotime($_GET['to']));
                                    $playPoints = Bets::whereIn('playerId', $players)
                                        ->whereBetween(
                                            'createdAt',
                                            array(
                                                Carbon::create($fY, $fm, $fd, 00, 00, 00),
                                                Carbon::create($tY, $tm, $td, 23, 59, 59),
                                            )
                                        )->get()
                                        ->groupBy(function ($val) {
                                            return $val->game;
                                        });
                                } elseif ($type == 7 || $type == 6) {
                                    $to = date('n-j-Y', strtotime($_GET['to']));
                                    $playPoints = Bets::whereIn('playerId', $players)
                                        ->where('DrDate', $to)->get()->groupBy(function ($val) {
                                            return $val->game;
                                        });;
                                }

                                // echo "<pre>";
                                // print_r($playPoints->toArray());
                                // die;
                                $game = array();

                                $game['rouletteTimer40'] = 0;
                                $game['rouletteTimer60'] = 0;
                                $game['roulette'] = 0;

                                // echo "<pre>";
                                // print_r($game);
                                // die;

                                $total = [];
                                foreach ($playPoints as $key => $play) {
                                    $totalPlayPoints = 0;
                                    $TotalWinPoints = 0;
                                    foreach ($play as $pl) {
                                        $totalPlayPoints += $pl['bet'];
                                        $TotalWinPoints += $pl['won'];
                                    }
                                    $total[$key]['totalPlayPoints'] = $totalPlayPoints;
                                    $total[$key]['TotalWinPoints'] = $TotalWinPoints;
                                }
                                // echo "<pre>";
                                // print_r($total);
                                // die;
                                // $profit = [];
                                foreach ($total as $key => $to) {
                                    if (array_key_exists($key, $game)) {
                                        $game[$key] = intval($to['totalPlayPoints'] - $to['TotalWinPoints']);
                                    }
                                }

                                // $total['EndPoint'] = 
                                // echo "<pre>";
                                // print_r($game);
                                // die;
                                return view('gameProfit', ['data' => $admin, 'total' => $game]);
                            }
                        }
                    }
                }
            }
        }
        // elseif (Session::get('role') == "superDistributer") {
        //     // echo "voijay";
        //     // die;
        //     $superDistributer = User::where('_id', new \MongoDB\BSON\ObjectID(Session::get('id')))->first();
        //     // echo "<pre>";
        //     // print_r($superDistributer->toArray());
        //     // die();
        //     $distributer = User::where('referralId', new \MongoDB\BSON\ObjectID(Session::get('id')))->get();
        //     if (count($distributer) == 0) {
        //         $total = [];
        //         $total['totalStartPoint'] = $totalStartPoint;
        //         $total['totalPlayPoints'] = $totalPlayPoints;
        //         $total['TotalWinPoints'] = $TotalWinPoints;
        //         $total['EndPoint'] = $EndPoint;
        //         return view('gameProfit', ['data' => $admin, 'total' => $total]);
        //     } else {
        //         foreach ($distributer as $dis_user) {
        //             $dis[] = new \MongoDB\BSON\ObjectID($dis_user['_id']);
        //         }
        //         $retailer = User::whereIn('referralId', $dis)->get();
        //         $retailers = [];
        //         foreach ($retailer as $re_user) {
        //             $retailers[] = new \MongoDB\BSON\ObjectID($re_user['_id']);
        //         }
        //         if (count($retailers) == 0) {
        //             $total = [];
        //             $total['totalStartPoint'] = $totalStartPoint;
        //             $total['totalPlayPoints'] = $totalPlayPoints;
        //             $total['TotalWinPoints'] = $TotalWinPoints;
        //             $total['EndPoint'] = $EndPoint;
        //             return view('gameProfit', ['data' => $admin, 'total' => $total]);
        //         } else {
        //             $groups = [];
        //             $group = [];
        //             $retailers = [];
        //             foreach ($retailer as $re_user) {
        //                 $retailers[] = new \MongoDB\BSON\ObjectID($re_user['_id']);
        //             }
        //             if ($type == 1 || $type == 2 || $type == 3 || $type == 4 || $type == 5 || $type == 8) {
        //                 $td = date('j', strtotime($_GET['to']));
        //                 $groups[$superDistributer['_id']] = Bets::select('bet', 'won',  'playerCommission', 'classicCommission', 'ExecutiveCommission', 'premiumCommission', 'agentCommission')
        //                     ->whereIn('retailerId', $retailers)
        //                     ->whereBetween(
        //                         'createdAt',
        //                         array(
        //                             Carbon::create($fY, $fm, $fd, 00, 00, 00),
        //                             Carbon::create($tY, $tm, $td, 23, 59, 59),
        //                         )
        //                     )->get()->toArray();
        //             } elseif ($type == 7 || $type == 6) {
        //                 $to = date('n-j-Y', strtotime($_GET['to']));
        //                 $groups[$superDistributer['_id']] = Bets::select('bet', 'won',  'playerCommission', 'classicCommission', 'ExecutiveCommission', 'premiumCommission', 'agentCommission')
        //                     ->whereIn('retailerId', $retailers)->where('DrDate', $to)->get()->toArray();
        //             }
        //             // echo "<pre>";
        //             // print_r($playPoints->toArray());
        //             // die();
        //             $commission = [];
        //             foreach ($groups as $key => $get) {
        //                 $PlayPoints = 0;
        //                 $WinPoints = 0;
        //                 $EndPoint = 0;
        //                 $RetailerCommission = 0;
        //                 $DistributerCommission = 0;
        //                 $SuperDistributerCommission = 0;
        //                 foreach ($get as $player) {
        //                     $commission[$key]['_id'] = $superDistributer['_id'];
        //                     $commission[$key]['userName'] = $superDistributer['userName'];
        //                     $commission[$key]['role'] = $superDistributer['role'];
        //                     $commission[$key]['name'] = $superDistributer['name'];
        //                     $PlayPoints += $player['bet'];
        //                     $WinPoints += $player['won'];
        //                     $EndPoint = $PlayPoints - $WinPoints;
        //                     $commission[$key]['playPoint'] = $PlayPoints;
        //                     $commission[$key]['wonPoint'] = $WinPoints;
        //                     $commission[$key]['endPoint'] = $EndPoint;
        //                 }
        //             }
        //             // echo "<pre>";
        //             // print_r($commission);die;
        //             foreach ($commission as $play) {
        //                 $totalPlayPoints += $play['playPoint'];
        //                 $TotalWinPoints += $play['wonPoint'];
        //                 $EndPoint = $totalPlayPoints - $TotalWinPoints;
        //             }
        //             $total = [];
        //             $total['totalPlayPoints'] = $totalPlayPoints;
        //             $total['TotalWinPoints'] = $TotalWinPoints;
        //             $total['EndPoint'] = $EndPoint;
        //             // echo "<pre>";
        //             // print_r($total);die();
        //             return view('gameProfit', ['data' => $commission, 'total' => $total, 'user' => $users]);
        //         }
        //     }
        // } elseif (Session::get('role') == "distributer") {
        //     $distributer = User::where('_id', new \MongoDB\BSON\ObjectID(Session::get('id')))->first();
        //     $retailer = User::where('referralId', new \MongoDB\BSON\ObjectID(Session::get('id')))->get();
        //     $groups = [];
        //     foreach ($retailer as $re_user) {
        //         $retailers[] = new \MongoDB\BSON\ObjectID($re_user['_id']);
        //     }
        //     if ($type == 1 || $type == 2 || $type == 3 || $type == 4 || $type == 5 || $type == 8) {
        //         $td = date('j', strtotime($_GET['to']));
        //         $groups[$distributer['_id']] = Bets::select('bet', 'won',  'playerCommission', 'classicCommission', 'ExecutiveCommission', 'premiumCommission', 'agentCommission')
        //             ->whereIn('retailerId', $retailers)
        //             ->whereBetween(
        //                 'createdAt',
        //                 array(
        //                     Carbon::create($fY, $fm, $fd, 00, 00, 00),
        //                     Carbon::create($tY, $tm, $td, 23, 59, 59),
        //                 )
        //             )->get()->toArray();
        //     } elseif ($type == 7 || $type == 6) {
        //         $to = date('n-j-Y', strtotime($_GET['to']));
        //         $groups[$distributer['_id']] = Bets::select('bet', 'won',  'playerCommission', 'classicCommission', 'ExecutiveCommission', 'premiumCommission', 'agentCommission')
        //             ->whereIn('retailerId', $retailers)->where('DrDate', $to)->get()->toArray();
        //     }
        //     // echo "<pre>";
        //     // print_r($groups);die;
        //     $commission = [];
        //     foreach ($groups as $key => $get) {
        //         $PlayPoints = 0;
        //         $WinPoints = 0;
        //         $EndPoint = 0;
        //         $RetailerCommission = 0;
        //         $DistributerCommission = 0;
        //         $SuperDistributerCommission = 0;
        //         foreach ($get as $player) {
        //             $commission[$key]['_id'] = $distributer['_id'];
        //             $commission[$key]['userName'] = $distributer['userName'];
        //             $commission[$key]['role'] = $distributer['role'];
        //             $commission[$key]['name'] = $distributer['name'];
        //             $PlayPoints += $player['bet'];
        //             $WinPoints += $player['won'];
        //             $EndPoint = $PlayPoints - $WinPoints;
        //             $commission[$key]['playPoint'] = $PlayPoints;
        //             $commission[$key]['wonPoint'] = $WinPoints;
        //             $commission[$key]['endPoint'] = $EndPoint;
        //         }
        //     }
        //     // echo "<pre>";
        //     // print_r($commission);die;
        //     foreach ($commission as $play) {
        //         $totalPlayPoints += $play['playPoint'];
        //         $TotalWinPoints += $play['wonPoint'];
        //         $EndPoint = $totalPlayPoints - $TotalWinPoints;
        //     }
        //     $total = [];
        //     $total['totalPlayPoints'] = $totalPlayPoints;
        //     $total['TotalWinPoints'] = $TotalWinPoints;
        //     $total['EndPoint'] = $EndPoint;
        //     // echo "<pre>";
        //     // print_r($total);die();
        //     return view('gameProfit', ['data' => $commission, 'total' => $total, 'user' => $users]);
        // } elseif (Session::get('role') == "retailer") {
        //     $retailer = User::where('_id', new \MongoDB\BSON\ObjectID(Session::get('id')))->first();
        //     $groups = [];
        //     $group = [];
        //     if ($type == 1 || $type == 2 || $type == 3 || $type == 4 || $type == 5 || $type == 8) {
        //         $td = date('j', strtotime($_GET['to']));
        //         $groups[$retailer['_id']] = Bets::select('bet', 'won',  'playerCommission', 'classicCommission', 'ExecutiveCommission', 'premiumCommission', 'agentCommission')
        //             ->where('retailerId', new \MongoDB\BSON\ObjectID(Session::get('id')))
        //             ->whereBetween(
        //                 'createdAt',
        //                 array(
        //                     Carbon::create($fY, $fm, $fd, 00, 00, 00),
        //                     Carbon::create($tY, $tm, $td, 23, 59, 59),
        //                 )
        //             )->get()->toArray();
        //     } elseif ($type == 7 || $type == 6) {
        //         $to = date('n-j-Y', strtotime($_GET['to']));
        //         $groups[$retailer['_id']] = Bets::select('bet', 'won',  'playerCommission', 'classicCommission', 'ExecutiveCommission', 'premiumCommission', 'agentCommission')
        //             ->where('retailerId', new \MongoDB\BSON\ObjectID(Session::get('id')))->where('DrDate', $to)->get()->toArray();
        //     }
        //     // echo "<pre>";
        //     // print_r($groups);die;
        //     $commission = [];
        //     $PlayPoints = 0;
        //     $WinPoints = 0;
        //     $WinPoint = 0;
        //     $EndPoint = 0;
        //     $RetailerCommission = 0;
        //     $DistributerCommission = 0;
        //     $SuperDistributerCommission = 0;
        //     if (count($groups) == 0) {
        //         $commission[0]['userName'] = $retailer['userName'];
        //         $commission[0]['role'] = $retailer['role'];
        //         $commission[0]['name'] = $retailer['name'];
        //         $commission[0]['playPoint'] = 0;
        //         $commission[0]['wonPoint'] = 0;
        //         $commission[0]['endPoint'] = 0;
        //         $commission[0]['TotalRetailerCommission'] = 0;
        //     } else {
        //         foreach ($groups as $key => $get) {
        //             $commission[$key]['_id'] = $retailer['_id'];
        //             $commission[$key]['userName'] = $retailer['userName'];
        //             $commission[$key]['role'] = $retailer['role'];
        //             $commission[$key]['name'] = $retailer['name'];
        //             if (count($get) == 0) {
        //                 $commission[$key]['userName'] = $retailer['userName'];
        //                 $commission[$key]['role'] = $retailer['role'];
        //                 $commission[$key]['name'] = $retailer['name'];
        //                 $commission[$key]['playPoint'] = $PlayPoints;
        //                 $commission[$key]['wonPoint'] = $WinPoint;
        //                 $commission[$key]['endPoint'] = $EndPoint;
        //             } else {
        //                 foreach ($get as $player) {
        //                     $PlayPoints += $player['bet'];
        //                     $WinPoints += $player['won'];
        //                     $commission[$key]['playPoint'] = $PlayPoints;
        //                     $commission[$key]['wonPoint'] = $WinPoints;
        //                     $commission[$key]['endPoint'] = $EndPoint;
        //                     $totalPlayPoints = $commission[$key]['playPoint'];
        //                     $TotalWinPoints = $commission[$key]['wonPoint'];
        //                 }
        //             }
        //         }
        //     }

        //     // echo "<pre>";
        //     // print_r($commission);die();

        //     $EndPoint = $totalPlayPoints - $TotalWinPoints;
        //     $total = [];
        //     $total['totalPlayPoints'] = $totalPlayPoints;
        //     $total['TotalWinPoints'] = $TotalWinPoints;
        //     $total['EndPoint'] = $EndPoint;
        //     // echo "<pre>";
        //     // print_r($commissions);die();
        //     return view('gameProfit', ['data' => $commission, 'total' => $total, 'user' => $users]);
        // }
    }
}
