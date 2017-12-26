<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App;

class Language extends Model
{
    protected $fillable = [
        'name', 'code', 'order',
    ];
    public function printActiveness(){
    	if($this->code===App::getLocale()){
    		return "active";
    	}else
    		return "";
    }
}
