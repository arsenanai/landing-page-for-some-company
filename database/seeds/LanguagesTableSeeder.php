<?php

use Illuminate\Database\Seeder;
use App\Language;

class LanguagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
		//DB::table('languages')->delete();
		
		$en = new Language();
		$en->name = 'eng';
		$en->code = 'en';
		$en->order = 2;

		$kk = new Language();
		$kk->name = 'қаз';
		$kk->code = 'kk';
		$kk->order = 1;

		$ru = new Language();
		$ru->name = 'рус';
		$ru->code = 'ru';
		$ru->order = 3;

		$languages = array($kk,$en,$ru);

		foreach($languages as $lang){
			if(Language::where('code',$lang->code)->count()==0){
				$lang->save();
			}
		}
	}
}