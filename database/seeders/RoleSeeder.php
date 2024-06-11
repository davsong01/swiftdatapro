<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\RolePermission;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menu = [
            'Dashboard',
            'Staff',
            'All Staff',
            'Staff Roles',
            'Staff Permissions',
            'Customers',
            'All Customers',
            'Active Customers',
            'In Active Customers',
            'Customer Levels',
            'Reserved Accounts',
            'Settings',
            'Contact Settings',
            'Blacklist Biller',
            'Order Number',
            'Manual Bank Details',
            'Catalogues',
            'API Providers',
            'Payment Gateways',
            'Categories',
            'Products',
            'Notifications',
            'Finance',
            'Transactions',
            'Credit Customer',
            'Debit Customer',
        ];

        $permissions = [
            'role.index',
            'role.show',
            'role.edit',
            'role.update',
            'role.destroy',

            'permission.show',
            'permission.index',
            'permission.edit',
            'permission.update',
            'permission.destroy',
            'permission.create',
            'settings.edit',
            'settings.update',

            'admin.dashboard',


            'api.index',
            'api.show',
            'api.edit',
            'api.update',
            'api.destroy',

            'paymentgateway.index',
            'paymentgateway.show',
            'paymentgateway.edit',
            'paymentgateway.update',
            'paymentgateway.destroy',

            'category.index',
            'category.show',
            'category.edit',
            'category.update',
            'category.destroy',

            'product.index',
            'product.show',
            'product.edit',
            'product.update',
            'product.destroy',

            'customerlevel.index',
            'customerlevel.show',
            'customerlevel.edit',
            'customerlevel.update',
            'customerlevel.destroy',

            'notification.index',
            'notification.show',
            'notification.edit',
            'notification.update',
            'notification.destroy',

            'bank.index',
            'bank.show',
            'bank.edit',
            'bank.update',
            'bank.destroy',

            'blacklist.index',
            'blacklist.show',
            'blacklist.edit',
            'blacklist.update',
            'blacklist.destroy',

            'accounts.index',
            'accounts.show',
            'accounts.edit',
            'accounts.update',
            'accounts.destroy',

            'customers.index',
            'customers.show',
            'customers.edit',
            'customers.update',
            'customers.destroy',

            'staff.index',
            'staff.show',
            'staff.edit',
            'staff.update',
            'staff.destroy',
            
            'account.update',
            'products.update',
            'customer.other.actions',
            
            'customers.active',
            'customers.inactive',

            'duplicate.product',

            'variations.old.pull',
            'variations.pull',
            'variations.update',
            'manual.variations.add',
            'variation.delete',

            'admin.credit.customer',
            'admin.debit.customer',
            'admin.process.credit.debit',
            'admin.transactions',
            'admin.single.transaction.view',
            'transaction.receipt.download', 
        ];

        // RolePermission::truncate();
        foreach($menu as $key=>$value){
            RolePermission::updateOrCreate(
                [
                    'name' => $value,
                    'route' => $value,
                    'type' => 'menu',
                ],[
                'name' => $value,
                'route' => $value,
                'type' => 'menu',
                'status' => 'active',
            ]);
        }

        foreach ($permissions as $key => $value) {
            RolePermission::updateOrCreate(
                [
                    'name' => $value,
                    'route' => $value,
                    'type' => 'link',
                ],[
                'name' => $value,
                'route' => $value,
                'type' => 'link',
                'status' => 'active',
            ]);
        }

        Role::updateOrCreate(['name' => 'Admin','status' => 'active'],[
            'name' => 'Admin',
            'permissions' => "1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,76,77,78,79,80,81,82,83,84,85,86,87,88,89,90,91,92,93,94,95,96,97,98,99,100,101,102,103,104,105,106,107,108,109,110,111,112,113,115,116,117,118,119,120,121",
            'status' => 'active'
        ]);
    }
}
