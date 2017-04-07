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
		DB::table('users')->delete();
		$admin = new User();
		$admin->name = 'Admin';
		$admin->email = 'admin@cybersec.kz';
		$admin->password = bcrypt('admin@cybersec.kz');

		$editor1 = new User();
		$editor1->name = 'Editor 1';
		$editor1->email = 'arsenanai@gmail.com';
		$editor1->password = bcrypt('arsenanai@gmail.com');

		$editor = new User();
		$editor->name = 'Editor 2';
		$editor->email = 'editor@cybersec.kz';
		$editor->password = bcrypt('editor@cybersec.kz');

		$users = array($admin,$editor1,$editor);

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
