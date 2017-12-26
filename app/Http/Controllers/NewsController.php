<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Language;
use App\News;
use App\File;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class NewsController extends Controller{
  public function __construct(){
    $this->middleware('auth');
  }
  public function index($category, $sz, $sr, $o, $f){
    $languages = Language::orderBy('order','asc')->get();
    $query1 = News::whereNotNull('content');
    if($category!=='all'){
      $query1 = $query1->where('content->category',$category);
    }
    if($f!=='##'){
      $query1 = $query1->where('content->title_ru', 'like', '%'.$f.'%')
          ->orWhere('content->body_ru', 'like', '%'.$f.'%');
    }
    if($sr==='created_at') {
      $query1 = $query1->orderBy($sr, $o);
    }else if($sr==='title'){
      $query1 = $query1->orderBy('content->title_ru', $o);
    }
      $news = $query1->paginate($sz);
    return view('news.index',['category'=>$category,'news'=>$news,'sz'=>$sz,'sr'=>$sr,'o'=>$o,'f'=>$f,
      'languages'=>$languages]);
  }
  public function add(){
    $languages = Language::orderBy('order','asc')->get();
    return view('news.add',compact('languages'));
  }
  public function edit($id){
    $languages = Language::orderBy('order','asc')->get();
    $new = News::findOrFail($id);
    return view('news.edit',['new'=>$new,'languages'=>$languages]);
  }
  public function save(Request $request){
    try{
      if(\Entrust::can('manage_content')){
        $news = new News();
        $news->content = $request->getContent();
        $news->save();
        return "success";
      }else{
        return "security exception";
      }
    }catch(\Exception $e){
      return "exception";
    }
  }
  public function update($id, Request $request){
    $result = "";
    try{
      if(\Entrust::can('manage_content')){
        $news = News::findOrFail($id);
        //delete old file if free
        try{
          foreach(json_decode($news->content)->fields as $field) {
            if ($field->id === 'field#file' and $field->value===''){
              break;
            }else if($field->id === 'field#file' and $field->value!==''){
              if(File::where('path',$field->value)->count()>0 and
                News::where('id','!=',$id)->where('content','like','%'.$field->value.'%')->count()==0){
                $file = File::where('path',$field->value)->firstOrFail();
                Storage::disk('images')->delete($file->filename);
                $file->delete();
                $result=$result."image file search and deleted;";
              }
              break;
            }
          }
        }catch(\Exception $e){
          $result=$result."image file search and delete failed;";
        }
        //end deletion
        $news->content = $request->getContent();
        $news->save();
        $result = "success; ".$result;
      }else{
        $result =  "security exception";
      }
    }catch(\Exception $e){
      $result = "exception";
    }
    return $result;
  }
  public function delete($id, Request $request){
    try {
      if(\Entrust::can('manage_content')) {
        $toDelete = News::findOrFail($id);
        //delete file todo
        $result="";
        try{
        foreach(json_decode($toDelete->content)->fields as $field) {
          if ($field->id === 'field#file' and $field->value===''){
            break;
          }else if($field->id === 'field#file' and $field->value!==''){
            if(File::where('path',$field->value)->count()>0 and
              News::where('id','!=',$toDelete->id)->where('content','like','%'.$field->value.'%')->count()==0){
              $file = File::where('path',$field->value)->firstOrFail();
              Storage::disk('images')->delete($file->filename);
              $file->delete();
              $result = __("Рисунки удалены");
            }
            break;
          }
        }
        }catch(\Exception $e){
          $result = __("Рисунки удалить не удалось");
        }
        //delete file todo end
        $toDelete->delete();
        $request->session()->flash('success', __('Новость успешно удалена'.'. '.$result));
        return Redirect::back();
      }else{
        $request->session()->flash('warning', __('У вас нет прав'));
        return Redirect::back();
      }
    }catch(\Exception $e){
      $request->session()->flash('warning', __('Ошибка на сервере'));
      return Redirect::back();
    }
  }
  public function check($id,$slug){
    if(News::where('id','!=',$id)->where("content",'like','%'.$slug.'%')->count()>0){
      return 'not valid';
    }else
      return 'valid';
  }
}
