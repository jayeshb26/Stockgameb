<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Rate;
use Session;

class RatesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rates = Rate::get();
        return view('rates.index', ['data' => $rates]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('stocks.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $request->validate([
            'symbol' => 'required',
            'name' => 'required|unique:stocks,name',
            'market' => 'required',
        ]);
        // $referral = new \MongoDB\BSON\ObjectID(Session::get('id'));
        $stock = new Stock();
        $stock->name = $request->name;
        $stock->symbol =  $request->symbol;
        $stock->market = $request->market;
        $stock->status = 1;
        // $stock->createdAt = date('n/j/Y, h:i:s A');
        // $stock->updatedAt = date('n/j/Y, h:i:s A');
        $stock->save();
        session()->flash('success', 'New stock created successfully....');
        return redirect('/stocks');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $rate = Rate::findOrFail($id);
        return view('rates.create', ['rate' => $rate]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'position' => 'required',
            'value' => 'required'
        ]);
        
        $update = Rate::where('_id', $id)->update([
            'position' => $request->position,
            'value' => $request->value
        ]);

        session()->flash('success', 'Rates updated successfully....');
        return redirect('/rates');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $update = Stock::where('_id', $id)->delete();
        session()->flash('success', 'Stock deleted successfully....');
        return redirect('/stocks');
    }
}
