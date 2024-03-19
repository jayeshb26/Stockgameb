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
    public function index()
    {
        $currentDateTime = Carbon::now();

        // Get the current time
        $currentTime = $currentDateTime->format('H:i:s');

        // Subtract 7 days from the current date and time
        $sevenDaysAgo = $currentDateTime->subDays(7);

        // Get the time 1 hour ago
        $oneHourAgo = $currentDateTime->copy()->subHour();

        // Fetch data for the last 7 days and the last hour
        $stocks = Winresults::where(function($query) use ($oneHourAgo, $currentDateTime) {
                                $query->where(function($q) use ($oneHourAgo, $currentDateTime) {
                                    $q->where('DrTime', '>=', $oneHourAgo->format('h:i:s'))
                                      ->where('DrTime', '<=', $currentDateTime->format('h:i:s'));
                                })
                                ->orWhere('DrDate', '>=', $currentDateTime->subDays(7)->format('n-j-Y'));
                            })
                            ->orderBy('DrDate', 'asc')
                            ->orderBy('DrTime', 'desc')
                            ->paginate(50);


dd($stocks);
return response()->json();
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
