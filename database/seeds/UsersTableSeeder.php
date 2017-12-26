<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Role;
use App\Permission;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
  public function run(){
	//DB::table('users')->delete();
  	if(User::count()==0){
			$admin = new User();
			$admin->name = 'Admin';
			$admin->email = 'admin@cybersec.kz';
			$admin->password = bcrypt('admin@cybersec.kz');

			$users = array($admin);
			for ($x = 0; $x <= 1; $x++) {
	    		$editor = new User();
				$editor->name = 'Editor '.$x;
				$editor->email = 'editor'.$x.'@cybersec.kz';
				$editor->password = bcrypt('editor'.$x.'@cybersec.kz');
				array_push($users,$editor);
			}
			
			$adminRole = Role::where('name','admin')->firstOrFail();
			$editorRole = Role::where('name','editor')->firstOrFail();

			foreach( $users as $user){
				if(User::where('email',$user->email)->count()==0){
					$user->save();
				}else{
					if($user->email==='admin@cybersec.kz'){
						$user = User::where('email','admin@cybersec.kz')->firstOrFail();
					}else if (strpos($user->name, 'Editor') !== false) {
						$user = User::where('name',$user->name)->firstOrFail();
					}
				}
				if($user->email==='admin@cybersec.kz'&&$user->hasRole('admin')==false){
					$user->attachRole($adminRole);
				}else if(strpos($user->name, 'Editor')!==false&&$user->hasRole('editor')==false){
					$user->attachRole($editorRole);
				}
			}	
		}
	}
}
