<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\SidebarGroup;
use App\Models\SidebarItem;
use Illuminate\Database\Seeder;

class SidebarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminGroup = SidebarGroup::create([
            'name' => 'Administración',
            'icon' => 'fa-circle-nodes',
            'description' => 'Módulo administrativo',
        ]);

        $storeGroup = SidebarGroup::create([
            'name' => 'Tienda',
            'icon' => 'fa-store',
            'description' => 'Módulo de tienda',
        ]);

        $configGroup = SidebarGroup::create([
            'name' => 'Configuración',
            'icon' => 'fa-gear',
            'description' => 'Módulo de configuración',
        ]);

        // Crear páginas
        $homePage = Page::create(['route' => '/']);
        $shoppingCart = Page::create(['route' => '/shopping-cart']);
        $ordersPage = Page::create(['route' => '/orders']);
        $myOrdersPage = Page::create(['route' => '/my-orders']);
        $paymentsPage = Page::create(['route' => '/payments']);
        $myPaymentsPage = Page::create(['route' => '/my-payments']);
        //$contactPage = Page::create(['route' => '/contact']);
        //$contactMessagesPage = Page::create(['route' => '/contact-messages']);
        $categoriesPage = Page::create(['route' => '/categories']);
        $productsPage = Page::create(['route' => '/products']);
        $inventoryPage = Page::create(['route' => '/inventory']);
        $promotionsPage = Page::create(['route' => '/promotions']);
        $usersPage = Page::create(['route' => '/users']);
        $rolesPage = Page::create(['route' => '/roles']);
        $debtorsPage = Page::create(['route' => '/debtors']);
        $pagesPage = Page::create(['route' => '/pages']);
        $sidebarPage = Page::create(['route' => '/sidebar']);
        $statisticsPage = Page::create(['route' => '/statistics']);

        $newItem = new SidebarItem([
            'name' => 'Home',
            'icon' => 'fa-house',
            'page_id' => $homePage->id,
        ]);
        $newItem->save();

        $newItem = new SidebarItem([
            'name' => 'Carrito',
            'icon' => 'fa-cart-shopping',
            'page_id' => $shoppingCart->id,
        ]);
        $newItem->save();

        $newItem = new SidebarItem([
            'name' => 'Mis pedidos',
            'page_id' => $myOrdersPage->id,
            'icon' => 'fa-bag-shopping',
            'permission' => 'order.index.own',
        ]);
        $newItem->save();

        $newItem = new SidebarItem([
            'name' => 'Mis pagos',
            'page_id' => $myPaymentsPage->id,
            'icon' => 'fa-money-bill',
            'permission' => 'payment.index.own',
        ]);
        $newItem->save();

        /* $newItem = new SidebarItem([
            'name' => 'Contacto',
            'page_id' => $contactPage->id,
            'icon' => 'fa-phone',
        ]);
        $newItem->save(); */

        $newItem = new SidebarItem([
            'name' => 'Usuarios',
            'page_id' => $usersPage->id,
            'permission' => 'user.index',
        ]);
        $adminGroup->items()->save($newItem);

        $newItem = new SidebarItem([
            'name' => 'Deudores',
            'page_id' => $debtorsPage->id,
            'permission' => 'debtor.index',
        ]);
        $adminGroup->items()->save($newItem);

        $newItem = new SidebarItem([
            'name' => 'Roles y permisos',
            'page_id' => $rolesPage->id,
            'permission' => 'role.index',
        ]);
        $adminGroup->items()->save($newItem);

        /* $newItem = new SidebarItem([
            'name' => 'Mensajes de contacto',
            'page_id' => $contactMessagesPage->id,
            'permission' => 'contact.index',
        ]);
        $adminGroup->items()->save($newItem); */

        $newItem = new SidebarItem([
            'name' => 'Pedidos',
            'page_id' => $ordersPage->id,
            'permission' => 'order.index',
        ]);
        $storeGroup->items()->save($newItem);

        $newItem = new SidebarItem([
            'name' => 'Pagos',
            'page_id' => $paymentsPage->id,
            'permission' => 'payment.index',
        ]);
        $storeGroup->items()->save($newItem);

        
        $newItem = new SidebarItem([
            'name' => 'Productos',
            'page_id' => $productsPage->id,
            'permission' => 'product.index',
        ]);
        $storeGroup->items()->save($newItem);

         
        $newItem = new SidebarItem([
            'name' => 'Inventario',
            'page_id' => $inventoryPage->id,
            'permission' => 'inventory.index',
        ]);
        $storeGroup->items()->save($newItem);

        $newItem = new SidebarItem([
            'name' => 'Promociones',
            'page_id' => $promotionsPage->id,
            'permission' => 'promotion.index',
        ]);
        $storeGroup->items()->save($newItem);
  
        $newItem = new SidebarItem([
            'name' => 'Categorias',
            'page_id' => $categoriesPage->id,
            'permission' => 'category.index',
        ]);
        $storeGroup->items()->save($newItem);

        $newItem = new SidebarItem([
            'name' => 'Estadísticas',
            'page_id' => $statisticsPage->id,
            'permission' => 'statistic.index',
        ]);
        $storeGroup->items()->save($newItem);
        
        $newItem = new SidebarItem([
            'name' => 'Páginas',
            'page_id' => $pagesPage->id,
            'permission' => 'page.index',
        ]);
        $configGroup->items()->save($newItem);

        $newItem = new SidebarItem([
            'name' => 'Sidebar',
            'page_id' => $sidebarPage->id,
            'permission' => 'sidebar.index',
        ]);
        $configGroup->items()->save($newItem);
    }
}
