<script>
    $('#form-cashes select[name="user"]').select2();
    //$('#form-cashes select[name="document"]').select2();
    $('#form-cashes select[name="warehouse"]').select2();
    
    function load_datatable()
    {
        let filters        = $('#form-cashes').serialize();
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
                'url'       : "{{ route('admin.get_arching_cashes') }}",
                'type'      : "POST",
                'data'      : {
                    '_token'    : "{{ csrf_token() }}",
                    'filters'  : filters,
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
                    data        : 'fecha1',
                    className   : 'text-center'
                },
                {
                    data        : 'fecha2',
                    className   : 'text-center'
                },
                {
                    data        : 'cajero',
                    className   : 'text-center'
                },
                {
                    data        : 'tienda',
                    className   : 'text-center'
                },
                {
                    data        : 'monto_inicial',
                    className   : 'text-center'
                },
                {
                    data        : 'monto_cierre',
                    className   : 'text-center'
                },
                {
                    data        : 'products',
                    className   : 'text-center'
                },
                {
                    data        : 'billings',
                    className   : 'text-center'
                },
                {
                    data        : 'estado',
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