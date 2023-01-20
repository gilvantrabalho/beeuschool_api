<?php

namespace App\Http\Controllers;

use App\Models\OneHundredTexts;
use Illuminate\Http\Request;

class OneHundredTextsController extends Controller
{
    public function index()
    {
        return response()->json([
            'texts' => OneHundredTexts::all()
        ]);
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
     * @param  \App\Models\OneHundredTexts  $oneHundredTexts
     * @return \Illuminate\Http\Response
     */
    public function show(OneHundredTexts $oneHundredTexts)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\OneHundredTexts  $oneHundredTexts
     * @return \Illuminate\Http\Response
     */
    public function edit(OneHundredTexts $oneHundredTexts)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\OneHundredTexts  $oneHundredTexts
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OneHundredTexts $oneHundredTexts)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\OneHundredTexts  $oneHundredTexts
     * @return \Illuminate\Http\Response
     */
    public function destroy(OneHundredTexts $oneHundredTexts)
    {
        //
    }
}
