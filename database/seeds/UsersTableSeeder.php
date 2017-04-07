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

		$editor = new User();
		$editor->name = 'Editor';
		$editor->email = 'editor@cybersec.kz';
		$editor->password = bcrypt('editor@cybersec.kz');

		$users = array($admin,$editor);

		foreach( $users as $user){
			if(User::where('email',$user->email)->count()==0){
				$user->save();
			}
		}
		$admin = User::where('email','admin@cybersec.kz')->firstOrFail();
		$editor = User::where('email','editor@cybersec.kz')->firstOrFail();
		$adminRole = Role::where('name','admin')->firstOrFail();
		$editorRole = Role::where('name','editor')->firstOrFail();
		if($admin->hasRole('admin')==false){
			$admin->attachRole($adminRole);
		}
		if($editor->hasRole('editor')==false){
			$editor->attachRole($editorRole);
		}
		$manageUser = Permission::where('name','manage_users')->firstOrFail();
		$manageContent = Permission::where('name','manage_content')->firstOrFail();
		if($admin->can('manage_users')==false)
			$adminRole->attachPermission($manageUser);
		if($editor->can('manage_content')==false)
			$editorRole->attachPermission($manageContent);
	}
}
