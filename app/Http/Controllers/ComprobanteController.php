<?php

namespace App\Http\Controllers;

use App\Models\Comprobante;
use App\Models\Reserva;
use App\Models\Cancha;
use Illuminate\Http\Request;
use App\Http\Requests\StorecomprobanteRequest;
use App\Http\Requests\UpdatecomprobanteRequest;

class ComprobanteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            $comprobantes = Comprobante::all();
            return response()->json([
                'comprobantes'=>$comprobantes
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
    public function comprobanteConFiltro(Request $request)
    {
        $entradas = $request->all();
        try{
            if($entradas['sortDesc']){
                $comprobantes = Comprobante::OrderBy($entradas['sortBy'], 'desc')->paginate($perPage = $entradas['perPage'], $columns = ['*'], $pageName = 'page', $page = $entradas['currentPage']);
            }else{
                $comprobantes = Comprobante::OrderBy($entradas['sortBy'], 'asc')->paginate($perPage = $entradas['perPage'], $columns = ['*'], $pageName = 'page', $page = $entradas['currentPage']);
            }
            foreach($comprobantes as $comprobante){
                $comprobante->usuario = $comprobante->getUsuario->fullname;
                $comprobante->estado = $comprobante->getEstado->descripcion;
            }
            return response()->json([ $comprobantes
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
     * @param  \App\Http\Requests\StorecomprobanteRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorecomprobanteRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\comprobante  $comprobante
     * @return \Illuminate\Http\Response
     */
    public function show(comprobante $comprobante)
    {
        $comprobante->getUsuario;
        $comprobante->getEstado;
        $payment = null;
        return response()->json([ $comprobante, $payment
            ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\comprobante  $comprobante
     * @return \Illuminate\Http\Response
     */
    public function edit(comprobante $comprobante)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatecomprobanteRequest  $request
     * @param  \App\Models\comprobante  $comprobante
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatecomprobanteRequest $request, comprobante $comprobante)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\comprobante  $comprobante
     * @return \Illuminate\Http\Response
     */
    public function destroy(comprobante $comprobante)
    {
        //
    }
}
