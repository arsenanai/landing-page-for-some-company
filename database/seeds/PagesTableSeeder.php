<?php

use Illuminate\Database\Seeder;
use App\Page;

class PagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
		//DB::table('languages')->delete();
		for($i=0;$i<5;$i++){
			$p = new Page();
			$p->order = $i+1;
			$p->content = json_encode($inital=true);
			if(Page::where('order',$i+1)->count()==0){
				$p->save();
			}
		}
	}
}