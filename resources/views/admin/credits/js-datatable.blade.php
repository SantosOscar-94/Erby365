<script>
    function load_datatable()
    {
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
            "ajax"      : "{{ route('admin.get_credits') }}",
            "columns": [
                {
                    data        : 'documento',
                    className   : 'text-center'
                },
                {
                    data        : 'fecha_de_emision',
                    className   : 'text-center'
                },
                {
                    data        : 'fecha_de_vencimiento',
                    className   : 'text-center'
                },
                {
                    data        : 'estado_cpe',
                    className   : 'text-center'
                },
                {
                    data        : null,
                    className   : 'text-left',
                    render      : function(data, type, row) {
                        return `${row.cliente} <br><small><span class="text-dni-ruc">${row.dni_ruc}</span></small>`;
                    }
                },
                {
                    data        : 'moneda',
                    className   : 'text-center'
                },
                {
                    data        : 'xml',
                    className   : 'text-center'
                },
                {
                    data        : 'cdr',
                    className   : 'text-center'
                },
                {
                    data        : 'gravada',
                    className   : 'text-center'
                },
                {
                    data        : 'igv',
                    className   : 'text-center'
                },
                {
                    data        : 'total',
                    className   : 'text-center'
                },
                {
                    data        : 'acciones',
                    className   : 'text-center'
                }
            ]
        });
    }
    load_datatable();
</script>
