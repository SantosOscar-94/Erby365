<script>
    $('body').on('click', '.btn-confirm-transfer', function() {
        event.preventDefault();
        let id  = $(this).data('id');
        $.ajax({
            url         : "{{ route('admin.detail_transfer') }}",
            method      : "POST",
            data        : {
                '_token': "{{ csrf_token() }}",
                id      : id
            },
            beforeSend  : function(){
                block_content('#layout-content');
            },
            success     : function(r){
                if(!r.status)
                {
                    close_block('#layout-content');
                    toast_msg(r.msg, r.type);
                    return;
                }
                close_block('#layout-content');
                let detail__transfer = '';
                $('input[name="idtransfer"]').val(r.transfer.id);
                $('.nro__orden').text(r.transfer.serie + '-' + r.transfer.correlativo);
                $('.td__emision').text(r.fecha_emision);
                $('.td__expiration').text(r.fecha_vencimiento);
                $('.td__dispatch').text(r.transfer.almacen_despacho);
                $('.td__receiver').text(r.transfer.almacen_receptor);
                $('.td__optional').text(r.transfer.observaciones);    
              
                $.each(r.detail_transfer, function(index, transfer){
                    detail__transfer += `<tr>
                                            <td class="text-center">${index + 1}</td>
                                            <td class="text-left">${(transfer.codigo_interno == null || transfer.codigo_interno == "") ? "-" : transfer.codigo_interno}</td>
                                            <td class="text-left">${transfer.producto}</td>
                                            <td class="text-center">${transfer.cantidad}</td>
                                        </tr>`;
                });

                $('#tbody_detail_transfer').html(detail__transfer);
                $('#modalConfirmTransfer').modal('show');
            },
            dataType    : "json"
        });
    });

    $('body').on('click', '.btn-save', function() {
        event.preventDefault();
        let form    = $('#form_save').serialize();
        $.ajax({
            url         : "{{ route('admin.move_transfer') }}",
            method      : "POST",
            data        : form,
            beforeSend  : function(){
                $('.btn-save').prop('disabled', true);
                $('.text-save').addClass('d-none');
                $('.text-saving').removeClass('d-none');
            },
            success     : function(r)
            {
                if(!r.status)
                {
                    $('.btn-save').prop('disabled', false);
                    $('.text-save').removeClass('d-none');
                    $('.text-saving').addClass('d-none');
                    toast_msg(r.msg, r.type);
                    return;
                }

                $('.btn-save').prop('disabled', false);
                $('.text-save').removeClass('d-none');
                $('.text-saving').addClass('d-none');
                $('#modalConfirmTransfer').modal('hide');
                load_alerts();
                reload_table();
                let pdf                 =   `{{ asset('files/transfer-orders/${r.pdf}') }}`;
                var iframe              = document.createElement('iframe');
                iframe.style.display    = "none";
                iframe.src              = pdf;
                document.body.appendChild(iframe);
                iframe.contentWindow.focus();
                iframe.contentWindow.print();
            },
            dataType    : "json"
        });
    });

    $('body').on('click', '.btn-print', function()
    {
        let id = $(this).data('id');
        $.ajax({
            url: "{{ route('admin.print_transfer') }}",
            method: "POST",
            data: {
                '_token': "{{ csrf_token() }}",
                id: id
            },
            beforeSend: function() {
                block_content('#layout-content');
            },
            success: function(r) {
                if (!r.status) {
                    close_block('#layout-content');
                    toast_msg(r.msg, r.type);
                    return;
                }
                close_block('#layout-content');
                let pdf                 =   `{{ asset('files/transfer-orders/${r.pdf}') }}`;
                var iframe              = document.createElement('iframe');
                iframe.style.display    = "none";
                iframe.src              = pdf;
                document.body.appendChild(iframe);
                iframe.contentWindow.focus();
                iframe.contentWindow.print();
            },
            dataType: "json"
        });
    });

    $('body').on('click', '.btn-download', function()
    {
        event.preventDefault();
        let id              = $(this).data('id');
        base_url            = "{{ url('/') }}",
        url_print           = `${base_url}/download-transfer-order/${id}`;
        window.open(url_print);
    });

    $('body').on('click', '.btn-confirm', function()
    {
        event.preventDefault();
        let id      = $(this).data('id');
        Swal.fire({
            title: 'Anular',
            text: "¿Desea anular la orden?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Si, anular',
            cancelButtonText: 'Cancelar',
            customClass: {
                confirmButton: 'btn btn-primary',
                cancelButton: 'btn btn-outline-danger ml-1'
            },
            buttonsStyling: false
        }).then(function (result) {
            if (result.value) 
            {
                $.ajax({
                    url         : "{{ route('admin.anulled_tansfer_order') }}",
                    method      : 'POST',
                    data        : {
                        '_token': "{{ csrf_token() }}",
                        id      : id
                    },
                    success     : function(r){
                        if(!r.status)
                        {
                            toast_msg(r.msg, r.type);
                            return;
                        }

                        toast_msg(r.msg, r.type);
                        reload_table();
                    },
                    dataType    : 'json'
                });
            }
        });
    });
</script>