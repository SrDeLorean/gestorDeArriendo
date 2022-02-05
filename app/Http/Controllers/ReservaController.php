<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\Horario;
use App\Models\Comprobante;
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function reservaDisponible(Request $request)
    {
        $entradas = $request->all();
        try{
            $reservas = [];
            $reservas['r60'] = $reserva = Reserva::where('dia', $entradas['dia'])->where('idCancha', $entradas['idCancha'])->where('idHorario', $entradas['idHorario']+1)->get();
            $reservas['r90'] = $reserva = Reserva::where('dia', $entradas['dia'])->where('idCancha', $entradas['idCancha'])->where('idHorario', $entradas['idHorario']+2)->get();
            $reservas['r120'] = $reserva = Reserva::where('dia', $entradas['dia'])->where('idCancha', $entradas['idCancha'])->where('idHorario', $entradas['idHorario']+3)->get();
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
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorereservaRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function reservasConFiltro(Request $request)
    {
        $entradas = $request->all();
        try{
            if($entradas['sortDesc']){
                $reservas = Reserva::OrderBy($entradas['sortBy'], 'desc')->paginate($perPage = $entradas['perPage'], $columns = ['*'], $pageName = 'page', $page = $entradas['currentPage']);
            }else{
                $reservas = Reserva::OrderBy($entradas['sortBy'], 'asc')->paginate($perPage = $entradas['perPage'], $columns = ['*'], $pageName = 'page', $page = $entradas['currentPage']);
            }
            return response()->json([ $reservas
            ], 200);
        } catch(\Illuminate\Database\QueryException $ex){
            return response()->json([
                'success' => false,
                'code' => 101,
                'message' => 'Error al solicitar peticion a la base de datos',
                'data' => ['error'=>$entradas]
            ], 409);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorereservaRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $entradas = $request->all();
        try{
            $total = 10000*$entradas['bloque'];
            $inicio = Horario::where('id', $entradas['idHorario'])->first();
            $termino = Horario::where('id', $entradas['idHorario']+$entradas['bloque'])->first();
            $comprobante = Comprobante::create([
                'idUsuario' => $entradas['idUsuario'],
                'dia' => $entradas['dia'],
                'inicio' => $inicio->descripcion,
                'termino' => $termino->descripcion,
                'bloques' => $entradas['bloque'],
                'total' => $total,
                'idEstado' => 1
            ]);
            for($i=0; $i < $entradas['bloque']; $i++){
                Reserva::create([
                    'dia' => $entradas['dia'],
                    'idUsuario' => $entradas['idUsuario'],
                    'idCancha' => $entradas['idCancha'],
                    'idHorario' => $entradas['idHorario']+$i,
                    'idComprobante' => $comprobante['id']
                ]);
            }
            return response()->json([
                '$comprobante'=>$comprobante
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
