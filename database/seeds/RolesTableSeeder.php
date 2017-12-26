<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
        //DB::table('roles')->delete();
        $admin = new Role();
        $admin->name = 'admin';
        $admin->display_name = 'Admin';
        $admin->description = 'General admin';

        $editor = new Role();
        $editor->name = 'editor';
        $editor->display_name = 'Editor';
        $editor->description = 'Site content manager';

        $roles = array($admin, $editor);

        foreach ($roles as $role) {
            $count = Role::where('name', $role->name)->count();
            if($count==0){
                $role->save();
            }
        }
	}
}
