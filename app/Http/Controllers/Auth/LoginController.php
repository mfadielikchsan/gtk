<?php

namespace App\Http\Controllers\Auth;


use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->login, 'password' => $request->password])) {
            // Authentication passed...
            return redirect()->intended(route("dashboard"));
        }else{
            Session::flash("flash_notification", [
                "level" => "danger",
                "message" => "Kata Sandi atau Password Salah."
            ]);
            return redirect("/");
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect("/");
    }

    public function createRegister(Request $request)
    {
        $array = [ 
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'created_at' =>  Carbon::now(new \DateTimeZone('Asia/Jakarta'))
        ];
        
        try {
            DB::beginTransaction();
            $cek = DB::connection("pgsql")->table('users')->whereRaw("email = '$request->email'")->first();
            if($cek == null) {
                DB::connection("pgsql")->table('users')->insert($array);
                DB::commit();
                Session::flash("flash_notification", [
                    "level" => "success",
                    "message" => "Register Berhasil."
                ]);
                return redirect("/");
            }else{
                Session::flash("flash_notification", [
                    "level" => "danger",
                    "message" => "Email sudah digunakan."
                ]);
                return redirect("/register");
            }  
        } catch (\Throwable $th) {
            DB::rollback();
            dd($th);
        }

        
    }
}
