<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\User;
use App\Role;
use App\Company;
use App\Page;
use App\Language;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

class PagesController extends Controller{
  public function __construct()
  {
    $this->middleware('auth');
  }
  public function siteContent(){
    $company = Company::firstOrFail();
    $languages = Language::orderBy('order','asc')->get();
    return view('pages.site-content',['company'=>$company,'languages'=>$languages]);
  }
  public function saveContent(Request $request){
    try{
      if(\Entrust::can('manage_content')){
        $company = Company::firstOrFail();
        $company->content = $request->getContent();
        $company->save();
        return "ok";
      }else{
        return "security exception";
      }
    }catch(\Exception $e){
      return "exception";
    }
  }
  public function index($id){
    $page = Page::findOrFail($id);
    $languages = Language::orderBy('order','asc')->get();
    return view('pages.page-content',['page'=>$page,'languages'=>$languages]);
  }
  public function pageSave(Request $request,$id){
    try{
      if(\Entrust::can('manage_content')){
        $page = Page::findOrFail($id);
        $page->content = $request->getContent();
        $page->save();
        return "ok";
      }else{
        return "security exception";
      }
    }catch(\Exception $e){
      return "exception";
    }
  }
}