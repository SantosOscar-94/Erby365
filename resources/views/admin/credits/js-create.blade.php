<script>
    var setTimeOutBuscador = '';

    function open_modal_client() {}

    function success_save_client(msg = null, type = null, idtipocomprobante = null, last_id = null) {
        toast_msg(msg, type);
        load_clients(idtipocomprobante);
        setTimeout(() => {
            $('#form_save_credit select[name="dni_ruc"]').val(last_id);
            $('#form_save_credit select[name="dni_ruc"]').trigger('change');
        }, 500);
    }

    function success_save_product(msg = null, type = null) {
        $('#modalAddProduct').modal('hide');
        toast_msg(msg, type);
        load_products();
    }

    $('#form_save_credit select[name="dni_ruc"]').select2({
        placeholder: "[SELECCIONE]"
    });


    function load_clients(idtipo_documento) {
        $.ajax({
            url: "{{ route('admin.get_serie_credit') }}",
            method: 'POST',
            data: {
                '_token': "{{ csrf_token() }}",
                idtipo_documento: idtipo_documento
            },
            success: function(r) {
                if (!r.status) {
                    toast_msg(r.msg, r.type);
                    return;
                }

                let html_clients = '<option></option>';
                $.each(r.clients, function(index, client) {
                    html_clients +=
                        `<option value="${client.id}">${client.dni_ruc + ' - ' + client.nombres}</option>`;
                });

                $('#form_save_credit select[name="dni_ruc"]').html(html_clients).select2({
                    placeholder: "[SELECCIONE]"
                });
            },
            dataType: 'json'
        });
        return;
    }

    $('#form_save_credit select[name="idtipo_comprobante"]').on('change', function() {
        let value = $(this).val();
        load_clients(value);
    });

    function load_products() {
        $.ajax({
            url: "{{ route('admin.get_products_update_q') }}",
            method: 'POST',
            data: {
                '_token': "{{ csrf_token() }}"
            },
            success: function(data) {
                let html_products = '<option value=""></option>';
                $.each(data, function(index, product) {
                    html_products +=
                        `<option value="${product.id}">${product.descripcion + ' - S/' + product.precio_venta}</option>`;
                });

                $('#form_save_to_product select[name="product"]').html(html_products);
                $('#form_save_to_product select[name="product"]').select2({
                    dropdownParent: $('#modalAddToProduct .modal-body'),
                    placeholder: "[SELECCIONE]"
                });
            },
            dataType: 'json'
        });
    }

    $('body').on('click', '.btn-add-product', function() {
        event.preventDefault();
        $('#modalAddToProduct').modal('show');
        $('#form_save_to_product select[name="product"]').select2({
            dropdownParent: $('#modalAddToProduct .modal-body'),
            placeholder: "[SELECCIONE]",
        });

        $('#form_save_to_product select[name="idalmacen"]').select2({
            dropdownParent: $('#modalAddToProduct .modal-body'),
            placeholder: "[SELECCIONE]",
        });
    });

    $('#modalAddToProduct select[name="product"]').on('change', function() {
        let value = $(this).val(),
            cantidad = $('#form_save_to_product input[name="cantidad"]').val();
        if (value.trim() == "") {
            return;
        }
        $.ajax({
            url: "{{ route('admin.get_price_product_credit') }}",
            method: "POST",
            data: {
                '_token': "{{ csrf_token() }}",
                id: value
            },
            success: function(r) {
                if (!r.status) {
                    toast_msg(r.msg, r.type);
                    return;
                }
                $('#form_save_to_product input[name="precio"]').val(r.product.precio_venta);
            },
            dataType: "json"
        });
    });

    touch_down('#form_save_to_product input[name="cantidad"]', 'product');
    touch_up('#form_save_to_product input[name="cantidad"]', 'product');


    let total_card;

    function load_cart() {
        $.ajax({
            url: "{{ route('admin.load_cart_credits') }}",
            method: 'POST',
            data: {
                '_token': "{{ csrf_token() }}",
                'detraccion': $('#form_save_detraccion select[name="servicio_detraccion"]').val() || 0
            },
            success: function(r) {
                if (!r.status) {
                    toast_msg(r.msg, r.title, r.type);
                    return;
                }

                $('#tbody_credits').html(r.html_cart);
                $('#wrapper_totals').html(r.html_totales);
                $('#form_save_detraccion #monto_detraccion').val(r.detraccion ?? 0);
                total_card = r.total_card
                valorCuotas();
                fechaVencimiento();
            },
            dataType: 'json'
        });
    }
    load_cart();

    $('body').on('click', '#form_save_to_product .btn-save', function() {
        event.preventDefault();
        let select_product = $('#form_save_to_product select[name="product"]').val(),
            cantidad = parseFloat($('#form_save_to_product input[name="cantidad"]').val()),
            precio = parseFloat($('#form_save_to_product input[name="precio"]').val()),
            idalmacen = $('#form_save_to_product select[name="idalmacen"]').val();
        if (select_product.trim() == "") {
            toast_msg('Debe seleccionar un producto', 'warning');
            return;
        }
        if (cantidad <= 0) {
            toast_msg('Ingrese una cantidad válida', 'warning');
            return;
        }
        if (precio <= 0) {
            toast_msg('Ingrese un precio válido', 'warning');
            return;
        }

        $.ajax({
            url: "{{ route('admin.add_product_credit') }}",
            method: "POST",
            data: {
                '_token': "{{ csrf_token() }}",
                id: select_product,
                cantidad: cantidad,
                precio: precio,
                idalmacen: idalmacen,
            },
            beforeSend: function() {
                $('#form_save_to_product .btn-save').prop('disabled', true);
                $('#form_save_to_product .text-save').addClass('d-none');
                $('#form_save_to_product .text-saving').removeClass('d-none');
            },
            success: function(r) {
                if (!r.status) {
                    $('#form_save_to_product .btn-save').prop('disabled', false);
                    $('#form_save_to_product .text-save').removeClass('d-none');
                    $('#form_save_to_product .text-saving').addClass('d-none');
                    toast_msg(r.msg, r.type);
                    return;
                }

                toast_msg(r.msg, r.type);
                $('#form_save_to_product .btn-save').prop('disabled', false);
                $('#form_save_to_product .text-save').removeClass('d-none');
                $('#form_save_to_product .text-saving').addClass('d-none');
                $('#form_save_to_product input[name="cantidad"]').val('1');
                $('#form_save_to_product input[name="precio"]').val('');
                $('#form_save_to_product select[name="product"]').val('').trigger('change');
                $('#form_save_to_product select[name="product"]').select2({
                    dropdownParent: $('#modalAddToProduct .modal-body'),
                    placeholder: "[SELECCIONE]",
                });
                load_cart();
            },
            dataType: "json"
        });
    });

    $('body').on('click', '.btn-delete-product', function() {
        event.preventDefault();
        let id = $(this).data('id');
        $.ajax({
            url: "{{ route('admin.delete_product_credit') }}",
            method: 'POST',
            data: {
                '_token': "{{ csrf_token() }}",
                id: id
            },
            success: function(r) {
                if (!r.status) {
                    toast_msg(r.msg, r.title, r.type);
                    return;
                }
                load_cart();
            },
            dataType: 'json'
        });
        return;
    });

    $('body').on('click', '.btn-down', function() {
        event.preventDefault();
        let id = $(this).data('id'),
            cantidad = parseInt($(this).data('cantidad')),
            cantidad_enviar = cantidad - 1,
            precio = parseFloat($(this).data('precio'));

        if (cantidad_enviar <= 0) {
            toast_msg('La cantidad no puede ser menor a 1', 'warning');
            return;
        }

        $.ajax({
            url: "{{ route('admin.store_product_credit') }}",
            method: "POST",
            data: {
                '_token': "{{ csrf_token() }}",
                id: id,
                cantidad: cantidad_enviar,
                precio: precio
            },
            success: function(r) {
                if (!r.status) {
                    toast_msg(r.msg, r.type);
                    return;
                }

                toast_msg(r.msg, r.type);
                load_cart();
            },
            dataType: "json"
        });
    });

    $('body').on('click', '.btn-up', function() {
        event.preventDefault();
        let id = $(this).data('id'),
            cantidad = parseInt($(this).data('cantidad')),
            cantidad_enviar = cantidad + 1,
            precio = parseFloat($(this).data('precio'));

        $.ajax({
            url: "{{ route('admin.store_product_credit') }}",
            method: "POST",
            data: {
                '_token': "{{ csrf_token() }}",
                id: id,
                cantidad: cantidad_enviar,
                precio: precio
            },
            success: function(r) {
                if (!r.status) {
                    toast_msg(r.msg, r.type);
                    return;
                }

                toast_msg(r.msg, r.type);
                load_cart();
            },
            dataType: "json"
        });
    });

    $('body').on('change', '.input-update', function() {
        let precio = $(this).val(),
            cantidad = $(this).data('cantidad'),
            id = $(this).data('id');

        if (precio.trim() == '') {
            return;
        }
        if (isNaN(precio)) {
            toast_msg('Solo se permiten números', 'warning');
            $(this).focus();
            return;
        }

        $.ajax({
            url: "{{ route('admin.store_product_credit') }}",
            method: 'POST',
            data: {
                '_token': "{{ csrf_token() }}",
                id: id,
                cantidad: cantidad,
                precio: precio
            },
            success: function(r) {
                if (!r.status) {
                    load_cart();
                    toast_msg(r.msg, r.type);
                    return;
                }

                toast_msg(r.msg, r.type);
                load_cart();
            },
            dataType: 'json'
        });
    });


    // Manejar el colapso de las secciones
    $('.el-collapse-item__header').click(function() {
        var arrow = $(this).find('.el-collapse-item__arrow');
        var content = $(this).parent().next('.el-collapse-item__wrap');

        // Alternar el ícono de la flecha
        if (arrow.hasClass('el-icon-arrow-right')) {
            arrow.removeClass('el-icon-arrow-right').addClass('el-icon-arrow-down');
        } else {
            arrow.removeClass('el-icon-arrow-down').addClass('el-icon-arrow-right');
        }

        // Mostrar u ocultar el contenido
        content.slideToggle();
    });

    // Manejar el switch
    $('.el-switch__input').click(function() {
        var switchCore = $(this).next('.el-switch__core');
        $(this).prop('checked', !$(this).prop('checked')); // Cambia el estado de "checked"

        if ($(this).prop('checked')) {
            $(this).closest('.el-switch').addClass('is-checked');
        } else {
            $(this).closest('.el-switch').removeClass('is-checked');
        }
    });

    $('body').on('change', '#tipo_operacion', function(e) {
        e.preventDefault();
        let option = $(this).val();
        if (option === '4') {
            $('#modalDetraccion').modal('show');
        }
    })

    $('body').on('click', '.el-dialog__close', function(e) {
        $('#modal-detracion').hide();
    })


    $('body').on('input', '#otros-cargos', function(e) {
        if ($(this).is(':checked')) {
            // Muestra el campo oculto
            $('.priceInput').show();
        } else {
            // Oculta el campo si el checkbox está desmarcado
            $('.priceInput').hide();
        }
    });

    // Formatea el valor cada vez que el usuario cambia el contenido del input
    $('body').on('input', '.priceInput', function(e) {
        $(this).val(parseFloat($(this).val()).toFixed(2));
    });

    // Asegura que siempre se muestren dos decimales cuando el input pierde el foco
    $('body').on('blur', '.priceInput', function(e) {
        if ($(this).val() === '' || isNaN($(this).val())) {
            $(this).val('0.00');
        } else {
            $(this).val(parseFloat($(this).val()).toFixed(2));
        }
    });


    $('body').on('change', '#condicion-pago', function(e) {
        e.preventDefault();
        let option = $(this).val();
        if (option === '1') {
            $('#op-pago-contado').show();
            $('#op-pago-credito').hide();
            $('#op-pago-credito-cuotas').hide();
        } else if (option === '2') {
            $('#op-pago-contado').hide();
            $('#op-pago-credito').show();
            $('#op-pago-credito-cuotas').hide();
        } else if (option === '3') {
            $('#op-pago-contado').hide();
            $('#op-pago-credito').hide();
            $('#op-pago-credito-cuotas').show();
        }
    })

    $('body').on('change', '#cuotas', function() {
        valorCuotas()
        fechaVencimiento()
    });

    $('body').on('change', '#tipo_cuotas', function() {
        fechaVencimiento()
    });


    function valorCuotas() {
        let num_cuotas = $('#cuotas').val();
        let valor_cuotas = (total_card / num_cuotas).toFixed(2);
        $('#valor_cuotas').val(valor_cuotas)
    }


    function fechaVencimiento() {

        let tipoCuotas = $('#tipo_cuotas').val();
        let fechaEmision = new Date($('#fecha_emision').val());
        let numeroCuotas = parseInt($('#cuotas').val());

        // Número válido de cuotas y una fecha de emisión válida
        if (!fechaEmision || isNaN(numeroCuotas)) return;

        let fechaVencimiento = new Date(fechaEmision);

        if (tipoCuotas == 'Semanal') {
            fechaVencimiento.setDate(fechaEmision.getDate() + (7 * numeroCuotas));
        } else if (tipoCuotas == 'Quincenal') {
            fechaVencimiento.setDate(fechaEmision.getDate() + (14 * numeroCuotas));
        } else if (tipoCuotas == 'Mensual') {
            fechaVencimiento.setMonth(fechaEmision.getMonth() + numeroCuotas);
            // Si el nuevo mes tiene menos días que el original (como pasar del 31 a febrero), ajustamos el día
            if (fechaVencimiento.getDate() !== fechaEmision.getDate()) {
                fechaVencimiento.setDate(0); // Último día del mes anterior
            }
        }

        if (fechaVencimiento) {
            let dia = ('0' + fechaVencimiento.getDate()).slice(-2);
            let mes = ('0' + (fechaVencimiento.getMonth() + 1)).slice(-2); // Los meses son 0-indexados
            let anio = fechaVencimiento.getFullYear();

            // Actualizamos el input de fecha de vencimiento
            $('#fecha_vencimiento').val(`${anio}-${mes}-${dia}`);
        }
    }



    $('body').on('click', '#form_save_credit .btn-save', function() {
        event.preventDefault();

        let form = $('#form_save_credit').serialize() + "&" + $('#form_save_detraccion').serialize();

        $.ajax({
            url: "{{ route('admin.save_credit') }}",
            method: "POST",
            data: form,
            beforeSend: function() {
                $('#form_save_credit .btn-save').prop('disabled', true);
                $('#form_save_credit .text-save').addClass('d-none');
                $('#form_save_credit .text-saving').removeClass('d-none');
            },
            success: function(r) {
                if (!r.status) {
                    $('#form_save_credit .btn-save').prop('disabled', false);
                    $('#form_save_credit .text-save').removeClass('d-none');
                    $('#form_save_credit .text-saving').addClass('d-none');
                    $(`input[name="${r.invalid}"]`).addClass('is-invalid');
                    toast_msg(r.msg, r.type);
                    return;
                }

                $('#form_save_credit .btn-save').prop('disabled', false);
                $('#form_save_credit .text-save').removeClass('d-none');
                $('#form_save_credit .text-saving').addClass('d-none');
                $('#form_save_credit select[name="idtipo_comprobante"] option[value="1"]').prop(
                    'selected', true);
                $('#form_save_credit select[name="modo_pago"] option[value="1"]').prop('selected',
                    true);
                load_clients(r.idtipo_comprobante);
                load_cart();

                send(r.idventa);

                let pdf = `{{ asset('files/ventas-generales/ticket//${r.pdf}') }}`;
                var iframe = document.createElement('iframe');
                iframe.style.display = "none";
                iframe.src = pdf;
                document.body.appendChild(iframe);
                iframe.contentWindow.focus();
                iframe.contentWindow.print();
            },
            dataType: "json"
        });
    });

    $('#servicio_detraccion').on('change', function() {
        //console.log($("#servicio_detraccion").val());
        load_cart();
    });

    function send(id)
    {
        $.ajax({
            url         : "{{ route('admin.send_vg') }}",
            method      : 'POST',
            data        : {'_token' : "{{ csrf_token() }}", id : id},
            beforeSend  : function(){
                $('.btn-save').prop('disabled', true);
                $('.text-save').addClass('d-none');
                $('.text-saving').removeClass('d-none');
            },
            success     : function(r){
                if(!r.status){}

                $('.btn-save').prop('disabled', false);
                $('.text-save').removeClass('d-none');
                $('.text-saving').addClass('d-none');
                
                let ip          = r.empresa.url_api,
                    api         = "Api/index.php",
                    datosJSON   = JSON.stringify(r.data);
                    datosJSON   = unescape(encodeURIComponent(datosJSON)),
                    idfactura   = parseInt(r.idfactura);

                    $.ajax({    
                        url         : ip + api,
                        method      : 'POST',
                        data        : {datosJSON},
                        beforeSend  : function(){
                            $('.btn-save').prop('disabled', true);
                            $('.text-save').addClass('d-none');
                            $('.text-saving').removeClass('d-none');
                    },
                    }).done(function(res){

                        $('.btn-save').prop('disabled', false);
                        $('.text-save').removeClass('d-none');
                        $('.text-saving').addClass('d-none');
                        if (res.trim() == "No se registró") 
                        {
                            toast_msg('El número de comprobante electrónico esta duplicado, revise la base de datos', 'error');
                            return;
                        }

                        load_alerts();
                        let respuesta_sunat = JSON.parse(res),
                            estado_conexion = JSON.parse(respuesta_sunat).status;
                        
                        if(estado_conexion != false)
                        {
                            update_cdr_nc(id, idrelacionado);
                        }
                    }).fail(function(jqxhr, textStatus, error){
                        $('.btn-save').prop('disabled', false);
                        $('.text-save').removeClass('d-none');
                        $('.text-saving').addClass('d-none');
                    });
            },
            dataType    : 'json'
        });
    }

    function update_cdr(idfactura) {
        let resp = '';
        $.ajax({
            url: "{{ route('admin.update_cdr_vg') }}",
            method: 'POST',
            data: {
                '_token': "{{ csrf_token() }}",
                idfactura: idfactura
            },
            success: function(r) {},
            dataType: 'json'
        });
    }
</script>
