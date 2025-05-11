<!DOCTYPE html>
<html
  lang="es"
  class="light-style layout-navbar-fixed layout-menu-fixed {{ request()->is('billings') || request()->is('credit-notes') || request()->is('sales-general') || request()->is('sales-seller') || request()->is('purchases-general') || request()->is('purchases-provider') || request()->is('purchases-expenses') || request()->is('inventories-items') || request()->is('prices') ? 'layout-menu-collapsed' : '' }}"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="assets/"
  data-template="vertical-menu-template">
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Facturaci&oacute;n Electr&oacute;nica</title>

    <meta name="description" content="Sistema de Facturación Electrónica desarrollado por Erby 365" />
    <meta name="author" content="Erby 365.">
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/img/favicon/icon-login.ico') }}">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/fontawesome.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/tabler-icons.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/flag-icons.css') }}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />


    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/node-waves/node-waves.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/typeahead-js/typeahead.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-rowgroup-bs5/rowgroup.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/pnotify/pnotify.custom.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/spinkit/spinkit.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/theme-default.css') }}" class="template-customizer-theme-css" />
    @yield('styles')

    <script src="{{ asset('assets/vendor/js/template-customizer.js') }}"></script>

    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{ asset('assets/js/config.js') }}"></script>
  </head>

  <body>
    @php
      // Consultar tipo de cambio PEN - USD
      $params = json_encode([
          'fecha' => date('Y-m-d'),
          'moneda' => 'USD'
      ]);
      $curl = curl_init();
      curl_setopt_array($curl, [
          CURLOPT_URL => "https://apiperu.dev/api/tipo_de_cambio",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_SSL_VERIFYPEER => false,
          CURLOPT_POSTFIELDS => $params,
          CURLOPT_HTTPHEADER => [
              'Accept: application/json',
              'Content-Type: application/json',
              'Authorization: Bearer a3e20be04068cd29796811fcdc6a4e79c32124c84fbe072e54afcb4b1d28ea86'
          ],
      ]);
      $response = json_decode(curl_exec($curl));
      $err = curl_error($curl);
      curl_close($curl);
      if ($err) {
          session([ 'cambio' => 3.70 ]); // tipo de cambio en caso de error o de no haber conexión a internet
      } else {
          session([ 'cambio' => $response->data->purchase ?? 3.70 ]);
      }
    @endphp
    <div id="ID-load" class="ID-load">
        <div>
            <div>
                <img src="{{ asset('assets/img/branding/load.png') }}" alt="" style="width: 55px;">
            </div>
            <div class="id-load-content">
                <div class="id-load-icon id-shop">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                        <path class="fa-primary"
                            d="M490.3 13.1l57.3 90.7c29.7 46.9 3.4 112-52.1 119.4c-4 .5-7.9 .8-12.1 .8c-26.1 0-49.2-11.4-65.2-29c-15.9 17.6-39 29-65.2 29c-26.1 0-49.3-11.4-65.2-29c-15.9 17.6-39 29-65.2 29c-26.1 0-49.3-11.4-65.2-29c-15.9 17.6-39.1 29-65.2 29c-4.1 0-8.2-.3-12.1-.8c-55.3-7.4-81.5-72.6-51.9-119.4L85.7 13.1C90.8 5 99.9 0 109.6 0H466.4c9.7 0 18.8 5 23.9 13.1z" />
                        <path class="fa-secondary"
                            d="M64 219.1V384v64c0 35.3 28.7 64 64 64H448c35.3 0 64-28.7 64-64V384 219.1c-5.1 2-10.6 3.4-16.5 4.1c-4 .5-7.9 .8-12.1 .8c-12.7 0-24.6-2.7-35.4-7.5V384H128V216.5c-10.8 4.8-22.9 7.5-35.6 7.5c-4.1 0-8.2-.3-12.1-.8c-5.7-.8-11.2-2.2-16.2-4.1z" />
                    </svg>
                </div>
                <div class="id-load-icon id-cart">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                        <path class="fa-primary"
                            d="M0 24C0 10.7 10.7 0 24 0H69.5c26.9 0 50 19.1 55 45.5l51.6 271c2.2 11.3 12.1 19.5 23.6 19.5H488c13.3 0 24 10.7 24 24s-10.7 24-24 24H199.7c-34.6 0-64.3-24.6-70.7-58.5L77.4 54.5c-.7-3.8-4-6.5-7.9-6.5H24C10.7 48 0 37.3 0 24zM128 464a48 48 0 1 1 96 0 48 48 0 1 1 -96 0zm336-48a48 48 0 1 1 0 96 48 48 0 1 1 0-96z" />
                        <path class="fa-secondary"
                            d="M170.7 288H459.2c32.6 0 61.1-21.8 69.5-53.3l41-152.3C576.6 57 557.4 32 531.1 32h-411c2 4.2 3.5 8.8 4.4 13.5L170.7 288z" />
                    </svg>
                </div>

                <div class="id-load-icon id-register">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path class="fa-primary"
                            d="M0 448V378.4c0-4.8 .4-9.6 1.1-14.4L23.8 214.4C28.5 183.1 55.4 160 87 160H425c31.6 0 58.5 23.1 63.3 54.4l22.7 149.6c.7 4.8 1.1 9.6 1.1 14.4V448c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64zm64-16c0 8.8 7.2 16 16 16H432c8.8 0 16-7.2 16-16s-7.2-16-16-16H80c-8.8 0-16 7.2-16 16zm48-216a24 24 0 1 0 0 48 24 24 0 1 0 0-48zm72 24a24 24 0 1 0 48 0 24 24 0 1 0 -48 0zm-24 56a24 24 0 1 0 0 48 24 24 0 1 0 0-48zm120-56a24 24 0 1 0 48 0 24 24 0 1 0 -48 0zm-24 56a24 24 0 1 0 0 48 24 24 0 1 0 0-48zm120-56a24 24 0 1 0 48 0 24 24 0 1 0 -48 0zm-24 56a24 24 0 1 0 0 48 24 24 0 1 0 0-48z" />
                        <path class="fa-secondary"
                            d="M64 0C46.3 0 32 14.3 32 32V96c0 17.7 14.3 32 32 32h80v32h64V128h80c17.7 0 32-14.3 32-32V32c0-17.7-14.3-32-32-32H64zM96 48H256c8.8 0 16 7.2 16 16s-7.2 16-16 16H96c-8.8 0-16-7.2-16-16s7.2-16 16-16zm16 168a24 24 0 1 0 0 48 24 24 0 1 0 0-48zm72 24a24 24 0 1 0 48 0 24 24 0 1 0 -48 0zm-24 56a24 24 0 1 0 0 48 24 24 0 1 0 0-48zm120-56a24 24 0 1 0 48 0 24 24 0 1 0 -48 0zm-24 56a24 24 0 1 0 0 48 24 24 0 1 0 0-48zm120-56a24 24 0 1 0 48 0 24 24 0 1 0 -48 0zm-24 56a24 24 0 1 0 0 48 24 24 0 1 0 0-48z" />
                    </svg>
                </div>
                <div class="id-load-icon id-report">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path class="fa-primary"
                            d="M505 44c11 13.8 8.8 33.9-5 45L340 217c-11.4 9.1-27.5 9.4-39.2 .6L192.6 136.5 52 249c-13.8 11-33.9 8.8-45-5s-8.8-33.9 5-45L172 71c11.4-9.1 27.5-9.4 39.2-.6l108.2 81.1L460 39c13.8-11 33.9-8.8 45 5z" />
                        <path class="fa-secondary"
                            d="M192 224c-17.7 0-32 14.3-32 32V448c0 17.7 14.3 32 32 32s32-14.3 32-32V256c0-17.7-14.3-32-32-32zM64 320c-17.7 0-32 14.3-32 32v96c0 17.7 14.3 32 32 32s32-14.3 32-32V352c0-17.7-14.3-32-32-32zm224 0V448c0 17.7 14.3 32 32 32s32-14.3 32-32V320c0-17.7-14.3-32-32-32s-32 14.3-32 32zm160-96c-17.7 0-32 14.3-32 32V448c0 17.7 14.3 32 32 32s32-14.3 32-32V256c0-17.7-14.3-32-32-32z" />
                    </svg>
                </div>

                <div class="id-load-icon id-sale">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path class="fa-primary"
                            d="M477.6 19.8c-1.5-3.7-3.8-7.3-6.9-10.3c0 0-.1-.1-.1-.1C464.8 3.6 456.8 0 448 0h0H352c-17.7 0-32 14.3-32 32s14.3 32 32 32h18.7l-84.4 84.4L196.8 71.7c-12-10.3-29.7-10.3-41.7 0l-112 96c-13.4 11.5-15 31.7-3.5 45.1s31.7 15 45.1 3.5L176 138.1l91.2 78.1c12.7 10.9 31.6 10.2 43.5-1.7L416 109.3V128c0 17.7 14.3 32 32 32s32-14.3 32-32V32v0c0 0 0-.1 0-.1c0-4.3-.9-8.4-2.4-12.2z" />
                        <path class="fa-secondary"
                            d="M48 256c-26.5 0-48 21.5-48 48V464c0 26.5 21.5 48 48 48H464c26.5 0 48-21.5 48-48V304c0-26.5-21.5-48-48-48H48zM96 464H48V416c26.5 0 48 21.5 48 48zM48 352V304H96c0 26.5-21.5 48-48 48zM416 464c0-26.5 21.5-48 48-48v48H416zm48-112c-26.5 0-48-21.5-48-48h48v48zM256 320a64 64 0 1 1 0 128 64 64 0 1 1 0-128z" />
                    </svg>
                </div>
                <div class="id-load-icon id-invetory">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512">
                        <path class="fa-primary"
                            d="M176 48A48 48 0 1 0 80 48a48 48 0 1 0 96 0zM144 306.7V241.7l19 28.5c4.6 7 11 12.6 18.5 16.3l60.2 30.1c15.8 7.9 35 1.5 42.9-14.3s1.5-35-14.3-42.9l-56.4-28.2L166.5 160c-13.3-20-35.8-32-59.9-32C74.2 128 48 154.2 48 186.6v88.1c0 17 6.7 33.3 18.7 45.3l79.4 79.4 14.3 85.9c2.9 17.4 19.4 29.2 36.8 26.3s29.2-19.4 26.3-36.8l-15.2-90.9c-1.6-9.9-6.3-19-13.4-26.1l-51-51zM2.3 468.1c-6.6 16.4 1.4 35 17.8 41.6s35-1.4 41.6-17.8l37.6-94L50.1 348.6 2.3 468.1zM464 48a48 48 0 1 0 96 0 48 48 0 1 0 -96 0zm32 258.7l-51 51c-7.1 7.1-11.8 16.2-13.4 26.1l-15.2 90.9c-2.9 17.4 8.9 33.9 26.3 36.8s33.9-8.9 36.8-26.3l14.3-85.9L573.3 320c12-12 18.7-28.3 18.7-45.3V186.6c0-32.4-26.2-58.6-58.6-58.6c-24.1 0-46.5 12-59.9 32l-47.4 71.1-56.4 28.2c-15.8 7.9-22.2 27.1-14.3 42.9s27.1 22.2 42.9 14.3l60.2-30.1c7.5-3.7 13.8-9.4 18.5-16.3l19-28.5v65.1zM637.7 468.1L589.9 348.6l-49.2 49.2 37.6 94c6.6 16.4 25.2 24.4 41.6 17.8s24.4-25.2 17.8-41.6z" />
                        <path class="fa-secondary"
                            d="M248.6 319.2c-2.4-.6-4.7-1.4-7-2.5l-.6-.3c2.4 1.2 4.9 2.2 7.5 2.8zm134.7 .8H256.6c11.5-.2 22.5-6.7 28-17.7c7.9-15.8 1.5-35-14.3-42.9L224 236.2V160c0-17.7 14.3-32 32-32H384c17.7 0 32 14.3 32 32v76.2l-46.3 23.2c-15.8 7.9-22.2 27.1-14.3 42.9c5.5 11 16.5 17.4 28 17.7zm8.5-1c2.5-.6 4.8-1.5 7-2.7l-.5 .3c-2.1 1.1-4.3 1.9-6.5 2.4z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!--  -->

    <!-- Layout wrapper -->
    <div id="layout-content" class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        <!-- Menu -->

        <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
          <div class="app-brand demo">
            <a href="{{ route('admin.home') }}" class="app-brand-link d-flex flex-column align-items-center">
                <span class="app-brand-text demo">
                    <img src="{{ asset('assets/img/icons/logo-horizontal.png') }}" alt=""
                    style="width: 160px !important;">
                </span>
{{--                <span class="app-brand-text demo menu-text fw-bold" style="font-size: 15px;">DEMO-ERBY365</span>--}}
            </a>

            <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
                <i class="ti menu-toggle-icon d-none d-xl-block ti-sm align-middle"></i>
                <i class="ti ti-x d-block d-xl-none ti-sm align-middle"></i>
            </a>
          </div>

          <div class="menu-inner-shadow"></div>

          <ul class="menu-inner py-1">
            <!-- Dashboards -->
            <!-- Layouts -->

            <!-- Apps & Pages -->
            <li class="menu-header small text-uppercase">
              <span class="menu-header-text">MENU de Facturaci&oacute;n</span>
            </li>
            @can('dashboard')
              <li class="menu-item {{ request()->is('home') ? 'active' : '' }}">
                <a href="{{ route('admin.home') }}" class="menu-link">
                  <i class="menu-icon" data-feather="bar-chart"></i>
                  <div data-i18n="Principal"> Principal</div>
                </a>
              </li>
            @endcan
            @can('archingCash')
              <li class="menu-item {{ request()->is('cashes') ? 'active' : '' }}">
                <a href="{{ route('admin.cashes') }}" class="menu-link">
                  <i class="menu-icon" data-feather="credit-card"></i>
                  <div data-i18n="Administrar Cajas">Administrar Cajas</div>
                </a>
              </li>
            @endcan
            @can('alerts.alerts_sale', 'alerts.alerts_stock', 'alerts.alerts_expiration')
              <li class="menu-item {{ request()->is('alerts-stock') || request()->is('alerts-expiration') || request()->is('alerts-sale')  ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                  <i class="menu-icon" data-feather="bell"></i>
                  <div data-i18n="Alertas">Alertas</div>
                </a>
                <ul class="menu-sub">
                  @can('alerts.alerts_sale')
                    <li class="menu-item {{ request()->is('alerts-sale') ? 'active' : '' }}">
                      <a href="{{ route('admin.alerts_sale') }}" class="menu-link">
                        <div data-i18n="Pendientes SUNAT">Pendientes SUNAT</div>
                      </a>
                    </li>
                  @endcan
                  @can('alerts.alerts_stock')
                    <li class="menu-item {{ request()->is('alerts-stock') ? 'active' : '' }}">
                      <a href="{{ route('admin.alerts_stock') }}" class="menu-link">
                        <div data-i18n="Productos por Agotar">Productos por Agotar</div>
                      </a>
                    </li>
                  @endcan
                  @can('alerts.alerts_expiration')
                    <li class="menu-item {{ request()->is('alerts-expiration') ? 'active' : '' }}">
                      <a href="{{ route('admin.alerts_expiration') }}" class="menu-link">
                        <div data-i18n="Productos por Vencer">Productos por Vencer</div>
                      </a>
                    </li>
                  @endcan
                </ul>
              </li>
            @endcan
            @can('sales.billings', 'sales.credit_notes', 'sales.sale_notes', 'sales.quotes')
              <li class="menu-item {{ request()->is('quotes') || request()->is('create-quote')  || request()->is('sale-notes') || request()->is('create-sale-note') || request()->is('billings') || request()->is('credit-notes') || request()->routeIs('admin.create_nc') || request()->routeIs('admin.edit_quote') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                  <i class="menu-icon" data-feather="file-text"></i>
                  <div data-i18n="Ventas">Ventas</div>
                </a>
                <ul class="menu-sub">
                  @can('sales.billings')
                    <li class="menu-item {{ request()->is('billings') || request()->routeIs('admin.create_nc') ? 'active' : '' }}">
                      <a href="{{ route('admin.billings') }}" class="menu-link">
                        <div data-i18n="Listado de Ventas">Listado de Ventas</div>
                      </a>
                    </li>
                  @endcan
                  @can('sales.credit_notes')
                    <li class="menu-item {{ request()->is('credit-notes') ? 'active' : '' }}">
                      <a href="{{ route('admin.credit_notes') }}" class="menu-link">
                        <div data-i18n="Notas de Crédito">Notas de Crédito</div>
                      </a>
                    </li>
                  @endcan
                  @can('sales.sale_notes')
                    <li class="menu-item {{ request()->is('sale-notes') || request()->is('create-sale-note') ? 'active' : '' }}">
                      <a href="{{ route('admin.sale_notes') }}" class="menu-link">
                        <div data-i18n="Notas de Venta">Notas de Venta</div>
                      </a>
                    </li>
                  @endcan
                  @can('sales.quotes')
                    <li class="menu-item {{ request()->is('quotes') || request()->is('create-quote') || request()->routeIs('admin.edit_quote') ? 'active' : '' }}">
                      <a href="{{ route('admin.quotes') }}" class="menu-link">
                        <div data-i18n="Cotizaciones">Cotizaciones</div>
                      </a>
                    </li>
                  @endcan
                  @can('sales.credits')
                    <li class="menu-item {{ request()->is('credits') || request()->is('create-venta') || request()->routeIs('admin.edit_credits') ? 'active' : '' }}">
                      <a href="{{ route('admin.credits') }}" class="menu-link">
                        <div data-i18n="Ventas General">Ventas General</div>
                      </a>
                    </li>
                  @endcan
                </ul>
              </li>
            @endcan

            @can('finanzas.accounts_receivable')
              <li class="menu-item {{ request()->is('accounts-receivable') ? 'active open' : '' }}">

                <a href="javascript:void(0);" class="menu-link menu-toggle">
                  <i class="menu-icon" data-feather="shopping-cart"></i>
                  <div data-i18n="Finanzas">Finanzas</div>
                </a>
                <ul class="menu-sub">
                  @can('finanzas.accounts_receivable')
                    <li class="menu-item {{ request()->is('cuentas') ? 'active' : '' }}">
                      <a href="{{ route('admin.accounts_receivable') }}" class="menu-link">
                        <div data-i18n="Cuentas Por Cobrar">Cuentas Por Cobrar</div>
                      </a>
                    </li>
                  @endcan


                </ul>
              </li>
            @endcan

            @can('buys.buys', 'buys.create_buy', 'buys.bills')
              <li class="menu-item {{ request()->is('bills') || request()->is('buys') || request()->is('create-buy')  ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                  <i class="menu-icon" data-feather="shopping-cart"></i>
                  <div data-i18n="Compras">Compras</div>
                </a>
                <ul class="menu-sub">
                  @can('buys.create_buy')
                    <li class="menu-item {{ request()->is('create-buy') ? 'active' : '' }}">
                      <a href="{{ route('admin.create_buy') }}" class="menu-link">
                        <div data-i18n="Compra de Mercadería">Compra de Mercadería</div>
                      </a>
                    </li>
                  @endcan
                  @can('buys.buys')
                    <li class="menu-item {{ request()->is('buys') ? 'active' : '' }}">
                      <a href="{{ route('admin.buys') }}" class="menu-link">
                        <div data-i18n="Listado de Compras">Listado de Compras</div>
                      </a>
                    </li>
                  @endcan
                  @can('buys.bills')
                    <li class="menu-item {{ request()->is('bills') ? 'active' : '' }}">
                      <a href="{{ route('admin.bills') }}" class="menu-link">
                        <div data-i18n="Gastos">Gastos</div>
                      </a>
                    </li>
                  @endcan
                </ul>
              </li>
            @endcan

              {{--            @can(true)--}}
              <li class="menu-item {{ request()->is('accounts-receivable')  ? 'active open' : '' }}">
                  <a href="javascript:void(0);" class="menu-link menu-toggle">
                      <svg xmlns="http://www.w3.org/2000/svg" width="16" viewBox="0 0 512 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M512 80c0 18-14.3 34.6-38.4 48c-29.1 16.1-72.5 27.5-122.3 30.9c-3.7-1.8-7.4-3.5-11.3-5C300.6 137.4 248.2 128 192 128c-8.3 0-16.4 .2-24.5 .6l-1.1-.6C142.3 114.6 128 98 128 80c0-44.2 86-80 192-80S512 35.8 512 80zM160.7 161.1c10.2-.7 20.7-1.1 31.3-1.1c62.2 0 117.4 12.3 152.5 31.4C369.3 204.9 384 221.7 384 240c0 4-.7 7.9-2.1 11.7c-4.6 13.2-17 25.3-35 35.5c0 0 0 0 0 0c-.1 .1-.3 .1-.4 .2c0 0 0 0 0 0s0 0 0 0c-.3 .2-.6 .3-.9 .5c-35 19.4-90.8 32-153.6 32c-59.6 0-112.9-11.3-148.2-29.1c-1.9-.9-3.7-1.9-5.5-2.9C14.3 274.6 0 258 0 240c0-34.8 53.4-64.5 128-75.4c10.5-1.5 21.4-2.7 32.7-3.5zM416 240c0-21.9-10.6-39.9-24.1-53.4c28.3-4.4 54.2-11.4 76.2-20.5c16.3-6.8 31.5-15.2 43.9-25.5l0 35.4c0 19.3-16.5 37.1-43.8 50.9c-14.6 7.4-32.4 13.7-52.4 18.5c.1-1.8 .2-3.5 .2-5.3zm-32 96c0 18-14.3 34.6-38.4 48c-1.8 1-3.6 1.9-5.5 2.9C304.9 404.7 251.6 416 192 416c-62.8 0-118.6-12.6-153.6-32C14.3 370.6 0 354 0 336l0-35.4c12.5 10.3 27.6 18.7 43.9 25.5C83.4 342.6 135.8 352 192 352s108.6-9.4 148.1-25.9c7.8-3.2 15.3-6.9 22.4-10.9c6.1-3.4 11.8-7.2 17.2-11.2c1.5-1.1 2.9-2.3 4.3-3.4l0 3.4 0 5.7 0 26.3zm32 0l0-32 0-25.9c19-4.2 36.5-9.5 52.1-16c16.3-6.8 31.5-15.2 43.9-25.5l0 35.4c0 10.5-5 21-14.9 30.9c-16.3 16.3-45 29.7-81.3 38.4c.1-1.7 .2-3.5 .2-5.3zM192 448c56.2 0 108.6-9.4 148.1-25.9c16.3-6.8 31.5-15.2 43.9-25.5l0 35.4c0 44.2-86 80-192 80S0 476.2 0 432l0-35.4c12.5 10.3 27.6 18.7 43.9 25.5C83.4 438.6 135.8 448 192 448z"/></svg>
                      <div data-i18n="&nbsp;&nbsp;Finanzas">Finanzas</div>
                  </a>
                  <ul class="menu-sub">
                      @can('buys.create_buy')
                          <li class="menu-item {{ request()->is('accounts-receivable') ? 'active' : '' }}">
                              <a href="{{ route('admin.accounts_receivable') }}" class="menu-link">
                                  <div data-i18n="Cuentas por Cobrar">Cuentas por Cobrar</div>
                              </a>
                          </li>
                      @endcan
                      @can('buys.buys')
                          <li class="menu-item {{ request()->is('accounts-receivable') ? 'active' : '' }}">
                              <a href="{{ route('admin.accounts_receivable') }}" class="menu-link">
                                  <div data-i18n="Cuentas de Pagar">Cuentas por Pagar</div>
                              </a>
                          </li>
                      @endcan
                  </ul>
              </li>
              {{--            @endcan--}}


            @can('inventary.products', 'inventary.warehouses', 'inventary.transfer_orders')
            <li class="menu-item {{ request()->is('products') || request()->is('warehouses') || request()->is('transfer-orders') || request()->routeIs('admin.create_transfer_order') ? 'active open' : '' }}">
              <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon" data-feather="package"></i>
                <div data-i18n="Inventario">Inventario</div>
              </a>
              <ul class="menu-sub">
                @can('inventary.products')
                  <li class="menu-item {{ request()->is('products') ? 'active' : '' }}">
                    <a href="{{ route('admin.products') }}" class="menu-link">
                      <div data-i18n="Productos / Servicios">Productos / Servicios</div>
                    </a>
                  </li>
                @endcan
                @can('inventary.warehouses')
                  <li class="menu-item {{ request()->is('warehouses') ? 'active' : '' }}">
                    <a href="{{ route('admin.warehouses') }}" class="menu-link">
                      <div data-i18n="Tiendas">Tiendas</div>
                    </a>
                  </li>
                @endcan
                @can('inventary.transfer_orders')
                  <li class="menu-item {{ request()->is('transfer-orders') || request()->routeIs('admin.create_transfer_order') ? 'active' : '' }}">
                    <a href="{{ route('admin.transfer_orders') }}" class="menu-link">
                      <div data-i18n="Orden de Traslados">Orden de Traslados</div>
                    </a>
                  </li>
                @endcan
              </ul>
            </li>
            @endcan


            @can('document_avanzados.guias.gr_remitente', 'document_avanzados.guias.gr_transportista', 'document_avanzados.guias.transportistas',
                'document_avanzados.guias.drivers', 'document_avanzados.guias.vehicles', 'document_avanzados.guias.start_points')
            <!-- Extended components -->
              <li class="menu-item {{ request()->is('gr_remitente') || request()->is('gr_transportista') || request()->is('transportistas') || request()->is('add_gr_remitente') || request()->is('add_gr_transportista') ? 'active open' : '' }}">
                <a href="javascript:void(0)" class="menu-link menu-toggle">
                  <i class="menu-icon" data-feather="calendar"></i>
                  <div data-i18n="Documentos Avanzados">Documentos Avanzados</div>
                </a>
                <ul class="menu-sub">
                  @can('document_avanzados.guias.gr_remitente', 'document_avanzados.guias.gr_transportista', 'document_avanzados.guias.sales_product1')
                    <li class="menu-item {{ request()->is('gr_remitente') || request()->is('gr_transportista') || request()->is('sales-product1') || request()->is('add_gr_remitente') || request()->is('add_gr_transportista') ? 'open active' : '' }}">
                      <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <div data-i18n="Guia de Remision">Guia de Remision</div>
                      </a>
                      <ul class="menu-sub">
                        @can('document_avanzados.guias.gr_remitente')
                          <li class="menu-item {{ request()->is('gr_remitente') || request()->is('add_gr_remitente') ? 'active' : '' }}">
                            <a href="{{ route('admin.gr_remitente') }}" class="menu-link">
                              <div data-i18n="G.R.Remitente">G.R.Remitente</div>
                            </a>
                          </li>
                        @endcan


                        @can('document_avanzados.guias.gr_transportista')
                          <li class="menu-item {{ request()->is('gr_transportista') || request()->is('add_gr_transportista') ? 'active' : '' }}">
                            <a href="{{ route('admin.gr_transportista') }}" class="menu-link">
                              <div data-i18n="G.R. Transportista">G.R. Transportista</div>
                            </a>
                          </li>
                        @endcan

                        @can('document_avanzados.guias.transportistas')
                            <li class="menu-item {{ request()->is('sales-product1') ? 'active' : '' }}">
                                <a href="{{ route('admin.sales_product1') }}" class="menu-link">
                                    <div data-i18n="Transportistas">Transportistas</div>
                                </a>
                            </li>
                        @endcan

                        @can('document_avanzados.guias.drivers')
                          <li class="menu-item {{ request()->is('sales-product1') ? 'active' : '' }}">
                            <a href="{{ route('admin.sales_conductores') }}" class="menu-link">
                              <div data-i18n="Conductores">Conductores</div>
                            </a>
                          </li>
                        @endcan

                        @can('document_avanzados.guias.vehicles')
                            <li class="menu-item {{ request()->is('sales-product1') ? 'active' : '' }}">
                                <a href="{{ route('admin.sales_providers') }}" class="menu-link">
                                    <div data-i18n="Vehículos">Vehículos</div>
                                </a>
                            </li>
                        @endcan

                        @can('document_avanzados.guias.start_points')
                            <li class="menu-item {{ request()->is('sales-product1') ? 'active' : '' }}">
                                <a href="{{ route('admin.sales_expenses') }}" class="menu-link">
                                    <div data-i18n="Direcciones de partida">Direcciones de partida</div>
                                </a>
                            </li>
                        @endcan
                      </ul>
                    </li>
                  @endcan


                </ul>
              </li>
            @endcan



            @can('pos')
              <li class="menu-item {{ request()->is('pos') ? 'active' : '' }}">
                <a href="{{ route('admin.pos') }}" class="menu-link window-open-pos" data-iduser="{{ Auth::user()['id'] }}" data-idcash="{{ Auth::user()['idcaja'] }}">
                  <i class="menu-icon" data-feather="shopping-bag"></i>
                  <div data-i18n="Terminal POS">Terminal POS</div>
                </a>
              </li>
            @endcan
            @can('contacts.clients', 'contacts.providers', 'contacts.users', 'contacts.roles')
              <li class="menu-item {{ request()->is('roles') || request()->is('clients') || request()->is('providers') || request()->is('users') || request()->routeIs('admin.view_role') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                  <i class="menu-icon" data-feather="users"></i>
                  <div data-i18n="Contactos">Contactos</div>
                </a>
                <ul class="menu-sub">
                  @can('contacts.clients')
                    <li class="menu-item {{ request()->is('clients') ? 'active' : '' }}">
                      <a href="{{ route('admin.clients') }}" class="menu-link">
                        <div data-i18n="Clientes">Clientes</div>
                      </a>
                    </li>
                  @endcan
                  @can('contacts.providers')
                    <li class="menu-item {{ request()->is('providers') ? 'active' : '' }}">
                      <a href="{{ route('admin.providers') }}" class="menu-link">
                        <div data-i18n="Proveedores">Proveedores</div>
                      </a>
                    </li>
                  @endcan
                  @can('contacts.users')
                    <li class="menu-item {{ request()->is('users') || request()->routeIs('admin.view_role') ? 'active' : '' }}">
                      <a href="{{ route('admin.users') }}" class="menu-link">
                        <div data-i18n="Usuarios">Usuarios</div>
                      </a>
                    </li>
                  @endcan
                  @can('contacts.roles')
                    <li class="menu-item {{ request()->is('roles') ? 'active' : '' }}">
                      <a href="{{ route('admin.roles') }}" class="menu-link">
                        <div data-i18n="Roles">Roles</div>
                      </a>
                    </li>
                  @endcan
                </ul>
              </li>
            @endcan

            @can('reports.sales.sales_general', 'reports.sales.sales_seller', 'reports.sales.sales_product',
            'reports.purchases.purchases_general', 'reports.purchases.purchases_provider', 'reports.purchases.purchases_expenses',
            'reports.contacts.contact_customers', 'reports.contacts.contact_providers', 'reports.inventory.inventory_products',
            'reports.kardex.purchases_expenses')
            <!-- Extended components -->
              <li class="menu-item {{ request()->is('inventories-items') || request()->is('contacts-customers') || request()->is('contacts-providers') || request()->is('purchases-expenses') || request()->is('purchases-provider') || request()->is('purchases-general') || request()->is('sales-general') || request()->is('sales-seller') || request()->is('sales-product') || request()->is('kardex') ? 'active open' : '' }}">
                <a href="javascript:void(0)" class="menu-link menu-toggle">
                  <i class="menu-icon" data-feather="calendar"></i>
                  <div data-i18n="Reportes">Reportes</div>
                </a>
                <ul class="menu-sub">
                  @can('reports.sales.sales_general', 'reports.sales.sales_seller', 'reports.sales.sales_product')
                    <li class="menu-item {{ request()->is('sales-general') || request()->is('sales-seller') || request()->is('sales-product') ? 'open active' : '' }}">
                      <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <div data-i18n="Ventas">Ventas</div>
                      </a>
                      <ul class="menu-sub">
                        @can('reports.sales.sales_general')
                          <li class="menu-item {{ request()->is('sales-general') ? 'active' : '' }}">
                            <a href="{{ route('admin.sales_general') }}" class="menu-link">
                              <div data-i18n="Ventas General">Ventas General</div>
                            </a>
                          </li>
                        @endcan
                        @can('reports.sales.sales_seller')
                          <li class="menu-item {{ request()->is('sales-seller') ? 'active' : '' }}">
                            <a href="{{ route('admin.sales_seller') }}" class="menu-link">
                              <div data-i18n="Ventas por Vendedor">Ventas por Vendedor</div>
                            </a>
                          </li>
                        @endcan
                        @can('reports.sales.sales_product')
                          <li class="menu-item {{ request()->is('sales-product') ? 'active' : '' }}">
                            <a href="{{ route('admin.sales_product') }}" class="menu-link">
                              <div data-i18n="Prod. más Vendidos">Prod. más Vendidos</div>
                            </a>
                          </li>
                        @endcan
                      </ul>
                    </li>
                  @endcan
                  @can('reports.purchases.purchases_general', 'reports.purchases.purchases_provider', 'reports.purchases.purchases_expenses')
                    <li class="menu-item {{ request()->is('purchases-expenses') || request()->is('purchases-provider') || request()->is('purchases-general') ? 'open active' : '' }}">
                      <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <div data-i18n="Compras">Compras</div>
                      </a>
                      <ul class="menu-sub">
                        @can('reports.purchases.purchases_general')
                          <li class="menu-item {{ request()->is('purchases-general') ? 'active' : '' }}">
                            <a href="{{  route('admin.purchases_general')  }}" class="menu-link">
                              <div data-i18n="Compras General">Compras General</div>
                            </a>
                          </li>
                        @endcan
                        @can('reports.purchases.purchases_provider')
                          <li class="menu-item {{ request()->is('purchases-provider') ? 'active' : '' }}">
                            <a href="{{ route('admin.purchases_provider') }}" class="menu-link">
                              <div data-i18n="Compras Proveedor">Compras Proveedor</div>
                            </a>
                          </li>
                        @endcan
                        @can('reports.purchases.purchases_expenses')
                          <li class="menu-item {{ request()->is('purchases-expenses') ? 'active' : '' }}">
                            <a href="{{ route('admin.purchases_expenses') }}" class="menu-link">
                              <div data-i18n="Gastos">Gastos</div>
                            </a>
                          </li>
                        @endcan
                      </ul>
                    </li>
                  @endcan
                  @can('reports.contacts.contact_customers', 'reports.contacts.contact_providers')
                    <li class="menu-item {{ request()->is('contacts-customers') || request()->is('contacts-providers') ? 'open active' : '' }}">
                      <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <div data-i18n="Contactos">Contactos</div>
                      </a>
                      <ul class="menu-sub">
                        @can('reports.contacts.contact_customers')
                          <li class="menu-item {{ request()->is('contacts-customers') ? 'active' : '' }}">
                            <a href="{{ route('admin.contact_customers') }}" class="menu-link">
                              <div data-i18n="Clientes">Clientes</div>
                            </a>
                          </li>
                        @endcan
                        @can('reports.contacts.contact_providers')
                          <li class="menu-item {{ request()->is('contacts-providers') ? 'active' : '' }}">
                            <a href="{{ route('admin.contact_providers') }}" class="menu-link">
                              <div data-i18n="Proveedores">Proveedores</div>
                            </a>
                          </li>
                        @endcan
                      </ul>
                    </li>
                  @endcan
                  @can('reports.inventory.inventory_products')
                    <li class="menu-item {{ request()->is('inventories-items') ? 'open active' : '' }}">
                      <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <div data-i18n="Inventario">Inventario</div>
                      </a>
                      <ul class="menu-sub">
                        @can('reports.inventory.inventory_products')
                          <li class="menu-item {{ request()->is('inventories-items') ? 'active' : '' }}">
                            <a href="{{ route('admin.inventory_products') }}" class="menu-link">
                              <div data-i18n="Productos">Productos</div>
                            </a>
                          </li>
                        @endcan
                      </ul>
                    </li>
                  @endcan
                  @can('reports.kardex.kardex_unitario')
                    <li class="menu-item {{ request()->is('kardex') ? 'active' : '' }}">
                      <a href="{{ route('admin.kardex.index') }}" class="menu-link">
                        <div data-i18n="Kardex Unitario">Kardex Unitario</div>
                      </a>
                    </li>
                  @endcan
                </ul>
              </li>
            @endcan

            @can('settings.business', 'settings.detracciones', 'settings.cuentas','settings.series', 'settings.list_cashes')
              <li class="menu-item {{ request()->is('list-cashes') || request()->is('cuentas') || request()->is('detracciones') || request()->is('series') || request()->is('business')? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                  <i class="menu-icon" data-feather="settings"></i>
                  <div data-i18n="Configuración">Configuración</div>
                </a>
                <ul class="menu-sub">
                  @can('settings.business')
                    <li class="menu-item {{ request()->is('business') ? 'active' : '' }}">
                      <a href="{{ route('admin.business') }}" class="menu-link">
                        <div data-i18n="Empresa">Empresa</div>
                      </a>
                    </li>
                  @endcan
                
                  @can('settings.detracciones')
                    <li class="menu-item {{ request()->is('detracciones') ? 'active' : '' }}">
                      <a href="{{ route('admin.listado_detracciones') }}" class="menu-link">
                        <div data-i18n="Listado Detracciones">Detracciones</div>
                      </a>
                    </li>
                  @endcan
                  
                  @can('settings.cuentas')
                    <li class="menu-item {{ request()->is('cuentas') ? 'active' : '' }}">
                      <a href="{{ route('admin.cuentas') }}" class="menu-link">
                        <div data-i18n="Cuentas">Cuentas</div>
                      </a>
                    </li>
                  @endcan

                  @can('settings.series')
                    <li class="menu-item {{ request()->is('series') ? 'active' : '' }}">
                      <a href="{{ route('admin.series') }}" class="menu-link">
                        <div data-i18n="Series">Series</div>
                      </a>
                    </li>
                  @endcan
                  @can('settings.list_cashes')
                    <li class="menu-item {{ request()->is('list-cashes') ? 'active' : '' }}">
                      <a href="{{ route('admin.list_cashes') }}" class="menu-link">
                        <div data-i18n="Cajas">Cajas</div>
                      </a>
                    </li>
                  @endcan
                </ul>
              </li>
            @endcan


            @can('reclamos.reclamos_quejas', 'reclamos.report_reclamos', 'reclamos.ajustes')
            <li class="menu-item {{ request()->is('reclamos_quejas') || request()->is('ajustes') || request()->is('report_reclamos')  ? 'active open' : '' }}">

                <a href="javascript:void(0);" class="menu-link menu-toggle">
                  <i class="menu-icon" data-feather="book-open"></i>
                  <div data-i18n="Libro De Reclamaciones">Reclamos</div>
                </a>


                <ul class="menu-sub">
                  @can('reclamos.reclamos_quejas')
                    <li class="menu-item {{ request()->is('reclamos_quejas') || request()->routeIs('admin.create_nc') ? 'active' : '' }}">
                      <a href="{{ route('admin.reclamos_quejas') }}" class="menu-link">
                        <div data-i18n="Reclamos / Quejas">Listado de Ventas</div>
                      </a>
                    </li>
                  @endcan
                  @can('reclamos.report_reclamos')
                    <li class="menu-item {{ request()->is('report_reclamos') ? 'active' : '' }}">
                      <a href="{{ route('admin.report_reclamos.index') }}" class="menu-link">
                        <div data-i18n="Reportes Reclamos">Reportes</div>
                      </a>
                    </li>
                  @endcan

                  @can('reclamos.ajustes')
                    <li class="menu-item {{ request()->is('ajustes') ? 'active' : '' }}">
                      <a href="{{ route('admin.ajustes') }}" class="menu-link">
                        <div data-i18n="Ajustes">Ajustes</div>
                      </a>
                    </li>
                  @endcan

                </ul>
              </li>
            @endcan

          </ul>
        </aside>
        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">
          <!-- Navbar -->
          <nav
            class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
            id="layout-navbar">
            <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
              <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                <i class="ti ti-menu-2 ti-sm"></i>
              </a>
            </div>

            <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
              <!-- Search -->
              <div class="navbar-nav align-items-center">
                <div class="nav-item navbar-search-wrapper mb-0">
                  <a class="nav-item nav-link search-toggler d-flex align-items-center px-0" href="javascript:void(0);"></a>
                </div>
              </div>
              <!-- /Search -->
              @if(request()->is('pos'))
              <!-- pos -->
                <div class="mb-xl-0 mb-0">
                  <div class="d-flex svg-illustration mb-0 gap-2 align-items-center" style="justify-content: flex-end;">
                    <div class="app-brand-logo demo">
                      <img src="{{ asset('assets/img/icons/icon-login.ico') }}" alt="" class="img-fluid">
                    </div>
                    <span class="fw-bold fs-10">{{ $business->razon_social }}</span>
                  </div>

                  <div style="text-align: left;">
                    <p class="mb-0">R.U.C. {{ $business->ruc }}</p>
                    <p class="mb-3">{{ $ubigeo["distrito"] }} - {{ $ubigeo["provincia"] }} - {{ $ubigeo["departamento"] }}</p>

                  </div>
                </div>

                <div class="col-auto" style="position: absolute; top: 10%; left: 50%; transform: translate(-50%, 0);">
                  <div class="alert alert-primary" role="alert">
                    Tipo de precio: <span class="fw-bold fs-5" id="kindPrice">Precio público</span>.
                  </div>
                </div>
                <!-- /pos -->
              @endif
              <ul class="navbar-nav flex-row align-items-center ms-auto">
                <!-- Style Switcher -->
                <li class="nav-item me-2 me-xl-0">
                  <a class="nav-link style-switcher-toggle hide-arrow" href="javascript:void(0);">
                    <i class="ti ti-md"></i>
                  </a>
                </li>
                <!--/ Style Switcher -->

                <!-- Quick links  -->
                <li class="nav-item dropdown-shortcuts navbar-dropdown dropdown me-2 me-xl-0">
                  <a
                    class="nav-link dropdown-toggle hide-arrow"
                    href="javascript:void(0);"
                    data-bs-toggle="dropdown"
                    data-bs-auto-close="outside"
                    aria-expanded="false">
                    <i class="ti ti-layout-grid-add ti-md"></i>
                  </a>
                  <div class="dropdown-menu dropdown-menu-end py-0">
                    <div class="dropdown-menu-header border-bottom">
                      <div class="dropdown-header d-flex align-items-center py-3">
                        <h5 class="text-body mb-0 me-auto">Atajos</h5>
                      </div>
                    </div>
                    <div class="dropdown-shortcuts-list scrollable-container">
                      <div class="row row-bordered overflow-visible g-0">
                        <div class="dropdown-shortcuts-item col">
                          <span class="dropdown-shortcuts-icon rounded-circle mb-2">
                            <i class="ti ti-file-invoice fs-4"></i>
                          </span>
                          <a href="{{ route('admin.pos') }}" class="stretched-link  window-open-pos" data-iduser="{{ Auth::user()['id'] }}" data-idcash="{{ Auth::user()['idcaja'] }}">Facturaci&oacute;n</a>
                          <small class="text-muted mb-0">Abrir Terminal POS</small>
                        </div>
                        <div class="dropdown-shortcuts-item col">
                          <span class="dropdown-shortcuts-icon rounded-circle mb-2">
                            <i class="ti ti-users fs-4"></i>
                          </span>
                          <a href="{{ route('admin.clients') }}" class="stretched-link">Contactos</a>
                          <small class="text-muted mb-0">Gesti&oacute;n de Clientes</small>
                        </div>
                      </div>
                      <div class="row row-bordered overflow-visible g-0">
                        <div class="dropdown-shortcuts-item col">
                          <span class="dropdown-shortcuts-icon rounded-circle mb-2">
                            <i class="ti ti-chart-bar fs-4"></i>
                          </span>
                          <a href="{{ route('admin.home') }}" class="stretched-link">Dashboard</a>
                          <small class="text-muted mb-0">Panel Principal</small>
                        </div>
                        <div class="dropdown-shortcuts-item col">
                          <span class="dropdown-shortcuts-icon rounded-circle mb-2">
                            <i class="ti ti-settings fs-4"></i>
                          </span>
                          <a href="{{ route('admin.business') }}" class="stretched-link">Configuraci&oacute;n</a>
                          <small class="text-muted mb-0">Empresa</small>
                        </div>
                      </div>
                    </div>
                  </div>
                </li>
                <!-- Quick links -->
                <div class="flex-grow-1">
                  <span class="fw-semibold d-block">{{ Auth::user()['nombres'] }}</span>
                  <small class="text-muted">{{ $role = App\Models\User::with('roles')->where('id', auth()->user()->id)->first()->roles[0]->name }}</small>
                </div>
                <!-- Notification -->
                <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-3 me-xl-1">
                  <a
                    class="nav-link dropdown-toggle hide-arrow"
                    href="javascript:void(0);"
                    data-bs-toggle="dropdown"
                    data-bs-auto-close="outside"
                    aria-expanded="false">
                    <i class="ti ti-bell ti-md"></i>
                    <span id="wrapper_badge_noti" class="badge bg-danger rounded-pill badge-notifications d-none"></span>
                  </a>
                  <ul class="dropdown-menu dropdown-menu-end py-0">
                    <li class="dropdown-menu-header border-bottom">
                      <div class="dropdown-header d-flex align-items-center py-3">
                        <h5 class="text-body mb-0 me-auto">Notificaciones</h5>
                      </div>
                    </li>
                    <li class="dropdown-notifications-list scrollable-container">
                      <ul class="list-group list-group-flush">
                        <li id="wrapper_f" class="list-group-item list-group-item-action dropdown-notifications-item d-none">
                          <a href="{{ route('admin.alerts_sale') }}">
                            <div class="d-flex">
                              <div class="flex-shrink-0 me-3">
                                <div class="avatar">
                                  <span class="avatar-initial rounded-circle bg-label-danger">
                                    <i class="fa fa-file-invoice"></i>
                                  </span>
                                </div>
                              </div>
                              <div class="flex-grow-1">
                                <h6 class="mb-1"></h6>
                                <small class="text-muted">Pendientes de envío</small>
                              </div>
                            </div>
                          </a>
                        </li>

                        <li id="wrapper_b" class="list-group-item list-group-item-action dropdown-notifications-item d-none">
                          <a href="{{ route('admin.alerts_sale') }}">
                            <div class="d-flex">
                              <div class="flex-shrink-0 me-3">
                                <div class="avatar">
                                  <span class="avatar-initial rounded-circle bg-label-warning">
                                    <i class="fa fa-file-alt"></i>
                                  </span>
                                </div>
                              </div>
                              <div class="flex-grow-1">
                                <h6 class="mb-1"></h6>
                                <small class="text-muted">Pendientes de envío</small>
                              </div>
                            </div>
                          </a>
                        </li>

                        <li id="wrapper_s" class="list-group-item list-group-item-action dropdown-notifications-item d-none">
                          <a href="{{ route('admin.alerts_stock') }}">
                            <div class="d-flex">
                              <div class="flex-shrink-0 me-3">
                                <div class="avatar">
                                  <span class="avatar-initial rounded-circle bg-label-primary">
                                    <i class="fa fa-boxes"></i>
                                  </span>
                                </div>
                              </div>
                              <div class="flex-grow-1">
                                <h6 class="mb-1"></h6>
                                <small class="text-muted">Productos por agotarse</small>
                              </div>
                            </div>
                          </a>
                        </li>

                        <li id="wrapper_e" class="list-group-item list-group-item-action dropdown-notifications-item d-none">
                          <a href="{{ route('admin.alerts_expiration') }}">
                            <div class="d-flex">
                              <div class="flex-shrink-0 me-3">
                                <div class="avatar">
                                  <span class="avatar-initial rounded-circle bg-label-info">
                                    <i class="fa fa-boxes"></i>
                                  </span>
                                </div>
                              </div>
                              <div class="flex-grow-1">
                                <h6 class="mb-1">2 PRODUCTOS</h6>
                                <small class="text-muted">Productos por vencer</small>
                              </div>
                            </div>
                          </a>
                        </li>

                        <li id="wrapper_empty" class="list-group-item list-group-item-action dropdown-notifications-item d-none">
                          <div class="d-flex">
                            <div class="flex-shrink-0 me-3">
                              <div class="avatar">
                                <span class="avatar-initial rounded-circle bg-label-success">
                                  <i class="fa fa-check"></i>
                                </span>
                              </div>
                            </div>
                            <div class="flex-grow-1">
                              <h6 class="mb-1 mt-2">No tiene alertas pendientes</h6>
                            </div>
                          </div>
                        </li>

                      </ul>
                    </li>
                  </ul>
                </li>
                <!--/ Notification -->

                <li class="nav-item navbar-dropdown dropdown-user dropdown">
                  <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                      <img src="{{ asset('assets/img/avatars/avatar.png') }}" width="10px" alt class="h-auto rounded-circle" />
                    </div>
                  </a>
                  <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                      <a class="dropdown-item" href="{{ route('admin.home') }}">
                        <div class="d-flex">
                          <div class="flex-shrink-0 me-3">
                            <div class="avatar avatar-online">
                              <img src="{{ asset('assets/img/avatars/avatar.png') }}" width="10px" alt class="h-auto rounded-circle" />
                            </div>
                          </div>

                        </div>
                      </a>
                    </li>
                    @can('admin.prices', 'admin.faq')..
                    <li>
                      <div class="dropdown-divider"></div>
                    </li>
                    <li>
                      <a class="dropdown-item" href="{{ route('admin.faq') }}">
                        <i class="ti ti-help me-2 ti-sm"></i>
                        <span class="align-middle">FAQ</span>
                      </a>
                    </li>
                    <li>
                      <a class="dropdown-item" href="{{ route('admin.prices') }}">
                        <i class="ti ti-currency-dollar me-2 ti-sm"></i>
                        <span class="align-middle">Precios</span>
                      </a>
                    </li>
                    @endcan
                    <li>
                      <div class="dropdown-divider"></div>
                    </li>
                    <li>
                      <a class="dropdown-item" href="{{ route('login.logout') }}">
                        <i class="ti ti-logout me-2 ti-sm"></i>
                        <span class="align-middle"> Salir</span>
                      </a>
                    </li>
                  </ul>
                </li>
                <!--/ User -->
              </ul>
            </div>

            <!-- Search Small Screens -->
            <div class="navbar-search-wrapper search-input-wrapper d-none">
              <input
                type="text"
                class="form-control search-input container-xxl border-0"
                placeholder="Buscar..."
                aria-label="Search..." />
              <i class="ti ti-x ti-sm search-toggler cursor-pointer"></i>
            </div>
          </nav>

          <!-- / Navbar -->

          <!-- modales -->
    <div class="modal fade" id="modalArchingCash2" data-bs-backdrop="static" tabindex="1">
        <div class="modal-dialog">
            <form id="form_save2" class="modal-content" onsubmit="event.preventDefault()">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalArchingCashTitle">Aperturar Caja</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="cajero" class="form-label">Responsable</label>
                            <input type="hidden" name="idusuario" value="{{ Auth::user()['id'] }}" />
                            <input type="hidden" name="idcaja" value="{{ Auth::user()['idcaja'] }}" />
                            <input class="form-control text-uppercase" id="cajero2" type="text" name="cajero"
                                value="{{ mb_strtoupper(Auth::user()['user']) }}" />
                            <div class="invalid-feedback">El campo no debe estar vacío.</div>
                        </div>

                        <div class="col-12 col-md-6 mb-3">
                            <label class="form-label" for="monto_inicial2">Monto Inicial</label>
                            <div class="input-group">
                                <span class="input-group-text" id="basic-addon11">S/</span>
                                <input type="text" id="monto_inicial2" class="form-control" name="monto_inicial">
                                <div class="invalid-feedback">El campo no debe estar vacío.</div>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 mb-3">
                            <label class="form-label" for="fecha2">Fecha de Emisi&oacute;n</label>
                            <input class="form-control" id="fecha2" type="date"name="fecha"
                                value="{{ date('Y-m-d') }}" />
                        </div>

                        <div class="col-12 text-end">
                            <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button class="btn btn-primary btn-save-cash">
                                <span class="text-save">Guardar</span>
                                <span class="spinner-border spinner-border-sm me-1 d-none text-saving" role="status"
                                    aria-hidden="true"></span>
                                <span class="text-saving d-none">Guardando...</span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->
            <div class="container-xxl flex-grow-1 container-p-y">
              @yield('content')
            </div>
            <!-- / Content -->
            <div class="content-backdrop fade"></div>
          </div>
          <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
      </div>

      <!-- Overlay -->
      <div class="layout-overlay layout-menu-toggle"></div>

      <!-- Drag Target Area To SlideIn Menu On Small Screens -->
      <div class="drag-target"></div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/node-waves/node-waves.js') }}"></script>

    <script src="{{ asset('assets/vendor/libs/hammer/hammer.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/i18n/i18n.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/typeahead-js/typeahead.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/feather/feather.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/menu.js') }}"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/pnotify/pnotify.custom.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/block-ui/block-ui.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/moment/moment.js') }}"></script>


    <!-- Main JS -->
    <script src="{{ asset('assets/js/main.min.js') }}"></script>
    <script src="{{ asset('assets/js/functions.min.js') }}"></script>

    <!-- Page JS -->
    <script src="{{ asset('assets/js/dashboards-ecommerce.min.js') }}"></script>
    @include('admin.js-home')
    {{--@include('admin.arching_cashes.js-store')--}}
    @yield('scripts')
    <script>
        $('body').on('click', '.btn-save-cash', function()
    {
        event.preventDefault();
        let form            = $('#form_save2').serialize(),
            monto_inicial   = $('input[id="monto_inicial2"]');

        if(monto_inicial.val() == '')
            monto_inicial.addClass('is-invalid');
        else
            monto_inicial.removeClass('is-invalid');

        if(monto_inicial.val().trim() != '')
        {
            $.ajax({
                url         :  "{{ route('admin.save_arching_cash') }}",
                method      : 'POST',
                data        : form,
                beforeSend  : function(){
                    $('.btn-save').prop('disabled', true);
                    $('.text-saving').removeClass('d-none');
                    $('.text-save').addClass('d-none');
                },
                success     : function(r)
                {
                    if(!r.status)
                    {
                        $('.btn-save').prop('disabled', false);
                        $('.text-saving').addClass('d-none');
                        $('.text-save').removeClass('d-none');
						toast_msg(r.msg, r.type);
                        return;
                    }

                    $('#modalArchingCash2').modal('hide');
                    $('#form_save2').trigger('reset');
                    $('.btn-save').prop('disabled', false);
                    $('.text-save').removeClass('d-none');
                    $('.text-saving').addClass('d-none');
                    toast_msg(r.msg, r.type);
                    $(location).attr('href', '/pos');
                },
                dataType    : 'json'
            });
            return;
        }
    });
    </script>
  </body>
</html>
