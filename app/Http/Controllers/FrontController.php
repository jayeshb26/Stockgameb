<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Winresults;
use Carbon\Carbon;

class FrontController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $currentDateTime = Carbon::now();
        $currentTime = $currentDateTime->format('h:i:s');
        $sevenDaysAgo = $currentDateTime->copy();
        $yestDays = $currentDateTime->copy()->subDays(1);
        $selectedTime = session('selectedTime');

        $timeParts = explode("-", $selectedTime);
        $current = trim($timeParts[0]);
        $past = trim($timeParts[1]);

        $currentDateTime = Carbon::createFromFormat('h:i A', $current);
        $pastDateTime = Carbon::createFromFormat('h:i A', $past);

        $stocks = Winresults::where(function ($query) use ($currentDateTime, $pastDateTime, $sevenDaysAgo, $yestDays) {
                $query->where('DrDate', $sevenDaysAgo->format('n-j-Y'))
                    ->whereBetween('DrTime', ["",$pastDateTime->format('h:i:s A'), "",$currentDateTime->format('h:i:s A')]);
            })
            ->orWhere(function ($query) use ($currentDateTime, $pastDateTime, $sevenDaysAgo, $yestDays) {
                $query->where('DrDate', $yestDays->format('n-j-Y'))
                    ->whereBetween('DrTime', ["",$pastDateTime->format('h:i:s A'), "",$currentDateTime->format('h:i:s A')]);
            })
            ->orderBy('DrDate', 'desc')
            ->orderBy('DrTime', 'desc')
            ->get();

$organizedData = [];

foreach ($stocks as $stock) {
    $date = $stock->DrDate;
    $time = $stock->DrTime;
    // dd($date);
    if (!isset($organizedData[$date])) {
        $organizedData[$date] = [];
    }

    // Get the hour part of the time

    // If there's no entry for this hour, create an array for it
    if (!isset($organizedData[$date][$time])) {
        $organizedData[$date][$time] = [];
    }

    // Add the time entry to the corresponding hour array
    $organizedData[$date][$time][] = $time;
}


            return response()->json($stocks);
    }

    public function saveSelectedTime(Request $request){

        $selectedTime = $request->input('time');

        session(['selectedTime' => $selectedTime]);
        return  response()->json(['message' => 'Selected time received successfully', 'time' => $selectedTime]);
        // return $response;
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
