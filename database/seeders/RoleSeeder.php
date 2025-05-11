<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role1      = Role::create(['name'  => 'SUPERADMIN']);
        $role2      = Role::create(['name'  => 'ADMIN']);
        $role3      = Role::create(['name'  => 'VENDEDOR']);

        Permission::create([
            'name'          => 'dashboard',
            'descripcion'   => 'Ver dashboard'
        ])->syncRoles([$role1, $role2, $role3]);

        Permission::create([
            'name'          => 'archingCash',
            'descripcion'   => 'Administrar cajas'
        ])->syncRoles([$role1, $role2, $role3]);

        // Permission::create([
        //     'name'          => 'alerts',
        //     'descripcion'   => 'Ver alertas'
        // ])->syncRoles([$role1, $role2, $role3]);

        Permission::create([
            'name'          => 'alerts.alerts_sale',
            'descripcion'   => 'Ver alertas pendientes de SUNAT'
        ])->syncRoles([$role1, $role2, $role3]);

        Permission::create([
            'name'          => 'alerts.alerts_stock',
            'descripcion'   => 'Ver alertas de productos por agotar'
        ])->syncRoles([$role1, $role2, $role3]);
        Permission::create([
            'name'          => 'alerts.alerts_expiration',
            'descripcion'   => 'Ver alertas de productos por vencer'
        ])->syncRoles([$role1, $role2, $role3]);

        // Permission::create([
        //     'name'          => 'sales',
        //     'descripcion'   => 'Ver ventas'
        // ])->syncRoles([$role1, $role2, $role3]);

        Permission::create([
            'name'          => 'sales.billings',
            'descripcion'   => 'Ver listado de ventas'
        ])->syncRoles([$role1, $role2, $role3]);

        Permission::create([
            'name'          => 'sales.credit_notes',
            'descripcion'   => 'Ver notas de crédito'
        ])->syncRoles([$role1, $role2, $role3]);

        Permission::create([
            'name'          => 'sales.sale_notes',
            'descripcion'   => 'Ver notas de ventas'
        ])->syncRoles([$role1, $role2, $role3]);

        Permission::create([
            'name'          => 'sales.quotes',
            'descripcion'   => 'Ver cotizaciones'
        ])->syncRoles([$role1, $role2, $role3]);

       Permission::create([
           'name'          => 'sales.credits',
           'descripcion'   => 'Ver creditos'
       ])->syncRoles([$role1, $role2, $role3]);

        // Permission::create([
        //     'name'          => 'buys',
        //     'descripcion'   => 'Ver compras'
        // ])->syncRoles([$role1, $role2, $role3]);

        Permission::create([
            'name'          => 'buys.create_buy',
            'descripcion'   => 'Ver compra de mercadería'
        ])->syncRoles([$role1, $role2, $role3]);

        Permission::create([
            'name'          => 'buys.buys',
            'descripcion'   => 'Ver Listado de compras'
        ])->syncRoles([$role1, $role2, $role3]);

        Permission::create([
            'name'          => 'buys.bills',
            'descripcion'   => 'Ver gastos'
        ])->syncRoles([$role1, $role2, $role3]);

        // Permission::create([
        //     'name'          => 'inventary',
        //     'descripcion'   => 'Ver inventario'
        // ])->syncRoles([$role1, $role2, $role3]);

        Permission::create([
            'name'          => 'inventary.products',
            'descripcion'   => 'Ver productos'
        ])->syncRoles([$role1, $role2, $role3]);
        Permission::create([
            'name'          => 'inventary.warehouses',
            'descripcion'   => 'Ver tiendas'
        ])->syncRoles([$role1, $role2, $role3]);

        Permission::create([
            'name'          => 'inventary.transfer_orders',
            'descripcion'   => 'Ver traslados'
        ])->syncRoles([$role1, $role2, $role3]);

        Permission::create([
            'name'          => 'pos',
            'descripcion'   => 'Ver pos'
        ])->syncRoles([$role1, $role2, $role3]);

        // Permission::create([
        //     'name'          => 'contact',
        //     'descripcion'   => 'Ver contactos'
        // ])->syncRoles([$role1, $role2, $role3]);

        Permission::create([
            'name'          => 'contacts.clients',
            'descripcion'   => 'Ver clientes'
        ])->syncRoles([$role1, $role2, $role3]);

        Permission::create([
            'name'          => 'contacts.providers',
            'descripcion'   => 'Ver proveedores'
        ])->syncRoles([$role1, $role2, $role3]);

        Permission::create([
            'name'          => 'contacts.users',
            'descripcion'   => 'Ver usuarios'
        ])->syncRoles([$role1, $role2, $role3]);

        Permission::create([
            'name'          => 'contacts.roles',
            'descripcion'   => 'Ver roles'
        ])->syncRoles([$role1, $role2, $role3]);

        Permission::create([
            'name'          => 'reports.sales.sales_general',
            'descripcion'   => 'Ver ventas general'
        ])->syncRoles([$role1, $role2, $role3]);

        Permission::create([
            'name'          => 'document_avanzados.guias.gr_remitente',
            'descripcion'   => 'G.R. Remitente'
        ])->syncRoles([$role1, $role2, $role3]);

        Permission::create([
            'name'          => 'document_avanzados.guias.gr_transportista',
            'descripcion'   => 'G.R. Transportista'
        ])->syncRoles([$role1, $role2, $role3]);

        Permission::create([
            'name'          => 'document_avanzados.guias.transportistas',
            'descripcion'   => 'Transportista'
        ])->syncRoles([$role1, $role2, $role3]);


        Permission::create([
            'name'          => 'document_avanzados.guias.drivers',
            'descripcion'   => 'Conductores'
        ])->syncRoles([$role1, $role2, $role3]);


        Permission::create([
            'name'          => 'document_avanzados.guias.vehicles',
            'descripcion'   => 'Vehiculos'
        ])->syncRoles([$role1, $role2, $role3]);


        Permission::create([
            'name'          => 'document_avanzados.guias.start_points',
            'descripcion'   => 'Direcciones de Partida'
        ])->syncRoles([$role1, $role2, $role3]);

        Permission::create([
            'name'          => 'reports.sales.sales_seller',
            'descripcion'   => 'Ver ventas por vendedor'
        ])->syncRoles([$role1, $role2, $role3]);

        Permission::create([
            'name'          => 'reports.sales.sales_product',
            'descripcion'   => 'Ver prod. más vendidos'
        ])->syncRoles([$role1, $role2, $role3]);

        Permission::create([
            'name'          => 'reports.purchases.purchases_general',
            'descripcion'   => 'Ver compras general'
        ])->syncRoles([$role1, $role2, $role3]);

        Permission::create([
            'name'          => 'reports.purchases.purchases_provider',
            'descripcion'   => 'Ver compras proveedor'
        ])->syncRoles([$role1, $role2, $role3]);

        Permission::create([
            'name'          => 'reports.purchases.purchases_expenses',
            'descripcion'   => 'Ver gastos'
        ])->syncRoles([$role1, $role2, $role3]);

        Permission::create([
            'name'          => 'reports.contacts.contact_customers',
            'descripcion'   => 'Ver clientes'
        ])->syncRoles([$role1, $role2, $role3]);

        Permission::create([
            'name'          => 'reports.contacts.contact_providers',
            'descripcion'   => 'Ver proveedores'
        ])->syncRoles([$role1, $role2, $role3]);

        Permission::create([
            'name'          => 'reports.inventory.inventory_products',
            'descripcion'   => 'Ver inventario de productos'
        ])->syncRoles([$role1, $role2, $role3]);

        Permission::create([
            'name'          => 'reports.kardex.kardex_unitario',
            'descripcion'   => 'Ver kardex unitario'
        ])->syncRoles([$role1, $role2, $role3]);

        Permission::create([
            'name'          => 'settings.business',
            'descripcion'   => 'Ver empresa'
        ])->syncRoles([$role1, $role2, $role3]);

        Permission::create([
            'name'          => 'settings.cuentas',
            'descripcion'   => 'Ver cuentas'
        ])->syncRoles([$role1, $role2, $role3]);

        Permission::create([
            'name'          => 'settings.detracciones',
            'descripcion'   => 'Ver Listado de detracciones'
        ])->syncRoles([$role1, $role2, $role3]);

        Permission::create([
            'name'          => 'settings.series',
            'descripcion'   => 'Ver series'
        ])->syncRoles([$role1, $role2, $role3]);

        Permission::create([
            'name'          => 'settings.list_cashes',
            'descripcion'   => 'Ver cajas'
        ])->syncRoles([$role1, $role2, $role3]);

        Permission::create([
            'name'          => 'reclamos.ajustes',
            'descripcion'   => 'Ver Ajustes'
        ])->syncRoles([$role1, $role2, $role3]);
        Permission::create([
            'name'          => 'reclamos.report_reclamos',
            'descripcion'   => 'Ver Reportes Reclamos / Quejas'
        ])->syncRoles([$role1, $role2, $role3]);
        Permission::create([
            'name'          => 'reclamos.reclamos_quejas',
            'descripcion'   => 'Ver Reclamos / Quejas'
        ])->syncRoles([$role1, $role2, $role3]);

        //////////////////////////////////////////////////////

        Permission::create([
            'name'          => 'admin.business',
            'descripcion'   => 'Ver información de empresa'
        ])->syncRoles([$role1]);

        Permission::create([
            'name'          => 'admin.buys',
            'descripcion'   => 'Ver compras'
        ])->syncRoles([$role1, $role2]);

        Permission::create([
            'name'          => 'admin.create_buy',
            'descripcion'   => 'Registrar nueva compra'
        ])->syncRoles([$role1, $role2]);

        Permission::create([
            'name'          => 'admin.products',
            'descripcion'   => 'Ver productos'
        ])->syncRoles([$role1, $role2]);

        Permission::create([
            'name'          => 'admin.users',
            'descripcion'   => 'Ver usuarios'
        ])->syncRoles([$role1, $role2]);

        Permission::create([
            'name'          => 'admin.roles',
            'descripcion'   => 'Ver roles'
        ])->syncRoles([$role1, $role2]);

        Permission::create([
            'name'          => 'admin.prices',
            'descripcion'   => 'Ver precios'
        ])->syncRoles([$role1, $role2]);

        Permission::create([
            'name'          => 'admin.faq',
            'descripcion'   => 'Ver preguntas frecuentes'
        ])->syncRoles([$role1, $role2]);

         // Permission:: Cuentas por Cobrar([
        Permission::create([
            'name'          => 'accounts_receivable',
            'descripcion'   => 'Cuentas por Cobrar'
        ])->syncRoles([$role1, $role2, $role3]);


    }
}

