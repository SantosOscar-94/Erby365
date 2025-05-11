<script>
    function load_navs() {
        $('.nav-link').each(function() {
            var currElem = $(this);
            if (parseInt(currElem.data('id')) == 1)
                currElem.addClass('active');
            else
                currElem.removeClass('active');
        });

        $('.card-body .tab-pane').each(function() {
            var currElem = $(this);
            if (parseInt(currElem.data('id')) == 1) {
                currElem.addClass('active');
                currElem.addClass('show');
            } else {
                currElem.removeClass('active');
                currElem.removeClass('show');
            }
        });
    }

    function load_warehouses_selected() {
        let warehouses = @json($warehouses); // Cargar todos los warehouses
        let selectedOptions = $('#warehouses_filter')
            .val(); // Obtener las opciones seleccionadas del select (array de IDs)

        let url = window.location.pathname
        let plantilla = '';
        let selectedWarehouses

        if (url === '/products') {
            if (selectedOptions.includes('0')) {
                selectedWarehouses = warehouses
            } else {
                // Filtrar los warehouses seleccionados
                selectedWarehouses = warehouses.filter(warehouse => selectedOptions.includes(warehouse.id.toString()));
            }
        } else {
            selectedWarehouses = warehouses
        }


        // Generar la plantilla solo para los warehouses seleccionados
        selectedWarehouses.forEach(warehouse => {
            plantilla += `
            <tr>
                <td><span>${warehouse.descripcion}</span></td>
                <td>
                    <div class="">
                        <input type="hidden" name="id[]" value="${warehouse.id}">
                        <input type="text" autocomplete="off" class="form-control" value="0" name="cantidad[]">
                    </div>
                </td>
            </tr>
        `;
        });

        $('#tbody-warehouses').html(plantilla);
    }

    $('body').on('click', '.btn-create-product', function() {
        event.preventDefault();
        load_navs();
        $('#form_save_product select[name="idunidad"]').val('46').trigger('change');
        $('#form_save_product select[name="operacion"] option[value="1"]').prop('selected', true);
        $('#form_save_product select[name="idunidad"]').select2({
            placeholder: "[SELECCIONE]",
            dropdownParent: $('#modalAddProduct')
        });
        $('#modalAddProduct').modal('show');
        load_warehouses_selected();
    });

    $('body').on('click', '.btn-save-product', function() {
        event.preventDefault();
        let form = $('#form_save_product').serialize(),
            descripcion = $('#form_save_product input[name="descripcion"]'),
            precio_compra = $('#form_save_product input[name="precio_compra"]'),
            precio_venta = $('#form_save_product input[name="precio_venta"]'),
            idunidad = $('#form_save_product select[name="idunidad"]');

        if (descripcion.val() == '')
            descripcion.addClass('is-invalid');
        else
            descripcion.removeClass('is-invalid');

        if (precio_compra.val() == '')
            precio_compra.addClass('is-invalid');
        else
            precio_compra.removeClass('is-invalid');

        if (precio_venta.val() == '')
            precio_venta.addClass('is-invalid');
        else
            precio_venta.removeClass('is-invalid');

        if (idunidad.val() == '')
            idunidad.addClass('is-invalid');
        else
            idunidad.removeClass('is-invalid');

        if (descripcion.val().trim() != '' && precio_compra.val().trim() != '' && precio_venta.val().trim() !=
            '' && idunidad.val().trim() != '') {
            $.ajax({
                url: "{{ route('admin.save_product') }}",
                method: 'POST',
                data: form,
                beforeSend: function() {
                    $('#form_save_product .btn-save-product').prop('disabled', true);
                    $('#form_save_product .text-save-product').addClass('d-none');
                    $('#form_save_product .text-saving-product').removeClass('d-none');
                },
                success: function(r) {
                    if (!r.status) {
                        $('#form_save_product .btn-save-product').prop('disabled', false);
                        $('#form_save_product .text-save-product').removeClass('d-none');
                        $('#form_save_product .text-saving-product').addClass('d-none');
                        toast_msg(r.msg, r.type);
                        return;
                    }

                    load_alerts();
                    $('#form_save_product').trigger('reset');
                    $('#form_save_product .btn-save-product').prop('disabled', false);
                    $('#form_save_product .text-save-product').removeClass('d-none');
                    $('#form_save_product .text-saving-product').addClass('d-none');
                    $('#form_save_product input[name="stock"]').prop('disabled', true);
                    $('#form_save_product input[name="stock"]').attr('placeholder', '');
                    $('#form_save_product input[name="stock"]').val('');
                    $('#modalAddProduct').modal('hide');
                    success_save_product(r.msg, r.type);
                },
                dataType: 'json'
            });
            return;
        }
    });
</script>
