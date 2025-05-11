<script>
    $('#form-purchases-general select[name="idtipo_documento"]').select2();

    $('body').on('click', '.btn-search', function()
    {
        event.preventDefault();
        let form        = $('#form-purchases-general').serialize();
        $.ajax({
            url         : "{{ route('admin.search_purchases_general') }}",
            method      : "POST",
            data        : form,
            beforeSend  : function(){
                $('.btn-search').prop('disabled', true);
                $('.text-search').addClass('d-none');
                $('.text-searching').removeClass('d-none');
            },
            success     : function(r){
                if(!r.status)
                {
                    $('.btn-search').prop('disabled', false);
                    $('.text-search').removeClass('d-none');
                    $('.text-searching').addClass('d-none');
                    toast_msg(r.msg, r.type);
                    return;
                }

                let html_tbody = '',
                    total      = 0;
                $('.btn-search').prop('disabled', false);
                $('.text-search').removeClass('d-none');
                $('.text-searching').addClass('d-none');
                $('.quantity').html(r.quantity);
                $.each(r.buys, function(index, buy){
                    total += parseFloat(buy.total);
                    html_tbody += `<tr>
                            <td class="text-center">${moment(buy.fecha_emision).format('DD-MM-yyyy')}</td>
                            <td class="text-center">${buy.serie}-${buy.correlativo}</td>
                            <td class="text-center">${buy.dni_ruc}</td>
                            <td class="text-center">${buy.proveedor}</td>
                            <td class="text-center">${buy.exonerada}</td>
                            <td class="text-center">${buy.gravada}</td>
                            <td class="text-center">${buy.inafecta}</td>
                            <td class="text-center">${buy.igv}</td>
                            <td class="text-center">${buy.total}</td>
                            </tr>`;
                });
                html_tbody += `<tr>
                                <th colspan="8" class="text-end">Total S/ </th>
                                <td class="text-center">${parseFloat(total).toFixed(2)}</td>
                            </tr>`;
                $('#wrapper_tbody').html(html_tbody);
                $('#wrapper_tbody').addClass('d-none');
                $('#wrapper_tbody').fadeIn('slow');
                $('#wrapper_tbody').removeClass('d-none');
            },
            dataType    : "json"
        });
    });

    $('body').on('click', '.btn-create-client', function()
    {
        event.preventDefault();
        $('#modalAddClient').modal('show');
        //Direccion obligatorio por defecto
        $('#opcional_direccion').addClass('d-none');
        console.log(123)
    });

    // Type document
    $('#form_save_client select[name="tipo_documento"]').on('change', function() {
        let value = $(this).val();
        switch (value) {
            case '2':
                $('#form_save_client #wrapper-input-reniec').removeClass('d-none');
                $('#form_save_client #wrapper-input-reniec').removeClass('d-none');
                $('#form_save_client .input-text-reniec').text('RENIEC');
                $('#form_save_client .input-text-reniec').text('RENIEC');
                break;

            case '4':
                $('#wrapper-input-reniec').removeClass('d-none');
                $('#form_save_client #wrapper-input-reniec').removeClass('d-none');
                $('#form_save_client .input-text-reniec').text('SUNAT');
                $('#form_save_client .input-text-reniec').text('SUNAT');
                break;

            default:
                $('#form_save_client #wrapper-input-reniec').addClass('d-none');
                $('#form_save_client #wrapper-input-reniec').addClass('d-none');
                break;
        }
    });

    $('body').on('click', '#form_save_client .btn-search-dniruc', function() {
        $('#form_save_client #wrapper_province').addClass('d-none');
        $('#form_save_client #wrapper_district').addClass('d-none');
        let type_document = $('#form_save_client select[name="tipo_documento"]').val(),
            dni_ruc = $('#form_save_client input[name="dni_ruc"]').val();

        if (type_document == 2) {
            if (dni_ruc.length != 8) {
                toast_msg('Ingrese un número válido', 'warning');
                return;
            }
        }

        if (type_document === 4) {
            if (dni_ruc.length != 11) {
                toast_msg('Ingrese un número válido', 'warning');
                return;
            }
        }

        $.ajax({
            url: "{{ route('admin.search_dni_ruc') }}",
            method: 'POST',
            data: {
                '_token': "{{ csrf_token() }}",
                type_document: type_document,
                dni_ruc: dni_ruc
            },
            beforeSend: function() {
                $('#form_save_client .btn-search-dniruc').prop('disabled', true);
                $('#form_save_client .text-search').addClass('d-none');
                $('#form_save_client .text-searching').removeClass('d-none');
            },
            success: function(r) {
                if (!r.status) {
                    $('#form_save_client .btn-search-dniruc').prop('disabled', false);
                    $('#form_save_client .text-search').removeClass('d-none');
                    $('#form_save_client .text-searching').addClass('d-none');
                    toast_msg(r.msg, r.type);
                    return;
                }

                $('#form_save_client .btn-search-dniruc').prop('disabled', false);
                $('#form_save_client .text-search').removeClass('d-none');
                $('#form_save_client .text-searching').addClass('d-none');
                $('#form_save_client input[name="razon_social"]').val(r.nombres);
                $('#form_save_client input[name="direccion"]').val(r.direccion);

                // Direccion opcional si es DNI o RUD 10...
                const dni = !!(type_document === '2' || (type_document === '4' &&
                    dni_ruc.startsWith('10')));
                if (dni)
                    $('#opcional_direccion').removeClass('d-none');
            },
            dataType: 'json'
        });
    });
</script>
