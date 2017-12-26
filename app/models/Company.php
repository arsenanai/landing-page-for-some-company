<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'content','d'
    ];
    public function getShortName(){
    	try{
    		foreach (json_decode($this->content)->companyShortNames as $name) {
    		 	if($name->languageCode===\App::getLocale())
    		 		return $name->value;
    		}
            foreach (json_decode($this->content)->companyShortNames as $name) {
                if($name->languageCode==="ru")
                    return $name->value;
            }
    		return "Cybersec";
    	}catch(\Exception $e){
    		return "App";
    	}
    }
    public function hasSocialLink($social){
        try{
            foreach (json_decode($this->content)->socialNetworks as $s) {
                if($s->name===$social && strlen($s->link)>0)
                    return true;
            }
            return false;
        }catch(\Exception $e){
            return false;
        }
    }
    public function getSocialLink($social){
        try{
            foreach (json_decode($this->content)->socialNetworks as $s) {
                if($s->name===$social)
                    return $s->link;
            }
            return "";
        }catch(\Exception $e){
            return "";
        }
    }
}
