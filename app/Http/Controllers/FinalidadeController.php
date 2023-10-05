<?php

namespace App\Http\Controllers;

use App\Models\Finalidade;
use Illuminate\Http\Request;

class FinalidadeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('settings.finalidades.index');
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
     * @param  \App\Models\Finalidade  $finalidade
     * @return \Illuminate\Http\Response
     */
    public function show(Finalidade $finalidade)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Finalidade  $finalidade
     * @return \Illuminate\Http\Response
     */
    public function edit(Finalidade $finalidade)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Finalidade  $finalidade
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Finalidade $finalidade)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Finalidade  $finalidade
     * @return \Illuminate\Http\Response
     */
    public function destroy(Finalidade $finalidade)
    {
        //
    }
}
