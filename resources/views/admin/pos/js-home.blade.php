<script>
    var setTimeOutBuscador = '';
    window.userBill = null;

    function open_modal_client() {
        $('#modalConfirmSale').css('z-index', '999');
    }

    $(document).ready(function() {
        $('input[name="input-search-product"]').focus();
        load_view_products();
        load_cart();
    });

    function success_save_product(msg = null, type = null) {
        toast_msg(msg, type);
        load_view_products();
    }

    function success_save_client(msg = null, type = null, idtipocomprobante = null, last_id = null) {
        toast_msg(msg, type);
        load_clients(idtipocomprobante);
        setTimeout(() => {
            $('#modalConfirmSale select[name="dni_ruc"]').val(last_id);
            $('#modalConfirmSale select[name="dni_ruc"]').trigger('change');
        }, 500);
    }

    function load_clients(idtipo_documento) {
        $.ajax({
            url: "{{ route('admin.get_serie_quote') }}",
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

                $('#modalConfirmSale select[name="dni_ruc"]').html(html_clients).select2({
                    placeholder: "[SELECCIONE]",
                    dropdownParent: $('#modalConfirmSale')
                });
            },
            dataType: 'json'
        });
        return;
    }

    //Load products
    function load_view_products() {
        $.ajax({
            url: "{{ route('admin.load_view_products') }}",
            method: 'POST',
            data: {
                '_token': "{{ csrf_token() }}"
            },
            success: function(r) {
                if (!r.status) {
                    toast_msg(r.msg, r.title, r.type);
                    return;
                }
                //console.log(JSON.parse(r.products));
                $('#wrapper-products').html(r.html_products);
            },
            dataType: 'json'
        });
        return;
    }

    $('body').on('keyup', '.input-search-product', function() {
        let value = $(this).val();
        if (event.keyCode === 13)
            return;

        if (event.keyCode === 27) {
            $('.input-search-product').val("");
            load_view_products();
            return;
        }

        if (value.trim() == '') {
            load_view_products();
            return;
        }

        clearTimeout(setTimeOutBuscador);
        setTimeOutBuscador = setTimeout(() => {
            $.ajax({
                url: "{{ route('admin.search_view_product') }}",
                method: 'POST',
                data: {
                    '_token': "{{ csrf_token() }}",
                    value: value
                },
                beforeSend: function() {
                    block_content(`#content-pos-product`);
                },
                success: function(r) {
                    if (!r.status) {
                        close_block(`#content-pos-product`);
                        toast_msg(r.msg, r.type);
                        return;
                    }
                    close_block(`#content-pos-product`);
                    $('#wrapper-products').html(r.html_products);
                },
                dataType: "json"
            }).catch((error) => {
                console.error(error.responseText); // Handle any errors here
            });
        }, 300);
    });

    $('body').on('click', '.btn-clear-input', function() {
        event.preventDefault();
        let input = $('input[name="input-search-product"]').val();
        if (input.trim() == '')
            return;

        $('input[name="input-search-product"]').val('');
        load_view_products();
    });

    // Cart
    function load_cart() {
        divisa = 1;

        if (window.usd) {
            divisa = window.cambio;
            console.log(divisa);
        }

        $.ajax({
            url: "{{ route('admin.load_cart_pos') }}",
            method: 'POST',
            data: {
                '_token': "{{ csrf_token() }}",
                'cambio': divisa
            },
            success: function(r) {
                if (!r.status) {
                    toast_msg(r.msg, r.title, r.type);
                    return;
                }

                $('#wrapper-tbody-pos').html(r.html_cart);
                $('#wrapper-totals').html(r.html_totals);
            },
            dataType: 'json'
        });
        return;
    }

    $('body').on('click', '.btn-add-product-cart', function() {
        event.preventDefault();
        id = $(this).data('id');
        cantidad = $(this).data('cantidad'),
        itemPriceText = $("#itemPrice" + $(this).data('id')).text();
        numbers = itemPriceText.match(/[\d.]+/g);
        precio = numbers[0];

        //precio = window.precio;
        //select = document.getElementById('s_tipo_precio');
        // valueSelect = select.value;

        // if (valueSelect == 0) {
        //     precio = parseFloat($(this).data('precio'));
        // } else if (valueSelect == 1) {
        //     precio = parseFloat($(this).data('precio2'));
        // }else{
        //     precio = parseFloat($(this).data('precio3'));
        // }

        //alert(precio)

        //precio = parseFloat($(this).data('precio'));
        // alert(cantidad)
        // return;
        if ($(this).data('cant') == 0) {

            Swal.fire({
                title: 'Producto agotado',
                text: "¿Desea solicitar traslado?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Solicitar traslado',
                cancelButtonText: 'Cancelar',
                customClass: {
                    confirmButton: 'btn btn-primary',
                    cancelButton: 'btn btn-outline-danger ml-1'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    window.open("/create-transfer-order");
                    return;
                }
            });
            // if (confirm("Producto agotado. ¿Desea solicitar traslado?")) {
            //     window.open("http://multi-sucursal.test/transfer-orders");
            //     return;
            // }
        }

        $.ajax({
            url: "{{ route('admin.add_product_pos') }}",
            method: 'POST',
            data: {
                '_token': "{{ csrf_token() }}",
                id: id,
                cantidad: cantidad,
                precio: precio,
                option: 1
            },
            beforeSend: function() {
                block_content(`.card[id="${id}"]`);
            },
            success: function(r) {
                if (!r.status) {
                    close_block(`.card[id="${id}"]`);
                    toast_msg(r.msg, r.type);
                    return;

                }
                close_block(`.card[id="${id}"]`);
                toast_msg(r.msg, r.type);
                load_cart();
            },
            dataType: 'json'
        });
        return;
    });

    $('body').on('click', '.btn-delete-product-cart', function() {
        event.preventDefault();
        window.userBill = null;
        let id = $(this).data('id'),
            option = $(this).data('option');
        $.ajax({
            url: "{{ route('admin.delete_product_pos') }}",
            method: 'POST',
            data: {
                '_token': "{{ csrf_token() }}",
                id: id,
                option: option
            },
            success: function(r) {
                if (!r.status) {
                    toast_msg(r.msg, r.type);
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
            url: "{{ route('admin.store_product_pos') }}",
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
            url: "{{ route('admin.store_product_pos') }}",
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

    $('body').on('change', '.quantity-counter', function() {
        let id = $(this).data('id'),
            cantidad = parseInt($(this).val()),
            precio = parseFloat($(this).data('precio'));

            if (isNaN(cantidad)) {
                toast_msg('Solo se permiten números', 'warning');
                $(this).focus();
                return;
            }

            $.ajax({
                url: "{{ route('admin.store_product_pos') }}",
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
            url: "{{ route('admin.store_product_pos') }}",
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

    $('body').on('click', '.btn-cancel-pay', function() {
        event.preventDefault();
        window.userBill = null;
        Swal.fire({
            title: 'Cancelar Venta',
            text: "¿Desea cancelar la venta actual?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Si, cancelar',
            cancelButtonText: 'Cancelar',
            customClass: {
                confirmButton: 'btn btn-primary',
                cancelButton: 'btn btn-outline-danger ml-1'
            },
            buttonsStyling: false
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    url: "{{ route('admin.cancel_cart_pos') }}",
                    method: 'POST',
                    data: {
                        '_token': "{{ csrf_token() }}"
                    },
                    success: function(r) {
                        if (!r.status) {
                            toast_msg(r.msg, r.type);
                            return;
                        }

                        toast_msg(r.msg, r.type);
                        load_cart();
                    },
                    dataType: 'json'
                });
            }
        });
    });

    function load_serie() {
        $.ajax({
            url: "{{ route('admin.load_serie_pos') }}",
            method: 'POST',
            data: {
                '_token': "{{ csrf_token() }}"
            },
            success: function(r) {
                if (!r.status) {
                    toast_msg(r.msg, r.title, r.type);
                    return;
                }
                $('#modalConfirmSale input[name="iddocumento_tipo"]').val(2);
                $('#modalConfirmSale input[name="quantity_paying_2"]').val("0");
                $('#modalConfirmSale input[name="quantity_paying_3"]').val("0");
                $(`#modalConfirmSale input[name="type_document"][value="2"]`).prop('checked', true);
                $(`#modalConfirmSale input[name="type_document"][value="1"]`).prop('checked', false);
                $(`#modalConfirmSale input[name="type_document"][value="7"]`).prop('checked', false);
                $(`#modalConfirmSale input[name="type_document"][value="2"]`).parent().parent().addClass(
                    'checked');
                $(`#modalConfirmSale input[name="type_document"][value="1"]`).parent().parent().removeClass(
                    'checked');
                $(`#modalConfirmSale input[name="type_document"][value="7"]`).parent().parent().removeClass(
                    'checked');
                $('#modalConfirmSale #serie-sale').html(r.serie.serie + '-' + r.serie.correlativo);
            },
            dataType: 'json'
        });
    }

    $('body').on('click', '.btn-type-document', function() {
        event.preventDefault();
        let value = $(this).find('input[name="type_document"]').val();
        $.ajax({
            url: "{{ route('admin.get_serie_pos') }}",
            method: 'POST',
            data: {
                '_token': "{{ csrf_token() }}",
                idtipo_documento: value
            },
            success: function(r) {
                if (!r.status) {
                    $('#modalConfirmSale input[name="serie_sale"]').val('');
                    toast_msg(r.msg, r.type);
                    return;
                }

                let serie = r.serie;
                switch (parseInt(r.serie.idtipo_documento)) {
                    case 1:
                        $(`#modalConfirmSale input[name="type_document"][value="1"]`).prop(
                            'checked', true);
                        $(`#modalConfirmSale input[name="type_document"][value="2"]`).prop(
                            'checked', false);
                        $(`#modalConfirmSale input[name="type_document"][value="7"]`).prop(
                            'checked', false);
                        $(`#modalConfirmSale input[name="type_document"][value="1"]`).parent()
                            .parent().addClass('checked');
                        $(`#modalConfirmSale input[name="type_document"][value="2"]`).parent()
                            .parent().removeClass('checked');
                        $(`#modalConfirmSale input[name="type_document"][value="7"]`).parent()
                            .parent().removeClass('checked');
                        break;

                    case 2:
                        $(`#modalConfirmSale input[name="type_document"][value="2"]`).prop(
                            'checked', true);
                        $(`#modalConfirmSale input[name="type_document"][value="1"]`).prop(
                            'checked', false);
                        $(`#modalConfirmSale input[name="type_document"][value="7"]`).prop(
                            'checked', false);
                        $(`#modalConfirmSale input[name="type_document"][value="2"]`).parent()
                            .parent().addClass('checked');
                        $(`#modalConfirmSale input[name="type_document"][value="1"]`).parent()
                            .parent().removeClass('checked');
                        $(`#modalConfirmSale input[name="type_document"][value="7"]`).parent()
                            .parent().removeClass('checked');
                        break;

                    case 7:
                        $(`#modalConfirmSale input[name="type_document"][value="7"]`).prop(
                            'checked', true);
                        $(`#modalConfirmSale input[name="type_document"][value="1"]`).prop(
                            'checked', false);
                        $(`#modalConfirmSale input[name="type_document"][value="2"]`).prop(
                            'checked', false);
                        $(`#modalConfirmSale input[name="type_document"][value="7"]`).parent()
                            .parent().addClass('checked');
                        $(`#modalConfirmSale input[name="type_document"][value="1"]`).parent()
                            .parent().removeClass('checked');
                        $(`#modalConfirmSale input[name="type_document"][value="2"]`).parent()
                            .parent().removeClass('checked');
                        break;
                }

                $('#modalConfirmSale input[name="iddocumento_tipo"]').val(r.serie.idtipo_documento);
                $('#modalConfirmSale input[name="serie_sale"]').val(r.serie.serie + '-' + r.serie
                    .correlativo);
                $('#modalConfirmSale #serie-sale').text(r.serie.serie + '-' + r.serie.correlativo);
                let html_clientes = '';
                $.each(r.clientes, function(index, cliente) {
                    html_clientes +=
                        `<option value="${cliente.id}">${cliente.dni_ruc + ' - ' + cliente.nombres}</option>`;
                });

                $('#modalConfirmSale select[name="dni_ruc"]').html(html_clientes).select2({
                    placeholder: "[SELECCIONE]",
                    dropdownParent: $('#modalConfirmSale')
                });
            },
            dataType: 'json'
        });
        return;
    });

    // Confirm Sale
    $('body').on('click', '.btn-process-pay', function() {
        event.preventDefault();
        $.ajax({
            url: "{{ route('admin.process_pay_pos') }}",
            method: "POST",
            data: {
                '_token': "{{ csrf_token() }}"
            },
            beforeSend: function() {
                $('.btn-process-pay').prop('disabled', true);
                $('.text-process').addClass('d-none');
                $('.text-processing').removeClass('d-none');
            },
            success: function(r) {
                if (!r.status) {
                    $('.btn-process-pay').prop('disabled', false);
                    $('.text-process').removeClass('d-none');
                    $('.text-processing').addClass('d-none');
                    toast_msg(r.msg, r.type);
                    return;
                }

                $('.btn-process-pay').prop('disabled', false);
                $('.text-process').removeClass('d-none');
                $('.text-processing').addClass('d-none');

                total = parseFloat($("#total2").html()).toFixed(2);
                console.log(total);
                //if (window.usd) {
                    $('#modalConfirmSale input[name="quantity_paying"]').val(total);
                // } else {
                //     $('#modalConfirmSale input[name="quantity_paying"]').val(parseFloat(r.cart.total).toFixed(2));
                // }

                $('#modalConfirmSale #total_pay').text(parseFloat(r.cart.total).toFixed(2));
                $('#modalConfirmSale #total_paying').text(parseFloat(r.cart.total).toFixed(2));
                $('#modalConfirmSale #difference').text((parseFloat($('#total_pay').text() -
                    parseFloat($('#total_paying').text()))).toFixed(2));
                $('#vuelto').text(parseFloat(0).toFixed(2));
                $('#modalConfirmSale select[name="modo_pago"] option[value="1"]').prop('selected',
                    true);
                $('#modalConfirmSale select[name="modo_pago_2"] option[value="2"]').prop('selected',
                    true);
                $('#modalConfirmSale select[name="modo_pago_3"] option[value="7"]').prop('selected',
                    true);
                $('#modalConfirmSale select[name="dni_ruc"]').select2({
                    placeholder: "[SELECCIONE]",
                    dropdownParent: $('#modalConfirmSale')
                });

                if (window.userBill == null) {
                    $('#modalConfirmSale select[name="dni_ruc"]').val(1).trigger('change');
                }

                load_serie();

                if (window.usd) {
                    dinero = parseFloat($("#total_pay").html());
                    $("#total_pay").data('valor', dinero);
                    $("#total_pay").html(decimales(dinero / window.cambio));

                    dinero = parseFloat($("#total_paying").html());
                    $("#total_paying").data('valor', dinero);
                    $("#total_paying").html(decimales(dinero / window.cambio));

                    dinero = parseFloat($("#difference").html());
                    $("#difference").data('valor', dinero);
                    $("#difference").html(decimales(dinero / window.cambio));

                    $("#cuentasDiv .cuenta-item").hide();
                    $("#cuentasDiv .cuenta-item[data-moneda='SOLES']").hide();
                    $("#cuentasDiv .cuenta-item[data-moneda='DOLARES']").show();
                    $("#cuentasDiv2 .cuenta-item").hide();

                } else {
                    dinero = parseFloat($("#total_pay").html());
                    $("#total_pay").data('valor', dinero);
                    $("#total_pay").html(decimales(dinero));

                    dinero = parseFloat($("#total_paying").html());
                    $("#total_paying").data('valor', dinero);
                    $("#total_paying").html(decimales(dinero));

                    dinero = parseFloat($("#difference").html());
                    $("#difference").data('valor', dinero);
                    $("#difference").html(decimales(dinero));

                    $("#cuentasDiv .cuenta-item").hide();
                    $("#cuentasDiv .cuenta-item[data-moneda='SOLES']").show();
                    $("#cuentasDiv .cuenta-item[data-moneda='DOLARES']").hide();
                    $("#cuentasDiv2 .cuenta-item").hide();

                }

                $('#modalConfirmSale').modal('show');
            },
            dataType: "json"
        });
    });

    $('input[name="quantity_paying"]').on('change', function() {
        let value = parseFloat($(this).val()),
            quantity_paying_2 = parseFloat($('input[name="quantity_paying_2"]').val()),
            quantity_paying_3 = parseFloat($('input[name="quantity_paying_3"]').val()),
            total = parseFloat($('#total_pay').text());

        if ($('input[name="quantity_paying"]').val() == "") {
            $('input[name="quantity_paying"]').val("0");
            return;
        }

        sum = (value + quantity_paying_2 + quantity_paying_3).toFixed(2);

        if (sum < total) {
            $('#total_paying').text(parseFloat(sum).toFixed(2));
            $('#difference').text((parseFloat(sum) - total).toFixed(2));
            $('#vuelto').text(parseFloat(0).toFixed(2));
            $('.wrapper_difference').removeClass('text-success');
            $('.wrapper_difference').addClass('text-danger');
            $('.btn-confirm-pay').prop('disabled', true);
        } else {
            $('#total_paying').text(parseFloat(sum).toFixed(2));
            $('#difference').text(parseFloat(0).toFixed(2));
            $('#vuelto').text((parseFloat(sum) - total).toFixed(2));
            $('#vuelto-input').val((parseFloat(sum) - total).toFixed(2));
            $('.wrapper_difference').addClass('text-warning');
            $('.wrapper_difference').removeClass('text-danger');
            $('.btn-confirm-pay').prop('disabled', false);
        }
    });

    $('input[name="quantity_paying_2"]').on('change', function() {
        let value = parseFloat($(this).val()),
            quantity_paying_2 = parseFloat($('input[name="quantity_paying"]').val()),
            quantity_paying_3 = parseFloat($('input[name="quantity_paying_3"]').val()),
            total = parseFloat($('#total_pay').text());

        if ($('input[name="quantity_paying_2"]').val() == "") {
            $('input[name="quantity_paying_2"]').val("0");
            return;
        }

        sum = (value + quantity_paying_2 + quantity_paying_3).toFixed(2);
        if (sum < total) {
            $('#total_paying').text(parseFloat(sum).toFixed(2));
            $('#difference').text((parseFloat(sum) - total).toFixed(2));
            $('#vuelto').text(parseFloat(0).toFixed(2));
            $('.wrapper_difference').removeClass('text-success');
            $('.wrapper_difference').addClass('text-danger');
            $('.btn-confirm-pay').prop('disabled', true);
        } else {
            $('#total_paying').text(parseFloat(sum).toFixed(2));
            $('#difference').text(parseFloat(0).toFixed(2));
            $('#vuelto').text((parseFloat(sum) - total).toFixed(2));
            $('#vuelto-input').val((parseFloat(sum) - total).toFixed(2));
            $('.wrapper_difference').addClass('text-warning');
            $('.wrapper_difference').removeClass('text-danger');
            $('.btn-confirm-pay').prop('disabled', false);
        }
    });

    $('input[name="quantity_paying_3"]').on('change', function() {
        let value = parseFloat($(this).val()),
            quantity_paying_2 = parseFloat($('input[name="quantity_paying"]').val()),
            quantity_paying_3 = parseFloat($('input[name="quantity_paying_2"]').val()),
            total = parseFloat($('#total_pay').text());

        if ($('input[name="quantity_paying_3"]').val() == "") {
            $('input[name="quantity_paying_3"]').val("0");
            return;
        }

        sum = (value + quantity_paying_2 + quantity_paying_3).toFixed(2);
        if (sum < total) {
            $('#total_paying').text(parseFloat(sum).toFixed(2));
            $('#difference').text((parseFloat(sum) - total).toFixed(2));
            $('#vuelto').text(parseFloat(0).toFixed(2));
            $('.wrapper_difference').removeClass('text-success');
            $('.wrapper_difference').addClass('text-danger');
            $('.btn-confirm-pay').prop('disabled', true);
        } else {
            $('#total_paying').text(parseFloat(sum).toFixed(2));
            $('#difference').text(parseFloat(0).toFixed(2));
            $('#vuelto').text((parseFloat(sum) - total).toFixed(2));
            $('#vuelto-input').val((parseFloat(sum) - total).toFixed(2));
            $('.wrapper_difference').addClass('text-warning');
            $('.wrapper_difference').removeClass('text-danger');
            $('.btn-confirm-pay').prop('disabled', false);
        }
    });

    // Save Payment
    $('body').on('click', '.btn-confirm-pay', function() {
        event.preventDefault();
        window.userBill = null;
        let form = $('#form-save-sale').serializeArray();
        form[form.length] = {
            "name": "iddocumento_tipo",
            "value": $('input[name="iddocumento_tipo"]').val()
        };
        form[form.length] = {
            "name": "quantity_paying",
            "value": $('input[name="quantity_paying"]').val()
        };
        form[form.length] = {
            "name": "quantity_paying_2",
            "value": $('input[name="quantity_paying_2"]').val()
        };
        form[form.length] = {
            "name": "quantity_paying_3",
            "value": $('input[name="quantity_paying_3"]').val()
        };
        form[form.length] = {
            "name": "dni_ruc",
            "value": $('select[name="dni_ruc"]').val()
        };
        form[form.length] = {
            "name": "modo_pago",
            "value": $('select[name="modo_pago"]').val()
        };
        form[form.length] = {
            "name": "modo_pago_2",
            "value": $('select[name="modo_pago_2"]').val()
        };
        form[form.length] = {
            "name": "modo_pago_3",
            "value": $('select[name="modo_pago_3"]').val()
        };
        form[form.length] = {
            "name": "_token",
            "value": "{{ csrf_token() }}"
        };
        form[form.length] = {
            "name": "serie_sale",
            "value": $('#serie-sale').text()
        };
        form[form.length] = {
            "name": "difference",
            "value": $('#difference').text()
        };
        form[form.length] = {
            "name": "vuelto",
            "value": $('#vuelto').text()
        };
        form[form.length] = {
            "name": "referencia",
            "value": $('input[name="referencia"]').val()
        };
        form[form.length] = {
            "name": "cuenta",
            "value": $("input[name='cuentas']:checked").val()
        };
        form[form.length] = {
            "name": "referencia2",
            "value": $('input[name="referencia2"]').val()
        };
        form[form.length] = {
            "name": "cuenta2",
            "value": $("input[name='cuentas2']:checked").val()
        };

        //console.log(form);
        //return;

        if (window.usd) {
            form[form.length] = {
                "name": "id_moneda",
                "value": 2
            };
        }else{
            form[form.length] = {
                "name": "id_moneda",
                "value": 1
            };
        }

        $.ajax({
            url: "{{ route('admin.save_billing_pos') }}",
            method: 'POST',
            data: form,
            beforeSend: function() {
                $('.btn-confirm-pay').prop('disabled', true);
                $('.text-confirm-pay').addClass('d-none');
                $('.text-confirm-payment').removeClass('d-none');
            },
            success: function(r) {
                if (!r.status) {
                    $('.btn-confirm-pay').prop('disabled', false);
                    $('.text-confirm-pay').removeClass('d-none');
                    $('.text-confirm-payment').addClass('d-none');
                    toast_msg(r.msg, r.type);
                    return;
                }

                if (r.type_document == '7') {
                    // Open ticket sale note
                    $('.btn-confirm-pay').prop('disabled', false);
                    $('.text-confirm-pay').removeClass('d-none');
                    $('.text-confirm-payment').addClass('d-none');
                    open_ticket_sn(r.pdf);
                } else {
                    // Send billing
                    send_data_sunat(r.id, r.pdf);
                }
            },
            dataType: 'json'
        });
    });

    function open_ticket_sn(ticket) {
        $('#modalConfirmSale').modal('hide');
        let pdf = `{{ asset('files/sale-notes/ticket/${ticket}') }}`;
        var iframe = document.createElement('iframe');
        iframe.style.display = "none";
        iframe.src = pdf;
        document.body.appendChild(iframe);
        iframe.contentWindow.focus();
        iframe.contentWindow.print();
        $('input[name="input-search-product"]').val('');
        load_alerts();
        load_serie();
        load_cart();
        load_view_products();
        load_clients(2);
    }

    function send_data_sunat(id, ticket)
    {
        $.ajax({
            url             : "{{ route('admin.send_bf') }}",
            method          : "POST",
            data            : {
                '_token'    : "{{ csrf_token() }}",
                id          : id
            },
            beforeSend      : function(){
                $('.btn-confirm-pay').prop('disabled', true);
                $('.text-confirm-pay').addClass('d-none');
                $('.text-confirm-payment').removeClass('d-none');
            },
            success         : function(r){
                if(!r.status){}
                $('.btn-confirm-pay').prop('disabled', false);
                $('.text-confirm-pay').removeClass('d-none');
                $('.text-confirm-payment').addClass('d-none');

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
                            $('.btn-confirm-pay').prop('disabled', true);
                            $('.text-confirm-pay').addClass('d-none');
                            $('.text-confirm-payment').removeClass('d-none');
                    },
                    }).done(function(res){
                        $('.btn-confirm-pay').prop('disabled', false);
                        $('.text-confirm-pay').removeClass('d-none');
                        $('.text-confirm-payment').addClass('d-none');
                        if (res.trim() == "No se registró")
                        {
                            toast_msg('El número de comprobante electrónico esta duplicado, revise la base de datos', 'error');
                            return;
                        }

                        let respuesta_sunat = JSON.parse(res),
                            estado_conexion = JSON.parse(respuesta_sunat).status;

                        $('#modalConfirmSale').modal('hide');
                        let pdf = `{{ asset('files/billings/ticket/${ticket}') }}`;
                        var iframe = document.createElement('iframe');
                        iframe.style.display = "none";
                        iframe.src = pdf;
                        document.body.appendChild(iframe);
                        iframe.contentWindow.focus();
                        iframe.contentWindow.print();
                        $('input[name="input-search-product"]').val('');
                        load_alerts();
                        load_serie();
                        load_cart();
                        load_view_products();
                        load_clients(2);
                        if(estado_conexion != false)
                        {
                            update_cdr(idfactura);
                        }
                    }).fail(function(jqxhr, textStatus, error){
                        $('.btn-confirm-pay').prop('disabled', false);
                        $('.text-confirm-pay').removeClass('d-none');
                        $('.text-confirm-payment').addClass('d-none');
                        $('#modalConfirmSale').modal('hide');
                        let pdf = `{{ asset('files/billings/ticket/${ticket}') }}`;
                        var iframe = document.createElement('iframe');
                        iframe.style.display = "none";
                        iframe.src = pdf;
                        document.body.appendChild(iframe);
                        iframe.contentWindow.focus();
                        iframe.contentWindow.print();
                        $('input[name="input-search-product"]').val('');
                        load_alerts();
                        load_serie();
                        load_cart();
                        load_view_products();
                        load_clients(2);
                    });
            },
            dataType        : "json"
        });
    }

    function update_cdr(idfactura)
    {
        let resp = '';
        $.ajax({
            url     : "{{ route('admin.update_cdr_bf') }}",
            method  : 'POST',
            data    : {
                '_token'   : "{{ csrf_token() }}",
                idfactura  : idfactura
            },
            success : function(r){},
            dataType : 'json'
        });
    }

    $('#modo_pago_2').on('change', function() {
        if ($('#modo_pago_2').val() == 7) {
            if (window.usd) {
                $("#cuentasDiv2 .cuenta-item").hide();
                $("#cuentasDiv2 .cuenta-item[data-moneda='SOLES']").hide();
                $("#cuentasDiv2 .cuenta-item[data-moneda='DOLARES']").show();
            } else {
                $("#cuentasDiv2 .cuenta-item").hide();
                $("#cuentasDiv2 .cuenta-item[data-moneda='SOLES']").show();
                $("#cuentasDiv2 .cuenta-item[data-moneda='DOLARES']").hide();

            }
        }else{
            $("#cuentasDiv2 .cuenta-item").hide();
        }
    });

// $('#fechaDesde').change(function () {
//   listarComprobante();
// })
// $('#fechaHasta').change(function () {
//   listarComprobante();
// })
// $('#tipoComprobante').change(function () {
//   listarComprobante();
// })

// $('#s_tipo_precio').change(function () {
//   busqueda = '';

//   listarProductos(busqueda);
//   //load_view_products()
// })


</script>

<script>

    document.addEventListener('DOMContentLoaded', function() {
        window.usd = false;
        fecha = new Date();
        url = "https://apiperu.dev/api/tipo_de_cambio";
        data = {
            fecha: `${fecha.getFullYear()}-${(fecha.getMonth() + 1).toString().padStart(2, '0')}-${fecha.getDate().toString().padStart(2, '0')}`,
            moneda: "USD",
        };

        authorizationToken = "a3e20be04068cd29796811fcdc6a4e79c32124c84fbe072e54afcb4b1d28ea86"; // Replace with your actual token

        fetch(url, {
            method: "POST",
            headers: {
                "Accept": "application/json",
                "Content-Type": "application/json",
                Authorization: `Bearer ${authorizationToken}`,
            },
            body: JSON.stringify(data),
        })
        .then((response) => response.json())
        .then((data) => {
            window.cambio = data.data.purchase;
            inputCambio = document.getElementById('tipo_cambio');
            inputCambio.value = "S/ " + data.data.purchase;
        })
        .catch((error) => {
            window.cambio = 370;
            inputCambio = document.getElementById('tipo_cambio');
            inputCambio.value = "S/ 370";
            console.error(error);
        });

        // $.ajax({
        //     url: "https://v6.exchangerate-api.com/v6/f9cb370829872ab4c1e4c4aa/latest/USD",
        //     method: "GET",
        //     data: {

        //     },
        //     success: function(response) {
        //         //console.log(response);
        //         console.log("Cambio: " + response.conversion_rates.PEN);
        //         window.cambio = response.conversion_rates.PEN;
        //         inputCambio = document.getElementById('tipo_cambio');
        //         inputCambio.value = window.cambio;
        //     },
        //     error: function(error) {
        //         console.error(error);
        //     }
        // });

        // select = document.getElementById('s_tipo_precio');
        // select.addEventListener('change', function() {
        //     selectedOption = this.options[select.selectedIndex];
        //     value = selectedOption.value;

        //     if (value == 0) {
        //         elementosConItem = document.querySelectorAll('[id^="itemPrice"]');

        //         elementosConItem.forEach((elemento) => {
        //             precio = parseFloat(elemento.getAttribute('data-precio'));

        //             elemento.innerHTML = "S/" + decimales(precio);
        //         });
        //     }else if (value == 1) {
        //         elementosConItem = document.querySelectorAll('[id^="itemPrice"]');

        //         elementosConItem.forEach((elemento) => {
        //             precio = parseFloat(elemento.getAttribute('data-precio2'));

        //             elemento.innerHTML = "S/" + decimales(precio);
        //         });
        //     } else {
        //         elementosConItem = document.querySelectorAll('[id^="itemPrice"]');

        //         elementosConItem.forEach((elemento) => {
        //             precio = parseFloat(elemento.getAttribute('data-precio3'));

        //             elemento.innerHTML = "S/" + decimales(precio);
        //         });
        //     }
        // })

        $('body').on('click', '#btnBuscarFacturas', function() {
            event.preventDefault();
            //alert("En proceso...")
            //return;

            identificacion = $('#identificacion').val()
            fechaDesde = $('#fechaDesde').val()
            fechaHasta = $('#fechaHasta').val()
            tipoComprobante = $('#tipoComprobante').val()
            product = $('#product').val()
            //console.log("{{ csrf_token() }}");

            $.ajax({
                url: "{{ route('admin.userBillings') }}",
                method: 'POST',
                data: {
                    '_token': "{{ csrf_token() }}",
                    identificacion: identificacion,
                    fechaDesde: fechaDesde,
                    fechaHasta: fechaHasta,
                    tipoComprobante: tipoComprobante,
                    product: product
                },
                success: function(r) {
                    //bills = JSON.parse(r.bills[0])
                    //console.log(r.bills[0]);
                    $('#bills').html(r.html_bills);
                },
                dataType: 'json'
            }).fail(function(error) {
                // Handle the error here
                console.error('Error:', error.responseText);
                // Perform error-specific actions
            });
            return;
        });

        $('body').on('click', '#btn_modalventas', function(e) {
            e.preventDefault();
            today = new Date().toISOString().slice(0, 10);

            $('#fechaDesde').val(today);

            today = new Date();
            today.setDate(today.getDate() + 1);
            tomorrow = today.toISOString().slice(0, 10);

            $('#tipoComprobante').val('todos');
            $('#product').val('0');
            $('#fechaHasta').val(tomorrow);

            //listarComprobante();
        });



        $('body').on('click', '#btn_tipo_precio', function(e) {
            e.preventDefault();

            Swal.fire({
                title:  'Tipos de precios',
                text:   'Elige el tipo de precio a aplicar',
                icon:   'info',
                showCancelButton: true,
                confirmButtonText:  'Precio publico',
                denyButtonText:     'Precio al por mayor',
                cancelButtonText:   'Precio distribuidor',
                customClass: {
                    confirmButton:  'btn btn-primary',
                    denyButton:     'btn btn-primary',
                    cancelButton:   'btn btn-primary',
                },
                buttonsStyling: false,
                backdrop: true,
                allowOutsideClick: false
            }).then((result) => {
                console.log(result);
                if (result.isConfirmed) {
                    window.tipoCobro = 1;
                    elementosConItem = document.querySelectorAll('[id^="itemPrice"]');

                    elementosConItem.forEach((elemento) => {
                        window.precio = parseFloat(elemento.getAttribute('data-precio'));

                        elemento.innerHTML = "S/ " + decimales(window.precio);
                    });

                    $("#kindPrice").html("Precio público");
                }else if (result.isDenied) {
                    window.tipoCobro = 2;
                    elementosConItem = document.querySelectorAll('[id^="itemPrice"]');

                    elementosConItem.forEach((elemento) => {
                        window.precio = parseFloat(elemento.getAttribute('data-precio2'));

                        elemento.innerHTML = "S/ " + decimales(window.precio);
                    });

                    $("#kindPrice").html("Precio al por mayor");
                }else if (result.isDismissed) {
                    window.tipoCobro = 3;
                    elementosConItem = document.querySelectorAll('[id^="itemPrice"]');

                    elementosConItem.forEach((elemento) => {
                        window.precio = parseFloat(elemento.getAttribute('data-precio3'));

                        elemento.innerHTML = "S/ " + decimales(window.precio);
                    });

                    $("#kindPrice").html("Precio distribuidor");
                }
            });
        });

        $('body').on('click', '#btn_cambioMoneda', function(e) {
            if (window.usd) {
                window.usd = false

                if (window.tipoCobro == 1) {
                    elementosConItem = document.querySelectorAll('[id^="itemPrice"]');

                    elementosConItem.forEach((elemento) => {
                        window.precio = parseFloat(elemento.getAttribute('data-precio'));

                        elemento.innerHTML = "S/ " + decimales(window.precio);
                    });
                }else if (window.tipoCobro == 2) {
                    elementosConItem = document.querySelectorAll('[id^="itemPrice"]');

                    elementosConItem.forEach((elemento) => {
                        window.precio = parseFloat(elemento.getAttribute('data-precio2'));

                        elemento.innerHTML = "S/ " + decimales(window.precio);
                    });
                }else if (window.tipoCobro == 3) {
                    elementosConItem = document.querySelectorAll('[id^="itemPrice"]');

                    elementosConItem.forEach((elemento) => {
                        window.precio = parseFloat(elemento.getAttribute('data-precio3'));

                        elemento.innerHTML = "S/ " + decimales(window.precio);
                    });
                }else{
                    elementosConItem = document.querySelectorAll('[id^="itemPrice"]');

                    elementosConItem.forEach((elemento) => {
                        window.precio = parseFloat(elemento.getAttribute('data-precio'));

                        elemento.innerHTML = "S/ " + decimales(window.precio);
                    });
                }

                $("input[name='precio']").each(function() {
                    precioSoles = $(this).data('precio');
                    precioSoles = parseFloat(precioSoles);

                    if (!isNaN(precioSoles)) {
                        $(this).val(precioSoles.toFixed(2));
                    } else{
                        alert("Error al realizar el cambio de moneda.")
                    }
                });

                $("span.total").each(function() {
                    precioSoles = parseFloat($(this).data('total'));
                    if (!isNaN(precioSoles)) {
                        $(this).html(precioSoles.toFixed(2));
                    } else {
                        alert("Error al realizar el cambio de moneda.")
                    }
                });

                $("span.gravadas").each(function() {
                    precioSoles = parseFloat($(this).data('valor'));
                    if (!isNaN(precioSoles)) {
                        $(this).html(precioSoles.toFixed(2));
                    } else {
                        alert("Error al realizar el cambio de moneda.")
                    }
                });

                $("span.igv").each(function() {
                    precioSoles = parseFloat($(this).data('valor'));
                    if (!isNaN(precioSoles)) {
                        $(this).html(precioSoles.toFixed(2));
                    } else {
                        alert("Error al realizar el cambio de moneda.")
                    }
                });

                $("span.total2").each(function() {
                    precioSoles = parseFloat($(this).data('valor'));
                    if (!isNaN(precioSoles)) {
                        $(this).html(precioSoles.toFixed(2));
                    } else {
                        alert("Error al realizar el cambio de moneda.")
                    }
                });

                $("span[name='moneda']").each(function() {
                    $(this).html("S/ ");
                });

                $("#btn_cambioMoneda").html("<i class=\"fa-solid fa-dollar-sign fa-2x\"></i>");
                $("#btn_cambioMoneda").removeClass("btn-warning");
                $("#btn_cambioMoneda").removeClass("btn-success");
                $("#btn_cambioMoneda").addClass("btn-success");

            } else {

                window.usd = true;

                if (window.tipoCobro == 1) {
                    elementosConItem = document.querySelectorAll('[id^="itemPrice"]');

                    elementosConItem.forEach((elemento) => {
                        window.precio = parseFloat(elemento.getAttribute('data-precio'));

                        elemento.innerHTML = "$ " + decimales((window.precio / window.cambio));
                    });
                }else if (window.tipoCobro == 2) {
                    elementosConItem = document.querySelectorAll('[id^="itemPrice"]');

                    elementosConItem.forEach((elemento) => {
                        window.precio = parseFloat(elemento.getAttribute('data-precio2'));

                        elemento.innerHTML = "$ " + decimales((window.precio / window.cambio));
                    });
                }else if (window.tipoCobro == 3) {
                    elementosConItem = document.querySelectorAll('[id^="itemPrice"]');

                    elementosConItem.forEach((elemento) => {
                        window.precio = parseFloat(elemento.getAttribute('data-precio3'));

                        elemento.innerHTML = "$ " + decimales((window.precio / window.cambio));
                    });
                }else{
                    elementosConItem = document.querySelectorAll('[id^="itemPrice"]');

                    elementosConItem.forEach((elemento) => {
                        window.precio = parseFloat(elemento.getAttribute('data-precio'));

                        elemento.innerHTML = "$ " + decimales((window.precio / window.cambio));
                    });
                }

                $("input[name='precio']").each(function() {
                    precioSoles = parseFloat($(this).data('precio'));

                    if (!isNaN(precioSoles)) {
                        precioUSD = (precioSoles / window.cambio);

                        $(this).val(precioUSD.toFixed(2));
                    } else {
                        alert("Error al realizar el cambio de moneda.")
                    }
                });

                $("span.total").each(function() {
                    precioSoles = parseFloat($(this).data('total'));

                    if (!isNaN(precioSoles)) {
                        precioUSD = precioSoles / window.cambio;

                        $(this).html(precioUSD.toFixed(2));
                    } else {
                        alert("Error al realizar el cambio de moneda.")
                    }
                });

                $("span.gravadas").each(function() {
                    precioSoles = parseFloat($(this).data('valor'));

                    if (!isNaN(precioSoles)) {
                        precioUSD = precioSoles / window.cambio;

                        $(this).html(precioUSD.toFixed(2));
                    } else {
                        alert("Error al realizar el cambio de moneda.")
                    }
                });

                $("span.igv").each(function() {
                    precioSoles = parseFloat($(this).data('valor'));

                    if (!isNaN(precioSoles)) {
                        precioUSD = precioSoles / window.cambio;

                        $(this).html(precioUSD.toFixed(2));
                    } else {
                        alert("Error al realizar el cambio de moneda.")
                    }
                });

                $("span.total2").each(function() {
                    precioSoles = parseFloat($(this).data('valor'));

                    if (!isNaN(precioSoles)) {
                        precioUSD = precioSoles / window.cambio;

                        $(this).html(precioUSD.toFixed(2));
                    } else {
                        alert("Error al realizar el cambio de moneda.")
                    }
                });

                $("span[name='moneda']").each(function() {
                    $(this).html("$ ");
                });

                $("#btn_cambioMoneda").html("<b  style=\"font-size: 24px;\">S/</b>");
                $("#btn_cambioMoneda").removeClass("btn-success");
                $("#btn_cambioMoneda").addClass("btn-warning");
            }

        });
    });

    function addProduct(id, precio, cliente, idCliente, dniCliente){
        console.log(idCliente);
        addOption(idCliente, dniCliente, cliente)

        $.ajax({
            url: "{{ route('admin.add_product_pos') }}",
            method: 'POST',
            data: {
                '_token': "{{ csrf_token() }}",
                id: id,
                cantidad: 1,
                precio: precio,
                option: 1
            },
            beforeSend: function() {
                block_content(`.card[id="${id}"]`);
            },
            success: function(r) {
                if (!r.status) {
                    close_block(`.card[id="${id}"]`);
                    toast_msg(r.msg, r.type);
                    return;

                }
                close_block(`.card[id="${id}"]`);
                toast_msg(r.msg, r.type);
                load_cart();
            },
            dataType: 'json'
        });
        return;
    }

    function addOption(idCliente, dniCliente, cliente) {
        select = document.getElementById('dni_ruc');
        newOption = document.createElement('option');

        newOption.text = dniCliente + ' - ' + cliente;
        newOption.value = idCliente;
        newOption.selected = true;

        options = select.options;
        // for (let i = 0; i < options.length; i++) {
        //     options[i].selected = false;
        //     if (options[i].selected) {
        //         console.log(options[i]);
        //     }

        // }

        select.add(newOption, select.firstChild);

        //userOption = document.getElementById('user');

        //if (condition) {
        // Modify option content
        //userOption.text = dniCliente + ' - ' + cliente; // Replace with your desired text
        //userOption.value = idCliente; // Replace with your desired value
        window.userBill = cliente;
        //} else {
        // Remove the option
        //userOption.parentNode.removeChild(userOption);
        //}
    }

    function decimales(n) {
        numeroFormateado = n.toFixed(2);
        partes = numeroFormateado.split('.');

        if (partes.length === 1) {
            return `${partes[0]}.00`;
        }

        const decimal = partes[1].padEnd(2, '0');
        return `${partes[0]}.${decimal}`;
    }
</script>
