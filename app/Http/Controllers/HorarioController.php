<?php

namespace App\Http\Controllers;

use App\Models\Horario;
use App\Http\Requests\StorehorarioRequest;
use App\Http\Requests\UpdatehorarioRequest;

class HorarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            $horarios = Horario::all();
            return response()->json([
                'horarios'=>$horarios
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
     * @param  \App\Http\Requests\StorehorarioRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorehorarioRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\horario  $horario
     * @return \Illuminate\Http\Response
     */
    public function show(horario $horario)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\horario  $horario
     * @return \Illuminate\Http\Response
     */
    public function edit(horario $horario)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatehorarioRequest  $request
     * @param  \App\Models\horario  $horario
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatehorarioRequest $request, horario $horario)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\horario  $horario
     * @return \Illuminate\Http\Response
     */
    public function destroy(horario $horario)
    {
        //
    }
}
