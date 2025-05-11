    <script>
        $('#form_reclamos').on('submit', function(e) {
            e.preventDefault();

            let formData = new FormData(this);

            $.ajax({
                url: "{{ route('reclamos_quejas.save') }}",
                method: 'POST',
                data: formData,
                contentType: false, // Necesario para enviar FormData
                processData: false, // Necesario para enviar FormData
                success: function(response) {
                    alert('Enviado exitosamente')
                    window.location.reload();
                },
                error: function(error) {
                    // Mostrar el primer mensaje de error
                    const firstError = error.responseJSON?.errors ?
                        Object.values(error.responseJSON.errors)[0][0] :
                        'Ocurrió un error inesperado';
                    alert(firstError);
                }
            });
        });

        // Obtenemos los datos de las tiendas desde la variable de Laravel
        const tiendas = @json($warehouses);

        $('[name="canal_compra"]').on('change', function() {
            const canalCompra = $(this).val();

            if (canalCompra === 'Tienda Virtual') {
                // Si es "Tienda Virtual",
                $('#field-tienda').hide();
                $('[name="tienda"]').val('');
                $('[name="direccion_tienda"]').val('');
                $('#field-direccion_tienda').hide();
            } else {
                // Mostramos el campo de tienda si es "Tienda Física"
                $('#field-tienda').show();
                $('#field-direccion_tienda').show();
            }
        });

        $('[name="tienda"]').on('change', function() {
            const selected = $(this).val();

            // Buscar la tienda seleccionada
            const selectedWarehouse = tiendas.find(warehouse => warehouse.descripcion === selected);

            if (selectedWarehouse) {
                // Autocompletar la dirección en el input
                $('[name="direccion_tienda"]').val(selectedWarehouse.direccion);
            } else {
                $('[name="direccion_tienda"]').val('');
            }
        });
    </script>
