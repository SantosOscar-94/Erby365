<script>
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
                    data        : 'created_at',
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
</script>
