<?php

namespace App\Http\Controllers;

use App\Bets;
use App\Complaints;
use App\Payments;
use App\User;
use App\pointrequests;
use App\WinnerIdHistoris;
use App\WinnerIds;
use App\generatepoints;
use App\Winnings;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class AdminController extends Controller
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
        // $this->middleware('admin');
        if (Session::get('is_f') == "false") {
            if (Session::get('role') == "Admin" || Session::get('role') == "subadmin") {
                $user = User::where('userName', '!=', "superadminA")->where('userName', '!=', "superadminF")->where('role', '!=', "Admin")->where('role', '!=', "subadmin")->orderBy('createdAt', 'DESC')->where('is_franchise', false)->get();
            } elseif (Session::get('role') == "agent") {
                $user = User::where('referralId', new \MongoDB\BSON\ObjectID(Session::get('id')))->where('role', 'premium')->where('is_franchise', false)->orderBy('createdAt', 'DESC')->get();
            } elseif (Session::get('role') == "premium") {
                $user = User::where('referralId', new \MongoDB\BSON\ObjectID(Session::get('id')))->where('role', 'executive')->where('is_franchise', false)->orderBy('createdAt', 'DESC')->get();
            } elseif (Session::get('role') == "executive") {
                $user = User::where('referralId', new \MongoDB\BSON\ObjectID(Session::get('id')))->where('role', 'classic')->where('is_franchise', false)->orderBy('createdAt', 'DESC')->get();
            } elseif (Session::get('role') == "classic") {
                $user = User::where('referralId', new \MongoDB\BSON\ObjectID(Session::get('id')))->where('role', 'player')->where('is_franchise', false)->orderBy('createdAt', 'DESC')->get();
            }

            $user_role = User::where('is_franchise', false)->where('role', '!=', "Admin")->pluck('role')->toArray();
            $user_role = array_unique($user_role);
            return view('admin.view', ['data' => $user, 'role' => $user_role]);
        }
    }

    public function user_Franchise()
    {
        // $this->middleware('admin');
        if (Session::get('is_f') == "true") {
            if (Session::get('role') == "Admin" || Session::get('role') == "subadmin") {
                $user = User::where('userName', '!=', "superadminA")->where('userName', '!=', "superadminF")->where('role', '!=', "Admin")->where('role', '!=', "subadmin")->orderBy('createdAt', 'DESC')->where('role', '!=', "Admin")->where('is_franchise', true)->get();
                $user_role = User::where('is_franchise', true)->pluck('role')->toArray();
            } elseif (Session::get('role') == "premium") {
                $user = User::where('referralId', new \MongoDB\BSON\ObjectID(Session::get('id')))->where('role', 'executive')->where('is_franchise', true)->orderBy('createdAt', 'DESC')->get();
                $user_role = User::where('is_franchise', true)->where('role', '!=', "Admin")->pluck('role')->toArray();
            } elseif (Session::get('role') == "executive") {
                $user = User::where('referralId', new \MongoDB\BSON\ObjectID(Session::get('id')))->where('role', 'classic')->where('is_franchise', true)->orderBy('createdAt', 'DESC')->get();
                $user_role = User::where('is_franchise', true)->where('role', '!=', "Admin")->pluck('role')->toArray();
            } elseif (Session::get('role') == "classic") {
                $user = User::where('referralId', new \MongoDB\BSON\ObjectID(Session::get('id')))->where('role', 'player')->where('is_franchise', true)->orderBy('createdAt', 'DESC')->get();
                $user_role = User::where('is_franchise', true)->where('role', '!=', "Admin")->pluck('role')->toArray();
            }
            $user_role = User::where('is_franchise', true)->pluck('role')->toArray();
            $user_role = array_unique($user_role);
            
            // distributer list
            if(request()->url() == url('users/distlist')){
                $user = User::where('role', "executive")
                    // where('userName', '!=', "superadminF")->where('role', '!=', "subadmin")->
                    ->get();
            }
            // distributer list

            // player list
            if(request()->url() == url('users/plyrlist')){
                $user = User::where('userName', '!=', "superadminF")->where('role', '!=', "subadmin")
                    ->where('role', "player")->get();
                    // ->where('referralId', new \MongoDB\BSON\ObjectID(Session::get('id')))
                    
            }
            // player list

            // agent list
            if(request()->url() == url('users/agentlist')){
                $user = User::where('role', "classic")->get();
            }
            return view('admin.view', ['data' => $user, 'role' => $user_role]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.create');
    }

    public function franchise()
    {
        return view('admin.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!isset($request->superDistributerId)){
            $ref_user = User::where('userName', "superadminF")->first();
            $request->superDistributerId = $ref_user->_id;
        }

        $ref_id = $request->superDistributerId;
        $ref_user = User::where('_id', new \MongoDB\BSON\ObjectID($ref_id))->first();
        
        if ($request->commissionPercentage >= 0 && $request->commissionPercentage == "") {
            $request->commissionPercentage = 0;
        }

        if ($request->password == $request->transactionPin) {
            session()->flash('msg', 'Password and TransactionPin is not same');
            return redirect()->back();
        }

        $request->validate([
            'userName' => 'required|unique:users,userName',
            'role' => 'required',
            'password' => 'required|min:6',
            'profile_pic' => 'required|image|mimes:jpeg,png,jpg',
            'adharcard' => 'required|image|mimes:jpeg,png,jpg',
            'pancard' => 'required|image|mimes:jpeg,png,jpg',
            // |max:2048
        ]);

        $path = storage_path();
        !is_dir($path) && mkdir($path, 0777, true);

        $profile_pic = null;
        if($profileFile = $request->file('profile_pic')) {
            $fileData = $this->uploads($profileFile,$path);
            $profile_pic = $fileData['fileName'];
        }

        $adharcard = null;
        if($adharcardFile = $request->file('adharcard')) {
            $fileData = $this->uploads($adharcardFile,$path);
            $adharcard = $fileData['fileName'];
        }

        $pancard = null;
        if($pancardFile = $request->file('pancard')) {
            $fileData = $this->uploads($pancardFile,$path);
            $pancard = $fileData['fileName'];
        }


        $referral = "";
        $role = "";


        if ($request->role == 1) {
            $referral = new \MongoDB\BSON\ObjectID(Session::get('id'));
            $role = "agent";
            $request->validate([
                'commissionPercentage' => 'required|numeric|between:0,100',
                'transactionPin' => 'required',
            ]);
        } elseif ($request->role == 3) {
            if (isset($request->superDistributerId)) {
                $referral = new \MongoDB\BSON\ObjectID($request->superDistributerId);
                $request->validate([
                    'commissionPercentage' => 'required|numeric|between:0,' . $ref_user->commissionPercentage,
                    'transactionPin' => 'required',
                ]);
            } else {
                $referral = new \MongoDB\BSON\ObjectID(Session::get('id'));
                $request->validate([
                    'commissionPercentage' => 'required|numeric|between:0,100',
                    'transactionPin' => 'required',
                ]);
            }
            $role = "premium";
        } elseif ($request->role == 5) {
            $referral = new \MongoDB\BSON\ObjectID($request->superDistributerId);
            $role = "executive";
            $request->validate([
                'commissionPercentage' => 'required|numeric|between:0,' . $ref_user->commissionPercentage,
                'transactionPin' => 'required',
            ]);
        } elseif ($request->role == 6) {
            $referral = new \MongoDB\BSON\ObjectID($request->superDistributerId);
            $role = "classic";
            $request->validate([
                'commissionPercentage' => 'required|numeric|between:0,' . $ref_user->commissionPercentage,
                'transactionPin' => 'required',
            ]);
        } elseif ($request->role == 7) {
            $referral = new \MongoDB\BSON\ObjectID($request->superDistributerId);
            $role = "player";
        }

        // $userName = 0;
        // echo $referral;
        // die;
        // $lastUser = User::where('role', $role)
        //     ->orderBy('userName', 'DESC')
        //     ->first();
        // if (!empty($lastUser)) {
        //     $userName = $lastUser['userName'] + 1;
        // }

        // if ($userName == 0) {
        //     if ($role == "Admin") {
        //         $userName = 10001;
        //     } else if ($role == "superDistributer") {
        //         $userName = 30001;
        //     } else if ($role == "distributer") {
        //         $userName = 50001;
        //     } else if ($role == "retailer") {
        //         $userName = 70001;
        //     }
        // }
        // $v = 0;
        // $a = 0;
        // $permissions = [];
        // foreach ($request->permission as $key => $va) {
        //     $v = $va;
        //     $a = $key;
        //     $permissions[$va] = true;
        // }
        // $userName = $request->username;
        $password = $request->password;
        $commissionPercentage = floatval(trim($request->commissionPercentage, '"'));
        $transactionPin = intval(trim($request->transactionPin, '"'));
        // echo "<pre>";
        // print_r($userName);die();
        $user = new User();
        $user->name = $request->name;
        $user->userName =  $request->userName;
        $user->password = $password;
        $user->role = $role;
        $user->isActive = true;
        $user->creditPoint = 0;
        $user->transactionPin = $transactionPin;
        $user->commissionPercentage = $commissionPercentage;
        $user->commissionPoint = 0;
        $user->is_franchise = ($request->is_franchise == "true") ? true : false;
        $user->isLogin = false;
        $user->referralId = $referral;
        $user->profile_pic = $profile_pic;
        $user->adharcard = $adharcard;
        $user->pancard = $pancard;
        $user->save();

        if (Session::get('is_f') == "true") {
            return redirect('/users/Franchise');
        } else {
            return redirect('/users');
        }
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
        // echo "<pre>";print_r($user->toArray());die();
        $data = User::all();
        return view('admin.edit', ['edata' => $user, 'udata' => $data]);
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
        $request->validate([
            'profile_pic' => 'image|mimes:jpeg,png,jpg',
            'adharcard' => 'image|mimes:jpeg,png,jpg',
            'pancard' => 'image|mimes:jpeg,png,jpg',
        ]);
        $path = storage_path();
        !is_dir($path) && mkdir($path, 0777, true);

        $profile_pic = null;
        if($profileFile = $request->file('profile_pic')) {
            $fileData = $this->uploads($profileFile,$path);
            $profile_pic = $fileData['fileName'];
        }

        $adharcard = null;
        if($adharcardFile = $request->file('adharcard')) {
            $fileData = $this->uploads($adharcardFile,$path);
            $adharcard = $fileData['fileName'];
        }

        $pancard = null;
        if($pancardFile = $request->file('pancard')) {
            $fileData = $this->uploads($pancardFile,$path);
            $pancard = $fileData['fileName'];
        }

        $referral = "";
        $role = "";
        if (Session::get('role') == "Admin") {
            if ($request->role == 1) {
                $referral = Session::get('id');
                $role = "agent";
            } elseif ($request->role == 3) {
                if (isset($request->referralId)) {
                    $referral = $request->referralId;
                } else {
                    $referral = Session::get('id');
                }
                $role = "premium";
            } elseif ($request->role == 5) {
                if (isset($request->referralId)) {
                    $referral = $request->referralId;
                } else {
                    $referral = $request->superDistributerId;
                }
                $role = "executive";
            } elseif ($request->role == 6) {
                if (isset($request->referralId)) {
                    $referral = $request->referralId;
                } else {
                    $referral = $request->superDistributerId;
                }
                $role = "classic";
            } elseif ($request->role == 7) {
                if (isset($request->referralId)) {
                    $referral = $request->referralId;
                } else {
                    $referral = $request->superDistributerId;
                }
                $role = "player";
            }
        } else {
            if ($request->role == "Admin") {
                $referral = Session::get('id');
                $role = "Admin";
            } elseif ($request->role == "agent") {
                $referral = Session::get('id');
                $role = "agent";
            } elseif ($request->role == "premium") {
                if (isset($request->referralId)) {
                    $referral = $request->referralId;
                } else {
                    $referral = Session::get('id');
                }
                $role = "premium";
            } elseif ($request->role == "executive") {
                if (isset($request->referralId)) {
                    $referral = $request->referralId;
                } else {
                    $referral = $request->superDistributerId;
                }
                $role = "executive";
            } elseif ($request->role == "classic") {
                if (isset($request->referralId)) {
                    $referral = $request->referralId;
                } else {
                    $referral = $request->superDistributerId;
                }
                $role = "classic";
            } elseif ($request->role == "player") {
                if (isset($request->referralId)) {
                    $referral = $request->referralId;
                } else {
                    $referral = $request->superDistributerId;
                }
                $role = "player";
            }
        }

        $refer = User::where('_id', new \MongoDB\BSON\ObjectID($referral))->first();
        if ($refer['role'] == Session::get('role') || Session::get('role') == "Admin") {
            $referral = new \MongoDB\BSON\ObjectID($referral);
            $permissions = [];
            foreach ($request->permission as $key => $va) {
                $v = $va;
                $a = $key;
                $permissions[$va] = true;
            }

            $password = $request->password;
            $commissionPercentage = floatval(trim($request->commissionPercentage, '"'));
            $transactionPin = intval(trim($request->transactionPin, '"'));

            $user = User::find($id);
            // $user = new User();
            $user->name = $request->name;
            $user->password = $password;
            $user->firmName = $request->firmName;
            $user->role = $role;
            $user->isActive = true;
            $user->permissions = $permissions;
            $user->transactionPin = $transactionPin;
            $user->commissionPercentage = $commissionPercentage;
            $user->isLogin = false;
            $user->referralId = $referral;

            if($profile_pic){
                if(Storage::disk('public')->exists($user->profile_pic)){
                    Storage::disk('public')->delete($user->profile_pic);
                }
                $user->profile_pic = $profile_pic;
            }
            if($adharcard){
                if(Storage::disk('public')->exists($user->adharcard)){
                    Storage::disk('public')->delete($user->adharcard);
                }
                $user->adharcard = $adharcard;
            }
            if($pancard){
                if(Storage::disk('public')->exists($user->pancard)){
                    Storage::disk('public')->delete($user->pancard);
                }
                $user->pancard = $pancard;
            }
            
            $user->save();
            if (Session::get('is_f') == "true") {
                return redirect('/users/Franchise');
            } else {
                return redirect('/users');
            }
        } else {
            session()->flash('msg', 'You are not Authorized to edit this User.');
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);

        if ($user['role'] == "agent" || $user['role'] == "premium" || $user['role'] == "executive" || $user['role'] == "classic") {
            $refer = User::where('referralId', new \MongoDB\BSON\ObjectID($user['_id']))->first();
            if (empty($refer['_id'])) {
                $user->delete();
                session()->flash('success', 'Deleted User...');
                return redirect()->back();
            } else {
                session()->flash('msg', 'He have a referal Users so first delete his referal Users');
                return redirect()->back();
            }
        } elseif ($user['role'] == "player") {
            $user->delete();
            session()->flash('success', 'Deleted User...');
            return redirect()->back();
        } elseif ($user['role'] == "subadmin") {
            $user->delete();
            session()->flash('success', 'Deleted User...');
            return redirect()->back();
        }

        return redirect()->back();
    }

    public function uploads($file, $path)
    {
        if($file) {
            $fileName   = time() . $file->getClientOriginalName();
            Storage::disk('public')->put($fileName, File::get($file));
            $file_name  = $file->getClientOriginalName();
            $file_type  = $file->getClientOriginalExtension();
            $filePath   = $path . $fileName;

            return $file = [
                'fileName' => $fileName,
                'fileType' => $file_type,
                'filePath' => $filePath,
                'fileSize' => $this->fileSize($file)
            ];
        }
    }

    public function fileSize($file, $precision = 2)
    {   
        $size = $file->getSize();

        if ( $size > 0 ) {
            $size = (int) $size;
            $base = log($size) / log(1024);
            $suffixes = array(' bytes', ' KB', ' MB', ' GB', ' TB');
            return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
        }

        return $size;
    }

    public function winningPercent()
    {
        $this->middleware('admin');
        $user = Winnings::find('602e55e9a494988def7acc25');
        return view('admin.winningPercent', ['data' => $user]);
    }

    public function percent(Request $request)
    {
        $this->middleware('admin');
        $request->validate([
            'RouletteTimer40' => 'required|not_in:0|numeric|between:0,200',
            'RouletteTimer60' => 'required|not_in:0|numeric|between:0,200',
            'Roulette' => 'required|not_in:0|numeric|between:0,200',
        ]);

        foreach ($request->listArray as $key => $value) {
            $listArray[$key] = intval(trim($value, '"'));
        }

        $user = Winnings::find('602e55e9a494988def7acc25');
        $user->rouletteTimer40 = $request->RouletteTimer40;
        $user->rouletteTimer60 = $request->RouletteTimer60;
        $user->roulette = $request->Roulette;
        if ($request->status == "true") {
            $user->isManual = true;
            $user->listArray = $listArray;
        } else {
            $user->isManual = false;
        }
        $user->save();
        session()->flash('success', 'winning percentage is updated');
        return view('admin.winningPercent', ['data' => $user]);
    }

    public function Winbyadmin()
    {
        $this->middleware('admin');
        $user = User::where('role', 'retailer')->get();
        $winner = WinnerIds::all();
        // echo "<pre>";print_r($winner->toArray());die();
        return view('admin.WinnerId', ['data' => $user, 'winner' => $winner]);
    }

    public function winnerIdAdmin(Request $request)
    {
        // echo "<pre>";
        // print_r($request->toArray());die();
        if ($request->amount == -1) {
            session()->flash('error', 'Plz select Username');
            return redirect()->back();
        }
        $this->middleware('admin');
        $request->validate([
            'amount' => 'required|not_in:-1',
            'percent' => 'required|not_in:0|numeric|between:0,99.99',
        ]);
        $user = new WinnerIds();
        $user->retailerId = new \MongoDB\BSON\ObjectID($request->amount);
        $user->percent = $request->percent;
        $user->save();
        $win = new WinnerIdHistoris();
        $win->retailerId = new \MongoDB\BSON\ObjectID($request->amount);
        $win->percent = $request->percent;
        $win->save();
        session()->flash('success', 'retailer percentage is added');
        return redirect('Winbyadmin');
    }

    public function complaint()
    {
        $Complaints = Complaints::orderBy('createdAt', 'DESC')->get();
        return view('/complaint', ['data' => $Complaints]);
    }

    public function complaintStatus($status, $id)
    {
        $Complaints = Complaints::find($id);
        if ($status == 0) {
            $Complaints->status = 1;
        } elseif ($status == 1) {
            $Complaints->status = 0;
        }
        $Complaints->save();
        return redirect('/complaint');
    }

    public function complaintDelete($id)
    {
        $Complaint = Complaints::find($id);
        $Complaint->delete();
        return redirect()->back();
    }

    public function chpass()
    {
        return view('changepassword');
    }

    public function chpassword(Request $request)
    {
        $validatedData = $request->validate([
            'opass' => 'required|numeric|min:6',
            'npass' => 'required|numeric|min:6',
            'cpass' => 'required|numeric|min:6',
        ]);

        $password = $request->cpass;
        $user = User::find(Session::get('id'));
        if ($user['password'] == $request->opass) {
            if ($request->opass != $request->npass) {
                if ($request->npass == $request->cpass) {
                    $user->password = $password;
                    $user->save();
                    Session::flush();
                    return redirect('/');
                } else {
                    return back()->with('msg', 'new pass not match....');
                }
            } else {
                return back()->with('msg', 'old pass and new pass match....');
            }
        } else {
            return back()->with('msg', 'old pass not match....');
        }
    }

    public function changepin()
    {
        return view('transactionpin');
    }

    public function chpin(Request $request)
    {
        // echo "<pre>";
        // print_r($request->toArray());
        // die;
        $validatedData = $request->validate([
            'otpass' => 'required|numeric|min:6',
            'ntpass' => 'required|numeric|min:6',
            'ctpass' => 'required|numeric|min:6',
        ]);
        $password = $request->ctpass;
        $user = User::find(Session::get('id'));

        if ($user['transactionPin'] == $request->otpass) {
            if ($request->otpass != $request->ntpass) {
                if ($request->ntpass == $request->ctpass) {
                    // $token = Session::get('token');
                    $user->transactionPin = $password;
                    $user->save();
                    Session::put('transactionPin', $password);
                    return back()->with('success', 'Transaction pin Updated.....');
                } else {
                    return back()->with('error', 'new pass not match....');
                }
            } else {
                return back()->with('error', 'old pass and new pass match....');
            }
        } else {
            return back()->with('msg', 'old transactionPin pass not match....');
        }
    }

    public function banuser($id, $isActive)
    {
        // echo "<pre>";
        // print_r($isActive);
        // die;
        $user = User::find($id);
        $refer = User::where('_id', new \MongoDB\BSON\ObjectID($user->referralId))->first();



        if ($refer['role'] == Session::get('role') || Session::get('role') == "Admin" || Session::get('role') == "subadmin") {
            if ($isActive == 1) {
                $is = false;
            } else {
                $is = true;
            }
            $user = User::find($id);
            // if ($user->referralId)
            $user->isActive = $is;
            $user->save();

            $refferUser = User::where('referralId', new \MongoDB\BSON\ObjectID($user->_id))->get();
            if (!empty($refferUser)) {
                foreach ($refferUser as $u) {
                    $referU = User::where('referralId', new \MongoDB\BSON\ObjectID($u->_id))->get();
                    if (!empty($referU)) {
                        foreach ($referU as $r) {
                            $referE = User::where('referralId', new \MongoDB\BSON\ObjectID($r->_id))->get();
                            if (!empty($referE)) {
                                foreach ($referE as $E) {
                                    $referA = User::where('referralId', new \MongoDB\BSON\ObjectID($E->_id))->get();
                                    if (!empty($referA)) {
                                        foreach ($referA as $A) {
                                            $A->isActive = $is;
                                            $A->save();
                                        }
                                    }
                                    $E->isActive = $is;
                                    $E->save();
                                }
                            }
                            $r->isActive = $is;
                            $r->save();
                        }
                    }
                    $u->isActive = $is;
                    $u->save();
                }
            }
        } else {
            session()->flash('msg', 'You are not Authorized to ban this User.');
            return redirect()->back();
        }
        return redirect()->back();
    }

    public function blockUser($id, $isActive)
    {
        // echo "<pre>";
        // print_r($isActive);
        // die;
        $user = User::find($id);
        $refer = User::where('_id', new \MongoDB\BSON\ObjectID($user->referralId))->first();

        if ($refer['role'] == Session::get('role') || Session::get('role') == "Admin" || Session::get('role') == "subadmin") {
            if ($isActive == 1) {
                $is = false;
            } else {
                $is = true;
            }
            $user = User::find($id);
            // if ($user->referralId)
            $user->isActive = $is;
            $user->save();
        } else {
            session()->flash('msg', 'You are not Authorized to ban this User.');
            return redirect()->back();
        }
        return redirect()->back();
    }

    public function get_data(Request $request)
    {

        // echo "<pre>";
        // print_r($request->toArray());
        // die;
        $is_f = $request->is_f;
        $id = $request->role;
        $output = "";
        $data = [];
        if ($is_f == "true") {
            // echo "vijay";
            if ($id == 1) {
                $output = "<div class='col-sm-6'>
                    <input type='hidden' class='form-control ui-autocomplete-input' id='exampleInputUsername1' value='" . Session::get('id') . "' name='referralId' autocomplete='off' placeholder='Enter Firm Name'>
                    </div>";
                echo $output;
            } elseif ($id == 3) {
                $output = "<div class='col-sm-6'>
                    <input type='hidden' class='form-control ui-autocomplete-input' id='exampleInputUsername1' value='" . Session::get('id') . "' name='referralId' autocomplete='off' placeholder='Enter Firm Name'>
                    </div>";
                echo $output;
            } elseif ($id == 5) {
                if (Session::get('role') == "Admin") {
                    $user = User::where('role', 'premium')->where('is_franchise', true)->get()->toArray();
                    // echo "<pre>";
                    // print_r($user);
                    // die;
                    if ($user != "") {
                        foreach ($user as $value) {
                            $data[] = $value;
                        }
                        // echo "<pre>";
                        // print_r($data);
                        // die();
                        echo "<option selected disabled>Select Premium</option>";
                        foreach ($data as $value) {
                            echo "<option value='" . $value['_id'] . "'>" . $value['userName'] . " " . $value['name'] . "</option>";
                        }
                    }
                } elseif (Session::get('role') == "agent" || Session::get('role') == "premium") {
                    echo "<option selected disabled>Select Substitute</option>";
                    $output = "<option value='" . Session::get('id') . "'>" . Session::get('username') . " " . Session::get('name') . "</option>";
                    echo $output;
                }
            } elseif ($id == 6) {
                if (Session::get('role') == "Admin") {
                    $user = User::where('role', 'executive')->where('is_franchise', true)->get()->toArray();
                    if ($user != "") {
                        foreach ($user as $value) {
                            $data[] = $value;
                        }
                        // echo "<pre>";
                        // print_r($data);
                        // die();
                        echo "<option selected disabled>Select Executive</option>";
                        foreach ($data as $value) {
                            echo "<option value='" . $value['_id'] . "'>" . $value['userName'] . " " . $value['name'] . "</option>";
                        }
                    }
                } elseif (Session::get('role') == "agent" || Session::get('role') == "premium" || Session::get('role') == "executive") {
                    echo "<option selected disabled>Select Substitute</option>";
                    $output = "<option value='" . Session::get('id') . "'>" . Session::get('username') . " " . Session::get('name') . "</option>";
                    echo $output;
                }
            } elseif ($id == 7) {
                if (Session::get('role') == "Admin") {
                    $user = User::where('role', 'classic')->where('is_franchise', true)->get();
                    if ($user != "") {
                        foreach ($user as $value) {
                            $data[] = $value;
                        }
                        echo "<option selected disabled>Select Classic</option>";
                        foreach ($data as $value) {
                            $id = $value['_id'];
                            echo "<option value='" . $value['_id'] . "'>" . $value['userName'] . " " . $value['name'] . "</option>";
                        }
                    }
                } elseif (Session::get('role') == "agent" || Session::get('role') == "premium" || Session::get('role') == "executive" || Session::get('role') == "classic") {

                    echo "<option selected disabled>Select Substitute</option>";
                    $output = "<option value='" . Session::get('id') . "'>" . Session::get('username') . " " . Session::get('name') . "</option>";
                    echo $output;
                }
            }
        } elseif ($request->is_f == "false") {
            if ($id == 1) {
                $output = "<div class='col-sm-6'>
                    <input type='hidden' class='form-control ui-autocomplete-input' id='exampleInputUsername1' value='" . Session::get('id') . "' name='referralId' autocomplete='off' placeholder='Enter Firm Name'>
                    </div>";
                echo $output;
            } elseif ($id == 3) {
                if (Session::get('role') == "Admin") {
                    $user = User::where('role', 'agent')->where('is_franchise', false)->get()->toArray();
                    if ($user != "") {
                        foreach ($user as $value) {
                            $data[] = $value;
                        }
                        // echo "<pre>";
                        // print_r($data);
                        // die();
                        echo "<option selected disabled>Select Agent</option>";
                        foreach ($data as $value) {
                            echo "<option value='" . $value['_id'] . "'>" . $value['userName'] . " " . $value['name'] . "</option>";
                        }
                    }
                }
            } elseif ($id == 5) {
                if (Session::get('role') == "Admin" || Session::get('role') == "agent") {
                    $user = User::where('role', 'premium')->where('is_franchise', false)->get()->toArray();
                    // echo "<pre>";
                    // print_r($user);
                    // die;
                    if ($user != "") {
                        foreach ($user as $value) {
                            $data[] = $value;
                        }
                        // echo "<pre>";
                        // print_r($data);
                        // die();
                        echo "<option selected disabled>Select Premium</option>";
                        foreach ($data as $value) {
                            echo "<option value='" . $value['_id'] . "'>" . $value['userName'] . " " . $value['name'] . "</option>";
                        }
                    }
                } elseif (Session::get('role') == "premium") {

                    echo "<option selected disabled>Select Substitute</option>";
                    $output = "<option value='" . Session::get('id') . "'>" . Session::get('username') . " " . Session::get('name') . "</option>";
                    echo $output;
                }
            } elseif ($id == 6) {
                if (Session::get('role') == "Admin" || Session::get('role') == "agent" || Session::get('role') == "premium") {
                    $user = User::where('role', 'executive')->where('is_franchise', false)->get()->toArray();
                    if ($user != "") {
                        foreach ($user as $value) {
                            $data[] = $value;
                        }
                        // echo "<pre>";
                        // print_r($data);
                        // die();
                        echo "<option selected disabled>Select Executive</option>";
                        foreach ($data as $value) {
                            echo "<option value='" . $value['_id'] . "'>" . $value['userName'] . " " . $value['name'] . "</option>";
                        }
                    }
                } elseif (Session::get('role') == "executive") {

                    echo "<option selected disabled>Select Substitute</option>";
                    $output = "<option value='" . Session::get('id') . "'>" . Session::get('username') . " " . Session::get('name') . "</option>";
                    echo $output;
                }
            } elseif ($id == 7) {
                if (Session::get('role') == "Admin" || Session::get('role') == "executive" || Session::get('role') == "agent" || Session::get('role') == "premium") {
                    $user = User::where('role', 'classic')->where('is_franchise', false)->get();
                    if ($user != "") {
                        foreach ($user as $value) {
                            $data[] = $value;
                        }
                        echo "<option selected disabled>Select Classic</option>";
                        foreach ($data as $value) {
                            $id = $value['_id'];
                            echo "<option value='" . $value['_id'] . "'>" . $value['userName'] . " " . $value['name'] . "</option>";
                        }
                    }
                } elseif (Session::get('role') == "classic") {

                    echo "<option selected disabled>Select Substitute</option>";
                    $output = "<option value='" . Session::get('id') . "'>" . Session::get('username') . " " . Session::get('name') . "</option>";
                    echo $output;
                }
            }
        }
    }

    public function get_distributer(Request $request)
    {
        $id = $request->role;
        if ($request->is_f == true) {
            $user = User::where('_id', new \MongoDB\BSON\ObjectID($id))->where('is_franchise', true)->first();
        } else {
            $user = User::where('_id', new \MongoDB\BSON\ObjectID($id))->where('is_franchise', false)->first();
        }

        // echo "<pre>";
        // print_r($user->toArray());
        // die;
        if ($user != "") {
            echo "<p class='form-text text-danger'>Margin cannot exceed (" . $user->commissionPercentage . ")</p>";
        }
    }

    public function detail($id)
    {
        $user = User::where('_id', new \MongoDB\BSON\ObjectID($id))->first();
        $LastStartPoint = 0;
        $totalStartPoint = 0;
        $LastTotalPlayPoint = 0;
        $TotalPlayPoint = 0;
        $LastTotalWinPoint = 0;
        $TotalWinPoint = 0;
        $LastTotalEndPoint = 0;
        $TotalEndPoint = 0;
        $LastTotalRetailerCommission = 0;
        $TotalRetailerCommission = 0;
        $total = [];

        $mon = strtotime("last monday");
        $monday = date('W', $mon) == date('W') ? $mon - 7 * 86400 : $mon;
        $sunday = strtotime(date("Y-m-d", $monday) . " +6 days");
        $week_sd = date("Y-m-d", $monday);
        $week_ed = date("Y-m-d", $sunday);

        $fm = date('m', strtotime($week_sd));
        $fd = date('d', strtotime($week_sd));
        $fY = date('Y', strtotime($week_sd));
        $tm = date('m', strtotime($week_ed));
        $td = date('d', strtotime($week_ed));
        $tY = date('Y', strtotime($week_ed));

        $cfm = date('m', strtotime("last monday"));
        $cfd = date('d', strtotime("last monday"));
        $cfY = date('Y', strtotime("last monday"));
        $ctm = date('m');
        $ctd = date('d');
        $ctY = date('Y');

        if ($cfd == $ctd) {
            $cfd = date('d', strtotime("-1 day"));
        }
        if ($user['role'] == "Admin") {
            $refer = User::where('referralId', new \MongoDB\BSON\ObjectID($user['_id']))->where('role', 'agent')->get();
        } elseif ($user['role'] == "agent") {
            $refer = User::where('referralId', new \MongoDB\BSON\ObjectID($user['_id']))->where('role', 'premium')->get();
        } elseif ($user['role'] == "premium") {
            $refer = User::where('referralId', new \MongoDB\BSON\ObjectID($user['_id']))->where('role', 'executive')->get();
        } elseif ($user['role'] == "executive") {
            $refer = User::where('referralId', new \MongoDB\BSON\ObjectID($user['_id']))->where('role', 'classic')->get();
        } elseif ($user['role'] == "classic") {
            $refer = User::where('referralId', new \MongoDB\BSON\ObjectID($user['_id']))->where('role', 'player')->get();
        }
        $data = array();
        foreach ($refer as $value) {
            $data[] = $value;
        }
        // echo "<pre>";
        // print_r($total);die;
        return view('admin.detail', ['user' => $data, 'data' => $user]);
    }

    public function transfercredit($id)
    {
        $user = User::find($id);
        return view('transfercredit', ['data' => $user]);
    }

    public function transfercredits(Request $request, $id)
    {
        // echo "<pre>";
        // print_r($request->toArray());
        // die();
        $MAC = exec('getmac');
        $MAC = strtok($MAC, ' ');

        $request->validate([
            'amount' => 'required|not_in:0|numeric|gt:0',
            'password' => 'required|not_in:0|numeric|min:5',
        ]);

        if (Session::get('role') == "Admin") {
            if ($request->amount >= 0) {
                $user = User::find($id);
                $admin = User::find(Session::get('id'));
                if ($request->password == Session::get('transactionPin')) {
                    if ($user->role == "agent" || $user->role == "premium" || $user->role == "executive" || $user->role == "classic" || $user->role == "player" || $user->role == "subadmin") {
                        if ($admin->creditPoint < $request->amount) {
                            session()->flash('msg', 'Check Credit Point! Credit Point is insufficient..');
                            return redirect()->back();
                        }
                        $checkPayment = Payments::where('fromId', Session::get('id'))->where('status', 'pending')->orderBy('createdAt', 'DESC')->get();
                        if (count($checkPayment) == 0) {
                            $paycheck = Session::get('creditPoint');
                        } else {
                            foreach ($checkPayment as $check) {
                                $paycheck = Session::get('creditPoint') - $check['creditPoint'];
                            }
                        }
                        if ($request->amount <= $paycheck) {
                            $payment = new Payments();
                            $payment->toId = $id;
                            $payment->fromId = Session::get('id');
                            $payment->creditPoint = intval($request->amount);
                            $payment->op_balance = intval($user->creditPoint);
                            $payment->comment = $request->comment;
                            $payment->macAddress = $MAC;
                            $payment->type = "transfer";
                            $payment->status = "success";
                            $payment->date = Date('Y-m-d');
                            $payment->user = $user->userName;
                            $payment->is_f = (Session::get('is_f') == 'true') ? true : false;
                            $payment->save();
                            $user->creditPoint = $user->creditPoint + $payment->creditPoint;
                            $user->save();
                            $refer = User::find(Session::get('id'));
                            $refer->creditPoint = $refer->creditPoint - $request->amount;
                            Session::put('creditPoint', $refer->creditPoint);
                            $refer->save();
                            session()->flash('success', 'added transfer creditpoint successfully....');
                            return redirect()->back();
                        } else {
                            session()->flash('msg', 'Check Credit Point! Credit Point is insufficient...');
                            return redirect()->back();
                        }
                    } else {
                        session()->flash('msg', 'You are not Authorized to Add Credit to this User.');
                        return redirect()->back();
                    }
                } else {
                    session()->flash('msg', 'Your Transaction PIn is Wrong...');
                    return redirect()->back();
                }
            } else {
                session()->flash('msg', 'Please Add Credit Point And Credit Point should not be 0');
                return redirect()->back();
            }
        } elseif (Session::get('role') == "agent" || Session::get('role') == "premium" || Session::get('role') == "subadmin") {
            // echo "<pre>";
            // print_r($request->toArray());
            // die;
            if ($request->amount >= 0) {
                $user = User::find($id);
                $refer = User::find(Session::get('id'));
                if ($request->password == Session::get('transactionPin')) {
                    if ($user->role ==  "premium" || $user->role == "executive" || $user->role == "classic" || $user->role == "player") {
                        if ($refer->creditPoint < $request->amount) {
                            session()->flash('msg', 'Check Credit Point! Credit Point is insufficient..');
                            return redirect()->back();
                        }
                        $checkPayment = Payments::where('fromId', Session::get('id'))->where('status', 'pending')->get();
                        if (count($checkPayment) == 0) {
                            $paycheck = Session::get('creditPoint');
                        } else {
                            foreach ($checkPayment as $check) {
                                $paycheck = Session::get('creditPoint') - $check['creditPoint'];
                            }
                        }
                        if ($request->amount <= $paycheck) {
                            $payment = new Payments();
                            $payment->toId = $id;
                            $payment->fromId = Session::get('id');
                            $payment->creditPoint = intval($request->amount);
                            $payment->balance = intval(Session::get('creditPoint') - $request->amount);
                            $payment->op_balance = intval($user->creditPoint);

                            $payment->comment = $request->comment;
                            $payment->macAddress = $MAC;
                            $payment->type = "transfer";
                            $payment->status = "success";
                            $payment->date = Date('Y-m-d');
                            $payment->user = $user->userName;
                            $payment->is_f = (Session::get('is_f') == 'true') ? true : false;
                            $payment->save();
                            $refer = User::find(Session::get('id'));
                            $refer->creditPoint = $refer->creditPoint - $request->amount;
                            Session::put('creditPoint', $refer->creditPoint);
                            $refer->save();
                            $user->creditPoint = $user->creditPoint + $payment->creditPoint;
                            $user->save();
                            session()->flash('success', 'Credit Points Transferred Successfully');
                            return redirect()->back();
                        } else {
                            session()->flash('msg', 'Check Credit Point! Credit Point is insufficient..');
                            return redirect()->back();
                        }
                    } else {
                        session()->flash('msg', 'You are not Authorized to Add Credit to this User.');
                        return redirect()->back();
                    }
                } else {
                    session()->flash('msg', 'Your Transaction PIn is Wrong...');
                    return redirect()->back();
                }
            } else {
                session()->flash('msg', 'Please Add Credit Point And Credit Point should not be 0');
                return redirect()->back();
            }
        } elseif (Session::get('role') == "executive") {
            if ($request->amount >= 0) {
                $user = User::find($id);
                $refer = User::find(Session::get('id'));
                if ($request->password == Session::get('transactionPin')) {
                    if ($user->role == "classic" || $user->role == "player") {
                        if ($refer->creditPoint < $request->amount) {
                            session()->flash('msg', 'Check Credit Point! Credit Point is insufficient..');
                            return redirect()->back();
                        }
                        $checkPayment = Payments::where('fromId', Session::get('id'))->where('status', 'pending')->get();
                        // echo count($checkPayment);die;
                        if (count($checkPayment) == 0) {
                            $paycheck = Session::get('creditPoint');
                        } else {
                            foreach ($checkPayment as $check) {
                                $paycheck = Session::get('creditPoint') - $check['creditPoint'];
                            }
                        }
                        if ($request->amount <= $paycheck) {
                            $payment = new Payments();
                            $payment->toId = $id;
                            $payment->fromId = Session::get('id');
                            $payment->creditPoint = intval($request->amount);
                            $payment->balance = intval(Session::get('creditPoint') - $request->amount);
                            $payment->op_balance = intval($user->creditPoint);
                            $payment->macAddress = $MAC;

                            $payment->comment = $request->comment;
                            $payment->type = "transfer";
                            $payment->status = "success";
                            $payment->date = Date('Y-m-d');
                            $payment->user = $user->userName;
                            $payment->is_f = (Session::get('is_f') == 'true') ? true : false;
                            $payment->save();
                            $refer = User::find(Session::get('id'));
                            $refer->creditPoint = $refer->creditPoint - $request->amount;
                            Session::put('creditPoint', $refer->creditPoint);
                            $refer->save();
                            $user->creditPoint = $user->creditPoint + $payment->creditPoint;
                            $user->save();
                            session()->flash('success', 'Credit Points Transferred Successfully');
                            return redirect()->back();
                        } else {
                            session()->flash('msg', 'Check Credit Point! Credit Point is insufficient..');
                            return redirect()->back();
                        }
                    } else {
                        session()->flash('msg', 'You are not Authorized to Add Credit to this User.');
                        return redirect()->back();
                    }
                } else {
                    session()->flash('msg', 'Your Transaction PIn is Wrong...');
                    return redirect()->back();
                }
            } else {
                session()->flash('msg', 'Please Add Credit Point And Credit Point should not be 0');
                return redirect()->back();
            }
        } elseif (Session::get('role') == "classic") {
            if ($request->amount >= 0) {
                $user = User::find($id);
                $refer = User::find(Session::get('id'));
                if ($request->password == Session::get('transactionPin')) {
                    if ($user->role == "player") {
                        if ($refer->creditPoint < $request->amount) {
                            session()->flash('msg', 'Check Credit Point! Credit Point is insufficient..');
                            return redirect()->back();
                        }
                        $checkPayment = Payments::where('fromId', Session::get('id'))->where('status', 'pending')->get();
                        // echo count($checkPayment);die;
                        if (count($checkPayment) == 0) {
                            $paycheck = Session::get('creditPoint');
                        } else {
                            foreach ($checkPayment as $check) {
                                $paycheck = Session::get('creditPoint') - $check['creditPoint'];
                            }
                        }
                        if ($request->amount <= $paycheck) {
                            $payment = new Payments();
                            $payment->toId = $id;
                            $payment->fromId = Session::get('id');
                            $payment->creditPoint = intval($request->amount);
                            $payment->balance = intval(Session::get('creditPoint') - $request->amount);
                            $payment->op_balance = intval($user->creditPoint);
                            $payment->macAddress = $MAC;

                            $payment->comment = $request->comment;
                            $payment->type = "transfer";
                            $payment->status = "success";
                            $payment->user = $user->userName;
                            $payment->is_f = (Session::get('is_f') == 'true') ? true : false;
                            $payment->date = Date('Y-m-d');
                            $payment->save();
                            $refer = User::find(Session::get('id'));
                            $refer->creditPoint = $refer->creditPoint - $request->amount;
                            Session::put('creditPoint', $refer->creditPoint);
                            $refer->save();
                            $user->creditPoint = $user->creditPoint + $payment->creditPoint;
                            $user->save();
                            session()->flash('success', 'Credit Points Transferred Successfully');
                            return redirect()->back();
                        } else {
                            session()->flash('msg', 'Check Credit Point! Credit Point is insufficient..');
                            return redirect()->back();
                        }
                    } else {
                        session()->flash('msg', 'You are not Authorized to Add Credit to this User.');
                        return redirect()->back();
                    }
                } else {
                    session()->flash('msg', 'Your Transaction PIn is Wrong...');
                    return redirect()->back();
                }
            } else {
                session()->flash('msg', 'Please Add Credit Point And Credit Point should not be 0');
                return redirect()->back();
            }
        }
    }

    public function adjustcredit($id)
    {
        $user = User::find($id);
        return view('adjustcredit', ['data' => $user]);
    }

    public function adjustcredits(Request $request, $id)
    {
        // echo "<pre>";print_r($request->toArray());
        // die();
        $MAC = exec('getmac');
        $MAC = strtok($MAC, ' ');

        $request->validate([
            'amount' => 'required|not_in:0|numeric|gt:0',
            'password' => 'required|not_in:0|numeric|min:5',
        ]);

        if ($request->amount >= 0) {
            if ($request->password == Session::get('transactionPin')) {
                $user = User::find($id);
                if ($user->role == "agent" || $user->role == "premium" || $user->role == "executive" || $user->role == "classic" || $user->role == "player" || $user->role == "subadmin") {
                    if ($user->creditPoint < $request->amount) {
                        session()->flash('msg', 'Check Credit Point! Credit Point is insufficient..');
                        return redirect()->back();
                    }
                    $payment = new Payments();
                    $payment->toId = $id;
                    $payment->fromId = Session::get('id');
                    $payment->creditPoint = intval($request->amount);
                    $payment->balance = intval($user->creditPoint - $request->amount);
                    $payment->op_balance = intval($user->creditPoint);
                    $payment->macAddress = $MAC;
                    $payment->comment = $request->comment;
                    $payment->type = "adjustment";
                    $payment->status = "success";
                    $payment->date = Date('Y-m-d');
                    $payment->user = $user->userName;
                    $payment->is_f = (Session::get('is_f') == 'true') ? true : false;
                    $payment->save();
                    $user->creditPoint = $user->creditPoint - $payment->creditPoint;
                    $user->save();
                    $add = User::where('_id', Session::get('id'))->first();
                    $add->creditPoint = $add->creditPoint + $payment->creditPoint;
                    Session::put('creditPoint', $add->creditPoint);
                    $add->save();
                    session()->flash('success', 'Credit Points Adjusted Successfully');
                    return redirect()->back();
                } else {
                    session()->flash('msg', 'You are not Authorized to Add Credit to this User.');
                    return redirect()->back();
                }
            } else {
                session()->flash('msg', 'Your Transaction PIn is Wrong...');
                return redirect()->back();
            }
        } else {
            session()->flash('msg', 'Please Add Credit Point And Credit Point should not be 0');
            return redirect()->back();
        }
    }

    public function transfer()
    {
        if (Session::get('role') == "Admin" || Session::get('role') == "subadmin") {
            $user = User::orderBy('userName', 'ASC')
                ->where('is_franchise', (Session::get('is_f') == "true") ? true : false)
                ->where('userName', "!=", "superadminA")
                ->where('userName', '!=', "superadminF")->get();
        } elseif (Session::get('role') == "agent") {
            $user = User::orderBy('userName', 'ASC')
                ->where('is_franchise', (Session::get('is_f') == "true") ? true : false)
                ->where('referralId', new \MongoDB\BSON\ObjectID(Session::get('id')))->get()->toArray();
            foreach ($user as $u) {
                $executive = User::orderBy('userName', 'ASC')
                    ->where('is_franchise', (Session::get('is_f') == "true") ? true : false)
                    ->where('referralId', new \MongoDB\BSON\ObjectID($u['_id']))->get()->toArray();
                foreach ($executive as $key => $e) {
                    $classic = User::orderBy('userName', 'ASC')
                        ->where('is_franchise', (Session::get('is_f') == "true") ? true : false)
                        ->where('referralId', new \MongoDB\BSON\ObjectID($e['_id']))->get()->toArray();
                    foreach ($classic as $key => $c) {
                        $player = User::orderBy('userName', 'ASC')
                            ->where('is_franchise', (Session::get('is_f') == "true") ? true : false)
                            ->where('referralId', new \MongoDB\BSON\ObjectID($c['_id']))->where('role', 'player')->get()->toArray();
                    }
                    $classic = array_merge($classic, $player);
                }
                $executive = array_merge($executive, $classic);
            }
            $user = array_merge($user, $executive);
        } elseif (Session::get('role') == "premium") {
            $user = User::orderBy('userName', 'ASC')
                ->where('is_franchise', (Session::get('is_f') == "true") ? true : false)
                ->where('referralId', new \MongoDB\BSON\ObjectID(Session::get('id')))->get()->toArray();
            foreach ($user as $key => $u) {
                $classic = User::orderBy('userName', 'ASC')
                    ->where('is_franchise', (Session::get('is_f') == "true") ? true : false)
                    ->where('referralId', new \MongoDB\BSON\ObjectID($u['_id']))->get()->toArray();
                foreach ($classic as $key => $c) {
                    $player = User::orderBy('userName', 'ASC')
                        ->where('is_franchise', (Session::get('is_f') == "true") ? true : false)
                        ->where('referralId', new \MongoDB\BSON\ObjectID($c['_id']))->where('role', 'player')->get()->toArray();
                }
                $classic = array_merge($classic, $player);
            }
            $user = array_merge($user, $classic);
        } elseif (Session::get('role') == "executive") {
            $user = User::orderBy('userName', 'ASC')
                ->where('is_franchise', (Session::get('is_f') == "true") ? true : false)
                ->where('referralId', new \MongoDB\BSON\ObjectID(Session::get('id')))->get()->toArray();
            foreach ($user as $key => $u) {
                $player = User::orderBy('userName', 'ASC')
                    ->where('is_franchise', (Session::get('is_f') == "true") ? true : false)
                    ->where('referralId', new \MongoDB\BSON\ObjectID($u['_id']))->where('role', 'player')->get()->toArray();
            }
            $user = array_merge($user, $player);
        } elseif (Session::get('role') == "classic") {
            $user = User::orderBy('userName', 'ASC')
                ->where('is_franchise', (Session::get('is_f') == "true") ? true : false)
                ->where('referralId', new \MongoDB\BSON\ObjectID(Session::get('id')))->where('role', 'player')->get();
        }

        $pending_accept = Payments::where('toId', Session::get('id'))
            ->where('status', 'pending')->where('is_f', (Session::get('is_f') == "true") ? true : false)
            ->orderBy('createdAt', 'DESC')->get();
        $pending_transfer = Payments::where('fromId', Session::get('id'))->where('is_f', (Session::get('is_f') == "true") ? true : false)
            ->orderBy('createdAt', 'DESC')->get();
        return view('transfer', ['data' => $user, 'pending_accept' => $pending_accept, 'pending_transfer' => $pending_transfer]);
    }

    public function search(Request $request)
    {
        $user = User::all();
        foreach ($user as $value) {
            if (Session::get('role') == "Admin" || Session::get('id') == $value['referralId']) {
                if ($request->id == $value['_id']) {
                    echo "<table class='col-md-3'>
                                <tr>
                                    <td>Name</td>
                                    <td>:</td>
                                    <td>" . $value['name'] . "</td>
                                </tr>
                                <tr>
                                    <td>Username</td>
                                    <td>:</td>
                                    <td>" . $value['userName'] . "</td>
                                </tr>
                                <tr>
                                    <td>Points</td>
                                    <td>:</td>
                                    <td>" . number_format($value['creditPoint'], 2) . "</td>
                                </tr>
                                <tr>
                                    <td><a href='transfercredit/" . $value['_id'] . "' class='btn btn-outline-success title='Transfer Credit'>Add Points</a></td>
                                    <td><a href='adjustcredit/" . $value['_id'] . "' class='btn btn-outline-warning' title='Adjust Credit'>Subtract Points</a></td>
                                </tr>
                            </table>";
                }
            } else {
                if ($request->id == $value['_id']) {
                    echo "<table class='col-md-3'>
                                <tr>
                                    <td>Name</td>
                                    <td>:</td>
                                    <td>" . $value['name'] . "</td>
                                </tr>
                                <tr>
                                    <td>Username</td>
                                    <td>:</td>
                                    <td>" . $value['userName'] . "</td>
                                </tr>
                            </table>";
                }
            }
        }
    }

    public function success($id)
    {
        $payment = Payments::find($id);
        $user = User::find($payment->fromId);
        if ($payment->status == "pending") {
            if ($user['role'] == "Admin") {
                $payment->status = "success";
                if ($payment->save()) {
                    if (Session::get('role') == "agent" || Session::get('role') == "premium" || Session::get('role') == "executive" || Session::get('role') == "classic" || Session::get('role') == "player") {
                        $user = User::find($payment->toId);
                        $user->creditPoint = $user->creditPoint + $payment->creditPoint;
                        Session::put('creditPoint', $user->creditPoint);
                        $user->save();
                    }
                }
                session()->flash('success', 'added transfer creditpoint successfully....');
                return redirect()->back();
            } else {
                if ($user['creditPoint'] < $payment['creditPoint']) {
                    session()->flash('msg', 'Check Credit To Your Parent! Credit Point is insufficient..');
                    return redirect()->back();
                } else {
                    $payment->status = "success";
                    if ($payment->save()) {
                        if (Session::get('role') == "agent") {
                            $user = User::find($payment->toId);
                            $user->creditPoint = $user->creditPoint + $payment->creditPoint;
                            Session::put('creditPoint', $user->creditPoint);
                            $user->save();
                            $refer = User::find($payment->fromId);
                            if (!empty($refer) && $refer->referralId != "603388bb7d20e50a81217277") {
                                $refer->creditPoint = $refer->creditPoint - $payment->creditPoint;
                                $refer->save();
                            }
                        } elseif (Session::get('role') == "premium") {
                            $user = User::find($payment->toId);
                            $user->creditPoint = $user->creditPoint + $payment->creditPoint;
                            Session::put('creditPoint', $user->creditPoint);
                            $user->save();
                            $refer = User::find($payment->fromId);
                            if (!empty($refer) && $refer->referralId != "603388bb7d20e50a81217277") {
                                $refer->creditPoint = $refer->creditPoint - $payment->creditPoint;
                                $refer->save();
                            }
                        } elseif (Session::get('role') == "executive" || Session::get('role') == "classic" || Session::get('role') == "player") {
                            $user = User::find($payment->toId);
                            $user->creditPoint = $user->creditPoint + $payment->creditPoint;
                            Session::put('creditPoint', $user->creditPoint);
                            $user->save();
                            $refer = User::find($payment->fromId);
                            $refer->creditPoint = $refer->creditPoint - $payment->creditPoint;
                            $refer->save();
                        }
                    }
                    session()->flash('success', 'added transfer creditpoint successfully....');
                    return redirect()->back();
                }
            }
        } elseif ($payment->status == "success") {
            session()->flash('msg', 'already transfer creditpoint....');
            return redirect()->back();
        }
    }

    public function reject($id)
    {
        $payment = Payments::find($id);
        if ($payment->status == "pending") {
            $payment->status = "reject";
            if ($payment->save()) {
                $user = User::find($payment->fromId);
                $user->creditPoint = $user->creditPoint + $payment->creditPoint;
                $user->save();
            }
            session()->flash('success', 'reject creditpoint successfully....');
            return redirect()->back();
        } elseif ($payment->status == "reject") {
            session()->flash('msg', ' already rejected creditpoint....');
            return redirect()->back();
        }
    }

    public function complaintAll($id)
    {
        if (Session::get('password') == $id) {
            $delete = Complaints::truncate();
            session()->flash('success', 'All Complaints Deleted...');
            return redirect('/complaint');
        } else {
            session()->flash('msg', 'Your Wrong Password....');
            return redirect('/complaint');
        }
    }

    public function WinByAdminDelete($id)
    {
        // echo "<pre>";
        $user = WinnerIds::find($id);
        // print_r($user->toArray());
        // die;
        $user->delete();
        return redirect()->back();
    }

    public function adminPercent()
    {
        $this->middleware('admin');
        $monday = strtotime("last monday");
        $monday = date('w', $monday) == date('w') ? $monday + 7 * 86400 : $monday;
        $sunday = strtotime(date("Y-m-d", $monday) . " +6 days");
        // echo $monday = date('y-m-d', $monday);
        // echo $sunday = date('y-m-d', $sunday);

        // die;
        $fm = date('m', $monday);
        $fd = date('j', $monday);
        $fY = date('Y', $monday);
        $tm = date('m', $sunday);
        $tY = date('Y', $sunday);
        $td = date('j', $sunday);

        $totalStartPoint = 0;
        $totalPlayPoints = 0;
        $TotalWinPoints = 0;
        $EndPoint = 0;
        $margin = 0;
        $netprofit = 0;
        $SuperDistributedProfit = 0;
        $player = User::where('role', 'player')->where('is_franchise', (Session::get('is_f') ? true : false))->get();
        foreach ($player as $player_user) {
            $players[] = new \MongoDB\BSON\ObjectID($player_user['_id']);
        }
        $playPoints = Bets::select('bet', 'won', 'playerCommission', 'classicCommission', 'ExecutiveCommission', 'premiumCommission', 'agentCommission')
            ->whereIn('playerId', $players)
            ->whereBetween(
                'createdAt',
                array(
                    Carbon::create($fY, $fm, $fd, 00, 00, 00),
                    Carbon::create($tY, $tm, $td, 23, 59, 59),
                )
            )->get();

        // echo "<Pre>";
        // print_r($playPoints->toArray());
        // die;
        foreach ($playPoints as $play) {
            $totalPlayPoints += $play['bet'];
            $TotalWinPoints += $play['won'];
        }
        $total = [];
        $total['totalPlayPoints'] = $totalPlayPoints;
        $total['TotalWinPoints'] = $TotalWinPoints;
        $total['EndPoint'] = $total['totalPlayPoints'] - $total['TotalWinPoints'];
        // return view('turnOver', ['data' => $admin, 'total' => $total]);

        // echo "<pre>";
        // print_r($total);
        // die;

        return view('admin.AdminPercent', ['total' => $total]);
    }

    public function point_request()
    {
        $user = User::orderBy('userName', 'ASC')
            ->where('is_franchise', (Session::get('is_f') == "true") ? true : false)
            ->where('userName', "!=", "superadminA")
            ->where('userName', '!=', "superadminF")->where('role', '!=', "subadmin")->get()->pluck('_id');
        foreach ($user as $key => $u) {
            $user[$key] = new \MongoDB\BSON\ObjectID($u);
        }
        if (Session::get('role') == "Admin" || Session::get('role') == "subadmin") {
            $data = pointrequests::orderBy('createdAt', 'DESC')->whereIn('playerId', $user)->where('status', 'Pending')->get();
        } elseif (Session::get('role') == "agent" || Session::get('role') == "premium" || Session::get('role') == "executive" || Session::get('role') == "classic") {
            $data = pointrequests::orderBy('createdAt', 'DESC')->where('fromId', new \MongoDB\BSON\ObjectID(Session::get('id')))->where('status', 'Pending')->get();
        }

        return view('pointrequests', [
            'data' => $data,
        ]);
    }

    public function point_requests_create()
    {
        return view('point_request_create');
    }

    public function point_request_store(Request $request)
    {
        // echo "<pre>";
        // print_r($request->toArray());
        // die;
        $request->validate([
            'Points' => 'required|numeric',
        ]);

        $data = new pointrequests();
        $data->point = intval($request->Points);
        $data->status = 'Pending';
        $data->comment = $request->Comment;
        $data->op_balance = intval(Session::get('creditPoint'));
        $data->playerId = new \MongoDB\BSON\ObjectID($request->id);
        $data->fromId = new \MongoDB\BSON\ObjectID(Session::get('referralId'));
        $data->DrTime = date('h:i:s A');
        $data->DrDate = date('n-j-Y');
        $data->createDate = date('n/j/Y, h:i:s A');
        $data->is_f = (Session::get('is_f') == 'true') ? true : false;
        $data->save();
        session()->flash('success', 'Point Request Send successfully....');
        return redirect('point_request');
    }

    public function point_request_id($id, $point)
    {
        $point = explode(',', $point);

        if (Session::get('transactionPin') == $point[1]) {
            $data = pointrequests::find($id);
            $user = User::find($data->playerId);
            if ($data->status == "Pending") {
                $refer = User::find(Session::get('id'));
                if ($refer->creditPoint > $point[0]) {
                    if (Session::get('role') == "Admin" || Session::get('role') == "agent" || Session::get('role') == "premium" || Session::get('role') == "executive" || Session::get('role') == "classic" || Session::get('role') == "subadmin") {
                        $data->status = "Success";
                        $user = User::find(
                            $data->playerId
                        );
                        $user->creditPoint = $user->creditPoint + $point[0];
                        $user->save();
                        $refer->creditPoint = $refer->creditPoint - $point[0];
                        $refer->save();
                        Session::put('creditPoint', $refer->creditPoint);
                        $data->allocated_point = intval($point[0]);
                        $data->allocated_user = Session::get('name');
                        $data->save();
                    }
                    session()->flash('success', 'added transfer creditpoint successfully....');
                    return redirect()->back();
                } else {
                    session()->flash('msg', 'Check Credit To Your Parent! Credit Point is insufficient..');
                    return redirect()->back();
                }
            }
        } else {
            session()->flash('msg', 'Your Transaction PIn is Wrong...');
            return redirect()->back();
        }
    }

    public function generatePointList()
    {
        return view('admin.generatePointsList', ['data' => generatepoints::where('is_f', (Session::get('is_f') == "true") ? true : false)->latest()->paginate(10)]);
    }

    public function do_generate_points()
    {
        return view('admin.GeneratePoints');
    }

    public function do_generate_points_create(Request $request)
    {
        // echo "<pre>";
        // print_r($request->toArray());
        // die;
        if (Session::get('transactionPin') == $request->password) {
            $data =  new generatepoints();
            $data->balance = intval(Session::get('creditPoint'));
            $data->is_f = (Session::get('is_f') == 'true') ? true : false;
            $data->notes = $request->note;
            $data->generateBalance = intval($request->amount);
            $refer = User::where('_id', new \MongoDB\BSON\ObjectID(Session::get('id')))->first();
            if (!empty($refer) && $refer->role == "Admin") {
                $refer->creditPoint = $refer->creditPoint + $request->amount;
                $refer->save();
                Session::put('creditPoint', $refer->creditPoint);
            }
            $data->save();
            session()->flash('success', 'Generated Point successfully...');
            return redirect('/generatePointList');
        } else {
            session()->flash('msg', 'Your Wrong Password....');
            return redirect('/do_generate_points');
        }
    }

    public function CheckUserName(Request $request)
    {
        // $request->check;
        $check = User::where('userName', $request->check)->get();
        if (count($check) == 0) {
            echo "<span class='text-success'></span>This UserName is Available..</span>";
        } else {
            echo "<span class='text-danger'>This UserName is Already Taken!!</span>";
        }
    }

    public function add_premium()
    {
        return view('admin.create');
    }

    public function add_executive()
    {
        return view('admin.create');
    }

    public function add_classic()
    {
        return view('admin.create');
    }

    public function add_player()
    {
        return view('admin.create');
    }
}
