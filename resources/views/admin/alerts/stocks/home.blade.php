@extends('admin.template')
@section('styles')
    <style>
        body {
            overflow-x: hidden;
        }
    </style>
@endsection
@section('content')
    <section class="basic-select2">
        <div class="row">
            <!-- Congratulations Card -->
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title">* Se visualiza la lista de los productos que est&aacute;n agotados y pronto a agotarse.</h6>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <div class="card-header">
                                    <ul class="nav nav-tabs nav-fill" role="tablist">
                                        @foreach ($warehouses as $warehouse)
                                        <li class="nav-item">
                                            <button type="button" class="nav-link {{ $warehouse->id == 1 ? 'active' : '' }}" role="tab"
                                                data-bs-toggle="tab" data-bs-target="#tab-{{ $warehouse->id }}"
                                                aria-controls="tab-{{ $warehouse->id }}"
                                                aria-selected="true" data-id="{{ $warehouse->id }}">{{ $warehouse->descripcion }}</button>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>

                                <div class="card-body mt-4 mb-4">
                                    <div id="wrapper-warehouses" class="tab-content p-0"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('scripts')
    @include('admin.alerts.stocks.js-home')
@endsection
