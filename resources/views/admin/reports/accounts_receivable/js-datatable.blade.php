<script>
    function load_datatable()
    {
        let filters        = $('#form-accounts_receivable').serialize();
        let datatable = $('#table').DataTable({
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
                'url'       : "{{ route('admin.filter_accounts_receivable') }}",
                'type'      : "POST",
                'data'      : {
                    '_token'    : "{{ csrf_token() }}",
                    'filters'  : filters,
                }
            },
            "columns"   : [
                {
                    data        : 'fecha_emision',
                    className   : 'text-center'
                },

                {
                    data        : 'fecha_vencimiento',
                    className   : 'text-center'
                },
                {
                    data        : 'comprobante',
                    className   : 'text-center'
                },
                {
                    data        : 'cliente',
                    className   : 'text-center'
                },
                {
                    data        : 'vendedor',
                    className   : 'text-center'
                },
                {
                    data        : 'deuda',
                    className   : 'text-center'
                },
                {
                    data        : 'retraso',
                    className   : 'text-center'
                },
                {
                    data        : 'total',
                    className   : 'text-center'
                },
                {
                    data        : 'acciones',
                    className   : 'text-center'
                },
            ]
        });
    }

    load_datatable();
</script>
