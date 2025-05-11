<script>
    function load_tbody()
    {
        $.ajax({
            url         : "{{ route('admin.load_tbody_stocks') }}",
            method      : "POST",
            data        : {
                '_token': "{{ csrf_token() }}"
            },
            success     : function(r){
                if(!r.status)
                {
                    toast_msg(r.msg, r.type);
                    return;
                }

                let tbody           = '',
                    html_warehouses = '';
                $.each(r.warehouses, function(index, warehouse) {
                    let show_active = (warehouse.id == 1) ? 'show active' : ''
                        contador    = 0;
                    html_warehouses += `<div class="tab-pane fade ${show_active}" data-id="${warehouse.id}" id="tab-${warehouse.id}" role="tabpanel">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="table-responsive-sm mt-3">
                                                        <table class="table table-sm mb-0 fs--1">
                                                            <thead>
                                                                <tr>
                                                                    <th class="text-center" width="7%">#</th>
                                                                    <th class="text-center" width="11%">C&oacute;digo</th>
                                                                    <th class="text-left">Descripci&oacute;n</th>
                                                                    <th class="text-center" width="13%">Stock</th>
                                                                    <th class="text-center" width="13%">Opciones</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="wrapper_tbody">`;
                                                                $.each(r.products, function(item, product){
                                                                    if(product.idalmacen == warehouse.id)
                                                                    {
                                                            html_warehouses += `<tr>
                                                                            <td class="text-center">${contador + 1}</td>
                                                                            <td class="text-center">${(product.codigo_interno == null) ? '-' : product.codigo_interno}</td>
                                                                            <td>${product.descripcion}</td>
                                                                            <td class="text-center">${(product.stock < 0) ? 0 : product.stock}</td>
                                                                            <td class="text-center"><a href="{{ route('admin.create_buy') }}" class="dt-button create-new btn btn-sm btn-danger waves-effect waves-light">
                                                                            <span><i class="fa fa-shopping-cart"></i> <span class="d-none d-sm-inline-block">Comprar</span></span>
                                                                        </a></td>
                                                                        </tr>`;
                                                                    }
                                                                });
                                        html_warehouses += `</tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>`;
                });

                $('#wrapper-warehouses').html(html_warehouses);
            },
            dataType    : "json"
        });
    }

    load_tbody();
</script>