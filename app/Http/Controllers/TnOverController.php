<?php

namespace App\Http\Controllers;

use App\Bets;
use App\User;
use Carbon\Carbon;
use Session;

class TnOverController extends Controller
{
    public function index()
    {
        // return view('turnOver');
        $type = $_GET['type'];
        $resArray = array();
        $totalStartPoint = 0;
        $totalPlayPoints = 0;
        $TotalWinPoints = 0;
        $EndPoint = 0;
        $margin = 0;
        $netprofit = 0;
        $SuperDistributedProfit = 0;
        $TotalCommission = 0;
        $today = date_create(date("Y-m-d"));
        $users = User::where('role', '=', 'retailer')->get();

        $fm = date('m', strtotime($_GET['from']));
        $fd = date('j', strtotime($_GET['from']));
        $fY = date('Y', strtotime($_GET['from']));
        $tm = date('m', strtotime($_GET['to']));
        $tY = date('Y', strtotime($_GET['to']));

        if (Session::get('role') == "Admin" || Session::get('role') == "subadmin") {
            if (isset($_GET['role']) && $_GET['role'] == "agent") {
                $agent = User::where('role', 'agent')->where('referralId', new \MongoDB\BSON\ObjectID(Session::get('id')))->get();
                // echo "<pre>";
                // print_r($superdistributer->toArray());die;
                $admin = User::where('_id', new \MongoDB\BSON\ObjectID(Session::get('id')))->get();
                if (count($agent) == 0) {
                    $total = [];
                    $total['totalStartPoint'] = $totalStartPoint;
                    $total['totalPlayPoints'] = $totalPlayPoints;
                    $total['TotalWinPoints'] = $TotalWinPoints;
                    $total['EndPoint'] = $EndPoint;
                    $total['Margin'] = $margin;
                    $total['NetProfit'] = $netprofit;
                    $total['SuperDistributedProfit'] = $SuperDistributedProfit;
                    return view('turnOver', ['data' => $admin, 'total' => $total]);
                } else {
                    foreach ($agent as $super) {
                        $agents[] = new \MongoDB\BSON\ObjectID($super['_id']);
                    }
                    $premium = User::whereIn('referralId', $agents)->get();
                    if (count($premium) == 0) {
                        $total = [];
                        $total['totalStartPoint'] = $totalStartPoint;
                        $total['totalPlayPoints'] = $totalPlayPoints;
                        $total['TotalWinPoints'] = $TotalWinPoints;
                        $total['EndPoint'] = $EndPoint;
                        $total['Margin'] = $margin;
                        $total['NetProfit'] = $netprofit;
                        $total['SuperDistributedProfit'] = $SuperDistributedProfit;
                        return view('turnOver', ['data' => $admin, 'total' => $total]);
                    } else {
                        foreach ($premium as $pre_user) {
                            $pre[] = new \MongoDB\BSON\ObjectID($pre_user['_id']);
                        }
                        $executive = User::whereIn('referralId', $pre)->get();
                        if (count($executive) == 0) {
                            $total = [];
                            $total['totalStartPoint'] = $totalStartPoint;
                            $total['totalPlayPoints'] = $totalPlayPoints;
                            $total['TotalWinPoints'] = $TotalWinPoints;
                            $total['EndPoint'] = $EndPoint;
                            $total['Margin'] = $margin;
                            $total['NetProfit'] = $netprofit;
                            $total['SuperDistributedProfit'] = $SuperDistributedProfit;
                            return view('turnOver', ['data' => $admin, 'total' => $total]);
                        } else {
                            foreach ($executive as $exe_user) {
                                $exe[] = new \MongoDB\BSON\ObjectID($exe_user['_id']);
                            }
                            $classic = User::whereIn('referralId', $exe)->get();
                            if (count($classic) == 0) {
                                $total = [];
                                $total['totalStartPoint'] = $totalStartPoint;
                                $total['totalPlayPoints'] = $totalPlayPoints;
                                $total['TotalWinPoints'] = $TotalWinPoints;
                                $total['EndPoint'] = $EndPoint;
                                $total['Margin'] = $margin;
                                $total['NetProfit'] = $netprofit;
                                $total['SuperDistributedProfit'] = $SuperDistributedProfit;
                                return view('turnOver', ['data' => $admin, 'total' => $total]);
                            } else {
                                foreach ($classic as $cal_user) {
                                    $cal[] = new \MongoDB\BSON\ObjectID($cal_user['_id']);
                                }
                                $player = User::whereIn('referralId', $cal)->get();
                                foreach ($player as $player_user) {
                                    $players[] = new \MongoDB\BSON\ObjectID($player_user['_id']);
                                }
                                if (count($players) == 0) {
                                    $total = [];
                                    $total['totalStartPoint'] = $totalStartPoint;
                                    $total['totalPlayPoints'] = $totalPlayPoints;
                                    $total['TotalWinPoints'] = $TotalWinPoints;
                                    $total['EndPoint'] = $EndPoint;
                                    $total['Margin'] = $margin;
                                    $total['NetProfit'] = $netprofit;
                                    $total['SuperDistributedProfit'] = $SuperDistributedProfit;
                                    return view('turnOver', ['data' => $admin, 'total' => $total]);
                                } else {
                                    if ($type == 1 || $type == 2 || $type == 3 || $type == 4 || $type == 5 || $type == 8) {
                                        $td = date('j', strtotime($_GET['to']));
                                        $playPoints = Bets::select('bet', 'won', 'playerCommission', 'classicCommission', 'ExecutiveCommission', 'premiumCommission', 'agentCommission')
                                            ->whereIn('playerId', $players)
                                            ->whereBetween(
                                                'createdAt',
                                                array(
                                                    Carbon::create($fY, $fm, $fd, 00, 00, 00),
                                                    Carbon::create($tY, $tm, $td, 23, 59, 59),
                                                )
                                            )->get();
                                    } elseif ($type == 7 || $type == 6) {
                                        $to = date('n-j-Y', strtotime($_GET['to']));
                                        $playPoints = Bets::select('bet', 'won',  'playerCommission', 'classicCommission', 'ExecutiveCommission', 'premiumCommission', 'agentCommission')->whereIn('playerId', $players)
                                            ->where('DrDate', $to)->get();
                                    }

                                    // echo "<Pre>";
                                    // print_r($playPoints->toArray());
                                    // die;
                                    foreach ($playPoints as $play) {
                                        $totalStartPoint += $play['startPoint'];
                                        $totalPlayPoints += $play['bet'];
                                        $TotalWinPoints += $play['won'];
                                    }
                                    $total = [];
                                    $total['totalStartPoint'] = $totalStartPoint;
                                    $total['totalPlayPoints'] = $totalPlayPoints;
                                    $total['TotalWinPoints'] = $TotalWinPoints;
                                    $total['EndPoint'] = $total['totalPlayPoints'] - $total['TotalWinPoints'];
                                    $total['Margin'] = $margin;
                                    $total['NetProfit'] = $netprofit;
                                    $total['SuperDistributedProfit'] = $SuperDistributedProfit;
                                    return view('turnOver', ['data' => $admin, 'total' => $total]);
                                }
                            }
                        }
                    }
                }
            } elseif (isset($_GET['role']) && $_GET['role'] == "franchise") {
                $premium = User::where('role', 'premium')->where('referralId', new \MongoDB\BSON\ObjectID(Session::get('id')))->get();
                // echo "<pre>";
                // print_r($superdistributer->toArray());die;
                $admin = User::where('_id', new \MongoDB\BSON\ObjectID(Session::get('id')))->get();

                if (count($premium) == 0) {
                    $total = [];
                    $total['totalStartPoint'] = $totalStartPoint;
                    $total['totalPlayPoints'] = $totalPlayPoints;
                    $total['TotalWinPoints'] = $TotalWinPoints;
                    $total['EndPoint'] = $EndPoint;
                    $total['Margin'] = $margin;
                    $total['NetProfit'] = $netprofit;
                    $total['SuperDistributedProfit'] = $SuperDistributedProfit;
                    return view('turnOver', ['data' => $admin, 'total' => $total]);
                } else {
                    $pre = [];
                    foreach ($premium as $pre_user) {
                        $pre[] = new \MongoDB\BSON\ObjectID($pre_user['_id']);
                    }
                    $executive = User::whereIn('referralId', $pre)->get();
                    if (count($executive) == 0) {
                        $total = [];
                        $total['totalStartPoint'] = $totalStartPoint;
                        $total['totalPlayPoints'] = $totalPlayPoints;
                        $total['TotalWinPoints'] = $TotalWinPoints;
                        $total['EndPoint'] = $EndPoint;
                        $total['Margin'] = $margin;
                        $total['NetProfit'] = $netprofit;
                        $total['SuperDistributedProfit'] = $SuperDistributedProfit;
                        return view('turnOver', ['data' => $admin, 'total' => $total]);
                    } else {
                        $exe = [];
                        foreach ($executive as $exe_user) {
                            $exe[] = new \MongoDB\BSON\ObjectID($exe_user['_id']);
                        }
                        $classic = User::whereIn('referralId', $exe)->get();
                        if (count($classic) == 0) {
                            $total = [];
                            $total['totalStartPoint'] = $totalStartPoint;
                            $total['totalPlayPoints'] = $totalPlayPoints;
                            $total['TotalWinPoints'] = $TotalWinPoints;
                            $total['EndPoint'] = $EndPoint;
                            $total['Margin'] = $margin;
                            $total['NetProfit'] = $netprofit;
                            $total['SuperDistributedProfit'] = $SuperDistributedProfit;
                            return view('turnOver', ['data' => $admin, 'total' => $total]);
                        } else {
                            $cal = [];
                            foreach ($classic as $cal_user) {
                                $cal[] = new \MongoDB\BSON\ObjectID($cal_user['_id']);
                            }
                            $players = [];
                            $player = User::whereIn('referralId', $cal)->get();
                            foreach ($player as $player_user) {
                                $players[] = new \MongoDB\BSON\ObjectID($player_user['_id']);
                            }
                            if (count($players) == 0) {
                                $total = [];
                                $total['totalStartPoint'] = $totalStartPoint;
                                $total['totalPlayPoints'] = $totalPlayPoints;
                                $total['TotalWinPoints'] = $TotalWinPoints;
                                $total['EndPoint'] = $EndPoint;
                                $total['Margin'] = $margin;
                                $total['NetProfit'] = $netprofit;
                                $total['SuperDistributedProfit'] = $SuperDistributedProfit;
                                return view('turnOver', ['data' => $admin, 'total' => $total]);
                            } else {
                                if ($type == 1 || $type == 2 || $type == 3 || $type == 4 || $type == 5 || $type == 8) {
                                    $td = date('j', strtotime($_GET['to']));
                                    $playPoints = Bets::select('bet', 'won', 'playerCommission', 'classicCommission', 'ExecutiveCommission', 'premiumCommission', 'agentCommission')
                                        ->whereIn('playerId', $players)
                                        ->whereBetween(
                                            'createdAt',
                                            array(
                                                Carbon::create($fY, $fm, $fd, 00, 00, 00),
                                                Carbon::create($tY, $tm, $td, 23, 59, 59),
                                            )
                                        )->get();
                                } elseif ($type == 7 || $type == 6) {
                                    $to = date('n-j-Y', strtotime($_GET['to']));
                                    $playPoints = Bets::select('bet', 'won',  'playerCommission', 'classicCommission', 'ExecutiveCommission', 'premiumCommission', 'agentCommission')->whereIn('playerId', $players)
                                        ->where('DrDate', $to)->get();
                                }

                                // echo "<Pre>";
                                // print_r($playPoints->toArray());
                                // die;
                                foreach ($playPoints as $play) {
                                    $totalStartPoint += $play['startPoint'];
                                    $totalPlayPoints += $play['bet'];
                                    $TotalWinPoints += $play['won'];
                                }
                                $total = [];
                                $total['totalStartPoint'] = $totalStartPoint;
                                $total['totalPlayPoints'] = $totalPlayPoints;
                                $total['TotalWinPoints'] = $TotalWinPoints;
                                $total['EndPoint'] = $total['totalPlayPoints'] - $total['TotalWinPoints'];
                                $total['Margin'] = $margin;
                                $total['NetProfit'] = $netprofit;
                                $total['SuperDistributedProfit'] = $SuperDistributedProfit;
                                return view('turnOver', ['data' => $admin, 'total' => $total]);
                            }
                        }
                    }
                }
            }
        } elseif (Session::get('role') == "agent") {
            // echo "voijay";
            // die;
            $agent = User::where('_id', new \MongoDB\BSON\ObjectID(Session::get('id')))->first();
            // echo "<pre>";
            // print_r($superDistributer->toArray());
            // die();
            $premium = User::where('referralId', new \MongoDB\BSON\ObjectID(Session::get('id')))->get();
            if (count($premium) == 0) {
                $total = [];
                $total['totalStartPoint'] = $totalStartPoint;
                $total['totalPlayPoints'] = $totalPlayPoints;
                $total['TotalWinPoints'] = $TotalWinPoints;
                $total['EndPoint'] = $EndPoint;
                $total['Margin'] = $margin;
                $total['NetProfit'] = $netprofit;
                $total['SuperDistributedProfit'] = $SuperDistributedProfit;
                $data = [];
                return view('turnOver', ['data' => $data, 'total' => $total]);
            } else {
                foreach ($premium as $pre_user) {
                    $pre[] = new \MongoDB\BSON\ObjectID($pre_user['_id']);
                }
                $executive = User::whereIn('referralId', $pre)->get();
                if (count($executive) == 0) {
                    $total = [];
                    $total['totalStartPoint'] = $totalStartPoint;
                    $total['totalPlayPoints'] = $totalPlayPoints;
                    $total['TotalWinPoints'] = $TotalWinPoints;
                    $total['EndPoint'] = $EndPoint;
                    $total['Margin'] = $margin;
                    $total['NetProfit'] = $netprofit;
                    $total['SuperDistributedProfit'] = $SuperDistributedProfit;
                    $data = [];
                    return view('turnOver', ['data' => $data, 'total' => $total]);
                } else {
                    foreach ($executive as $exe_user) {
                        $exe[] = new \MongoDB\BSON\ObjectID($exe_user['_id']);
                    }
                    $classic = User::whereIn('referralId', $exe)->get();
                    if (count($classic) == 0) {
                        $total = [];
                        $total['totalStartPoint'] = $totalStartPoint;
                        $total['totalPlayPoints'] = $totalPlayPoints;
                        $total['TotalWinPoints'] = $TotalWinPoints;
                        $total['EndPoint'] = $EndPoint;
                        $total['Margin'] = $margin;
                        $total['NetProfit'] = $netprofit;
                        $total['SuperDistributedProfit'] = $SuperDistributedProfit;
                        $data = [];
                        return view('turnOver', ['data' => $data, 'total' => $total]);
                    } else {
                        foreach ($classic as $cal_user) {
                            $cal[] = new \MongoDB\BSON\ObjectID($cal_user['_id']);
                        }
                        $player = User::whereIn('referralId', $cal)->get();
                        foreach ($player as $player_user) {
                            $players[] = new \MongoDB\BSON\ObjectID($player_user['_id']);
                        }
                        if (count($players) == 0) {
                            $total = [];
                            $total['totalStartPoint'] = $totalStartPoint;
                            $total['totalPlayPoints'] = $totalPlayPoints;
                            $total['TotalWinPoints'] = $TotalWinPoints;
                            $total['EndPoint'] = $EndPoint;
                            $total['Margin'] = $margin;
                            $total['NetProfit'] = $netprofit;
                            $total['SuperDistributedProfit'] = $SuperDistributedProfit;
                            $data = [];
                            return view('turnOver', ['data' => $data, 'total' => $total]);
                        } else {
                            // echo "<pre>";
                            // print_r($players);
                            // die;
                            if ($type == 1 || $type == 2 || $type == 3 || $type == 4 || $type == 5 || $type == 8) {
                                $td = date('j', strtotime($_GET['to']));
                                $groups[$agent['_id']] = Bets::select('bet', 'won',  'playerCommission', 'classicCommission', 'ExecutiveCommission', 'premiumCommission', 'agentCommission')
                                    ->whereIn('playerId', $players)
                                    ->whereBetween(
                                        'createdAt',
                                        array(
                                            Carbon::create($fY, $fm, $fd, 00, 00, 00),
                                            Carbon::create($tY, $tm, $td, 23, 59, 59),
                                        )
                                    )->get()->toArray();
                            } elseif ($type == 7 || $type == 6) {
                                $to = date('n-j-Y', strtotime($_GET['to']));
                                $groups[$agent['_id']] = Bets::select('bet', 'won',  'playerCommission', 'classicCommission', 'ExecutiveCommission', 'premiumCommission', 'agentCommission')
                                    ->whereIn('playerId', $players)->where('DrDate', $to)->get()->toArray();
                            }

                            // echo "<Pre>";
                            // print_r($groups);
                            // die;
                            $commission = [];
                            foreach ($groups as $key => $get) {
                                $PlayPoints = 0;
                                $WinPoints = 0;
                                $EndPoint = 0;
                                $RetailerCommission = 0;
                                $DistributerCommission = 0;
                                $SuperDistributerCommission = 0;
                                $commission[$key]['_id'] = $agent['_id'];
                                $commission[$key]['userName'] = $agent['userName'];
                                $commission[$key]['role'] = $agent['role'];
                                $commission[$key]['name'] = $agent['name'];
                                $commission[$key]['commission'] = $agent['commissionPercentage'];
                                foreach ($get as $player) {
                                    $PlayPoints += $player['bet'];
                                    $WinPoints += $player['won'];
                                    $EndPoint = $PlayPoints - $WinPoints;
                                }
                                $commission[$key]['playPoint'] = $PlayPoints;
                                $commission[$key]['wonPoint'] = $WinPoints;
                                $commission[$key]['endPoint'] = $EndPoint;
                                $commission[$key]['SuperDistributedProfit'] = 0;
                            }
                            // echo "<pre>";
                            // print_r($commission);
                            // die;
                            foreach ($commission as $play) {
                                $totalPlayPoints += $play['playPoint'];
                                $TotalWinPoints += $play['wonPoint'];
                                $EndPoint = $totalPlayPoints - $TotalWinPoints;
                            }
                            $total = [];
                            $total['totalPlayPoints'] = $totalPlayPoints;
                            $total['TotalWinPoints'] = $TotalWinPoints;
                            $total['EndPoint'] = $EndPoint;
                            $total['Margin'] = $margin;
                            $total['NetProfit'] = $netprofit;
                            $total['SuperDistributedProfit'] = $SuperDistributedProfit;
                            // echo "<pre>";
                            // print_r($total);
                            // die();
                            return view('turnOver', ['data' => $commission, 'total' => $total, 'user' => $users]);
                        }
                    }
                }
            }
        } elseif (Session::get('role') == "premium") {
            $premium = User::where('_id', new \MongoDB\BSON\ObjectID(Session::get('id')))->first();
            // echo "<pre>";
            // print_r($premium->toArray());
            // die();
            $executive = User::where('referralId', new \MongoDB\BSON\ObjectID(Session::get('id')))->get();
            if (count($executive) == 0) {
                $total = [];
                $total['totalStartPoint'] = $totalStartPoint;
                $total['totalPlayPoints'] = $totalPlayPoints;
                $total['TotalWinPoints'] = $TotalWinPoints;
                $total['EndPoint'] =  $EndPoint;
                $data = [];
                return view('turnOver', ['data' => $data, 'total' => $total]);
            } else {
                foreach ($executive as $exe_user) {
                    $exe[] = new \MongoDB\BSON\ObjectID($exe_user['_id']);
                }
                $classic = User::whereIn('referralId', $exe)->get();
                if (count($classic) == 0) {
                    $total = [];
                    $total['totalStartPoint'] = $totalStartPoint;
                    $total['totalPlayPoints'] = $totalPlayPoints;
                    $total['TotalWinPoints'] = $TotalWinPoints;
                    $total['EndPoint'] = $EndPoint;
                    $total['Margin'] = $margin;
                    $total['NetProfit'] = $netprofit;
                    $total['SuperDistributedProfit'] = $SuperDistributedProfit;

                    $data = [];
                    // echo "<pre>";
                    // print_r($total);
                    // print_r($premium->toArray());
                    // die;
                    return view('turnOver', ['data' => $data, 'total' => $total]);
                } else {
                    foreach ($classic as $cal_user) {
                        $cal[] = new \MongoDB\BSON\ObjectID($cal_user['_id']);
                    }
                    $player = User::whereIn('referralId', $cal)->get();
                    foreach ($player as $player_user) {
                        $players[] = new \MongoDB\BSON\ObjectID($player_user['_id']);
                    }
                    if (count($players) == 0) {
                        $total = [];
                        $total['totalStartPoint'] = $totalStartPoint;
                        $total['totalPlayPoints'] = $totalPlayPoints;
                        $total['TotalWinPoints'] = $TotalWinPoints;
                        $total['EndPoint'] = $EndPoint;
                        $total['Margin'] = $margin;
                        $total['NetProfit'] = $netprofit;
                        $total['SuperDistributedProfit'] = $SuperDistributedProfit;

                        $data = [];
                        return view('turnOver', ['data' => $data, 'total' => $total]);
                    } else {
                        // echo "<pre>";
                        // print_r($players);
                        // die;
                        if ($type == 1 || $type == 2 || $type == 3 || $type == 4 || $type == 5 || $type == 8) {
                            $td = date('j', strtotime($_GET['to']));
                            $groups[$premium['_id']] = Bets::select('bet', 'won',  'playerCommission', 'classicCommission', 'ExecutiveCommission', 'premiumCommission', 'agentCommission')
                                ->whereIn('playerId', $players)
                                ->whereBetween(
                                    'createdAt',
                                    array(
                                        Carbon::create($fY, $fm, $fd, 00, 00, 00),
                                        Carbon::create($tY, $tm, $td, 23, 59, 59),
                                    )
                                )->get()->toArray();
                        } elseif ($type == 7 || $type == 6) {
                            $to = date('n-j-Y', strtotime($_GET['to']));
                            $groups[$premium['_id']] = Bets::select('bet', 'won',  'playerCommission', 'classicCommission', 'ExecutiveCommission', 'premiumCommission', 'agentCommission')
                                ->whereIn('playerId', $players)->where('DrDate', $to)->get()->toArray();
                        }

                        // echo "<Pre>";
                        // print_r($groups);
                        // die;
                        $commission = [];
                        foreach ($groups as $key => $get) {
                            $PlayPoints = 0;
                            $WinPoints = 0;
                            $EndPoint = 0;
                            $RetailerCommission = 0;
                            $DistributerCommission = 0;
                            $SuperDistributerCommission = 0;
                            $commission[$key]['_id'] = $premium['_id'];
                            $commission[$key]['userName'] = $premium['userName'];
                            $commission[$key]['role'] = $premium['role'];
                            $commission[$key]['name'] = $premium['name'];
                            $commission[$key]['commission'] = $premium['commissionPercentage'];
                            foreach ($get as $player) {
                                $PlayPoints += $player['bet'];
                                $WinPoints += $player['won'];
                                $EndPoint = $PlayPoints - $WinPoints;
                            }
                            $commission[$key]['playPoint'] = $PlayPoints;
                            $commission[$key]['wonPoint'] = $WinPoints;
                            $commission[$key]['endPoint'] = $EndPoint;
                            $commission[$key]['SuperDistributedProfit'] = 0;
                        }
                        // echo "<pre>";
                        // print_r($commission);die;
                        foreach ($commission as $play) {
                            $totalPlayPoints += $play['playPoint'];
                            $TotalWinPoints += $play['wonPoint'];
                            $EndPoint = $totalPlayPoints - $TotalWinPoints;
                        }
                        $total = [];
                        $total['totalPlayPoints'] = $totalPlayPoints;
                        $total['TotalWinPoints'] = $TotalWinPoints;
                        $total['EndPoint'] = $EndPoint;
                        $total['Margin'] = $margin;
                        $total['NetProfit'] = $netprofit;
                        $total['SuperDistributedProfit'] = $SuperDistributedProfit;
                        // echo "<pre>";
                        // print_r($total);die();
                        return view('turnOver', ['data' => $commission, 'total' => $total, 'user' => $users]);
                    }
                }
            }
        } elseif (Session::get('role') == "executive") {
            $executive = User::where('_id', new \MongoDB\BSON\ObjectID(Session::get('id')))->first();
            // echo "<pre>";
            // print_r($superDistributer->toArray());
            // die();
            $classic = User::where('referralId', new \MongoDB\BSON\ObjectID(Session::get('id')))->get();

            if (count($classic) == 0) {
                $total = [];
                $total['totalStartPoint'] = $totalStartPoint;
                $total['totalPlayPoints'] = $totalPlayPoints;
                $total['TotalWinPoints'] = $TotalWinPoints;
                $total['EndPoint'] = $EndPoint;
                $total['Margin'] = $margin;
                $total['NetProfit'] = $netprofit;
                $total['SuperDistributedProfit'] = $SuperDistributedProfit;
                $data = [];
                return view('turnOver', ['data' => $data, 'total' => $total]);
            } else {
                foreach ($classic as $cal_user) {
                    $cal[] = new \MongoDB\BSON\ObjectID($cal_user['_id']);
                }
                $player = User::whereIn('referralId', $cal)->get();
                foreach ($player as $player_user) {
                    $players[] = new \MongoDB\BSON\ObjectID($player_user['_id']);
                }
                if (count($players) == 0) {
                    $total = [];
                    $total['totalStartPoint'] = $totalStartPoint;
                    $total['totalPlayPoints'] = $totalPlayPoints;
                    $total['TotalWinPoints'] = $TotalWinPoints;
                    $total['EndPoint'] = $EndPoint;
                    $total['Margin'] = $margin;
                    $total['NetProfit'] = $netprofit;
                    $total['SuperDistributedProfit'] = $SuperDistributedProfit;
                    $data = [];
                    return view('turnOver', ['data' => $data, 'total' => $total]);
                } else {
                    // echo "<pre>";
                    // print_r($players);
                    // die;
                    if ($type == 1 || $type == 2 || $type == 3 || $type == 4 || $type == 5 || $type == 8) {
                        $td = date('j', strtotime($_GET['to']));
                        $groups[$executive['_id']] = Bets::select('bet', 'won',  'playerCommission', 'classicCommission', 'ExecutiveCommission', 'premiumCommission', 'agentCommission')
                            ->whereIn('playerId', $players)
                            ->whereBetween(
                                'createdAt',
                                array(
                                    Carbon::create($fY, $fm, $fd, 00, 00, 00),
                                    Carbon::create($tY, $tm, $td, 23, 59, 59),
                                )
                            )->get()->toArray();
                    } elseif ($type == 7 || $type == 6) {
                        $to = date('n-j-Y', strtotime($_GET['to']));
                        $groups[$executive['_id']] = Bets::select('bet', 'won',  'playerCommission', 'classicCommission', 'ExecutiveCommission', 'premiumCommission', 'agentCommission')
                            ->whereIn('playerId', $players)->where('DrDate', $to)->get()->toArray();
                    }

                    // echo "<Pre>";
                    // print_r($groups);
                    // die;
                    $commission = [];
                    foreach ($groups as $key => $get) {
                        $PlayPoints = 0;
                        $WinPoints = 0;
                        $EndPoint = 0;
                        $RetailerCommission = 0;
                        $DistributerCommission = 0;
                        $SuperDistributerCommission = 0;
                        $commission[$key]['_id'] = $executive['_id'];
                        $commission[$key]['userName'] = $executive['userName'];
                        $commission[$key]['role'] = $executive['role'];
                        $commission[$key]['name'] = $executive['name'];
                        $commission[$key]['commission'] = $executive['commissionPercentage'];
                        foreach ($get as $player) {
                            $PlayPoints += $player['bet'];
                            $WinPoints += $player['won'];
                            $EndPoint = $PlayPoints - $WinPoints;
                        }
                        $commission[$key]['playPoint'] = $PlayPoints;
                        $commission[$key]['wonPoint'] = $WinPoints;
                        $commission[$key]['endPoint'] = $EndPoint;
                        $commission[$key]['SuperDistributedProfit'] = 0;
                    }
                    // echo "<pre>";
                    // print_r($commission);die;
                    foreach ($commission as $play) {
                        $totalPlayPoints += $play['playPoint'];
                        $TotalWinPoints += $play['wonPoint'];
                        $EndPoint = $totalPlayPoints - $TotalWinPoints;
                    }
                    $total = [];
                    $total['totalPlayPoints'] = $totalPlayPoints;
                    $total['TotalWinPoints'] = $TotalWinPoints;
                    $total['EndPoint'] = $EndPoint;
                    $total['Margin'] = $margin;
                    $total['NetProfit'] = $netprofit;
                    $total['SuperDistributedProfit'] = $SuperDistributedProfit;
                    // echo "<pre>";
                    // print_r($total);die();
                    return view('turnOver', ['data' => $commission, 'total' => $total, 'user' => $users]);
                }
            }
        } elseif (Session::get('role') == "classic") {
            $classic = User::where('_id', new \MongoDB\BSON\ObjectID(Session::get('id')))->first();
            // echo "<pre>";
            // print_r($superDistributer->toArray());
            // die();
            $player = User::where('referralId', new \MongoDB\BSON\ObjectID(Session::get('id')))->get();

            foreach ($player as $player_user) {
                $players[] = new \MongoDB\BSON\ObjectID($player_user['_id']);
            }
            if (count($players) == 0) {
                $total = [];
                $total['totalStartPoint'] = $totalStartPoint;
                $total['totalPlayPoints'] = $totalPlayPoints;
                $total['TotalWinPoints'] = $TotalWinPoints;
                $total['EndPoint'] = $EndPoint;
                $total['Margin'] = $margin;
                $total['NetProfit'] = $netprofit;
                $total['SuperDistributedProfit'] = $SuperDistributedProfit;
                $data = [];
                return view('turnOver', ['data' => $data, 'total' => $total]);
            } else {
                // echo "<pre>";
                // print_r($players);
                // die;
                if ($type == 1 || $type == 2 || $type == 3 || $type == 4 || $type == 5 || $type == 8) {
                    $td = date('j', strtotime($_GET['to']));
                    $groups[$classic['_id']] = Bets::select('bet', 'won',  'playerCommission', 'classicCommission', 'ExecutiveCommission', 'premiumCommission', 'agentCommission')
                        ->whereIn('playerId', $players)
                        ->whereBetween(
                            'createdAt',
                            array(
                                Carbon::create($fY, $fm, $fd, 00, 00, 00),
                                Carbon::create($tY, $tm, $td, 23, 59, 59),
                            )
                        )->get()->toArray();
                } elseif ($type == 7 || $type == 6) {
                    $to = date('n-j-Y', strtotime($_GET['to']));
                    $groups[$classic['_id']] = Bets::select('bet', 'won',  'playerCommission', 'classicCommission', 'ExecutiveCommission', 'premiumCommission', 'agentCommission')
                        ->whereIn('playerId', $players)->where('DrDate', $to)->get()->toArray();
                }

                // echo "<Pre>";
                // print_r($groups);
                // die;
                $commission = [];
                foreach ($groups as $key => $get) {
                    $PlayPoints = 0;
                    $WinPoints = 0;
                    $EndPoint = 0;
                    $RetailerCommission = 0;
                    $DistributerCommission = 0;
                    $SuperDistributerCommission = 0;
                    $commission[$key]['_id'] = $classic['_id'];
                    $commission[$key]['userName'] = $classic['userName'];
                    $commission[$key]['role'] = $classic['role'];
                    $commission[$key]['name'] = $classic['name'];
                    $commission[$key]['commission'] = $classic['commissionPercentage'];
                    foreach ($get as $player) {
                        $PlayPoints += $player['bet'];
                        $WinPoints += $player['won'];
                        $EndPoint = $PlayPoints - $WinPoints;
                    }
                    $commission[$key]['playPoint'] = $PlayPoints;
                    $commission[$key]['wonPoint'] = $WinPoints;
                    $commission[$key]['endPoint'] = $EndPoint;
                    $commission[$key]['SuperDistributedProfit'] = 0;
                }
                // echo "<pre>";
                // print_r($commission);die;
                foreach ($commission as $play) {
                    $totalPlayPoints += $play['playPoint'];
                    $TotalWinPoints += $play['wonPoint'];
                    $EndPoint = $totalPlayPoints - $TotalWinPoints;
                }
                $total = [];
                $total['totalPlayPoints'] = $totalPlayPoints;
                $total['TotalWinPoints'] = $TotalWinPoints;
                $total['EndPoint'] = $EndPoint;
                $total['Margin'] = $margin;
                $total['NetProfit'] = $netprofit;
                $total['SuperDistributedProfit'] = $SuperDistributedProfit;
                // echo "<pre>";
                // print_r($total);die();
                return view('turnOver', ['data' => $commission, 'total' => $total, 'user' => $users]);
            }
        }
        // return view('turnOver');
    }

    public function detail($id)
    {
        $type = $_GET['type'];
        $resArray = array();
        $totalStartPoint = 0;
        $totalPlayPoints = 0;
        $TotalWinPoints = 0;
        $EndPoint = 0;
        $EndPoint = 0;
        $margin = 0;
        $netprofit = 0;
        $SuperDistributedProfit = 0;
        $TotalCommission = 0;
        $today = date_create(date("Y-m-d"));
        $user = User::where('_id', '=', new \MongoDB\BSON\ObjectID($id))->first();

        // echo "<pre>";
        // print_r();die();

        $fm = date('m', strtotime($_GET['from']));
        $fd = date('j', strtotime($_GET['from']));
        $fY = date('Y', strtotime($_GET['from']));
        $tm = date('m', strtotime($_GET['to']));
        $tY = date('Y', strtotime($_GET['to']));

        if ($user->role == 'Admin') {
            if ($_GET['role'] == 'agent') {
                $refer = User::where('role', 'agent')->where('referralId', new \MongoDB\BSON\ObjectID($id))->get();
                // echo "<pre>";
                // print_r($refer->toArray());
                // die;
                if (count($refer) == 0) {
                    $total = [];
                    $total['totalStartPoint'] = $totalStartPoint;
                    $total['totalPlayPoints'] = $totalPlayPoints;
                    $total['TotalWinPoints'] = $TotalWinPoints;
                    $total['EndPoint'] = $EndPoint;
                    $total['Margin'] = $margin;
                    $total['NetProfit'] = $netprofit;
                    $total['SuperDistributedProfit'] = $SuperDistributedProfit;
                    return view('turnOver', ['data' => $refer, 'total' => $total]);
                } else {
                    $commission = [];
                    foreach ($refer as $super) {
                        $premium = User::where('referralId', new \MongoDB\BSON\ObjectID($super['_id']))->get();
                        if (count($premium) == 0) {
                            $total = [];
                            $total['totalStartPoint'] = $totalStartPoint;
                            $total['totalPlayPoints'] = $totalPlayPoints;
                            $total['TotalWinPoints'] = $TotalWinPoints;
                            $total['EndPoint'] = $EndPoint;
                            $total['Margin'] = $margin;
                            $total['NetProfit'] = $netprofit;
                            $total['SuperDistributedProfit'] = $SuperDistributedProfit;
                            // return view('turnOver', ['data' => $refer, 'total' => $total]);
                        } else {
                            $pre = [];
                            foreach ($premium as $pre_user) {
                                $pre[] = new \MongoDB\BSON\ObjectID($pre_user['_id']);
                            }
                            $executive = User::whereIn('referralId', $pre)->get();
                            if (count($executive) == 0) {
                                $total = [];
                                $total['totalStartPoint'] = $totalStartPoint;
                                $total['totalPlayPoints'] = $totalPlayPoints;
                                $total['TotalWinPoints'] = $TotalWinPoints;
                                $total['EndPoint'] = $EndPoint;
                                $total['Margin'] = $margin;
                                $total['NetProfit'] = $netprofit;
                                $total['SuperDistributedProfit'] = $SuperDistributedProfit;
                            } else {
                                $exe = [];
                                foreach ($executive as $exe_user) {
                                    $exe[] = new \MongoDB\BSON\ObjectID($exe_user['_id']);
                                }
                                $cal = [];
                                $classic = User::whereIn('referralId', $exe)->get();
                                foreach ($classic as $cal_user) {
                                    $cal[] = new \MongoDB\BSON\ObjectID($cal_user['_id']);
                                }
                                $retailers = [];
                                $retailer = User::whereIn('referralId', $cal)->get();
                                foreach ($retailer as $re_user) {
                                    $retailers[] = new \MongoDB\BSON\ObjectID($re_user['_id']);
                                }

                                // $groups = [];
                                if ($type == 1 || $type == 2 || $type == 3 || $type == 4 || $type == 5 || $type == 8) {
                                    $td = date('j', strtotime($_GET['to']));
                                    $groups[$super['_id']] = Bets::select('bet', 'won',  'playerCommission', 'classicCommission', 'ExecutiveCommission', 'premiumCommission', 'agentCommission')
                                        ->whereIn('playerId', $retailers)
                                        ->whereBetween(
                                            'createdAt',
                                            array(
                                                Carbon::create($fY, $fm, $fd, 00, 00, 00),
                                                Carbon::create($tY, $tm, $td, 23, 59, 59),
                                            )
                                        )->get()->toArray();
                                } elseif ($type == 7 || $type == 6) {
                                    $to = date('n-j-Y', strtotime($_GET['to']));
                                    $groups[$super['_id']] = Bets::select('bet', 'won',  'playerCommission', 'classicCommission', 'ExecutiveCommission', 'premiumCommission', 'agentCommission')
                                        ->whereIn('playerId', $retailers)->where('DrDate', $to)->get()->toArray();
                                }
                                // echo "<pre>";
                                // print_r($groups);
                                // die;

                                foreach ($groups as $key => $get) {
                                    $PlayPoints = 0;
                                    $WinPoints = 0;
                                    $EndPoint = 0;
                                    $commission[$key]['role'] = $super['role'];
                                    foreach ($get as $player) {
                                        $PlayPoints += $player['bet'];
                                        $WinPoints += $player['won'];
                                    }
                                    foreach ($refer as $sup) {
                                        if ($key == $sup['_id']) {
                                            $commission[$key]['_id'] = $sup['_id'];
                                            $commission[$key]['userName'] = $sup['userName'];
                                            $commission[$key]['name'] = $sup['name'];
                                            $commission[$key]['commission'] = $sup['commissionPercentage'];
                                        }
                                    }
                                    $commission[$key]['playPoint'] = $PlayPoints;
                                    $commission[$key]['wonPoint'] = $WinPoints;
                                    $commission[$key]['endPoint'] = $PlayPoints - $WinPoints;
                                    $commission[$key]['margin'] =  ($commission[$key]['endPoint'] * $commission[$key]['commission']) / 100;
                                    $commission[$key]['netprofit'] =  $commission[$key]['endPoint'] - $commission[$key]['margin'];
                                    $commission[$key]['SuperDistributedProfit'] =  0;
                                }
                            }
                        }
                        // echo "<pre>";
                        // print_r($commission);
                    }

                    // die;
                    foreach ($commission as $play) {
                        $totalPlayPoints += $play['playPoint'];
                        $TotalWinPoints += $play['wonPoint'];
                        $EndPoint = $totalPlayPoints - $TotalWinPoints;
                        $margin += $play['margin'];
                        $netprofit += $play['netprofit'];
                    }
                    $total = [];
                    $total['totalPlayPoints'] = $totalPlayPoints;
                    $total['TotalWinPoints'] = $TotalWinPoints;
                    $total['EndPoint'] = $EndPoint;
                    $total['Margin'] = $margin;
                    $total['NetProfit'] = $netprofit;
                    $total['SuperDistributedProfit'] = $SuperDistributedProfit;

                    // echo "<pre>";
                    // print_r($playPoints->toArray());die();
                    // return view('turnOver', ['data' => $refer, 'total' => $total]);
                }
                return view('turnOver', ['data' => $commission, 'total' => $total]);
            } elseif ($_GET['role'] == 'franchise') {
                $refer = User::where('role', 'premium')->where('referralId', new \MongoDB\BSON\ObjectID($id))->get();
                if (count($refer) == 0) {
                    $total = [];
                    $total['totalStartPoint'] = $totalStartPoint;
                    $total['totalPlayPoints'] = $totalPlayPoints;
                    $total['TotalWinPoints'] = $TotalWinPoints;
                    $total['EndPoint'] = $EndPoint;
                    $total['Margin'] = $margin;
                    $total['NetProfit'] = $netprofit;
                    $total['SuperDistributedProfit'] = $SuperDistributedProfit;
                    return view('turnOver', ['data' => $refer, 'total' => $total]);
                } else {
                    $commission = [];
                    foreach ($refer as $pre_user) {
                        $executive = User::where('referralId', new \MongoDB\BSON\ObjectID($pre_user['_id']))->get();

                        if (count($executive) == 0) {
                            $total = [];
                            $total['totalStartPoint'] = $totalStartPoint;
                            $total['totalPlayPoints'] = $totalPlayPoints;
                            $total['TotalWinPoints'] = $TotalWinPoints;
                            $total['EndPoint'] = $EndPoint;
                            $total['Margin'] = $margin;
                            $total['NetProfit'] = $netprofit;
                            $total['SuperDistributedProfit'] = $SuperDistributedProfit;
                            // return view('turnOver', ['data' => $refer, 'total' => $total]);
                        } else {
                            $exe = [];
                            foreach ($executive as $exe_user) {
                                $exe[] = new \MongoDB\BSON\ObjectID($exe_user['_id']);
                            }
                            $cal = [];
                            $classic = User::whereIn('referralId', $exe)->get();
                            foreach ($classic as $cal_user) {
                                $cal[] = new \MongoDB\BSON\ObjectID($cal_user['_id']);
                            }
                            $retailers = [];
                            $retailer = User::whereIn('referralId', $cal)->get();
                            foreach ($retailer as $re_user) {
                                $retailers[] = new \MongoDB\BSON\ObjectID($re_user['_id']);
                            }
                            // echo "<pre>";
                            // print_r($retailers);
                            // die;
                            // $groups = [];
                            if ($type == 1 || $type == 2 || $type == 3 || $type == 4 || $type == 5 || $type == 8) {
                                $td = date('j', strtotime($_GET['to']));
                                $groups[$pre_user['_id']] = Bets::select('bet', 'won',  'playerCommission', 'classicCommission', 'ExecutiveCommission', 'premiumCommission', 'agentCommission')
                                    ->whereIn('playerId', $retailers)
                                    ->whereBetween(
                                        'createdAt',
                                        array(
                                            Carbon::create($fY, $fm, $fd, 00, 00, 00),
                                            Carbon::create($tY, $tm, $td, 23, 59, 59),
                                        )
                                    )->get()->toArray();
                            } elseif ($type == 7 || $type == 6) {
                                $to = date('n-j-Y', strtotime($_GET['to']));
                                $groups[$pre_user['_id']] = Bets::select('bet', 'won',  'playerCommission', 'classicCommission', 'ExecutiveCommission', 'premiumCommission', 'agentCommission')
                                    ->whereIn('playerId', $retailers)->where('DrDate', $to)->get()->toArray();
                            }
                            // echo "<pre>";
                            // print_r($groups);
                            // die;

                            foreach ($groups as $key => $get) {
                                $PlayPoints = 0;
                                $WinPoints = 0;
                                $EndPoint = 0;
                                $commission[$key]['role'] = $pre_user['role'];
                                foreach ($get as $player) {
                                    $PlayPoints += $player['bet'];
                                    $WinPoints += $player['won'];
                                }
                                foreach ($refer as $sup) {
                                    if ($key == $sup['_id']) {
                                        $commission[$key]['_id'] = $sup['_id'];
                                        $commission[$key]['userName'] = $sup['userName'];
                                        $commission[$key]['name'] = $sup['name'];
                                        $commission[$key]['commission'] = $sup['commissionPercentage'];
                                    }
                                }
                                $commission[$key]['playPoint'] = $PlayPoints;
                                $commission[$key]['wonPoint'] = $WinPoints;
                                $commission[$key]['endPoint'] = $PlayPoints - $WinPoints;
                                $commission[$key]['margin'] =  ($commission[$key]['playPoint'] * $commission[$key]['commission']) / 100;
                                $commission[$key]['netprofit'] =  $commission[$key]['endPoint'] - $commission[$key]['margin'];
                                $commission[$key]['SuperDistributedProfit'] =  0;
                            }
                        }
                    }
                    // echo "<pre>";
                    // print_r($commission);
                    // die;
                    foreach ($commission as $play) {
                        $totalPlayPoints += $play['playPoint'];
                        $TotalWinPoints += $play['wonPoint'];
                        $EndPoint = $totalPlayPoints - $TotalWinPoints;
                        $margin += $play['margin'];
                        $netprofit += $play['netprofit'];
                    }
                    $total = [];
                    $total['totalPlayPoints'] = $totalPlayPoints;
                    $total['TotalWinPoints'] = $TotalWinPoints;
                    $total['EndPoint'] = $EndPoint;
                    $total['Margin'] = $margin;
                    $total['NetProfit'] = $netprofit;
                    $total['SuperDistributedProfit'] = $SuperDistributedProfit;
                    // echo "<pre>";
                    // print_r($commission);
                    // die;

                    // echo "<pre>";
                    // print_r($playPoints->toArray());die();
                    // return view('turnOver', ['data' => $refer, 'total' => $total]);


                }
                return view('turnOver', ['data' => $commission, 'total' => $total]);
            }
        } elseif ($user->role == 'agent') {
            $refer = User::where('role', 'premium')->where('referralId', new \MongoDB\BSON\ObjectID($id))->get();
            if (count($refer) == 0) {
                $total = [];
                $total['totalStartPoint'] = $totalStartPoint;
                $total['totalPlayPoints'] = $totalPlayPoints;
                $total['TotalWinPoints'] = $TotalWinPoints;
                $total['EndPoint'] = $EndPoint;
                $total['Margin'] = $margin;
                $total['NetProfit'] = $netprofit;
                $total['SuperDistributedProfit'] = $SuperDistributedProfit;
                return view('turnOver', ['data' => $refer, 'total' => $total]);
            } else {
                $commission = [];
                foreach ($refer as $pre_user) {
                    $executive = User::where('referralId', new \MongoDB\BSON\ObjectID($pre_user['_id']))->get();

                    if (count($executive) == 0) {
                        $total = [];
                        $total['totalStartPoint'] = $totalStartPoint;
                        $total['totalPlayPoints'] = $totalPlayPoints;
                        $total['TotalWinPoints'] = $TotalWinPoints;
                        $total['EndPoint'] = $EndPoint;
                        $total['Margin'] = $margin;
                        $total['NetProfit'] = $netprofit;
                        $total['SuperDistributedProfit'] = $SuperDistributedProfit;
                        // return view('turnOver', ['data' => $refer, 'total' => $total]);
                    } else {
                        $exe = [];
                        foreach ($executive as $exe_user) {
                            $exe[] = new \MongoDB\BSON\ObjectID($exe_user['_id']);
                        }
                        $cal = [];
                        $classic = User::whereIn('referralId', $exe)->get();
                        foreach ($classic as $cal_user) {
                            $cal[] = new \MongoDB\BSON\ObjectID($cal_user['_id']);
                        }
                        $retailers = [];
                        $retailer = User::whereIn('referralId', $cal)->get();
                        foreach ($retailer as $re_user) {
                            $retailers[] = new \MongoDB\BSON\ObjectID($re_user['_id']);
                        }
                        // echo "<pre>";
                        // print_r($retailers);
                        // die;
                        // $groups = [];
                        if ($type == 1 || $type == 2 || $type == 3 || $type == 4 || $type == 5 || $type == 8) {
                            $td = date('j', strtotime($_GET['to']));
                            $groups[$pre_user['_id']] = Bets::select('bet', 'won',  'playerCommission', 'classicCommission', 'ExecutiveCommission', 'premiumCommission', 'agentCommission')
                                ->whereIn('playerId', $retailers)
                                ->whereBetween(
                                    'createdAt',
                                    array(
                                        Carbon::create($fY, $fm, $fd, 00, 00, 00),
                                        Carbon::create($tY, $tm, $td, 23, 59, 59),
                                    )
                                )->get()->toArray();
                        } elseif ($type == 7 || $type == 6) {
                            $to = date('n-j-Y', strtotime($_GET['to']));
                            $groups[$pre_user['_id']] = Bets::select('bet', 'won',  'playerCommission', 'classicCommission', 'ExecutiveCommission', 'premiumCommission', 'agentCommission')
                                ->whereIn('playerId', $retailers)->where('DrDate', $to)->get()->toArray();
                        }
                        // echo "<pre>";
                        // print_r($groups);
                        // die;

                        foreach ($groups as $key => $get) {
                            $PlayPoints = 0;
                            $WinPoints = 0;
                            $EndPoint = 0;
                            $commission[$key]['role'] = $pre_user['role'];
                            foreach ($get as $player) {
                                $PlayPoints += $player['bet'];
                                $WinPoints += $player['won'];
                            }
                            foreach ($refer as $sup) {
                                if ($key == $sup['_id']) {
                                    $commission[$key]['_id'] = $sup['_id'];
                                    $commission[$key]['userName'] = $sup['userName'];
                                    $commission[$key]['name'] = $sup['name'];
                                    $commission[$key]['commission'] = $sup['commissionPercentage'];
                                    $commission[$key]['superCommission'] = $user->commissionPercentage - $sup['commissionPercentage'];
                                }
                            }
                            $commission[$key]['playPoint'] = $PlayPoints;
                            $commission[$key]['wonPoint'] = $WinPoints;
                            $commission[$key]['endPoint'] = $PlayPoints - $WinPoints;
                            $commission[$key]['margin'] =  ($commission[$key]['endPoint'] * $commission[$key]['commission']) / 100;
                            $commission[$key]['netprofit'] =  $commission[$key]['endPoint'] - $commission[$key]['margin'];
                            $commission[$key]['SuperDistributedProfit'] =  ($commission[$key]['superCommission'] * $commission[$key]['endPoint']) / 100;
                        }
                    }
                }
                // echo "<pre>";
                // print_r($commission);
                // die;
                foreach ($commission as $play) {
                    $totalPlayPoints += $play['playPoint'];
                    $TotalWinPoints += $play['wonPoint'];
                    $EndPoint = $totalPlayPoints - $TotalWinPoints;
                    $margin += $play['margin'];
                    $netprofit += $play['netprofit'];
                    $SuperDistributedProfit += $play['SuperDistributedProfit'];
                }
                $total = [];
                $total['totalPlayPoints'] = $totalPlayPoints;
                $total['TotalWinPoints'] = $TotalWinPoints;
                $total['EndPoint'] = $EndPoint;
                $total['Margin'] = $margin;
                $total['NetProfit'] = $netprofit;
                $total['SuperDistributedProfit'] = $SuperDistributedProfit;
                // echo "<pre>";
                // print_r($commission);
                // die;

                // echo "<pre>";
                // print_r($playPoints->toArray());die();
                // return view('turnOver', ['data' => $refer, 'total' => $total]);


            }
            return view('turnOver', ['data' => $commission, 'total' => $total]);
        } elseif ($user->role == 'premium') {
            $refer = User::where('role', 'executive')->where('referralId', new \MongoDB\BSON\ObjectID($id))->get();
            // echo "<pre>";
            // print_r($refer->toArray());
            // die;
            if (count($refer) == 0) {
                $total = [];
                $total['totalStartPoint'] = $totalStartPoint;
                $total['totalPlayPoints'] = $totalPlayPoints;
                $total['TotalWinPoints'] = $TotalWinPoints;
                $total['EndPoint'] = $EndPoint;
                $total['Margin'] = $margin;
                $total['NetProfit'] = $netprofit;
                $total['SuperDistributedProfit'] = $SuperDistributedProfit;
                return view('turnOver', ['data' => $admin, 'total' => $total]);
            } else {
                $commission = [];
                foreach ($refer as $exe_user) {
                    $classic = User::where('referralId', new \MongoDB\BSON\ObjectID($exe_user['_id']))->get();
                    if (count($classic) == 0) {
                        $total = [];
                        $total['totalStartPoint'] = $totalStartPoint;
                        $total['totalPlayPoints'] = $totalPlayPoints;
                        $total['TotalWinPoints'] = $TotalWinPoints;
                        $total['EndPoint'] = $EndPoint;
                        $total['Margin'] = $margin;
                        $total['NetProfit'] = $netprofit;
                        $total['SuperDistributedProfit'] = $SuperDistributedProfit;
                        return view('turnOver', ['data' => $refer, 'total' => $total]);
                    } else {
                        $cal = [];
                        foreach ($classic as $cal_user) {
                            $cal[] = new \MongoDB\BSON\ObjectID($cal_user['_id']);
                        }
                        $retailers = [];
                        $retailer = User::whereIn('referralId', $cal)->get();
                        foreach ($retailer as $re_user) {
                            $retailers[] = new \MongoDB\BSON\ObjectID($re_user['_id']);
                        }
                        // echo "<pre>";
                        // print_r($retailers);
                        // die;
                        // $groups = [];
                        if ($type == 1 || $type == 2 || $type == 3 || $type == 4 || $type == 5 || $type == 8) {
                            $td = date('j', strtotime($_GET['to']));
                            $groups[$exe_user['_id']] = Bets::select('bet', 'won',  'playerCommission', 'classicCommission', 'ExecutiveCommission', 'premiumCommission', 'agentCommission')
                                ->whereIn('playerId', $retailers)
                                ->whereBetween(
                                    'createdAt',
                                    array(
                                        Carbon::create($fY, $fm, $fd, 00, 00, 00),
                                        Carbon::create($tY, $tm, $td, 23, 59, 59),
                                    )
                                )->get()->toArray();
                        } elseif ($type == 7 || $type == 6) {
                            $to = date('n-j-Y', strtotime($_GET['to']));
                            $groups[$exe_user['_id']] = Bets::select('bet', 'won',  'playerCommission', 'classicCommission', 'ExecutiveCommission', 'premiumCommission', 'agentCommission')
                                ->whereIn('playerId', $retailers)->where('DrDate', $to)->get()->toArray();
                        }
                        // echo "<pre>";
                        // print_r($groups);
                        // die;

                        foreach ($groups as $key => $get) {
                            $PlayPoints = 0;
                            $WinPoints = 0;
                            $EndPoint = 0;
                            $commission[$key]['role'] = $exe_user['role'];
                            foreach ($get as $player) {
                                $PlayPoints += $player['bet'];
                                $WinPoints += $player['won'];
                            }
                            foreach ($refer as $sup) {
                                if ($key == $sup['_id']) {
                                    $commission[$key]['_id'] = $sup['_id'];
                                    $commission[$key]['userName'] = $sup['userName'];
                                    $commission[$key]['name'] = $sup['name'];
                                    $commission[$key]['commission'] = $sup['commissionPercentage'];
                                    $commission[$key]['superCommission'] = $user->commissionPercentage - $sup['commissionPercentage'];
                                }
                            }
                            $commission[$key]['playPoint'] = $PlayPoints;
                            $commission[$key]['wonPoint'] = $WinPoints;
                            $commission[$key]['endPoint'] = $PlayPoints - $WinPoints;
                            $commission[$key]['margin'] =  ($commission[$key]['playPoint'] * $commission[$key]['commission']) / 100;
                            $commission[$key]['netprofit'] =  $commission[$key]['endPoint'] - $commission[$key]['margin'];
                            $commission[$key]['SuperDistributedProfit'] =  ($commission[$key]['superCommission'] * $commission[$key]['playPoint']) / 100;
                        }
                    }
                }

                foreach ($commission as $play) {
                    $totalPlayPoints += $play['playPoint'];
                    $TotalWinPoints += $play['wonPoint'];
                    $EndPoint = $totalPlayPoints - $TotalWinPoints;
                    $margin += $play['margin'];
                    $netprofit += $play['netprofit'];
                    $SuperDistributedProfit += $play['SuperDistributedProfit'];
                }
                $total = [];
                $total['totalPlayPoints'] = $totalPlayPoints;
                $total['TotalWinPoints'] = $TotalWinPoints;
                $total['EndPoint'] = $EndPoint;
                $total['Margin'] = $margin;
                $total['NetProfit'] = $netprofit;
                $total['SuperDistributedProfit'] = $SuperDistributedProfit;
                // echo "<pre>";
                // print_r($commission);
                // die;
                return view('turnOver', ['data' => $commission, 'total' => $total]);
            }
        } elseif ($user->role == 'executive') {
            $refer = User::where('role', 'classic')->where('referralId', new \MongoDB\BSON\ObjectID($id))->get();
            // echo "<pre>";
            // print_r($refer->toArray());
            // die;
            if (count($refer) == 0) {
                $total = [];
                $total['totalStartPoint'] = $totalStartPoint;
                $total['totalPlayPoints'] = $totalPlayPoints;
                $total['TotalWinPoints'] = $TotalWinPoints;
                $total['EndPoint'] = $EndPoint;
                $total['Margin'] = $margin;
                $total['NetProfit'] = $netprofit;
                $total['SuperDistributedProfit'] = $SuperDistributedProfit;
                return view('turnOver', ['data' => $admin, 'total' => $total]);
            } else {
                $commission = [];
                foreach ($refer as $cal_user) {
                    $player = User::where('referralId', new \MongoDB\BSON\ObjectID($cal_user['_id']))->get();
                    $retailers = [];
                    foreach ($player as $player_user) {
                        $retailers[] = new \MongoDB\BSON\ObjectID($player_user['_id']);
                    }
                    // echo "<pre>";
                    // print_r($retailers);
                    // die;
                    // $groups = [];
                    if ($type == 1 || $type == 2 || $type == 3 || $type == 4 || $type == 5 || $type == 8) {
                        $td = date('j', strtotime($_GET['to']));
                        $groups[$cal_user['_id']] = Bets::select('bet', 'won',  'playerCommission', 'classicCommission', 'ExecutiveCommission', 'premiumCommission', 'agentCommission')
                            ->whereIn('playerId', $retailers)
                            ->whereBetween(
                                'createdAt',
                                array(
                                    Carbon::create($fY, $fm, $fd, 00, 00, 00),
                                    Carbon::create($tY, $tm, $td, 23, 59, 59),
                                )
                            )->get()->toArray();
                    } elseif ($type == 7 || $type == 6) {
                        $to = date('n-j-Y', strtotime($_GET['to']));
                        $groups[$cal_user['_id']] = Bets::select('bet', 'won',  'playerCommission', 'classicCommission', 'ExecutiveCommission', 'premiumCommission', 'agentCommission')
                            ->whereIn('playerId', $retailers)->where('DrDate', $to)->get()->toArray();
                    }
                    // echo "<pre>";
                    // print_r($groups);
                    // die;

                    foreach ($groups as $key => $get) {
                        $PlayPoints = 0;
                        $WinPoints = 0;
                        $EndPoint = 0;
                        $commission[$key]['role'] = $cal_user['role'];
                        foreach ($get as $player) {
                            $PlayPoints += $player['bet'];
                            $WinPoints += $player['won'];
                        }
                        foreach ($refer as $sup) {
                            if ($key == $sup['_id']) {
                                $commission[$key]['_id'] = $sup['_id'];
                                $commission[$key]['userName'] = $sup['userName'];
                                $commission[$key]['name'] = $sup['name'];
                                $commission[$key]['commission'] = $sup['commissionPercentage'];
                                $commission[$key]['superCommission'] = $user->commissionPercentage - $sup['commissionPercentage'];
                            }
                        }
                        $commission[$key]['playPoint'] = $PlayPoints;
                        $commission[$key]['wonPoint'] = $WinPoints;
                        $commission[$key]['endPoint'] = $PlayPoints - $WinPoints;
                        $commission[$key]['margin'] =  ($commission[$key]['playPoint'] * $commission[$key]['commission']) / 100;
                        $commission[$key]['netprofit'] =  $commission[$key]['endPoint'] + $commission[$key]['margin'];
                        $commission[$key]['SuperDistributedProfit'] =  ($commission[$key]['superCommission'] * $commission[$key]['playPoint']) / 100;
                    }
                }
                foreach ($commission as $play) {
                    $totalPlayPoints += $play['playPoint'];
                    $TotalWinPoints += $play['wonPoint'];
                    $EndPoint = $totalPlayPoints - $TotalWinPoints;
                    $margin += $play['margin'];
                    $netprofit += $play['netprofit'];
                    $SuperDistributedProfit += $play['SuperDistributedProfit'];
                }
                $total = [];
                $total['totalPlayPoints'] = $totalPlayPoints;
                $total['TotalWinPoints'] = $TotalWinPoints;
                $total['EndPoint'] = $EndPoint;
                $total['Margin'] = $margin;
                $total['NetProfit'] = $netprofit;
                $total['SuperDistributedProfit'] = $SuperDistributedProfit;
                // echo "<pre>";
                // print_r($commission);
                // die;
                return view('turnOver', ['data' => $commission, 'total' => $total]);
            }
        } elseif ($user->role == 'classic') {
            $refer = User::where('role', 'player')->where('referralId', new \MongoDB\BSON\ObjectID($id))->get();
            // echo "<pre>";
            // print_r($refer->toArray());
            // die;
            if (count($refer) == 0) {
                $total = [];
                $total['totalStartPoint'] = $totalStartPoint;
                $total['totalPlayPoints'] = $totalPlayPoints;
                $total['TotalWinPoints'] = $TotalWinPoints;
                $total['EndPoint'] = $EndPoint;
                $total['Margin'] = $margin;
                $total['NetProfit'] = $netprofit;
                $total['SuperDistributedProfit'] = $SuperDistributedProfit;
                return view('turnOver', ['data' => $admin, 'total' => $total]);
            } else {
                $commission = [];
                foreach ($refer as $player_user) {
                    if ($type == 1 || $type == 2 || $type == 3 || $type == 4 || $type == 5 || $type == 8) {
                        $td = date('j', strtotime($_GET['to']));
                        $groups[$player_user['_id']] = Bets::select('bet', 'won',  'playerCommission', 'classicCommission', 'ExecutiveCommission', 'premiumCommission', 'agentCommission')
                            ->where('playerId', new \MongoDB\BSON\ObjectID($player_user['_id']))
                            ->whereBetween(
                                'createdAt',
                                array(
                                    Carbon::create($fY, $fm, $fd, 00, 00, 00),
                                    Carbon::create($tY, $tm, $td, 23, 59, 59),
                                )
                            )->get()->toArray();
                    } elseif ($type == 7 || $type == 6) {
                        $to = date('n-j-Y', strtotime($_GET['to']));
                        $groups[$player_user['_id']] = Bets::select('bet', 'won',  'playerCommission', 'classicCommission', 'ExecutiveCommission', 'premiumCommission', 'agentCommission')
                            ->where('playerId', new \MongoDB\BSON\ObjectID($player_user['_id']))->where('DrDate', $to)->get()->toArray();
                    }
                    // echo "<pre>";
                    // print_r($groups);
                    // die;

                    foreach ($groups as $key => $get) {
                        $PlayPoints = 0;
                        $WinPoints = 0;
                        $EndPoint = 0;
                        $commission[$key]['role'] = $player_user['role'];
                        foreach ($get as $player) {
                            $PlayPoints += $player['bet'];
                            $WinPoints += $player['won'];
                        }
                        foreach ($refer as $sup) {
                            if ($key == $sup['_id']) {
                                $commission[$key]['_id'] = $sup['_id'];
                                $commission[$key]['userName'] = $sup['userName'];
                                $commission[$key]['name'] = $sup['name'];
                                $commission[$key]['commission'] = $sup['commissionPercentage'];
                                $commission[$key]['superCommission'] = $user->commissionPercentage - $sup['commissionPercentage'];
                            }
                        }
                        $commission[$key]['playPoint'] = $PlayPoints;
                        $commission[$key]['wonPoint'] = $WinPoints;
                        $commission[$key]['endPoint'] = $PlayPoints - $WinPoints;
                        $commission[$key]['margin'] =  ($commission[$key]['playPoint'] * $commission[$key]['commission']) / 100;
                        $commission[$key]['netprofit'] =  $commission[$key]['endPoint'] + $commission[$key]['margin'];
                        $commission[$key]['SuperDistributedProfit'] =  ($commission[$key]['superCommission'] * $commission[$key]['playPoint']) / 100;
                    }
                }

                foreach ($commission as $play) {
                    $totalPlayPoints += $play['playPoint'];
                    $TotalWinPoints += $play['wonPoint'];
                    $EndPoint = $totalPlayPoints - $TotalWinPoints;
                    $margin += $play['margin'];
                    $netprofit += $play['netprofit'];
                    $SuperDistributedProfit += $play['SuperDistributedProfit'];
                }
                $total = [];
                $total['totalPlayPoints'] = $totalPlayPoints;
                $total['TotalWinPoints'] = $TotalWinPoints;
                $total['EndPoint'] = $EndPoint;
                $total['Margin'] = $margin;
                $total['NetProfit'] = $netprofit;
                $total['SuperDistributedProfit'] = $SuperDistributedProfit;
                // echo "<pre>";
                // print_r($commission);
                // die;
                return view('turnOver', ['data' => $commission, 'total' => $total]);
            }
        }
    }
}
