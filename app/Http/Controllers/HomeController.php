<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\User;
use App;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(){
        if(\Entrust::hasRole('admin')){
            return redirect()->route('editors',['sz'=>20,'sr'=>'created_at','o'=>'desc','f'=>'%23%23','sc'=>'name']);
        }else if(\Entrust::hasRole('editor')){
            return redirect()->route('news-page',['category'=>'all','sz'=>20,'sr'=>'created_at','o'=>'desc','filter'=>'%23%23']);
        }
        return redirect()->route('login');
    }
    public function languages(){
        $languages = Language::orderBy('order','asc')->get();
        return compact($languages);
    }
}