<?php   
namespace App\Http\Controllers\Auth;

    use App\Models\User;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Hash;
    use Illuminate\Support\Facades\Validator;
    use JWTAuth;
    use Tymon\JWTAuth\Exceptions\JWTException;
    use App\Http\Controllers\Controller;

class UserController extends Controller
{
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