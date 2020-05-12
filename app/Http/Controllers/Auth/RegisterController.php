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

use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

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
            // 'password' => 'required|string|min:6|confirmed',
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
            'password' => bcrypt(str_random(6)),
        ]);
    }

    public function register(Request $request)
    {
        log::info("i am inside RegisterController->register()");
        //                ?: redirect($this->redirectPath());

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


            //this is another way of inserting
            DB::table('users_activations')->insert(['id_user' => $user['id'], 'token' => $user['link'], 'created_at' => Carbon::now()]);

            log::info("i am inside RegisterController->register()->before mail function");

            Mail::to($emailParams->usersEmail)->send(new UserActivationLinkMail($emailParams));

            // log::info("i am inside RegisterController->register()->after mail function");

            
            // return view('emailActivationLink');
            return redirect()->to('showActivationLinkStatus')->with('Success', "Activation Link sent to your email! ");
            // return redirect()->to('login')->with('Success', "Activation code sent to your email! ");
            // here i am going to redirect to an url '/login' with a variable 'Success' which is having a message 

        }
        return back()->with('Error', $validator->errors());
    }





    public function verifyLinkAndShowActivationForm($token) {
        $check = DB::table('users_activations')->Where('token', $token)->first();
    // select * from users_activations where token=$token

        if (!is_null($check)) {
            $user = User::find($check->id_user);

            $tokenCreatedAt = $check->created_at;
            $currentTime = Carbon::now();
            $diff_in_minutes = $currentTime->diffInMinutes($tokenCreatedAt);

            log::info($tokenCreatedAt);
            log::info($currentTime);
            log::info($diff_in_minutes);

            if($diff_in_minutes <= 60){
                log::info("Hi i am below 1 hr");

                if ($user->is_activated == 1){
                    return redirect()->to('login')->with('info', "User is already activated. Try Login!");
                }else{
                    return view('setPassword')->with(
                        ['email' => $user->email, 'token' => $check->token]
                    );
                }

                
            }
            else {
             DB::table('users_activations')->where('id_user', $check->id)->delete();

             return redirect()->to('showActivationLinkStatus')->with('warning', "Link Expired!");
         }

     }

     return redirect()->to('showActivationLinkStatus')->with('warning', "Link used!!");
 }




    protected function setPassword(Request $request)
    {

        $this->validate($request, [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6',
            ]);

        $password = $request->input('password');
        $email = $request->input('email');
        $token = $request->input('token');

        log::info($password);
        log::info($email);
        log::info($token);

        // $user = User::whereemail($email)->firstOrFail();
 // $user = DB::table('users')
 //        ->where('email', $email)
 //        ->update(['is_activated' => 1, 'password' => bcrypt($password)]);
       DB::table('users')
        ->where('email', $email)
        ->update(['is_activated' => 1, 'password' => bcrypt($password)]);

        DB::table('users_activations')->where('token', $token)->delete();

        return redirect()->to('login')->with('Success', "Password Reset Done!");
    }

    protected function showActivationLinkStatus(){

    Log::info('I am in RegisterController->showActivationLinkStatus()');
    return view('activationLinkStatus');
    }

}