<?php

namespace App\Http\Controllers;

use App\Http\Controllers;
use Mail;
use Illuminate\Http\Request;
use App\Mail\TestEmailSender;
use Illuminate\Support\Facades\Log;

class TestController extends Controller
{
    public $name;
    public $email;

    public function __construct()
    {
           
    }

    public function sendEmail()
    {               
            $this->name = "Deepak";
            $this->email = "deepak.cotocus@gmail.com";
            log::info("I am inside TestController->sendEmail()");
            $emailParams = new \stdClass();
            $emailParams->usersName = $this->name;
            $emailParams->usersEmail = $this->email;
           
            $emailParams->subject = "Testing Email sending feature";

            Mail::to($emailParams->usersEmail)->send(new TestEmailSender($emailParams)); 

    }   

    public function test(){
            log::info("I am inside TestController->test()");
            

           $this->sendEmail();
    }
}

