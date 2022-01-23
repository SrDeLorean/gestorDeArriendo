<?php

namespace App\Http\Controllers;

use App\Models\reserva;
use Illuminate\Http\Request;
use App\Http\Requests\StorereservaRequest;
use App\Http\Requests\UpdatereservaRequest;

class ReservaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            $reservas = Reserva::all();
            return response()->json([
                'reservas'=>$reservas
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function reservaPorDia(Request $request)
    {
        $entradas = $request->all();
        try{
            $reservas = Reserva::where('dia', $entradas['dia'])->orderBy('idHorario')->orderBy('idCancha')->get();
            return response()->json([
                'reservas'=>$reservas
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
     * @param  \App\Http\Requests\StorereservaRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorereservaRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\reserva  $reserva
     * @return \Illuminate\Http\Response
     */
    public function show(reserva $reserva)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\reserva  $reserva
     * @return \Illuminate\Http\Response
     */
    public function edit(reserva $reserva)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatereservaRequest  $request
     * @param  \App\Models\reserva  $reserva
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatereservaRequest $request, reserva $reserva)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\reserva  $reserva
     * @return \Illuminate\Http\Response
     */
    public function destroy(reserva $reserva)
    {
        //
    }
}
