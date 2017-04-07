<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Permission;
use App\Role;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
		//DB::table('permissions')->delete();
		$manageUser = new Permission();
		$manageUser->name = 'manage_users';
		$manageUser->display_name = 'Manage users';
		$manageUser->description = 'Permission to create, update or remove users';

		$manageContent = new Permission();
		$manageContent->name = 'manage_content';
		$manageContent->display_name = 'Manage content';
		$manageContent->description = 'Permission to write, edit or delete site content';

		$permissions = array($manageUser,$manageContent);
		$adminRole = Role::where('name','admin')->firstOrFail();
		$editorRole = Role::where('name','editor')->firstOrFail();

		foreach( $permissions as $permission){
			if(Permission::where('name',$permission->name)->count()==0){
				$permission->save();
				if($permission->name==='manage_users'){
					$adminRole->attachPermission($manageUser);	
				}else if($permission->name==='manage_content'){
					$editorRole->attachPermission($manageContent);
				}
			}
		}
		/*DB::table('permissions')->insert(array(
				array(
					'name'=>'manage_users',
					'display_name'=>'Manage users',
					'description'=>'Ability to create, update or remove users',
					'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
             		'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
					),
				array(
					'name'=>'manage_content',
					'display_name'=>'Manage content',
					'description'=>'Ability to write, edit or delete site content',
					'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
             		'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
					),
			));*/
	}
}
