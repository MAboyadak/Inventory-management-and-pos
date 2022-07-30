<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Unit;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        // $this->call(PermissionsSeeder::class);
        // create permissions
        Permission::create(['name' => 'view product']);
        Permission::create(['name' => 'create product']);
        Permission::create(['name' => 'delete product']);
        Permission::create(['name' => 'edit product']);
        Permission::create(['name' => 'update product']);
        Permission::create(['name' => 'store product']);


        // Product::create(
        //     [
        //         'name'=>'short-m',
        //         'code'=>'00001',
        //         'category_id'=>'2',
        //         'unit_id'=>'1',
        //         'cost'=>'150',
        //         'price'=>'280',
        //         'alert_quantity'=>'5',
        //         'image'=>'',
        //         'product_details'=>'medium jeans short',
        //     ]
        // );

        Unit::create([
            'name' => 'Dozen Box',
            'code' => 'dozen',
            'base_unit' => 1,
        ]);
    }
}
