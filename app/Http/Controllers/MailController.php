<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactUs;
use GuzzleHttp\Client;

class MailController extends Controller
{
    public function index(){
        return view('mail');
    }
    public function sendMail(){
        try{
            $recaptcha = request()->get("g-recaptcha-response");
            $client = new Client();
            $response = $client->post(
                'https://www.google.com/recaptcha/api/siteverify',
                ['form_params'=>
                    [
                        'secret'=>'6LfQQScUAAAAAJVs3kaBcWO8I875lmad6YhuzCNM',
                        'response'=>$recaptcha
                     ]
                ]
            );
            $body = json_decode((string)$response->getBody());
            if($body->success===true){
            	$this->validate(request(), [
    			    'name' => 'required|min:3',
    			    'phone' => 'required',
    			    'email' => 'required|email',
    			    'msg' => 'required',
    			]);
            	$fio = request()->get("name");
            	$phone = request()->get("phone");
            	$email = request()->get("email");
            	$message = request()->get("msg");
                //if(strpos($fio, ' ') !== false && strlen($fio)>3)
                Mail::to(env('MAIL_USERNAME','contact@cybersec.kz'))
                    ->queue(new ContactUs($fio,$phone,env('MAIL_USERNAME','contact@cybersec.kz'),$email.": ".$message));
                return "ok";
            }else{
                abort(404);
            }
        }catch(Exception $e){
            return "exception";
        }
    }
}