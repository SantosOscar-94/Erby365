<script>
    $('#form-sales-general select[name="idclient"]').select2();
    $('#form-sales-general select[name="document"]').select2();
    $('body').on('click', '.btn-search', function() {
        event.preventDefault();
        let form = $('#form-sales-general').serialize();
        $.ajax({
            url: "{{ route('admin.search_sales_general') }}",
            method: "POST",
            data: form,
            beforeSend: function() {
                $('.btn-search').prop('disabled', true);
                $('.text-search').addClass('d-none');
                $('.text-searching').removeClass('d-none');
            },
            success: function(r) {
                console.log(r)
                if (!r.status) {
                    $('.btn-search').prop('disabled', false);
                    $('.text-search').removeClass('d-none');
                    $('.text-searching').addClass('d-none');
                    toast_msg(r.msg, r.type);
                    return;
                }

                let html_tbody = '',
                    total = 0,
                    total_anulado = 0,
                    total_neto = 0,
                    tachado = '',
                    doc_relacionado = null;
                $('.btn-search').prop('disabled', false);
                $('.text-search').removeClass('d-none');
                $('.text-searching').addClass('d-none');
                $('.quantity').html(r.quantity);
                $.each(r.billings, function(index, billing) {
                    if (billing.estado_venta == 2) {
                        total_anulado += parseFloat(billing.total);
                        tachado = 'tachado';
                    } else {
                        tachado = '';
                    }

                    if (billing.idtipo_comprobante != 6) {
                        total += parseFloat(billing.total);
                    }
                    //Date
                    html_tbody += `<tr class="${tachado}">
                            <td class="text-center">${moment(billing.fecha_emision).format('DD-MM-yyyy')}</td>`;
                    //Tipo documento
                    html_tbody += `<td class="text-center">${billing.tipo_documento}</td>`;
                    //Documento
                    html_tbody += `<td class="text-center">${billing.serie}-${billing.correlativo}</td>`;
                    //Documento relacionado
                    html_tbody += `<td>`;
                    $.each(r.doc_relacionados, function(i, doc_relacionado) {
                        if (doc_relacionado != null) {
                            if (doc_relacionado.id == billing.idfactura_anular) {
                                html_tbody +=
                                    `<p class="pay_mode">${doc_relacionado.serie}-${doc_relacionado.correlativo}</p>`;
                            }
                        } else {
                            html_tbody += `<p class="pay_mode"></p>`;
                        }
                    });
                    html_tbody += `</td>`;
                    //RUC / DNI
                    html_tbody += `<td class="text-center">${billing.dni_ruc}</td>`;
                    //Razon social
                    html_tbody += `<td class="text-left">${billing.nombre_cliente}</td>`
                    //Pago
                    html_tbody += `<td>`;
                    $.each(r.pagos, function(i, pago) {
                        $.each(pago, function(k, item_pago) {
                            if (item_pago.idfactura == billing.id &&
                                item_pago.idtipo_comprobante == billing
                                .idtipo_comprobante) {
                                html_tbody +=
                                    `<p class="pay_mode">${item_pago.tipo_pago}: ${item_pago.monto}</p>`;
                            }
                        });
                    });
                    html_tbody += `</td>
                            <td class="text-center">${billing.exonerada}</td>
                            <td class="text-center">${billing.gravada}</td>
                            <td class="text-center">${billing.inafecta}</td>
                            <td class="text-center">${billing.igv}</td>
                            <td class="text-center">${billing.total}</td>
                            </tr>`;
                });
                html_tbody += `<tr>
                                <th colspan="10" class="text-end">Total S/ </th>
                                <td class="text-center">${parseFloat(total).toFixed(2)}</td>
                            </tr>
                            <tr>
                                <th colspan="10" class="text-end text-danger">Anulado S/ </th>
                                <td class="text-center text-danger">${parseFloat(total_anulado).toFixed(2)}</td>
                            </tr>
                            <tr>
                                <th colspan="10" class="text-end">Total Neto S/ </th>
                                <td class="text-center">${parseFloat(total - total_anulado).toFixed(2)}</td>
                            </tr>`;
                $('#wrapper_tbody').html(html_tbody);
                $('#wrapper_tbody').addClass('d-none');
                $('#wrapper_tbody').fadeIn('slow');
                $('#wrapper_tbody').removeClass('d-none');
            },
            dataType: "json"
        });
    });
</script>
