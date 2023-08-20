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
        return view('rates.create');
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
            'position' => 'required|unique:rates,position',
            'value' => 'required'
        ]);

        $rate = new Rate();
        $rate->position = $request->position;
        $rate->value =  $request->value;
        $rate->save();
        session()->flash('success', 'New rate created successfully....');
        return redirect('/rates');
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
