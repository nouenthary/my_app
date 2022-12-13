<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\UserDetails;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Validator;


class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function login(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'password' => 'required|string|min:6',
        ]);

        $credentials = request(['name', 'password']);

        $token = auth()->guard('api')->attempt($credentials);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (!$token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $this->store_access_token($token, $request->get('device'));

        return $this->createNewToken($token);
    }

    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100|unique:users',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|confirmed|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }
        $user = User::create(array_merge(
            $validator->validated(),
            ['password' => bcrypt($request->password)]
        ));
        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user
        ], 201);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        $user_id = auth('api')->user()->id;
        DB::delete("DELETE FROM personal_access_tokens WHERE tokenable_id = $user_id");
        auth()->logout();
        return response()->json(['message' => 'User successfully signed out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->createNewToken(auth()->refresh());
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile()
    {
        $user = DB::table('tec_users')
            ->join('tec_permission','tec_users.id','=','tec_permission.user_id')
            ->where('id', auth()
            ->user()
            ->user_id)
            ->first();
        return response()->json($user);
    }

    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token)
    {

        $user = [];

        $user_id = auth('api')->user()->user_id;

        $user_details = UserDetails::where('id', $user_id)->first();

        $user['id'] = $user_id;
        $user['name'] = $user_details->username;
        $user['salt'] = $user_details->salt;
        $user['first_name'] = $user_details->first_name;
        $user['last_name'] = $user_details->last_name;
        $user['phone'] = $user_details->phone;
        $user['avatar'] = $user_details->avatar;
        $user['gender'] = $user_details->gender;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'user' => $user,
        ]);
    }

    private function store_access_token($token, $device = 'android')
    {
        $user_id = auth('api')->user()->id;
        $personal_access_tokens = DB::table('personal_access_tokens')->where('tokenable_id', $user_id)->first();

        if ($personal_access_tokens == '') {
            DB::table('personal_access_tokens')->insert([
                'tokenable_type' => 'App\Models\User',
                'tokenable_id' => $user_id,
                'name' => $device,
                'token' => $token,
                'abilities' => '["*"]',
                //'last_used_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
    }
}
