<script>
    $('#form-kardex select[name="user"]').select2();
    $('#form-kardex select[name="document"]').select2();
    $('#form-kardex select[name="warehouse"]').select2();


    $('body').on('click', '.btn-search', function()
    {
        event.preventDefault();
        let form        = $('#form-kardex').serialize();
        $.ajax({
            url         : "{{ route('admin.kardex.filter') }}",
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

                let html_tbody      = '',
                    total           = 0,
                    total_anulado   = 0,
                    total_neto      = 0,
                    tachado         = '';
                $('.btn-search').prop('disabled', false);
                $('.text-search').removeClass('d-none');
                $('.text-searching').addClass('d-none');
                $('.quantity').html(r.quantity);
                $.each(r.kardex, function(index, item){
                    // if(billing.estado_venta == 2)
                    // {
                    //     total_anulado += parseFloat(billing.total);
                    //     tachado = 'tachado';
                    // }
                    // else
                    // {
                    //     tachado = '';
                    // }

                    // if(billing.idtipo_comprobante != 6) {
                    //     total += parseFloat(billing.total);
                    // }
                    // html_tbody += `<tr class="">
                    //         <td class="text-center">${moment(item.created_at).format('DD-MM-yyyy')}</td>
                    //         <td class="text-center">${item.documentTypeId}</td>
                    //         <td class="text-center">${item.document}</td>
                    //         <td class="text-center">${item.product}</td>
                    //         <td class="text-center">${item.cant1 ?? 0}</td>
                    //         <td class="text-center">${parseFloat(item.price1 ?? 0).toLocaleString('es-PE', { maximumFractionDigits: 2 })}</td>
                    //         <td class="text-center">${parseFloat(item.total1 ?? 0).toLocaleString('es-PE', { maximumFractionDigits: 2 })}</td>
                    //         <td class="text-center">${item.cant2 ?? 0}</td>
                    //         <td class="text-center">${parseFloat(item.price2 ?? 0).toLocaleString('es-PE', { maximumFractionDigits: 2 })}</td>
                    //         <td class="text-center">${parseFloat(item.total2 ?? 0).toLocaleString('es-PE', { maximumFractionDigits: 2 })}</td>
                    //         <td class="text-center">${item.cant3 ?? 0}</td>
                    //         <td class="text-center">${parseFloat(item.price3 ?? 0).toLocaleString('es-PE', { maximumFractionDigits: 2 })}</td>
                    //         <td class="text-center">${parseFloat(item.total3 ?? 0).toLocaleString('es-PE', { maximumFractionDigits: 2 })}</td>
                    //         </tr>`;

                    html_tbody += `<tr class="">
                            <td class="text-center">${moment(item.created_at).format('DD-MM-yyyy')}</td>
                            <td class="text-center">${item.vendedor}</td>
                            <td class="text-center">${item.tipo}</td>
                            <td class="text-center">${item.tipo_documento}</td>
                            <td class="text-center">${item.document}</td>
                            <td class="text-center">${item.nombre_tienda}</td>
                            <td class="text-center">${item.product}</td>
                            <td class="text-center">${item.cant1 ?? 0}</td>
                            <td class="text-center">${item.cant2 ?? 0}</td>
                            <td class="text-center">${item.cant3 ?? 0}</td>
                            </tr>`;
                });

                $('#wrapper_tbody').html(html_tbody);
                $('#wrapper_tbody').addClass('d-none');
                $('#wrapper_tbody').fadeIn('slow');
                $('#wrapper_tbody').removeClass('d-none');
            },
            dataType    : "json"
        });
    });
</script>
