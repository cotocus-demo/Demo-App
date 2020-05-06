<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

use DB;
use Mail;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use App\Mail\UserActivationLinkMail;

use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    public function register(Request $request)
    {
        log::info("i am inside RegisterController->register()");
        // $this->validator($request->all())->validate();

        // event(new Registered($user = $this->create($request->all())));

        // $this->guard()->login($user);

        // return $this->registered($request, $user)
        //                 ?: redirect($this->redirectPath());

        $input = $request->all();
        $validator = $this->validator($input);

        if ($validator->passes()){
            $user = $this->create($input)->toArray(); 
            // $user will have this value after execution of line:92  => ['id'=>'1', name' => 'Deepak','email' => 'deepak@gmail.com','password' => 'dk123']

            $user['link'] = str_random(30);
            // $user will have this value after execution of line:92  => ['id'=>'1', name' => 'Deepak','email' => 'deepak@gmail.com','password' => 'dk123', 'link' => 'mhvejwfkwjnfejhwjdwf']

            $emailParams = new \stdClass(); //need to be explored
            $emailParams->usersEmail = $user['email'];
            $emailParams->usersName = $user['name'];
            $emailParams->link = $user['link'];
            $emailParams->subject = "Click activation code to activate account";



            DB::table('users_activations')->insert(['id_user' => $user['id'], 'token' => $user['link']]);

            log::info("i am inside RegisterController->register()->before mail function");

            Mail::to($emailParams->usersEmail)->send(new UserActivationLinkMail($emailParams));

            log::info("i am inside RegisterController->register()->after mail function");

            

            return redirect()->to('login')->with('Success', "Activation code sent to your email! ");
            // here i am going to redirect to an url '/login' with a variable 'Success' which is having a message 

        }
        return back()->with('Error', $validator->errors());
    }

    public function userActivation($token) {
        $check = DB::table('users_activations')->Where('token', $token)->first();
        if (!is_null($check)) {
            $user = User::find($check->id_user);
            if ($user->is_activated == 1){
                return redirect()->to('login')->with('Success', "User is already activated");
            }

            $user->update(['is_activated' => 1]);
            DB::table('users_activations')->where('token', $token)->delete();

            return redirect()->to('password/reset')->with('Success', "user activated successfully");
           
        }

        return redirect()->to('login')->with('warning', "your token is invalid");
       
    }


}
