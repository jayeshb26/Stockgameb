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
$sevenDaysAgo = $currentDateTime->copy()->subDays(7); // Adjusted to subtract 7 days instead of a single day
$yestDays = $currentDateTime->copy()->subDay(1);

$selectedTime = session('selectedTime');
$previousSelectedTime = session('previousSelectedTime');

session(['previousSelectedTime' => $selectedTime]);

$timeParts = explode("-", $selectedTime);
$startFormatted = ' '.trim($timeParts[0]);
$endFormatted = ' '.trim($timeParts[1]);

$stocks = Winresults::where(function ($query) use ($startFormatted, $endFormatted, $sevenDaysAgo, $yestDays) {
    $query->where('DrDate', $sevenDaysAgo->format('n-j-Y'))
        ->where('result', '!=', '');
    if (isset($startFormatted)) {

        if ($startFormatted > $endFormatted) {

            $query->whereBetween('DrTime', [$startFormatted, '23:59:59']);
        } else {
            $query->whereBetween('DrTime', [$startFormatted, $endFormatted]);
        }
    }
})
->orWhere(function ($query) use ($startFormatted, $endFormatted, $sevenDaysAgo, $yestDays) {
    $query->where('DrDate', $yestDays->format('n-j-Y'))
        ->where('result', '!=', '');
        if (isset($startFormatted)) {

            if ($startFormatted > $endFormatted) {

                $query->whereBetween('DrTime', [$startFormatted, '23:59:59']);
            } else {
                $query->whereBetween('DrTime', [$startFormatted, $endFormatted]);
            }
        }
})
->get()->toArray();





// dd($stocks);
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
