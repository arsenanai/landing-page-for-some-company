<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;
use Illuminate\Support\Facades\Route;
use App\Page;
use App\Company;
use App\Language;
use App\News;
use Illuminate\Support\Facades\Blade;

class LandingController extends Controller
{
    public function main($lang){
        if($lang==='en' || $lang==='ru' || $lang==='kk'){
            $id = "main";
            App::setLocale($lang);
            $co = Company::firstOrFail();
            $cr = Page::where('content','like','%pres#'.$id.'%')->firstOrFail();
            $co->d = json_decode($co->content);
            $cr->d = json_decode($cr->content);
            $ps = Page::where('order','!=','1')->orderBy('order','asc')->get();
            $ls = Language::orderBy('order','asc')->get();
            $nm = $cr->printName();
            //if($id==='main'){
                $wp = Page::where('content','like','%pres#main%')->firstOrFail();
                $au = Page::where('content','like','%pres#about-us%')->firstOrFail();
                $ss = Page::where('content','like','%pres#services%')->firstOrFail();
                $cs = Page::where('content','like','%pres#contacts%')->firstOrFail();
                $pc = Page::where('content','like','%pres#press-center%')->firstOrFail();
                $wp->d = json_decode($wp->content);
                $au->d = json_decode($au->content);
                $ss->d = json_decode($ss->content);
                $cs->d = json_decode($cs->content);
                $pc->d = json_decode($pc->content);
                $si = News::whereNotNull('content')->where('content->category','smi')->orderBy('created_at','desc')->get();
                $mn = News::whereNotNull('content')->where('content->category','main')->orderBy('created_at','desc')->paginate(3);
                foreach($si as $post){
                    $post->d = json_decode($post->content);
                }
                foreach($mn as $post){
                    $post->d = json_decode($post->content);
                }
                return view('landing.welcome',['current'=>$nm,'company'=>$co,'id'=>$id,'pages'=>$ps,'languages'=>$ls,'welcome'=>$wp,'about'=>$au,'services'=>$ss,'contacts'=>$cs,'news'=>$pc,'smi'=>$si,'main'=>$mn]);
            //}
        }else
            abort(404);
    }
	public function page($lang, $id){
        if(($lang==='en' || $lang==='ru' || $lang==='kk') && 
            ($id==='about-us' || $id==='services' || $id==='press-center' || $id==='contacts')){
            App::setLocale($lang);
            $co = Company::firstOrFail();
            $cr = Page::where('content','like','%pres#'.$id.'%')->firstOrFail();
            $co->d = json_decode($co->content);
            $cr->d = json_decode($cr->content);
            $ps = Page::where('order','!=','1')->orderBy('order','asc')->get();
            $ls = Language::orderBy('order','asc')->get();
            $nm = $cr->printName();
            if($id==='about-us')
                return view('landing.about',['current'=>$nm,'about'=>$cr,'company'=>$co,'id'=>$id,'pages'=>$ps,'languages'=>$ls]);
            else if($id==='services')
                return view('landing.services',['current'=>$nm,'services'=>$cr,'company'=>$co,'id'=>$id,'pages'=>$ps,'languages'=>$ls]);
            else if($id==='press-center'){
                $smi = News::whereNotNull('content')->where('content->category','smi')->orderBy('created_at','desc')->get();
                $main = News::whereNotNull('content')->where('content->category','main')->orderBy('created_at','desc')->paginate(3);
                foreach($smi as $post){
                    $post->d = json_decode($post->content);
                }
                foreach($main as $post){
                    $post->d = json_decode($post->content);
                }
                return view('landing.press',['current'=>$nm,'company'=>$co,'id'=>$id,'pages'=>$ps,'languages'=>$ls,'smi'=>$smi,'main'=>$main,'news'=>$cr]);
            }else if($id==='contacts')
                return view('landing.contacts',['current'=>$nm,'contacts'=>$cr,'company'=>$co,'id'=>$id,'pages'=>$ps,'languages'=>$ls]);
        }else
            abort(404);
	}

    public function service($lang, $index){
        App::setLocale($lang);
        $co = Company::firstOrFail();
        $cr = Page::where('content','like','%pres#services%')->firstOrFail();
        $co->d = json_decode($co->content);
        $cr->d = json_decode($cr->content);
        $ps = Page::where('order','!=','1')->orderBy('order','asc')->get();
        $ls = Language::orderBy('order','asc')->get();
        $nm = $cr->printName();
        $res=0;
        for($i=19; $i<19+8; $i++){
            if($cr->d->presentations[0]->fields[$i]->value === $index){
                $res = $i-19;
                break;
            }
        }
        return view('landing.service',['current'=>$nm,'services'=>$cr,'company'=>$co,'id'=>'services','index'=>$res,'pages'=>$ps,'languages'=>$ls]);
    }

    public function post($lang, $pid){
        App::setLocale($lang);
        $post = News::where("content",'like','%'.$pid.'%')->firstOrFail();
        $post->d = json_decode($post->content);
        $co = Company::firstOrFail();
        $cr = Page::where('content','like','%pres#press-center%')->firstOrFail();
        $co->d = json_decode($co->content);
        $cr->d = json_decode($cr->content);
        $ps = Page::where('order','!=','1')->orderBy('order','asc')->get();
        $ls = Language::orderBy('order','asc')->get();
        $nm = $post->d->title_ru;
        $ot = News::whereNotNull('content')->where('content->category',$post->d->category)->where("id","!=",$pid)->orderBy('created_at','desc')->limit(3)->get();
        foreach($ot as $pos){
            $pos->d = json_decode($pos->content);
        }
        return view('landing.post',['current'=>$nm,'company'=>$co,'pages'=>$ps,'languages'=>$ls,'news'=>$cr,'others'=>$ot,'id'=>'press-center','post'=>$post]);
    }

    /*public function pageResource($id, $lang){
        try{
            if(Page::where('content','like','{"presentations": [{"id": "pres#'.$id.'"%')->count()>0)
                return Page::where('content','like','{"presentations": [{"id": "pres#'.$id.'"%')->firstOrFail()->content;
            else
                return "not found";
        }catch(\Exception $e){
            return "exception";
        }
    }*/
}