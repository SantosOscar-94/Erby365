<script>
    let downloadUrl = `{{ route('admin.download_excel', '') }}/0`;
    // Actualiza el enlace
    $('#download-excel').attr('href', downloadUrl);

    $('#warehouses_filter').select2({
        placeholder: "[SELECCIONE]",
    });

    $('#warehouses_filter').on('change', function() {
        let selectedOptions = $(this).val(); // Obtener las opciones seleccionadas

        if (selectedOptions === null || selectedOptions.length === 0) {
            // Si no hay ninguna opción seleccionada, seleccionar 'TODOS' por defecto
            $(this).val(['0']).trigger('change.select2');
        } else if (selectedOptions.includes('0')) {
            // Si 'TODOS' está seleccionado
            if (selectedOptions.length > 1) {
                // Si se seleccionan otras opciones además de 'TODOS'
                // Desmarcar 'TODOS' y dejar las demás opciones seleccionadas
                $(this).val(selectedOptions.filter(option => option !== '0')).trigger('change.select2');
            }
        }

        let selectedOptions2 = $(this).val();
        let ids = selectedOptions2.join(','); // Convierte el array a una cadena separada por comas
        // Construye la URL con los IDs
        let downloadUrl = `{{ route('admin.download_excel', '') }}/${ids}`;

        // Actualiza el enlace
        $('#download-excel').attr('href', downloadUrl);

    });

    $('#clear-filters').on('click', function() {
        // Seleccionar solo 'TODOS' y desmarcar todas las demás opciones
        $('#warehouses_filter').val(['0']).trigger('change.select2');

        let selectedOptions2 = $('#warehouses_filter').val();
        let ids = selectedOptions2.join(',');
        let downloadUrl = `{{ route('admin.download_excel', '') }}/${ids}`;
        $('#download-excel').attr('href', downloadUrl);
    });

    function load_datatable() {
        let datatable = $('#table').DataTable({
            serverSide: true,
            "paging": true,
            "searching": true,
            "destroy": true,
            responsive: true,
            ordering: false,
            autoWidth: false,
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "Todos"]
            ],
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
            "ajax": "{{ route('admin.get_products') }}",
            "columns": [{
                    data: 'id',
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                    className: 'text-center'
                },
                {
                    data: 'descripcion'
                },
                {
                    data: 'codigo_unidad',
                    className: 'text-center'
                },
                {
                    data: 'precio_compra',
                    className: 'text-center'
                },
                {
                    data: 'precio_venta',
                    className: 'text-center'
                },
                {
                    data        : 'enable_tag',
                    className   : 'text-center'
                },
                {
                    data: 'acciones',
                    className: 'text-center'
                },
            ]
        });
    }

    load_datatable();
</script>
