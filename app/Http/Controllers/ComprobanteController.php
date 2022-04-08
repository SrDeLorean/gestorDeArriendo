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
        $token = null;
        if($comprobante->getEstado->descripcion == "Reservada"){
            $payment = $this->methods( $comprobante->id,  $comprobante->total);
            $token = null;
        }else{
            $payment = "aaaaaaa";
        }
        return response()->json([ $comprobante, $payment, $token
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

    function get_ws($data,$method,$type,$endpoint){
        $curl = curl_init();
        if($type=='live'){
            $TbkApiKeyId='597043815471';
            $TbkApiKeySecret='137bb6c6cdad1d54ca3861c5a15f7491';
            $url="https://webpay3g.transbank.cl".$endpoint;//Live
        }else{
            $TbkApiKeyId='597055555532';
            $TbkApiKeySecret='579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C';
            $url="https://webpay3gint.transbank.cl".$endpoint;//Testing
        }
        curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $data,
        CURLOPT_HTTPHEADER => array(
            'Tbk-Api-Key-Id: '.$TbkApiKeyId.'',
            'Tbk-Api-Key-Secret: '.$TbkApiKeySecret.'',
            'Content-Type: application/json'
        ),
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);
        //echo $response;
        return json_decode($response);
    }

    function methods($buy_order, $monto){

        $baseurl = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
        $url="https://webpay3g.transbank.cl/";//Live
        $url="https://webpay3gint.transbank.cl/";//Testing
        $post_array = false;
        $session_id=rand();
        $amount=$monto;
        $return_url = "http://127.0.0.1:8000/api/webpayplus/getStatus";
        //$return_url = 'http://localhost/webpay-plus-api-rest/ok.php';
        $type="sandbox";
        $data='{
                "buy_order": "'.$buy_order.'",
                "session_id": "'.$session_id.'",
                "amount": '.$amount.',
                "return_url": "'.$return_url.'"
                }';
        $method='POST';
        $endpoint='/rswebpaytransaction/api/webpay/v1.2//transactions';
        
        $response = $this->get_ws($data,$method,$type,$endpoint);
        $url_tbk = $response->url;
        $token = $response->token;
        $submit='Continuar!';
        $data = [
            "token" => $token,
            "url" => $url_tbk
            ];
        
        return $data;

                        

          
    }
    function getResult($token){      
       
        $data='';
        $method='PUT';
        $type='sandbox';
        $endpoint='/rswebpaytransaction/api/webpay/v1.2//transactions/'.$token;
        
        $response = $this->get_ws($data,$method,$type,$endpoint);
        $comprobante = Comprobante::where('id',$response->buy_order)->first();
        if($response->vci=="TSY"){
            $comprobante->idEstado = 2;
            $comprobante->save();
            /** 
            $data = array(
                'fecha'=> $reserva->fecha,
                'hora'=> $reserva->inicio,
                'servicio'=> $reserva->getServicio->titulo,
                'valor'=> $reserva->getServicio->precio,
                'abono'=> $reserva->abono,
                'estado'=> $reserva->getEstadoreserva->estado,
            );
            try{
                Mail::send('mail', $data, function($message) use ($reserva){
                    $message->to($reserva->email, $reserva->nombre)->subject('Registro de reserva');
                    $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
                });
            } catch(\Exception $e) {
                return redirect('http://127.0.0.1:8000/api/reserva/show/'.$response->buy_order);
            }
            */
            
        
        }else {
            //aca iria si falla si quiere hacer algo
        }
        return redirect('http://127.0.0.1:8000/reserva/show/'.$response->buy_order);
    }

    function getStatus(Request $request){      
        if($request->input('TBK_TOKEN')!=null){
            $token = $request->input('TBK_TOKEN');
            $data='';
            $method='GET';
            $type='sandbox';
            $endpoint='/rswebpaytransaction/api/webpay/v1.2//transactions/'.$token;
            
            $response = $this->get_ws($data,$method,$type,$endpoint);
            /** 
            $data = array(
                'fecha'=> $reserva->fecha,
                'hora'=> $reserva->inicio,
                'servicio'=> $reserva->getServicio->titulo,
                'valor'=> $reserva->getServicio->precio,
                'abono'=> $reserva->abono,
                'estado'=> $reserva->getEstadoreserva->estado,
            );
            try{
                Mail::send('mail', $data, function($message) use ($reserva){
                    $message->to($reserva->email,$reserva->nombre)->subject('Cancelacion de reserva');
                    $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
                });
            } catch(\Exception $e) {
                return redirect('http://127.0.0.1:8000/api/reserva/show/14'.$response->buy_order);
            }
            */ 
            return redirect('http://127.0.0.1:8000/reserva/show/'.$response->buy_order);
            
        }
        
        return $this->getResult($request->input('token_ws'));
    }

    function refund($token, $buy_order){
        $reserva = Reserva::where('buy_order', $buy_order)->first();
        $data='{
            "amount": $reserva->abono
            }';
        $method='POST';
        $type='sandbox';
        $endpoint='/rswebpaytransaction/api/webpay/v1.2//transactions/'.$token.'/refunds';
        
        $response = $this->get_ws($data,$method,$type,$endpoint);
        $data = [
            "response"=> $response,
            "amount"=>  $reserva->abono,
            "token" => $token,
            "buy_order" =>$buy_order
            ];
        return $data;
     
    }
}
