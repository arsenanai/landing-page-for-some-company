<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\User;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function settings(){
    	$user = Auth::user();
        return view('auth.profile',compact('user'));
    }
    public function profileSave(Request $request){
        $user = Auth::user();
    	$this->validate($request, [
	        'name' => 'required|max:255',
            'email' => 'required|email|max:255',
	    ]);
        if(User::where("email",$request->email)->firstOrFail()->id!=$user->id){
            $request->session()->flash('success', __('Email уже использован'));
            return Redirect::back();
        }
	    $user->name = $request->name;
	    $user->email = $request->email;
	    $user->save();
	    $request->session()->flash('success', __('Changes saved successfully'));
    	return Redirect::back();
    }
    public function passwordReset(Request $request){
        $this->validate($request, [
            'old-password' => 'required',
            'password' => 'required|confirmed',
        ]);
        $user = Auth::user();
        if($user->password===bcrypt($request->password)){
            $request->session()->flash('warning', __('Current password is wrong'));
            return Redirect::back();
        }
        $user->password = bcrypt($request->password);
        $user->save();
        $request->session()->flash('success', __('Password changed successfully'));
        return Redirect::back();
    }
}
