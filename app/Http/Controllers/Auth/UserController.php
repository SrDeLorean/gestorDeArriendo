<?php   
namespace App\Http\Controllers\Auth;

    use App\Models\User;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Hash;
    use Illuminate\Support\Facades\Validator;
    use JWTAuth;
    use App\Models\Comprobante;
    use Tymon\JWTAuth\Exceptions\JWTException;
    use App\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
{
        try{
            $usuarios = User::withTrashed()->get();
            return response()->json([
                'total' => $usuarios->count(),
                'users'=>$usuarios
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try{
            $credenciales = JWTAuth::parseToken()->authenticate();
            if($credenciales->role=="admin" || $credenciales->role=="desarrollador"){
                $userData = User::withTrashed()->find($id);
            }else{
                $userData = $credenciales;
            }
            $comprobantes = Comprobante::where('idUsuario', $userData->id)->get();
            foreach($comprobantes as $comprobante){
                $comprobante->usuario = $comprobante->getUsuario->fullname;
                $comprobante->estado = $comprobante->getEstado->descripcion;
            }
            return response()->json([
                'userData'=>$userData,
                '$credenciales'=>$credenciales,
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try{
            $userData = User::find($id);
            return response()->json([
                'userData'=>$userData
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function usuariosConFiltro(Request $request)
    {
        $entradas = $request->all();
        try{
            if($entradas['sortDesc']){
                $usuarios = User::withTrashed()->OrderBy($entradas['sortBy'], 'desc')->paginate($perPage = $entradas['perPage'], $columns = ['*'], $pageName = 'page', $page = $entradas['currentPage']);
            }else{
                $usuarios = User::withTrashed()->OrderBy($entradas['sortBy'], 'asc')->paginate($perPage = $entradas['perPage'], $columns = ['*'], $pageName = 'page', $page = $entradas['currentPage']);
            }
            return response()->json([ $usuarios
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'fullname' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|string',
        ]);

        if($validator->fails()){
            return response()->json([
                'success' => false,
                'code' => 2,
                'message' => 'Error en las credenciales',
                'data' => ['error'=>$validator->errors()]
            ], 422);
        }
        try{
            $userData = User::create([
                'fullname' => $request->get('fullname'),
                'email' => $request->get('email'),
                'password' => Hash::make($request->get('password')),
                'role' => $request->get('role'),
            ]);

            $accessToken = JWTAuth::fromUser($userData);

            return response()->json([
                'success' => true,
                'code' => 1,
                'message' => 'Se registro al usuario con exito',
                'data' => $userData
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'fullname' => 'required|string',
            'email' => 'required|string|email',
            'password' => 'required|string|min:6',
            'role' => 'required|string',
        ]);

        if($validator->fails()){
            return response()->json([
                'success' => false,
                'code' => 2,
                'message' => 'Error en las credenciales',
                'data' => ['error'=>$validator->errors()]
            ], 422);
        }
        try{
            $usuario = User::find($id);
            $usuario->fullname = $request->get('fullname');
            $usuario->email = $request->get('email');
            $usuario->role = $request->get('role');
            $usuario->password = bcrypt($request->get('password'));
            $usuario->save();
            return response()->json([
                'success' => true,
                'code' => 1,
                'message' => 'Se registro al usuario con exito',
                'data' => $usuario
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $usuario = User::find($id);
            if($usuario==null){
                return response()->json([
                    'success' => false,
                    'code' => 701,
                    'message' => 'El usuario con el id '.$id.' no existe',
                    'data' => null
                ], 409 );
            }
            $usuario->delete();
            return response()->json([
                'success' => true,
                'code' => 700,
                'message' => "Operacion realizada con exito",
                'data' => ['usuario'=>$usuario]
            ], 200);
        //----- Mecanismos anticaidas y reporte de errores -----
        //catch que se encarga en responder que paso en la sentencia sql
        }catch(\Illuminate\Database\QueryException $ex){ 
            return response()->json([
                'success' => false,
                'code' => 702,
                'message' => "Error: Tenemos problemas con nuestra base de datos, intente mas tarde ",
                'data' => ['error'=>$ex]
            ], 409 );
        }
    }

    /**
     * Metodo que se encarga recuperar un usuario
     * Errores code inician 900
     * @return \Illuminate\Http\Response
     */
    public function restore($id){
        try{
            $usuario=User::onlyTrashed()->where('id',$id)->first();
            if($usuario==null){
                return response()->json([
                    'success' => false,
                    'code' => 901,
                    'message' => "El usuario no se logro recuperar",
                    'data' => ['usuario'=>$usuario]
                ], 409);
            }
            User::onlyTrashed()->where('id',$id)->restore();
            return response()->json([
                'success' => true,
                'code' => 900,
                'message' => "El usuario recupero con exito",
                'data' => ['usuario'=>$usuario]
            ], 200);
        }catch(\Illuminate\Database\QueryException $ex){ 
            return response()->json([
                'success' => false,
                'code' => 902,
                'message' => 'Error al solicitar peticion a la base de datos',
                'data' => ['error'=>$ex]
            ], 409);
        }
    }

    public function authenticate(Request $request)
    {
        $credenciales = $request->only('email', 'password');
        $validator = Validator::make($credenciales, [
            'email' => 'required|email',
            'password' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'code' => 2,
                'message' => 'Error en las credenciales',
                'data' => ['error'=>$validator->errors()]
            ], 422);
        }
        try {
            if (! $accessToken = JWTAuth::attempt($credenciales)) {
                return response()->json([
                    'success' => false,
                    'code' => 3,
                    'message' => 'Usuario no encontrado',
                    'data' => ['error'=>'El usuario con la clave no estan correctos']
                ], 401);
            }
        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'code' => 4,
                'message' => 'Error interno',
                'data' => ['error'=>$ex]
            ], 409);
        }
        $refreshToken = $accessToken;
        $userData = JWTAuth::user();
        if($userData->role == "desarrollador"){
            $a = [
                "action" => 'manage',
                "subject" => 'all',
            ];
            $userData->ability = [
                0 => $a
            ];
        }else if ($userData->role == "admin"){
            $a = [
                "action" => 'read',
                "subject" => 'admin',
            ];
            $userData->ability = [
                0 => $a
            ];
        }else{
            $a = [
                "action" => 'read',
                "subject" => 'client',
            ];
            $userData->ability = [
                0 => $a
            ];
        }
        return response()->json(compact('accessToken', 'userData'));
    }

    public function getAuthenticatedUser()
    {
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                    return response()->json(['user_not_found'], 404);
            }
            } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
                    return response()->json(['token_expired'], $e->getStatusCode());
            } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
                    return response()->json(['token_invalid'], $e->getStatusCode());
            } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
                    return response()->json(['token_absent'], $e->getStatusCode());
            }
            return response()->json(compact('user'));
    }

    public function register(Request $request)
        {
            $validator = Validator::make($request->all(), [
                'fullName' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6',
            ]);

            if($validator->fails()){
                return response()->json([
                    'success' => false,
                    'code' => 2,
                    'message' => 'Error en las credenciales',
                    'data' => ['error'=>$validator->errors()]
                ], 422);
            }

            $userData = User::create([
                'fullName' => $request->get('fullName'),
                'email' => $request->get('email'),
                'password' => Hash::make($request->get('password')),
                'role' => 'client'
            ]);

            $accessToken = JWTAuth::fromUser($userData);
            if($userData->role == "desarrollador"){
                $a = [
                    "action" => 'manage',
                    "subject" => 'all',
                ];
                $userData->ability = [
                    0 => $a
                ];
            }else if ($userData->role == "admin"){
                $a = [
                    "action" => 'read',
                    "subject" => 'admin',
                ];
                $userData->ability = [
                    0 => $a
                ];
            }else{
                $a = [
                    "action" => 'read',
                    "subject" => 'client',
                ];
                $userData->ability = [
                    0 => $a
                ];
            }

            return response()->json(compact('accessToken', 'userData'));
        }

    public function refreshToken()
    {
        $token = JWTAuth::getToken();
        if(!$token){
            throw new BadRequestHtttpException('Token not provided');
        }
        try{
            $token = JWTAuth::refresh($token);
        }catch(TokenInvalidException $e){
            throw new AccessDeniedHttpException('The token is invalid');
        }
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }
}