<script>
    //document.getElementById('date_pay').value = new Date().toISOString().split('T')[0]; //inserta fecha actual
    $('#form-accounts_receivable select[name="user"]').select2();
    $('#form-accounts_receivable select[name="document"]').select2();
    $('#form-accounts_receivable select[name="warehouse"]').select2();

    $('body').on('click', '.btn-detail', function()
    {
        event.preventDefault();
        let id  = $(this).data('id');
        $.ajax({
            url         : "{{ route('admin.details_accounts_receivable') }}",
            method      : 'POST',
            data        : {'_token' : "{{ csrf_token() }}",id: id},
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
                console.log(r.details)

                $('#invoice').val(r.details[0].serie + '-' + r.details[0].correlativo);
                $('#currency').val(r.details[0].idmoneda == 1 ? 'Soles' : 'Dolares');
                $('#client').val( r.details[0].cliente);
                $('#seller').val( r.details[0].vendedor);
                $('#total').val( r.details[0].total);
                $('#installment-value').val( r.details[0].valor_cuotas);
                $('#issue-date').val(r.details[0].fecha_emision);
                $('#due-date').val(r.details[0].fecha_vencimiento);
                $('#productList').html(r.products);
                $('#outstanding-balance').val((r.details[0].total - r.details[0].payed).toFixed(2));

                $('#modalEditWarehouse').modal('show');
            },
            dataType    : 'json'
        });
        return;
    });

    $('body').on('click', '.btn-quotes', function()
    {
        event.preventDefault();
        window.id  = $(this).data('id');
        total = $(this).data('total');
        pending = $(this).data('deuda');
        load_datatable_quotes(id);

        $('#payed').html('&nbsp;&nbsp;&nbsp;&nbsp;' + (total - pending).toFixed(2));
        $('#pending').html('&nbsp;&nbsp;&nbsp;&nbsp;' + pending);
        $('#total2').html('&nbsp;&nbsp;&nbsp;&nbsp;' + total);

        $('#modalQuotes').modal('show');
    });

    $('body').on('click', '.btn-add-quote', function()
    {
        $('#modalQuotes').modal('hide');
        $('#modalAddQuote').modal('show');

        $.ajax({
            url         : "{{ route('admin.amount_accounts_receivable') }}",
            method      : 'POST',
            data        : {'_token' : "{{ csrf_token() }}",id: window.id},

            success     : function(r){
                if(!r.status)
                {
                    toast_msg(r.msg, r.type);
                    return;
                }

                $('#amount').val(r.amount);
            },
            dataType    : 'json'
        });
    });

    $('body').on('click', '.btn-store', function()
    {
        event.preventDefault();
        $('#modalAddQuote').modal('hide');
        let form      = $('#form_save').serialize()+'&id='+window.id;

        $.ajax({
            url         : "{{ route('admin.save_accounts_receivable') }}",
            method      : 'POST',
            data        : form,

            success     : function(r){
                if(!r.status)
                {
                    toast_msg(r.msg, r.type);
                    return;
                }

                pending = (parseFloat($('#pending').text().match(/[\d.]+/g)?.[0]) - parseFloat($('#amount').val()));
                payed = (parseFloat($('#payed').text().match(/[\d.]+/g)?.[0]) + parseFloat($('#amount').val()));

                $('#payed').html('&nbsp;&nbsp;&nbsp;&nbsp;' + payed.toFixed(2));
                $('#pending').html('&nbsp;&nbsp;&nbsp;&nbsp;' + pending.toFixed(2));

                toast_msg(r.msg, r.type);
                load_datatable();
                load_datatable_quotes(window.id);
            },
            dataType    : 'json'
        });

        $('#modalQuotes').modal('show');
    });

    function load_datatable_quotes(id)
    {
        let datatable = $('#tableQuotes').DataTable({
            serverSide  :true,
            "paging"    :true,
            "searching" :true,
            "destroy"   :true,
            responsive  :true,
            ordering    :false,
            autoWidth   :false,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
            language: {
                "decimal": "",
                "emptyTable": "No hay información",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ Registros",
                "infoEmpty": "Mostrando 0 a 0 de 0 Registros",
                "infoFiltered": "(Filtrado de _MAX_ total Registros)",
                "infoPostFix": "",
                "thousands": ",",
                "lengthMenu": "Mostrar _MENU_ Registros",
                "loadingRecords": "Cargando...",
                "processing": "Procesando...",
                "search": "",
                "searchPlaceholder": "",
                "zeroRecords": "Sin resultados encontrados",
                "paginate": {
                    "first": "Primero",
                    "last": "Ultimo",
                    "next": "Siguiente",
                    "previous": "Anterior"
                }
            },
            "ajax"      : {
                'url'       : "{{ route('admin.filter_accounts_receivable.quotes') }}",
                'type'      : "POST",
                'data'      : {
                    '_token'    : "{{ csrf_token() }}",
                    'id'  : id,
                }
            },
            "columns"   : [
                {
                    data        : 'id',
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                    className   : 'text-center'
                },
                {
                    data        : 'date',
                    className   : 'text-center'
                },
                {
                    data        : 'pay_method',
                    className   : 'text-center'
                },
                {
                    data        : 'bank_account',
                    className   : 'text-center'
                },
                {
                    data        : 'total',
                    className   : 'text-center'
                },
                {
                    data        : 'received_payment',
                    className   : 'text-center'
                },
                {
                    data        : 'print',
                    className   : 'text-center'
                },
                {
                    data        : 'acciones',
                    className   : 'text-center'
                },
            ]
        });
    }

    $('body').on('click', '.btn-delete', function() {
        event.preventDefault();
        let id = $(this).data('id');
        Swal.fire({
            title: 'Eliminar',
            text: "¿Desea eliminar el registro?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Si, eliminar',
            cancelButtonText: 'Cancelar',
            customClass: {
                confirmButton: 'btn btn-primary',
                cancelButton: 'btn btn-outline-danger ml-1'
            },
            buttonsStyling: false
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    url: "{{ route('admin.delete_accounts_receivable.quotes') }}",
                    method: 'POST',
                    data: {
                        '_token': "{{ csrf_token() }}",
                        id: id
                    },
                    success: function(r) {
                        if (!r.status) {
                            toast_msg(r.msg, r.type);
                            return;
                        }
                        cuota = r.cuota;

                        pending = (parseFloat($('#pending').text().match(/[\d.]+/g)?.[0]) + parseFloat(cuota));
                        payed = (parseFloat($('#payed').text().match(/[\d.]+/g)?.[0]) - parseFloat(cuota));

                        $('#payed').html('&nbsp;&nbsp;&nbsp;&nbsp;' + payed.toFixed(2));
                        $('#pending').html('&nbsp;&nbsp;&nbsp;&nbsp;' + pending.toFixed(2));

                        toast_msg(r.msg, r.type);
                        load_datatable();
                        load_datatable_quotes(window.id)
                    },
                    dataType: 'json'
                });
            }
        });
    });
</script>
