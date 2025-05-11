<script>
    $('body').on('click', '.btn-create', function() {
        event.preventDefault();
        $('#modalAddCuentas').modal('show');
    });

    $('body').on('click', '.btn-save', function(event) {
        event.preventDefault(); // Mueve esto al inicio del evento
        let form = $('#form_save').serialize(), // Serializa el formulario
            tipo_detra = $('#form_save input[name="tipo_detra"]'),
            codigo_detra = $('#form_save input[name="codigo_detra"]'),
            descripcion_detra = $('#form_save input[name="descripcion_detra"]'),
            porcentaje_detra = $('#form_save input[name="porcentaje_detra"]'),
            estado_detra = $('#form_save input[name="estado_detra"]');


        let bandera = true;
        // Validación de campos vacíos
        if (tipo_detra.val() == '') {
            tipo_detra.removeClass('is-invalid');
            bandera = false;
        } else
            tipo_detra.removeClass('is-invalid');

        if (codigo_detra.val() == '') {
            codigo_detra.removeClass('is-invalid');
            bandera = false;
        } else
            codigo_detra.removeClass('is-invalid');

        if (descripcion_detra.val() == '') {
            descripcion_detra.removeClass('is-invalid');
            bandera = false;
        } else
            descripcion_detra.removeClass('is-invalid');

        if (porcentaje_detra.val() == '') {
            porcentaje_detra.removeClass('is-invalid');
            bandera = false;
        } else
            porcentaje_detra.removeClass('is-invalid');

        // Enviar solo si el correo no está vacío
        if (bandera === true) {
            $.ajax({
                url: "{{ route('admin.save_detra') }}", // Asegúrate que la ruta esté bien definida
                method: 'POST',
                data: form, // Enviar el formulario serializado
                beforeSend: function() {
                    $('.btn-save').prop('disabled', true);
                    $('.text-saving').removeClass('d-none');
                    $('.text-save').addClass('d-none');
                },
                success: function(r) {
                    if (!r.status) {
                        $('.btn-save').prop('disabled', false);
                        $('.text-saving').addClass('d-none');
                        $('.text-save').removeClass('d-none');
                        toast_msg(r.msg, r.type); // Mensaje de error
                        return;
                    }

                    // Resetear el formulario y ocultar el modal
                    $('#modalAddCuentas').modal('hide');
                    $('#form_save').trigger('reset');
                    $('.btn-save').prop('disabled', false);
                    $('.text-save').removeClass('d-none');
                    $('.text-saving').addClass('d-none');
                    toast_msg(r.msg, r.type); // Mensaje de éxito
                    reload_table(); // Recargar la tabla
                },
                dataType: 'json'
            });
        }
    });

    $('body').on('click', '.btn-store', function() {
        event.preventDefault();
        let form = $('#form_edit').serialize(),
            tipo_detra = $('#form_edit #tipo_detra'),
            codigo_detra = $('#form_edit input[name="codigo_detra"]'),
            descripcion_detra = $('#form_edit input[name="descripcion_detra"]'),
            porcentaje_detra = $('#form_edit input[name="porcentaje_detra"]');
        if (tipo_detra.val() == '')
            tipo_detra.addClass('is-invalid');
        else
            tipo_detra.removeClass('is-invalid');

        if (codigo_detra.val() == '')
            codigo_detra.addClass('is-invalid');
        else
            codigo_detra.removeClass('is-invalid');

        if (descripcion_detra.val() == '')
            descripcion_detra.addClass('is-invalid');
        else
            descripcion_detra.removeClass('is-invalid');

        if (porcentaje_detra.val() == '')
            porcentaje_detra.addClass('is-invalid');
        else
            porcentaje_detra.removeClass('is-invalid');

        // Enviar solo si el correo no está vacío
        if (tipo_detra.val().trim() != '' && codigo_detra.val().trim() != '' && descripcion_detra.val()
            .trim() != '' && porcentaje_detra.val().trim() != '')

        {
            $.ajax({
                url: "{{ route('admin.store_detra') }}",
                method: 'POST',
                data: form,
                beforeSend: function() {
                    $('.btn-store').prop('disabled', true);
                    $('.text-store').addClass('d-none');
                    $('.text-storing').removeClass('d-none');
                },
                success: function(r) {
                    if (!r.status) {
                        $('.btn-store').prop('disabled', false);
                        $('.text-store').removeClass('d-none');
                        $('.text-storing').addClass('d-none');
                        toast_msg(r.msg, r.type);
                        return;
                    }

                    $('#modalEditCuentas').modal('hide');
                    $('.btn-store').prop('disabled', false);
                    $('.text-store').removeClass('d-none');
                    $('.text-storing').addClass('d-none');
                    toast_msg(r.msg, r.type);
                    reload_table();
                },
                dataType: 'json'
            });

        }

    });

    $('body').on('click', '.btn-detail', function() {
        event.preventDefault();
        let id = $(this).data('id');
        $.ajax({
            url: "{{ route('admin.detail_detra') }}",
            method: 'POST',
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
                $('#form_edit input[name="id"]').val(r.listado_detra.id);
                $('#form_edit #tipo_detra').val(r.listado_detra.tipo_operacion).trigger('change');
                $('#form_edit input[name="codigo_detra"]').val(r.listado_detra.codigo);
                $('#form_edit input[name="descripcion_detra"]').val(r.listado_detra.descripcion);
                $('#form_edit input[name="porcentaje_detra"]').val(r.listado_detra.porcentaje);
                $('#modalEditCuentas').modal('show');

            },
            dataType: 'json'
        });
        return;
    });

    $('body').on('click', '.btn-confirm', function() {
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
                    url: "{{ route('admin.delete_detra') }}",
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

                        toast_msg(r.msg, r.type);
                        reload_table();
                    },
                    dataType: 'json'
                });
            }
        });
    });
</script>
