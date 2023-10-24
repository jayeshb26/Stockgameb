<?php

namespace App\Http\Controllers;

use App\Market;
use Illuminate\Http\Request;
use App\User;
use App\Stock;
use Session;

class StocksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $stocks = Stock::get();
        $markets = Market::all()->pluck('market');
        return view('stocks.index', ['data' => $stocks, 'markets' => $markets]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $markets = Market::all()->pluck('market');
        return view('stocks.create', compact('markets'));
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
        $lastStock = Stock::orderBy('number', 'desc')->first();
        $newNmber = $lastStock->number + 1;

        $stock = new Stock();
        $stock->name = $request->name;
        $stock->symbol =  $request->symbol;
        $stock->market = $request->market;
        $stock->status = 1;
        $stock->number = $newNmber;
        // $stock->createdAt = date('n/j/Y, h:i:s A');
        // $stock->updatedAt = date('n/j/Y, h:i:s A');
        // dd($stock);
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
        $stock = Stock::findOrFail($id);
        $markets = Market::all()->pluck('market');

        return view('stocks.create', ['stock' => $stock, 'markets' => $markets]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'symbol' => 'required',
            'name' => 'required',
            'market' => 'required',
        ]);

        $update = Stock::where('_id', $id)->update([
            'name' => $request->name,
            'symbol' => $request->symbol,
            'market' => $request->market,
            'status' => (int) $request->status
            // 'updatedAt' => $request->updatedAt
        ]);

        session()->flash('success', 'Stock updated successfully....');
        return redirect('/stocks');
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

    public function changeStockStatus(Request $request)
    {
        $stockNumers = [];
        foreach ($request->stock_numbers as $key => $value) {
            $stockNumers[] = (int) $value;
        }
        // $stocks = Stock::whereIn('number', $stockNumers)->get([]);
        $res = false;
        if ($request->status == 'active') {
            $res = Stock::whereIn('number', $stockNumers)->update(['status' => 1]);
        } else {
            $res = Stock::whereIn('number', $stockNumers)->update(['status' => 0]);
        }
        return response()->json(true);
    }
}
