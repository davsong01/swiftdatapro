<?php

namespace App\Models;

use App\Models\Role;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Foundation\Auth\User as Authenticatable;


class Admin extends Authenticatable
{
    use HasApiTokens;
    protected $table = 'vtuapp_admin';

    protected $appends = ['permissions'];

    protected $fillable = [
        'full_name','name', 'username', 'email', 'pass','type','username','password', 'status','username', 'rolepermissions' // Update the column name
    ];

    protected $hidden = [
        'pass', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getroleidsAttribute(){
        $ids = explode(',',$this->rolepermissions);
        return !empty($ids) ? $ids : [];
    }

    public function getPermissionsAttribute(){
        $permission = $this->rolepermissions;
        $allPermissions = [];
        $routes = [];
        if (empty($permission)) {
            return $allPermissions;
        } else {
            $allroles = explode(",", $permission);
            $rolePermission = Role::where('status', 'active')->whereIn('id', $allroles)->pluck('permissions')->toArray();
            
            foreach ($rolePermission as $permissions) {
                $explode = explode(",", $permissions);
                foreach ($explode as $key => $permissions) {
                    $allPermissions[] = $permissions;
                }
            }
        }

        $allPermissions = array_unique($allPermissions);
        // Getting the slugs
        foreach ($allPermissions as $key => $value) {
            $routes[] = RolePermission::where('id', $value)->value('route');
        }
        
        return $routes;
    }
}
