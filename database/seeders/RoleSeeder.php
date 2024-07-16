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
            'General Settings',
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
            'Deposit Transactions'
        ];

        $permissions = [
            'role.index',
            'role.create',
            'role.show',
            'role.edit',
            'role.update',
            'role.destroy',
            'role.store',

            'admin.profile',
            'admin.profile.update',

            'permission.show',
            'permission.store',
            'permission.create',
            'permission.index',
            'permission.edit',
            'permission.update',
            'permission.destroy',
            'permission.create',
            'settings.edit',
            'settings.update',

            'admin.dashboard',

            'api.index',
            'api.store',
            'api.create',
            'api.show',
            'api.edit',
            'api.update',
            'api.destroy',

            'paymentgateway.index',
            'paymentgateway.store',
            'paymentgateway.create',
            'paymentgateway.show',
            'paymentgateway.edit',
            'paymentgateway.update',
            'paymentgateway.destroy',

            'category.index',
            'category.store',
            'category.create',
            'category.show',
            'category.edit',
            'category.update',
            'category.destroy',

            'product.index',
            'product.store',
            'product.create',
            'product.show',
            'product.edit',
            'product.update',
            'product.destroy',

            'customerlevel.index',
            'customerlevel.store',
            'customerlevel.show',
            'customerlevel.edit',
            'customerlevel.update',
            'customerlevel.destroy',
            'customerlevel.create',

            'notification.index',
            'notification.store',
            'notification.show',
            'notification.edit',
            'notification.update',
            'notification.destroy',
            'notification.create',

            'bank.index',
            'bank.store',
            'bank.create',
            'bank.show',
            'bank.edit',
            'bank.update',
            'bank.destroy',

            'blacklist.index',
            'blacklist.store',
            'blacklist.show',
            'blacklist.edit',
            'blacklist.update',
            'blacklist.destroy',
            'blacklist.create',

            'accounts.index',
            'accounts.create',
            'accounts.store',
            'accounts.show',
            'accounts.edit',
            'accounts.update',
            'accounts.destroy',

            'customers.index',
            'customers.store',
            'customers.create',
            'customers.show',
            'customers.edit',
            'customers.update',
            'customers.destroy',

            'staff.index',
            'staff.store',
            'staff.create',
            'staff.show',
            'staff.edit',
            'staff.update',
            'staff.destroy',

            'airtime2cash.index',
            'airtime2cash.store',
            'airtime2cash.create',
            'airtime2cash.show',
            'airtime2cash.edit',
            'airtime2cash.update',
            'airtime2cash.destroy',
            
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
            'admin.deposit.transactions',
            'view.sensitive.data',
            'pull.reserved.account.by.reference',
            'add.manual.reserved.account'
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
