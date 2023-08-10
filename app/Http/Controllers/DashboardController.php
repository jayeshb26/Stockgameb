<?php

namespace App\Http\Controllers;

use App\User;
use Session;
use App\Bets;
use App\generatepoints;
use App\Payments;
use App\pointrequests;

use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('CheckAuth');
    }

    public function index()
    {
        $dash = [];
        $chart_f = "";
        $chart_a = "";
        $chart_w = "";
        $chart_p = "";
        if (Session::get('role') == 'Admin') {
            if (Session::get('is_f') == "false") {
                $dash['users'] = User::where('is_franchise', false)->where('userName', '!=', "superadminA")->where('role', '!=', "subadmin")->count();
                $chart_a = array(
                    User::where('is_franchise', false)->where('role', 'agent')->count(),
                    User::where('is_franchise', false)->where('role', 'premium')->count(),
                    User::where('is_franchise', false)->where('role', 'executive')->count(),
                    User::where('is_franchise', false)->where('role', 'classic')->count(),
                    User::where('is_franchise', false)->where('role', 'player')->count(),
                );
                $chart_a = implode(', ', array_values($chart_a));
            } else {
                $dash['superDistributer'] = User::where('is_franchise', true)->where('userName', '!=', "superadminF")->where('role', '!=', "subadmin")->count();
                $chart_f = array(
                    // User::where('is_franchise', true)->where('role', 'premium')->count(),
                    User::where('is_franchise', true)->where('role', 'executive')->count(),
                    User::where('is_franchise', true)->where('role', 'classic')->count(),
                    User::where('is_franchise', true)->where('role', 'player')->count(),
                );
                $chart_f = implode(', ', array_values($chart_f));
            }
            $chart_w = array(
                Bets::where('game', 'rouletteTimer40')->sum('won'),
                Bets::where('game', 'rouletteTimer60')->sum('won'),
                Bets::where('game', 'roulette')->sum('won'),
            );
            $chart_p = array(
                Bets::where('game', 'rouletteTimer40')->sum('bet'),
                Bets::where('game', 'rouletteTimer60')->sum('bet'),
                Bets::where('game', 'roulette')->sum('bet'),
            );
            $chart_w = implode(', ', array_values($chart_w));
            $chart_p = implode(', ', array_values($chart_p));
        } else {
            $dash['users'] = User::where('is_franchise', false)->where('_id', '!=', new \MongoDB\BSON\ObjectID(Session::get('id')))->where('role', '!=', "subadmin")->where('userName', '!=', "superadminA")->count();
            $dash['superDistributer'] = User::where('is_franchise', true)->where('userName', '!=', "superadminF")->where('role', '!=', "subadmin")->where('referralId', new \MongoDB\BSON\ObjectID(Session::get('id')))->count();
        }
        $dash['distributers'] = User::where('userName', '!=', "superadminF")->where('role', '!=', "subadmin")->where('role', "executive")
            // ->where('referralId', new \MongoDB\BSON\ObjectID(Session::get('id')))
            ->count();
        $dash['players'] = User::where('userName', '!=', "superadminF")
            ->where('role', '!=', "subadmin")
            ->where('role', "player")
            // ->where('referralId', new \MongoDB\BSON\ObjectID(Session::get('id')))
            ->count();
        return view('dashboard', ['data' => $dash, 'chart_f' => $chart_f, 'chart_a' => $chart_a, 'chart_w' => $chart_w, 'chart_p' => $chart_p]);
    }

    public function point_file(Request $request)
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
                $payment = Payments::whereBetween(
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
            } elseif (Session::get('role') == "agent" || Session::get('role') == "premium" || Session::get('role') == "executive" || Session::get('role') == "classic") {
                $payment = Payments::where('toId', Session::get('id'))->orderBy('createdAt', 'DESC')
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
            $payment = Payments::orderBy('createdAt', 'DESC')
                ->where('toId', $_GET['username'])
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
                $payment = Payments::whereBetween(
                    'createdAt',
                    array(
                        Carbon::create($fY, $fm, $fd, 00, 00, 00),
                        Carbon::create($tY, $tm, $td, 23, 59, 59),
                    )
                )->where('is_f', (Session::get('is_f') == "true") ? true : false)
                    ->where('toId', $_GET['username'])
                    ->orderBy('createdAt', 'DESC')->get();
                foreach ($payment as $key => $pay) {
                    $refer = User::where('_id', new \MongoDB\BSON\ObjectID($pay['fromId']))->first();
                    // echo "<pre>";
                    // $payment[$key]['createdAt'] = Carbon::parse( $pay['createdAt'] )->toDayDateTimeString();
                    $payment[$key]['userName'] = $refer['userName'];
                }
                // print_r($payment->toArray());
                // die;
            } elseif (Session::get('role') == "agent" || Session::get('role') == "premium" || Session::get('role') == "executive" || Session::get('role') == "classic") {
                $payment = Payments::where('toId', Session::get('id'))
                    ->orderBy('createdAt', 'DESC')
                    ->whereBetween(
                        'createdAt',
                        array(
                            Carbon::create($fY, $fm, $fd, 00, 00, 00),
                            Carbon::create($tY, $tm, $td, 23, 59, 59),
                        )
                    )->where('is_f', (Session::get('is_f') == "true") ? true : false)
                    ->where('toId', $_GET['username'])
                    ->get();
                foreach ($payment as $key => $pay) {
                    $refer = User::where('_id', new \MongoDB\BSON\ObjectID($pay['fromId']))->first();
                    // echo "<pre>";print_r($users);die();
                    // $payment[$key]['createdAt'] = Carbon::parse( $pay['createdAt'] )->toDayDateTimeString();
                    $payment[$key]['userName'] = $refer['userName'];
                }
            }
        }
        return view('pointfile', ['data' => $users, 'payment' => $payment, 'user' => $user]);
    }

    public function points_in(Request $request)
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
                $payment = Payments::whereBetween(
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
                // print_r($payment->toArray());
                // die;
            } elseif (Session::get('role') == "agent" || Session::get('role') == "premium" || Session::get('role') == "executive" || Session::get('role') == "classic") {
                $payment = Payments::where('toId', Session::get('id'))->orderBy('createdAt', 'DESC')
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
            $payment = Payments::orderBy('createdAt', 'DESC')
                ->where('toId', $_GET['username'])
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
                $payment = Payments::whereBetween(
                    'createdAt',
                    array(
                        Carbon::create($fY, $fm, $fd, 00, 00, 00),
                        Carbon::create($tY, $tm, $td, 23, 59, 59),
                    )
                )->where('is_f', (Session::get('is_f') == "true") ? true : false)
                    ->where('toId', $_GET['username'])
                    ->orderBy('createdAt', 'DESC')->get();
                foreach ($payment as $key => $pay) {
                    $refer = User::where('_id', new \MongoDB\BSON\ObjectID($pay['fromId']))->first();
                    // echo "<pre>";
                    // $payment[$key]['createdAt'] = Carbon::parse( $pay['createdAt'] )->toDayDateTimeString();
                    $payment[$key]['userName'] = $refer['userName'];
                }
                // print_r($payment->toArray());
                // die;
            } elseif (Session::get('role') == "agent" || Session::get('role') == "premium" || Session::get('role') == "executive" || Session::get('role') == "classic") {
                $payment = Payments::where('toId', Session::get('id'))
                    ->orderBy('createdAt', 'DESC')
                    ->whereBetween(
                        'createdAt',
                        array(
                            Carbon::create($fY, $fm, $fd, 00, 00, 00),
                            Carbon::create($tY, $tm, $td, 23, 59, 59),
                        )
                    )->where('is_f', (Session::get('is_f') == "true") ? true : false)
                    ->where('toId', $_GET['username'])
                    ->get();
                foreach ($payment as $key => $pay) {
                    $refer = User::where('_id', new \MongoDB\BSON\ObjectID($pay['fromId']))->first();
                    // echo "<pre>";print_r($users);die();
                    // $payment[$key]['createdAt'] = Carbon::parse( $pay['createdAt'] )->toDayDateTimeString();
                    $payment[$key]['userName'] = $refer['userName'];
                }
            }
        }
        return view('inreport', ['data' => $users, 'payment' => $payment, 'user' => $user]);
    }

    public function points_out(Request $request)
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
                $payment = Payments::whereBetween(
                    'createdAt',
                    array(
                        Carbon::create($fY, $fm, $fd, 00, 00, 00),
                        Carbon::create($tY, $tm, $td, 23, 59, 59),
                    )
                )->where('is_f', (Session::get('is_f') == "true") ? true : false)->orderBy('createdAt', 'DESC')->get();
                // echo "<pre>";
                // print_r($payment->toArray());
                // die;
                foreach ($payment as $key => $pay) {
                    $refer = User::where('_id', new \MongoDB\BSON\ObjectID($pay['fromId']))->first();
                    // echo "<pre>";
                    // $payment[$key]['createdAt'] = Carbon::parse( $pay['createdAt'] )->toDayDateTimeString();
                    $payment[$key]['userName'] = $refer['userName'];
                }
            } elseif (Session::get('role') == "agent" || Session::get('role') == "premium" || Session::get('role') == "executive" || Session::get('role') == "classic") {
                $payment = Payments::where('toId', Session::get('id'))->orderBy('createdAt', 'DESC')
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
            $payment = Payments::orderBy('createdAt', 'DESC')
                ->where('toId', $_GET['username'])
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
                $payment = Payments::whereBetween(
                    'createdAt',
                    array(
                        Carbon::create($fY, $fm, $fd, 00, 00, 00),
                        Carbon::create($tY, $tm, $td, 23, 59, 59),
                    )
                )->where('is_f', (Session::get('is_f') == "true") ? true : false)
                    ->where('toId', $_GET['username'])
                    ->orderBy('createdAt', 'DESC')->get();
                foreach ($payment as $key => $pay) {
                    $refer = User::where('_id', new \MongoDB\BSON\ObjectID($pay['fromId']))->first();
                    // echo "<pre>";
                    // $payment[$key]['createdAt'] = Carbon::parse( $pay['createdAt'] )->toDayDateTimeString();
                    $payment[$key]['userName'] = $refer['userName'];
                }
                // print_r($payment->toArray());
                // die;
            } elseif (Session::get('role') == "agent" || Session::get('role') == "premium" || Session::get('role') == "executive" || Session::get('role') == "classic") {
                $payment = Payments::where('toId', Session::get('id'))
                    ->orderBy('createdAt', 'DESC')
                    ->whereBetween(
                        'createdAt',
                        array(
                            Carbon::create($fY, $fm, $fd, 00, 00, 00),
                            Carbon::create($tY, $tm, $td, 23, 59, 59),
                        )
                    )->where('is_f', (Session::get('is_f') == "true") ? true : false)
                    ->where('toId', $_GET['username'])
                    ->get();
                foreach ($payment as $key => $pay) {
                    $refer = User::where('_id', new \MongoDB\BSON\ObjectID($pay['fromId']))->first();
                    // echo "<pre>";print_r($users);die();
                    // $payment[$key]['createdAt'] = Carbon::parse( $pay['createdAt'] )->toDayDateTimeString();
                    $payment[$key]['userName'] = $refer['userName'];
                }
            }
        }
        return view('OutReport', ['data' => $users, 'payment' => $payment, 'user' => $user]);
    }

    public function verify_pointFile(Request $request)
    {
        $user = User::orderBy('userName', 'ASC')
            ->where('is_franchise', (Session::get('is_f') == "true") ? true : false)
            ->where('userName', "!=", "superadminA")
            ->where('userName', '!=', "superadminF")->where('role', '!=', "subadmin")->get()->pluck('_id');
        foreach ($user as $key => $u) {
            $user[$key] = new \MongoDB\BSON\ObjectID($u);
        }
        if (Session::get('role') == "Admin" || Session::get('role') == "subadmin") {
            $data = pointrequests::orderBy('createdAt', 'DESC')->whereIn('playerId', $user)->where('status', 'Success')->get();
        } elseif (Session::get('role') == "agent" || Session::get('role') == "premium" || Session::get('role') == "executive" || Session::get('role') == "classic") {
            $data = pointrequests::orderBy('createdAt', 'DESC')->where('playerId', new \MongoDB\BSON\ObjectID(Session::get('id')))->where('status', 'Success')->get();
        }

        return view('verifyPoint', [
            'data' => $data,
        ]);
    }
}
