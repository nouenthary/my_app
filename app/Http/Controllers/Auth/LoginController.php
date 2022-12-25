<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        $credentials = [
            'name' => $request['username'],
            'password' => $request['password'],
        ];

        if (Auth::attempt($credentials)) {        
            $token = auth()->guard('api')->attempt($credentials);
            //$this->store_access_token($token, 'web');
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    // private function store_access_token($token, $device = 'android')
    // {
    //     $user_id = auth('api')->user()->id;
    //     $personal_access_tokens = DB::table('personal_access_tokens')->where('tokenable_id', $user_id)->first();

    //     if ($personal_access_tokens == '') {
    //         DB::table('personal_access_tokens')->insert([
    //             'tokenable_type' => 'App\Models\User',
    //             'tokenable_id' => $user_id,
    //             'name' => $device,
    //             'token' => $token,
    //             'abilities' => '["*"]',
    //             //'last_used_at' => date('Y-m-d H:i:s'),
    //             'created_at' => date('Y-m-d H:i:s'),
    //             'updated_at' => date('Y-m-d H:i:s')
    //         ]);
    //     }
    // }
}
