<?php

use App\Http\Controllers\AccountsReceivableController;
use App\Http\Controllers\AlertController;
use App\Http\Controllers\ArchingCashController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\BillReportController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\BuyController;
use App\Http\Controllers\CashController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ContactReportController;
use App\Http\Controllers\CreditNoteController;
use App\Http\Controllers\CuentasController;
use App\Http\Controllers\ExtraController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InventoryReportController;
use App\Http\Controllers\KardexController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\PurchaseReportController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\SaleNoteController;
use App\Http\Controllers\SaleReportController;
use App\Http\Controllers\SerieController;
use App\Http\Controllers\TransferOrderController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\VentaGeneralController;
use App\Http\Controllers\AjustesController;
use App\Http\Controllers\ReclamosQuejasController;
use App\Http\Controllers\ReporteReclamosController;
use App\Http\Controllers\DocumentAvanzadosController;
use App\Http\Controllers\GRRemitenteController;
use App\Http\Controllers\GRTransportistaController;
use App\Http\Controllers\ListadoDetraccionController;



use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('optimize:clear');
    return 'Cache cleared!';
});

Route::get('/migrate', function () {
    ini_set('max_execution_time', 300); //aumentar tiempo de espera en 5 mins
    Artisan::call('migrate:refresh --seed');
    echo Artisan::output();
    return 'migration refreshed!';
});

Route::get('/', [LoginController::class, 'index'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'login'])->name('login.login');
Route::get('/logout', [LoginController::class, 'logout'])->name('login.logout');

# Extra
Route::get('/prices', [ExtraController::class, 'prices'])->name('admin.prices')->middleware('can:admin.prices');
Route::get('/faq', [ExtraController::class, 'faq'])->name('admin.faq')->middleware('can:admin.faq');
Route::post('/send-voucher', [ExtraController::class, 'send_voucher'])->name('admin.send_voucher');

// Business
Route::get('/business', [BusinessController::class, 'index'])->middleware('can:settings.business')->name('admin.business');
Route::post('/load-ubigeo', [BusinessController::class, 'load_ubigeo'])->name('admin.load_ubigeo');
Route::post('/load-provinces', [BusinessController::class, 'load_provinces'])->name('admin.load_provinces');
Route::post('/load-districts', [BusinessController::class, 'load_districts'])->name('admin.load_districts');
Route::post('/save-info-business', [BusinessController::class, 'save_info_business'])->name('admin.save_info_business');
Route::post('/save-info-user', [BusinessController::class, 'save_info_user'])->name('admin.save_info_user');
Route::post('/gen-json', [BusinessController::class, 'gen_json'])->name('admin.gen_json');
## Home
Route::get('/home', [HomeController::class, 'index'])->name('admin.home')->middleware('auth', 'can:dashboard');
Route::post('/load-alerts', [HomeController::class, 'alerts'])->name('admin.load_alerts');
Route::post('/dash-bills', [HomeController::class, 'dash_bills'])->name('admin.dash_bills');
Route::post('/dash-profits', [HomeController::class, 'dash_profits'])->name('admin.dash_profits');
Route::post('/dash-clients', [HomeController::class, 'dash_clients'])->name('admin.dash_clients');
Route::post('/dash-incomes', [HomeController::class, 'dash_incomes'])->name('admin.dash_incomes');
## Alerts
Route::get('/alerts-stock', [AlertController::class, 'stock'])->name('admin.alerts_stock')->middleware('auth', 'can:alerts.alerts_stock');
Route::post('/tbody-stocks', [AlertController::class, 'tbody_stocks'])->name('admin.load_tbody_stocks');
Route::get('/alerts-expiration', [AlertController::class, 'expiration'])->name('admin.alerts_expiration')->middleware('auth', 'can:alerts.alerts_expiration');
Route::post('/tbody-expirations', [AlertController::class, 'tbody_expirations'])->name('admin.load_tbody_expirations');
Route::get('/alerts-sale', [AlertController::class, 'sale'])->name('admin.alerts_sale')->middleware('auth', 'can:alerts.alerts_sale');
Route::post('/tbody-sales', [AlertController::class, 'tbody_sales'])->name('admin.load_tbody_sales');
## Serie
Route::get('/series', [SerieController::class, 'index'])->name('admin.series')->middleware('auth', 'can:settings.series');
Route::get('/get-series', [SerieController::class, 'get'])->name('admin.get_series');
Route::post('/save-serie', [SerieController::class, 'save'])->name('admin.save_serie');
Route::post('/detail-serie', [SerieController::class, 'detail'])->name('admin.detail_serie');
Route::post('/store-serie', [SerieController::class, 'store'])->name('admin.store_serie');
Route::post('/delete-serie', [SerieController::class, 'delete'])->name('admin.delete_serie');
## Cuentas
Route::get('/cuentas', [CuentasController::class, 'index'])->name('admin.cuentas')->middleware('auth', 'can:settings.cuentas');
Route::get('/list-cuentas', [CuentasController::class, 'index'])->name('admin.list_cuentas')->middleware('auth', 'can:settings.list_cuentas');
Route::get('/get-cuentas', [CuentasController::class, 'get'])->name('admin.get_cuentas');
Route::post('/save-cuentas', [CuentasController::class, 'save'])->name('admin.save_cuentas');
Route::post('/detail-cuentas', [CuentasController::class, 'detail'])->name('admin.detail_cuentas');
Route::post('/store-cuentas', [CuentasController::class, 'store'])->name('admin.store_cuentas');
Route::post('/delete-cuentas', [CuentasController::class, 'delete'])->name('admin.delete_cuentas');

##LISTADO DE DETRACCIONES

Route::get('/listado_detracciones', [ListadoDetraccionController::class, 'index'])->name('admin.listado_detracciones')->middleware('auth', 'can:settings.detracciones');
Route::get('/list-detra', [ListadoDetraccionController::class, 'index'])->name('admin.list_detra')->middleware('auth', 'can:settings.list_detra');
Route::get('/get-detra', [ListadoDetraccionController::class, 'get'])->name('admin.get_detra');
Route::post('/save-detra', [ListadoDetraccionController::class, 'save'])->name('admin.save_detra');
Route::post('/detail-detra', [ListadoDetraccionController::class, 'detail'])->name('admin.detail_detra');
Route::post('/store-detra', [ListadoDetraccionController::class, 'store'])->name('admin.store_detra');
Route::post('/delete-detra', [ListadoDetraccionController::class, 'delete'])->name('admin.delete_detra');



## Cash
Route::get('/list-cashes', [CashController::class, 'index'])->name('admin.list_cashes')->middleware('auth', 'can:settings.list_cashes');
Route::get('/get-cashes', [CashController::class, 'get'])->name('admin.get_cashes');
Route::post('/save-cash', [CashController::class, 'save'])->name('admin.save_cash');
Route::post('/detail-cash', [CashController::class, 'detail'])->name('admin.detail_cash');
Route::post('/store-cash', [CashController::class, 'store'])->name('admin.store_cash');
Route::post('/delete-cash', [CashController::class, 'delete'])->name('admin.delete_cash');
## Client
Route::get('/clients', [ClientController::class, 'index'])->name('admin.clients')->middleware('auth', 'can:contacts.clients');
Route::get('/get-clients', [ClientController::class, 'get'])->name('admin.get_clients');
Route::post('/load-ubigeo-client', [ClientController::class, 'load_ubigeo'])->name('admin.load_ubigeo_client');
Route::post('/search-dni-ruc', [ClientController::class, 'search'])->name('admin.search_dni_ruc');
Route::post('/save-client', [ClientController::class, 'save'])->name('admin.save_client');
Route::post('/detail-client', [ClientController::class, 'detail'])->name('admin.detail_client');
Route::post('/store-client', [ClientController::class, 'store'])->name('admin.store_client');
Route::post('/delete-client', [ClientController::class, 'delete'])->name('admin.delete_client');
## Provider
Route::get('/providers', [ProviderController::class, 'index'])->name('admin.providers')->middleware('auth', 'can:contacts.providers');
Route::get('/get-providers', [ProviderController::class, 'get'])->name('admin.get_providers');
Route::post('/save-provider', [ProviderController::class, 'save'])->name('admin.save_provider');
Route::post('/detail-provider', [ProviderController::class, 'detail'])->name('admin.detail_provider');
Route::post('/store-provider', [ProviderController::class, 'store'])->name('admin.store_provider');
Route::post('/delete-provider', [ProviderController::class, 'delete'])->name('admin.delete_provider');
## Roles
Route::get('/roles', [RoleController::class, 'index'])->name('admin.roles')->middleware('can:contacts.roles');
Route::get('/get-roles', [RoleController::class, 'get'])->name('admin.get_roles');
Route::post('/save-role', [RoleController::class, 'save'])->name('admin.save_role');
Route::post('/detail-role', [RoleController::class, 'detail'])->name('admin.detail_role');
Route::post('/store-role', [RoleController::class, 'store'])->name('admin.store_role');
Route::post('/delete-role', [RoleController::class, 'delete'])->name('admin.delete_role');
## Product
Route::get('/products', [ProductController::class, 'index'])->name('admin.products')->middleware('can:inventary.products');
Route::get('/get-products', [ProductController::class, 'get'])->name('admin.get_products');
Route::post('/save-product', [ProductController::class, 'save'])->name('admin.save_product');
Route::post('/detail-product', [ProductController::class, 'detail'])->name('admin.detail_product');
Route::post('/store-product', [ProductController::class, 'store'])->name('admin.store_product');
Route::post('/delete-product', [ProductController::class, 'delete'])->name('admin.delete_product');
Route::post('/upload-excel', [ProductController::class, 'upload'])->name('admin.upload_excel');
Route::get('/download-excel/{ids?}', [ProductController::class, 'download'])->name('admin.download_excel');
Route::post('/view-stocks', [ProductController::class, 'view_stocks'])->name('admin.view_stocks');
Route::post('/enable-product', [ProductController::class, 'enableProduct'])->name('admin.enable_product');

## Bill
Route::get('/bills', [BillController::class, 'index'])->name('admin.bills')->middleware('can:buys.bills');
Route::get('/get-bills', [BillController::class, 'get'])->name('admin.get_bills');
Route::post('/save-bill', [BillController::class, 'save'])->name('admin.save_bill');
Route::post('/delete-bill', [BillController::class, 'delete'])->name('admin.delete_bill');
## Buy
Route::get('/buys', [BuyController::class, 'index'])->name('admin.buys')->middleware('can:buys.buys');
Route::get('/get-buys', [BuyController::class, 'get'])->name('admin.get_buys');
Route::get('/create-buy', [BuyController::class, 'create'])->name('admin.create_buy')->middleware('can:buys.create_buy');
Route::post('/load-serie-buy', [BuyController::class, 'load_serie'])->name('admin.load_serie_buy');
Route::post('/get-serie-buy', [BuyController::class, 'get_serie'])->name('admin.get_serie_buy');
Route::post('/get-price-product-buy', [BuyController::class, 'get_price_product'])->name('admin.get_price_product_buy');
Route::post('/add-product-buy', [BuyController::class, 'add_product'])->name('admin.add_product_buy');
Route::post('/load-cart-buys', [BuyController::class, 'load_cart'])->name('admin.load_cart_buys');
Route::post('/delete-product-buy', [BuyController::class, 'delete_product'])->name('admin.delete_product_buy');
Route::post('/store-product-buy', [BuyController::class, 'store_product'])->name('admin.store_product_buy');
Route::post('/get-providers-update', [BuyController::class, 'get_providers_update'])->name('admin.get_providers_update');
Route::post('/save-buy', [BuyController::class, 'save'])->name('admin.save_buy');
Route::post('/get-products-update', [BuyController::class, 'get_products_update'])->name('admin.get_products_update');
Route::post('/print-buy', [BuyController::class, 'print_buy'])->name('admin.print_buy');
Route::get('/download-buy/{id}', [BuyController::class, 'download'])->name('admin.download_buy');
Route::post('/options-print', [BuyController::class, 'options'])->name('admin.options_print');
Route::post('/delete-buy', [BuyController::class, 'delete'])->name('admin.delete_buy');
## Credits
Route::get('/general', [VentaGeneralController::class, 'index'])->name('admin.credits')->middleware('auth', 'can:sales.credits');
Route::get('/get-credits', [VentaGeneralController::class, 'get'])->name('admin.get_credits');
Route::get('/create-venta', [VentaGeneralController::class, 'create'])->name('admin.create_credit')->middleware('auth');
Route::post('/load-cart-credits', [VentaGeneralController::class, 'load_cart'])->name('admin.load_cart_credits');
Route::post('/searc-product-cart', [QuoteController::class, 'search_product_cart'])->name('admin.search_product_cart');
Route::post('/add-product-credit', [QuoteController::class, 'add_product'])->name('admin.add_product_credit');
Route::post('/store-product-credit', [VentaGeneralController::class, 'store_product'])->name('admin.store_product_credit');
Route::post('/get-clients-credits', [QuoteController::class, 'get_clients'])->name('admin.get_clients_update');
Route::post('/get-products-update-q', [QuoteController::class, 'get_products_update'])->name('admin.get_products_update_q');
Route::post('/get-serie-credit', [VentaGeneralController::class, 'get_serie'])->name('admin.get_serie_credit');
Route::post('/store-product-credit-price', [QuoteController::class, 'store_product_credit_price'])->name('admin.store_product_credit_price');
Route::post('/delete-product-credit', [QuoteController::class, 'delete_product'])->name('admin.delete_product_credit');
Route::post('/get-price-product-credit', [VentaGeneralController::class, 'get_price_product'])->name('admin.get_price_product_credit');
Route::post('/save-credit', [VentaGeneralController::class, 'save'])->name('admin.save_credit');
Route::post('/print-credit', [VentaGeneralController::class, 'print_credit'])->name('admin.print_credit');
Route::post('/options-print-credit', [VentaGeneralController::class, 'options'])->name('admin.options_print_credit');
Route::get('/test-credit', [VentaGeneralController::class, 'test'])->name('admin.test_credit');
Route::get('/download-credit/{id}', [VentaGeneralController::class, 'download'])->name('admin.download_credit');
Route::post('/gen-credit-voucher', [CreditController::class, 'gen_voucher'])->name('admin.gen_credit_voucher');
Route::get('/edit-credit-{id}', [CreditController::class, 'edit'])->name('admin.edit_credit');
Route::post('/get-product-credit-update', [CreditController::class, 'get_product_update'])->name('admin.get_product_credit_update');
Route::post('/store-product-credit-update', [CreditController::class, 'store_product_update'])->name('admin.store_product_credit_update');
Route::post('/gen-credit-update', [CreditController::class, 'gen_update'])->name('admin.gen_credit_update');
Route::post('/update-cdr-vg', [VentaGeneralController::class, 'update_cdr'])->name('admin.update_cdr_vg');
Route::post('/send-vg-sunat', [VentaGeneralController::class, 'send'])->name('admin.send_vg');

## Quote
Route::get('/quotes', [QuoteController::class, 'index'])->name('admin.quotes')->middleware('auth', 'can:sales.quotes');
Route::get('/get-quotes', [QuoteController::class, 'get'])->name('admin.get_quotes');
Route::get('/create-quote', [QuoteController::class, 'create'])->name('admin.create_quote')->middleware('auth');
Route::post('/load-cart-quotes', [QuoteController::class, 'load_cart'])->name('admin.load_cart_quotes');
Route::post('/searc-product-cart', [QuoteController::class, 'search_product_cart'])->name('admin.search_product_cart');
Route::post('/add-product-quote', [QuoteController::class, 'add_product'])->name('admin.add_product_quote');
Route::post('/store-product-quote', [QuoteController::class, 'store_product'])->name('admin.store_product_quote');
Route::post('/get-clients-quotes', [QuoteController::class, 'get_clients'])->name('admin.get_clients_update');
Route::post('/get-products-update-q', [QuoteController::class, 'get_products_update'])->name('admin.get_products_update_q');
Route::post('/get-serie-quote', [QuoteController::class, 'get_serie'])->name('admin.get_serie_quote');
Route::post('/store-product-quote-price', [QuoteController::class, 'store_product_quote_price'])->name('admin.store_product_quote_price');
Route::post('/delete-product-quote', [QuoteController::class, 'delete_product'])->name('admin.delete_product_quote');
Route::post('/get-price-product-quote', [QuoteController::class, 'get_price_product'])->name('admin.get_price_product_quote');
Route::post('/save-quote', [QuoteController::class, 'save'])->name('admin.save_quote');
Route::post('/print-quote', [QuoteController::class, 'print_quote'])->name('admin.print_quote');
Route::post('/options-print-quote', [QuoteController::class, 'options'])->name('admin.options_print_quote');
Route::get('/test-quote', [QuoteController::class, 'test'])->name('admin.test_quote');
Route::get('/download-quote/{id}', [QuoteController::class, 'download'])->name('admin.download_quote');
Route::post('/gen-quote-voucher', [QuoteController::class, 'gen_voucher'])->name('admin.gen_quote_voucher');
Route::get('/edit-quote-{id}', [QuoteController::class, 'edit'])->name('admin.edit_quote');
Route::post('/get-product-quote-update', [QuoteController::class, 'get_product_update'])->name('admin.get_product_quote_update');
Route::post('/store-product-quote-update', [QuoteController::class, 'store_product_update'])->name('admin.store_product_quote_update');
Route::post('/gen-quote-update', [QuoteController::class, 'gen_update'])->name('admin.gen_quote_update');
## Arching Cash
Route::get('/cashes', [ArchingCashController::class, 'index'])->name('admin.cashes')->middleware('auth', 'can:archingCash');
Route::post('/get-arching-cashes', [ArchingCashController::class, 'get'])->name('admin.get_arching_cashes');
Route::post('/save-arching-cash', [ArchingCashController::class, 'save'])->name('admin.save_arching_cash');
Route::post('/close-cash', [ArchingCashController::class, 'close'])->name('admin.close_cash');
Route::post('/get-detail-cash', [ArchingCashController::class, 'get_detail_cash'])->name('admin.get_detail_cash');
Route::post('/get-detail-cashes', [ArchingCashController::class, 'get_detail_cashes'])->name('admin.get_detail_cashes');
Route::post('/get-summary', [ArchingCashController::class, 'get_summary'])->name('admin.get_summary');
Route::get('/download-detail-cash/{id}', [ArchingCashController::class, 'download'])->name('admin.download_detail_cash');
Route::get('/download-resumen-cash/{id}', [ArchingCashController::class, 'download_resumen'])->name('admin.download_resumen_cash');
Route::get('/download-resumen-cash-ticket/{id}', [ArchingCashController::class, 'download_resumen_ticket'])->name('admin.download_resumen_cash_ticket');
## Sale Note
Route::get('/get-sale-notes', [SaleNoteController::class, 'get'])->name('admin.get_sale_notes');
Route::get('/sale-notes', [SaleNoteController::class, 'index'])->name('admin.sale_notes')->middleware('auth', 'can:sales.sale_notes');
Route::get('/create-sale-note', [SaleNoteController::class, 'create'])->name('admin.create_sale_note')->middleware('auth');
Route::get('/test-sale', [SaleNoteController::class, 'test'])->name('admin.test_sale');
Route::post('/load-serie-sale-note', [SaleNoteController::class, 'load_serie'])->name('admin.load_serie_sale_note');
Route::post('/load-cart-sale-notes', [SaleNoteController::class, 'load_cart'])->name('admin.load_cart_sale_notes');
Route::post('/add-product-sale-note', [SaleNoteController::class, 'add_product'])->name('admin.add_product_sale_note');
Route::post('/delete-product-sale-note', [SaleNoteController::class, 'delete_product'])->name('admin.delete_product_sale_note');
Route::post('/store-product-sale-note', [SaleNoteController::class, 'store_product'])->name('admin.store_product_sale_note');
Route::post('/save-sale-note', [SaleNoteController::class, 'save'])->name('admin.save_sale_note');
Route::get('/test-ticket', [SaleNoteController::class, 'test_ticket'])->name('admin.test_ticket');
Route::post('/print-sale-note', [SaleNoteController::class, 'print'])->name('admin.print_sale_note');
Route::get('/download-sale-note/{id}', [SaleNoteController::class, 'download'])->name('admin.download_sale_note');
Route::post('/anulled-sale-note', [SaleNoteController::class, 'anulled'])->name('admin.anulled_sale_note');
Route::post('/gen-voucher-sale-note', [SaleNoteController::class, 'gen_voucher'])->name('admin.gen_sale_note_voucher');
## POS
Route::get('/pos', [PosController::class, 'index'])->name('admin.pos')->middleware('auth', 'can:pos');
Route::post('/load-view-products', [PosController::class, 'view_products'])->name('admin.load_view_products');
Route::post('/search-view-product', [PosController::class, 'search_view_product'])->name('admin.search_view_product');
Route::post('/load-cart-pos', [PosController::class, 'load_cart'])->name('admin.load_cart_pos');
Route::post('/add-product-pos', [PosController::class, 'add_product'])->name('admin.add_product_pos');
Route::post('/delete-product-pos', [PosController::class, 'delete_product'])->name('admin.delete_product_pos');
Route::post('/store-product-pos', [PosController::class, 'store_product'])->name('admin.store_product_pos');
Route::post('/cancel-cart-pos', [PosController::class, 'cancel_cart'])->name('admin.cancel_cart_pos');
Route::post('/load-serie-pos', [PosController::class, 'load_serie'])->name('admin.load_serie_pos');
Route::post('/get-serie-pos', [PosController::class, 'get_serie'])->name('admin.get_serie_pos');
Route::post('/process-pay-pos', [PosController::class, 'process_pay'])->name('admin.process_pay_pos');
Route::post('/save-billing-pos', [PosController::class, 'save'])->name('admin.save_billing_pos');
Route::get('/test-billing', [PosController::class, 'test'])->name('admin.test_billing');
Route::post('/check-cash-active', [PosController::class, 'check_cash'])->name('admin.check_cash_active');
## Billing
Route::get('/billings', [BillingController::class, 'index'])->name('admin.billings')->middleware('auth', 'can:sales.billings');
Route::get('/get-billings', [BillingController::class, 'get'])->name('admin.get_billings');
Route::post('/update-cdr-bf', [BillingController::class, 'update_cdr_bf'])->name('admin.update_cdr_bf');
Route::post('/send-bf-sunat', [BillingController::class, 'send'])->name('admin.send_bf');
Route::post('/print-billing', [BillingController::class, 'print'])->name('admin.print_billing');
Route::get('/download-billing/{id}', [BillingController::class, 'download'])->name('admin.download_billing');
Route::get('/test-billing', [BillingController::class, 'test'])->name('admin.test_billing');
Route::post('/billings/user-billings', [BillingController::class, 'userBills'])->name('admin.userBillings');
## Reports
Route::get('/inventories-items', [InventoryReportController::class, 'products'])->name('admin.inventory_products')->middleware('auth', 'can:reports.inventory.inventory_products');
Route::post('/search-inventory-products', [InventoryReportController::class, 'search_products'])->name('admin.search_inventory_products');
Route::post('/export-inventory-products', [InventoryReportController::class, 'export_products'])->name('admin.export_inventory_products');
##kardex
Route::get('/kardex', [KardexController::class, 'index'])->name('admin.kardex.index')->middleware('auth', 'can:reports.kardex.kardex_unitario');
Route::post('/kardex/filter', [KardexController::class, 'filter'])->name('admin.kardex.filter');
Route::post('/kardex/download', [KardexController::class, 'download'])->name('admin.kardex.download');
// Customers
Route::get('/contacts-customers', [ContactReportController::class, 'customers'])->name('admin.contact_customers')->middleware('auth', 'can:reports.contacts.contact_customers');
Route::post('/search-inventory-customers', [ContactReportController::class, 'search_customers'])->name('admin.search_contacts_customers');
Route::post('/export-inventory-customers', [ContactReportController::class, 'export_customers'])->name('admin.export_contacts_customers');
// Providers
Route::get('/contacts-providers', [ContactReportController::class, 'providers'])->name('admin.contact_providers')->middleware('auth', 'can:reports.contacts.contact_providers');
Route::post('/search-inventory-providers', [ContactReportController::class, 'search_providers'])->name('admin.search_contacts_providers');
Route::post('/export-inventory-providers', [ContactReportController::class, 'export_providers'])->name('admin.export_contacts_providers');
// Sales General
Route::get('/sales-general', [SaleReportController::class, 'sales_general'])->name('admin.sales_general')->middleware('auth', 'can:reports.sales.sales_general');
Route::post('/search-sales-general', [SaleReportController::class, 'search_sales_general'])->name('admin.search_sales_general');
Route::post('/export-sales-general', [SaleReportController::class, 'export_sales_general'])->name('admin.export_sales_general');
// Sales Seller
Route::get('/sales-seller', [SaleReportController::class, 'sales_seller'])->name('admin.sales_seller')->middleware('auth', 'can:reports.sales.sales_seller');
Route::post('/search-sales-seller', [SaleReportController::class, 'search_sales_seller'])->name('admin.search_sales_seller');
Route::post('/export-sales-seller', [SaleReportController::class, 'export_sales_seller'])->name('admin.export_sales_seller');
// Sales Products
Route::get('/sales-product', [SaleReportController::class, 'sales_product'])->name('admin.sales_product')->middleware('auth', 'can:reports.sales.sales_product');
Route::post('/search-sales-product', [SaleReportController::class, 'search_sales_product'])->name('admin.search_sales_product');
Route::post('/export-sales-product', [SaleReportController::class, 'export_sales_product'])->name('admin.export_sales_product');
// Buys General
Route::get('/purchases-general', [PurchaseReportController::class, 'purchases_general'])->name('admin.purchases_general')->middleware('auth', 'can:reports.purchases.purchases_general');
Route::post('/search-purchase-general', [PurchaseReportController::class, 'search_purchases_general'])->name('admin.search_purchases_general');
Route::post('/export-purchase-general', [PurchaseReportController::class, 'export_purchases_general'])->name('admin.export_purchases_general');
// Buys Provider
Route::get('/purchases-provider', [PurchaseReportController::class, 'purchases_provider'])->name('admin.purchases_provider')->middleware('auth', 'can:reports.purchases.purchases_provider');
Route::post('/search-purchase-provider', [PurchaseReportController::class, 'search_purchases_provider'])->name('admin.search_purchases_provider');
Route::post('/export-purchase-provider', [PurchaseReportController::class, 'export_purchases_provider'])->name('admin.export_purchases_provider');
// Bill
Route::get('/purchases-expenses', [BillReportController::class, 'purchases'])->name('admin.purchases_expenses')->middleware('auth', 'can:reports.purchases.purchases_expenses');
Route::post('/search-purchase-expenses', [BillReportController::class, 'search_expenses'])->name('admin.search_purchases_expenses');
Route::post('/export-purchase-expenses', [BillReportController::class, 'export_expenses'])->name('admin.export_purchases_expenses');
## Credit Note
Route::get('/credit-notes', [CreditNoteController::class, 'index'])->name('admin.credit_notes')->middleware('auth', 'can:sales.credit_notes');
Route::get('/create-nc-{id}', [CreditNoteController::class, 'create'])->name('admin.create_nc')->middleware('auth');
Route::get('/get-credit-notes', [CreditNoteController::class, 'get'])->name('admin.get_credit_notes');
Route::post('/save-nc', [CreditNoteController::class, 'save'])->name('admin.save_nc');
Route::post('/send-nc', [CreditNoteController::class, 'send'])->name('admin.send_nc');
Route::post('/update-cdr-nc', [CreditNoteController::class, 'update_cdr'])->name('admin.update_cdr_nc');
## User
Route::get('/users', [UserController::class, 'index'])->name('admin.users')->middleware('can:contacts.users');
Route::get('/get-users', [UserController::class, 'get'])->name('admin.get_users');
Route::post('/save-user', [UserController::class, 'save'])->name('admin.save_user');
Route::post('/detail-user', [UserController::class, 'detail'])->name('admin.detail_user');
Route::post('/store-user', [UserController::class, 'store'])->name('admin.store_user');
Route::post('/delete-user', [UserController::class, 'delete'])->name('admin.delete_user');
Route::post('/view-role', [UserController::class, 'view_role'])->name('admin.view_role')->middleware('auth');
Route::post('/update-role', [UserController::class, 'update'])->name('admin.update_role');
## Warehouse
Route::get('/warehouses', [WarehouseController::class, 'index'])->name('admin.warehouses')->middleware('auth', 'can:inventary.warehouses');
Route::get('/get-warehouses', [WarehouseController::class, 'get'])->name('admin.get_warehouses');
Route::post('/save-warehouse', [WarehouseController::class, 'save'])->name('admin.save_warehouse');
Route::post('/detail-warehouse', [WarehouseController::class, 'detail'])->name('admin.detail_warehouse');
Route::post('/store-warehouse', [WarehouseController::class, 'store'])->name('admin.store_warehouse');
Route::post('/delete-warehouse', [WarehouseController::class, 'delete'])->name('admin.delete_warehouse');
## Transfer Order
Route::get('/transfer-orders', [TransferOrderController::class, 'index'])->name('admin.transfer_orders')->middleware('auth', 'can:inventary.transfer_orders');
Route::get('/create-transfer-order', [TransferOrderController::class, 'create'])->name('admin.create_transfer_order')->middleware('auth');
Route::get('/get-transfer-orders', [TransferOrderController::class, 'get'])->name('admin.get_transfer_orders');
Route::post('/load-serie-transfer', [TransferOrderController::class, 'load_serie'])->name('admin.load_serie_transfer');
Route::post('/load-warehouse-office', [TransferOrderController::class, 'load_warehouse_office'])->name('admin.load_warehouse_office');
Route::post('/load-warehouse-dispatch', [TransferOrderController::class, 'load_warehouse_dispatch'])->name('admin.load_warehouse_dispatch');
Route::post('/load-cart-transfer', [TransferOrderController::class, 'load_cart'])->name('admin.load_cart_transfer');
Route::post('/search-product-transfer', [TransferOrderController::class, 'search_product'])->name('admin.search_product_transfer');
Route::post('/detail-product-transfer', [TransferOrderController::class, 'detail_product'])->name('admin.detail_product_transfer');
Route::post('/add-product-transfer', [TransferOrderController::class, 'add_product'])->name('admin.add_product_transfer');
Route::post('/delete-product-transfer', [TransferOrderController::class, 'delete_product'])->name('admin.delete_product_transfer');
Route::post('/store-product-transfer', [TransferOrderController::class, 'store_product'])->name('admin.store_product_transfer');
Route::get('/test-transfer', [TransferOrderController::class, 'test'])->name('admin.test_transfer');
Route::post('/save-transfer', [TransferOrderController::class, 'save'])->name('admin.save_transfer');
Route::post('/detail-transfer', [TransferOrderController::class, 'detail'])->name('admin.detail_transfer');
Route::post('/anulled-transfer', [TransferOrderController::class, 'anulled'])->name('admin.anulled_tansfer_order');
Route::post('/move-transfer', [TransferOrderController::class, 'move'])->name('admin.move_transfer');
Route::post('/print-transfer', [TransferOrderController::class, 'print'])->name('admin.print_transfer');
Route::get('/download-transfer-order/{id}', [TransferOrderController::class, 'download'])->name('admin.download_transfer_order');

# RECLAMOS / QUEJAS
Route::get('/ajustes', [AjustesController::class, 'index'])->name('admin.ajustes')->middleware('auth', 'can:reclamos.ajustes');
Route::post('/ajustes', [AjustesController::class, 'save'])->name('admin.ajustes.save')->middleware('auth', 'can:reclamos.ajustes');
Route::post('/ajustes', [AjustesController::class, 'save'])->name('admin.ajustes.save')->middleware('auth', 'can:reclamos.ajustes');

Route::get('/reclamos_quejas', [ReclamosQuejasController::class, 'index'])->name('admin.reclamos_quejas')->middleware('auth', 'can:reclamos.reclamos_quejas');
Route::post('/get-reclamos-quejas', [ReclamosQuejasController::class, 'get'])->name('admin.get_reclamos_quejas')->middleware('auth', 'can:reclamos.reclamos_quejas');
Route::post('/details-reclamos-quejas', [ReclamosQuejasController::class, 'details'])->name('admin.get_detail_reclamos_quejas');
Route::post('/responder-reclamos-quejas', [ReclamosQuejasController::class, 'respuestaReclamo'])->name('admin.responder_reclamos_quejas');
Route::get('/form_reclamos', [ReclamosQuejasController::class, 'form'])->name('admin.reclamos_form');
Route::post('/form_reclamos', [ReclamosQuejasController::class, 'save'])->name('reclamos_quejas.save');
Route::get('/form-reclamos/{id}', [ReclamosQuejasController::class, 'formCopy']);



Route::get('/report_reclamos', [ReporteReclamosController::class, 'index'])->name('admin.report_reclamos.index')->middleware('auth', 'can:reclamos.report_reclamos');
Route::post('/kardex/filter', [KardexController::class, 'filter'])->name('admin.kardex.filter');
Route::post('/kardex/download', [KardexController::class, 'download'])->name('admin.kardex.download');



## Correos
Route::get('/list-correos', [AjustesController::class, 'index'])->name('admin.list_correos')->middleware('auth', 'can:settings.list_correos');
Route::get('/get-correos', [AjustesController::class, 'get'])->name('admin.get_correos');
Route::post('/save-correos', [AjustesController::class, 'save1'])->name('admin.save_correos');
Route::post('/detail-correos', [AjustesController::class, 'detail'])->name('admin.detail_correos');
Route::post('/store-correos', [AjustesController::class, 'store'])->name('admin.store_correos');
Route::post('/delete-correos', [AjustesController::class, 'delete'])->name('admin.delete_correos');

#AccountsReceivable
Route::get('/accounts-receivable', [AccountsReceivableController::class, 'index'])->name('admin.accounts_receivable'); //->middleware('auth', 'can:finanzas.accounts_receivable');
Route::post('/filter-accounts-receivable', [AccountsReceivableController::class, 'filter'])->name('admin.filter_accounts_receivable');
Route::post('/filter-accounts-receivable-quotes', [AccountsReceivableController::class, 'filterQuotes'])->name('admin.filter_accounts_receivable.quotes');
Route::post('/save-accounts-receivable', [AccountsReceivableController::class, 'save'])->name('admin.save_accounts_receivable');
Route::post('/detail-accounts-receivable', [AccountsReceivableController::class, 'detail'])->name('admin.detail_accounts_receivable');
Route::post('/store-accounts-receivable', [AccountsReceivableController::class, 'download'])->name('admin.download_accounts_receivable');
Route::post('/accounts-receivable/details', [AccountsReceivableController::class, 'getDetails'])->name('admin.details_accounts_receivable');
Route::post('/accounts-receivable/amount', [AccountsReceivableController::class, 'getAmount'])->name('admin.amount_accounts_receivable');
Route::post('/accounts-receivable-quotes/delete', [AccountsReceivableController::class, 'delete'])->name('admin.delete_accounts_receivable.quotes');

// documentos avanzados
// Sales General (G.R. REMITENTE)--
Route::get('/gr_remitente', [GRRemitenteController::class, 'index'])->name('admin.gr_remitente')->middleware('auth', 'can:document_avanzados.guias.gr_remitente');
Route::get('/add_gr_remitente', [GRRemitenteController::class, 'add'])->name('admin.add_gr_remitente');
Route::post('/load_cart_gr_remitente', [GRRemitenteController::class, 'load_cart'])->name('admin.load_cart_gr_remitente');
Route::post('/add_product_gr_remitente', [GRRemitenteController::class, 'add_product'])->name('admin.add_product_gr_remitente');
Route::post('/delete_product_gr_remitente', [GRRemitenteController::class, 'delete_product'])->name('admin.delete_product_gr_remitente');
Route::post('/save_gr_remitente', [GRRemitenteController::class, 'save'])->name('admin.save_gr_remitente');
Route::get('/get_gr_remitente', [GRRemitenteController::class, 'get'])->name('admin.get_gr_remitente');


// Sales Seller (G.R. TRASPORTISTA)
Route::get('/gr_transportista', [GRTransportistaController::class, 'index'])->name('admin.gr_transportista')->middleware('auth', 'can:document_avanzados.guias.gr_transportista');
Route::get('/add_gr_transportista', [GRTransportistaController::class, 'add'])->name('admin.add_gr_transportista');
Route::post('/load_cart_gr_transportista', [GRTransportistaController::class, 'load_cart'])->name('admin.load_cart_gr_transportista');
Route::post('/add_product_gr_transportista', [GRTransportistaController::class, 'add_product'])->name('admin.add_product_gr_transportista');
Route::post('/delete_product_gr_transportista', [GRTransportistaController::class, 'delete_product'])->name('admin.delete_product_gr_transportista');
Route::post('/save_gr_transportista', [GRTransportistaController::class, 'save'])->name('admin.save_gr_transportista');
Route::get('/get_gr_transportista', [GRTransportistaController::class, 'get'])->name('admin.get_gr_transportista');


// Sales Products (TRASPORTISTAS)
Route::get('/sales-product1', [DocumentAvanzadosController::class, 'sales_product1'])->name('admin.sales_product1')->middleware('auth', 'can:document_avanzados.guias.transportistas');
Route::get('/transportistas', [DocumentAvanzadosController::class, 'index_t'])->name('admin.transportistas')->middleware('auth', 'can:contacts.clients');
Route::get('/get-transportistas', [DocumentAvanzadosController::class, 'get_t'])->name('admin.get_transportistas');
Route::post('/load-ubigeo-transportistas', [DocumentAvanzadosController::class, 'load_ubigeo_t'])->name('admin.load_ubigeo_transportistas');
Route::post('/search-dni-ruc', [DocumentAvanzadosController::class, 'search_t'])->name('admin.search_dni_ruc');
Route::post('/save-transportistas', [DocumentAvanzadosController::class, 'save_t'])->name('admin.save_transportistas');
Route::post('/detail-transportistas', [DocumentAvanzadosController::class, 'detail_t'])->name('admin.detail_transportistas');
Route::post('/store-transportistas', [DocumentAvanzadosController::class, 'store_t'])->name('admin.store_transportistas');
Route::post('/delete-transportistas', [DocumentAvanzadosController::class, 'delete_t'])->name('admin.delete_transportistas');

// Sales Products (CONDUCTORES)
Route::get('/sales_conductores', [DocumentAvanzadosController::class, 'sales_conductores'])->name('admin.sales_conductores')->middleware('auth', 'can:document_avanzados.guias.drivers');
Route::get('/conductores', [DocumentAvanzadosController::class, 'index_c'])->name('admin.conductores')->middleware('auth', 'can:contacts.clients');
Route::get('/get-conductores', [DocumentAvanzadosController::class, 'get_c'])->name('admin.get_conductores');
Route::post('/load-ubigeo-conductores', [DocumentAvanzadosController::class, 'load_ubigeo_c'])->name('admin.load_ubigeo_conductores');
Route::post('/search-dni-ruc', [DocumentAvanzadosController::class, 'search_c'])->name('admin.search_dni_ruc');
Route::post('/save-conductores', [DocumentAvanzadosController::class, 'save_c'])->name('admin.save_conductores');
Route::post('/detail-conductores', [DocumentAvanzadosController::class, 'detail_c'])->name('admin.detail_conductores');
Route::post('/store-conductores', [DocumentAvanzadosController::class, 'store_c'])->name('admin.store_conductores');
Route::post('/delete-conductores', [DocumentAvanzadosController::class, 'delete_c'])->name('admin.delete_conductores');
// Sales Products (VEHICULOS)
Route::get('/sales_providers', [DocumentAvanzadosController::class, 'sales_providers'])->name('admin.sales_providers')->middleware('auth', 'can:document_avanzados.guias.vehicles');
Route::get('/list-vehiculos', [DocumentAvanzadosController::class, 'index'])->name('admin.list_vehiculos')->middleware('auth', 'can:settings.list_vehiculos');
Route::get('/get-vehiculos', [DocumentAvanzadosController::class, 'get_v'])->name('admin.get_vehiculos');
Route::post('/save-vehiculos', [DocumentAvanzadosController::class, 'save_v'])->name('admin.save_vehiculos');
Route::post('/detail-vehiculos', [DocumentAvanzadosController::class, 'detail_v'])->name('admin.detail_vehiculos');
Route::post('/store-vehiculos', [DocumentAvanzadosController::class, 'store_v'])->name('admin.store_vehiculos');
Route::post('/delete-vehiculos', [DocumentAvanzadosController::class, 'delete_v'])->name('admin.delete_vehiculos');

//(DIRECCIONES DE PARTIDA)
Route::get('/sales_expenses', [DocumentAvanzadosController::class, 'sales_expenses'])->name('admin.sales_expenses')->middleware('auth', 'can:document_avanzados.guias.start_points');
Route::get('/list-dire_partida', [DocumentAvanzadosController::class, 'index'])->name('admin.list_dire_partida')->middleware('auth', 'can:settings.list_dire_partida');
Route::get('/get-dire_partida', [DocumentAvanzadosController::class, 'get'])->name('admin.get_dire_partida');
Route::post('/save-dire_partida', [DocumentAvanzadosController::class, 'save1'])->name('admin.save_dire_partida');
Route::post('/detail-dire_partida', [DocumentAvanzadosController::class, 'detail'])->name('admin.detail_dire_partida');
Route::post('/store-dire_partida', [DocumentAvanzadosController::class, 'store'])->name('admin.store_dire_partida');
Route::post('/delete-dire_partida', [DocumentAvanzadosController::class, 'delete'])->name('admin.delete_dire_partida');


