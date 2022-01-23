<?php

namespace App\Http\Controllers;

use App\Models\Cancha;
use App\Http\Requests\StorecanchaRequest;
use App\Http\Requests\UpdatecanchaRequest;

class CanchaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            $canchas = Cancha::all();
            return response()->json([
                'canchas'=>$canchas
            ], 200);
        } catch(\Illuminate\Database\QueryException $ex){
            return response()->json([
                'success' => false,
                'code' => 101,
                'message' => 'Error al solicitar peticion a la base de datos',
                'data' => ['error'=>$ex]
            ], 409);
        }
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
     * @param  \App\Http\Requests\StorecanchaRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorecanchaRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\cancha  $cancha
     * @return \Illuminate\Http\Response
     */
    public function show(cancha $cancha)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\cancha  $cancha
     * @return \Illuminate\Http\Response
     */
    public function edit(cancha $cancha)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatecanchaRequest  $request
     * @param  \App\Models\cancha  $cancha
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatecanchaRequest $request, cancha $cancha)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\cancha  $cancha
     * @return \Illuminate\Http\Response
     */
    public function destroy(cancha $cancha)
    {
        //
    }
}
