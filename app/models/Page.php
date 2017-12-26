<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Language;
use App;

class Page extends Model
{
	protected $fillable = [
        'order', 'content', 'd'
    ];
    public function printName(){
        try{
            $result = "";
        	$content = json_decode($this->content);
        	if($content!==true){
                $found = false;
        		foreach($content->presentations[0]->titles as $pageTitle){
        			if($pageTitle->languageCode===App::getLocale() and $pageTitle->value!==''){
        				$result = $pageTitle->value;
                        $found = true;
        				break;
        			}
        		}
                if($found==false){
                    foreach($content->presentations[0]->titles as $pageTitle){
                        if($pageTitle->languageCode==="ru"){
                            $result = $pageTitle->value;
                            $found = true;
                            break;
                        }
                    }
                }
        	}
            return $result;
        }catch(\Exception $e){
            return $this->order;
        }
    }
    public function printNameByIndex($index){
        try{
            $result = "";
            $content = json_decode($this->content);
            if($content!==true){
                $found = false;
                foreach($content->presentations[$index]->titles as $pageTitle){
                    if($pageTitle->languageCode===App::getLocale() and $pageTitle->value!==''){
                        $result = $pageTitle->value;
                        $found = true;
                        break;
                    }
                }
                if($found==false){
                    foreach($content->presentations[$index]->titles as $pageTitle){
                        if($pageTitle->languageCode==="ru"){
                            $result = $pageTitle->value;
                            $found = true;
                            break;
                        }
                    }
                }
            }
            return $result;
        }catch(\Exception $e){
            return $this->order;
        }
    }
    public function printId(){
        try{
            $result = "";
            $content = json_decode($this->content);
            if($content!==true){
                $result = explode("#",$content->presentations[0]->id)[1];
            }
            return $result;
        }catch(\Exception $e){
            return $this->order;
        }
    }
    public function printActiveness($id){
        try{
            $result = "";
            $content = json_decode($this->content);
            if($content!==true){
                if(explode("#",$content->presentations[0]->id)[1]===$id){
                    $result = "active";
                }
            }
            return $result;
        }catch(\Exception $e){
            return $this->order;
        }
    }
}