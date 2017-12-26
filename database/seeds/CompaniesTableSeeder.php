<?php

use Illuminate\Database\Seeder;
use App\Company;

class CompaniesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
		//DB::table('languages')->delete();
		if(Company::count()==0){
			$c = new Company();
			$c->content=json_encode($initial=true);
			$c->save();
		}
	}
}