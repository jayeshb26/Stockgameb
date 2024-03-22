<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Winresults;
use \DateTime;
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
        $yestDays = $currentDateTime->copy()->subDay(1);

        $selectedTime = session('selectedTime');
        $selectedDate = session('selectedDate');
        $date = new DateTime($selectedDate);
        $formattedDate = $date->format('n-j-Y');
        $previousSelectedTime = session('previousSelectedTime');

        session(['previousSelectedTime' => $selectedTime]);

        $timeParts = explode("-", $selectedTime);

        // Trim the first part (start time)
        $startFormatted = ' '.trim($timeParts[0]);

        // Trim the second part (end time)
        $endFormatted = ' '.trim($timeParts[1]);

        $selectedDateTime = Carbon::parse($selectedDate);
        $selectedStartDateTime = Carbon::parse($selectedDateTime->toDateString() . ' ' . $startFormatted);
        $selectedEndDateTime = Carbon::parse($selectedDateTime->toDateString() . ' ' . $endFormatted);


        // dd($selectedStartDateTime,$selectedEndDateTime, [$startFormatted, $endFormatted]);

        $stocks = Winresults::where(function($query) use ($selectedStartDateTime, $selectedEndDateTime) {
            $query->where('createdAt', '>=', new Carbon($selectedEndDateTime));
        })
        ->whereNotNull('result') // Ensuring 'result' field is not null
        ->orderBy('DrDate')
        ->get()
        ->toArray();





        // Query Winresults entries based on date and time range
        // $stocks = Winresults::where('DrDate', $formattedDate)
        //     ->whereBetween('DrTime', [$combinedString])
        //     ->orderBy('DrTime')
        //     ->get()
        //     ->toArray();

        // dd($formattedDate,$startFormatted, $endFormatted);
// dd($stocks);
                // $stocks = Winresults::where(function ($query) use ($startFormatted, $endFormatted, $sevenDaysAgo, $yestDays) {
        //     $query->where('DrDate', $sevenDaysAgo->format('n-j-Y'))
        //         ->where('result', '!=', '');
        //     if (isset($startFormatted)) {

        //         if ($startFormatted > $endFormatted) {

        //             $query->whereBetween('DrTime', [$startFormatted, '23:59:59']);
        //         } else {
        //             $query->whereBetween('DrTime', [$startFormatted, $endFormatted]);
        //         }
        //     }
        // })
        // ->orWhere(function ($query) use ($startFormatted, $endFormatted, $sevenDaysAgo, $yestDays) {
        //     $query->where('DrDate', $yestDays->format('n-j-Y'))
        //         ->where('result', '!=', '');
        //         if (isset($startFormatted)) {

        //             if ($startFormatted > $endFormatted) {

        //                 $query->whereBetween('DrTime', [$startFormatted, '23:59:59']);
        //             } else {
        //                 $query->whereBetween('DrTime', [$startFormatted, $endFormatted]);
        //             }
        //         }
        // })
        // ->get()->toArray();
        return response()->json($stocks);
    }

    public function saveSelectedTime(Request $request){
        $selectedTime = $request->input('time');
        $selectedDate = $request->input('date');
        session(['selectedTime' => $selectedTime, 'selectedDate' => $selectedDate]);
        return  response()->json(['message' => 'Selected time received successfully', 'time' => $selectedTime,  'date' => $selectedDate]);
    }

    public function saveSelectedDate(Request $request){
        $selectedDataDate = $request->input('Datadate');
        session(['selectedDataDate' => $selectedDataDate]);
        return response()->json(['message' => 'Selected Date received successfully', 'Datadate' => $selectedDataDate]);
    }

    public function indexDate(Request $request)
    {
        $selectedDataDate = session('selectedDataDate');


        $stocks = Winresults::where(function($query) use ($selectedDataDate) {
                $query->where('DrDate', '>=', $selectedDataDate)
                    ->where('DrDate', '<=', $selectedDataDate);
            })
            ->whereNotNull('result') // Ensuring 'result' field is not null
            ->orderBy('DrDate')
            ->get()
            ->toArray();


        // dd($stocks);
        return response()->json($stocks);

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

