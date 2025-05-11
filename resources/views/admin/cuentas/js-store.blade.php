<script>
    $('body').on('click', '.btn-create', function() {
        event.preventDefault();
        $('#modalAddCuentas').modal('show');
    });

    $('body').on('click', '.btn-save', function(event) {
        event.preventDefault(); // Mueve esto al inicio del evento
        let form        = $('#form_save').serialize(), // Serializa el formulario
            nombre_ban      = $('#form_save input[name="nombre_ban"]'),
            moneda = $('#form_save input[name="moneda"]'),
            num_cuenta      = $('#form_save input[name="num_cuenta"]'),
            cci = $('#form_save input[name="cci"]');

        // Validación de campos vacíos
        if (nombre_ban.val() == '')
            nombre_ban.addClass('is-invalid');
        else
            nombre_ban.removeClass('is-invalid');

        if (moneda.val() == '')
            moneda.addClass('is-invalid');
        else
            moneda.removeClass('is-invalid');

        if (num_cuenta.val() == '')
            num_cuenta.addClass('is-invalid');
        else
            num_cuenta.removeClass('is-invalid');

        if (cci.val() == '')
            cci.addClass('is-invalid');
        else
            cci.removeClass('is-invalid');

        // Enviar solo si el correo no está vacío
        if (nombre_ban.val().trim() != ''&& moneda.val().trim() != '' && num_cuenta.val().trim() != '' && cci.val().trim() != '') {

            $.ajax({
                url: "{{ route('admin.save_cuentas') }}", // Asegúrate que la ruta esté bien definida
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
            nombre_ban      = $('#form_edit input[name="nombre_ban"]'),
            moneda = $('#form_edit input[name="moneda"]'),
            num_cuenta      = $('#form_edit input[name="num_cuenta"]'),
            cci = $('#form_edit input[name="cci"]');
        if (nombre_ban.val() == '')
            nombre_ban.addClass('is-invalid');
        else
            nombre_ban.removeClass('is-invalid');

        if (moneda.val() == '')
            moneda.addClass('is-invalid');
        else
            moneda.removeClass('is-invalid');

        if (num_cuenta.val() == '')
            num_cuenta.addClass('is-invalid');
        else
            num_cuenta.removeClass('is-invalid');

        if (cci.val() == '')
            cci.addClass('is-invalid');
        else
            cci.removeClass('is-invalid');

        // Enviar solo si el correo no está vacío
        if (nombre_ban.val().trim() != ''&& moneda.val().trim() != '' && num_cuenta.val().trim() != '' && cci.val().trim() != '')

        {
            $.ajax({
                url: "{{ route('admin.store_cuentas') }}",
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
            url: "{{ route('admin.detail_cuentas') }}",
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
                $('#form_edit input[name="id"]').val(r.cuentas.id);
                $('#form_edit input[name="nombre_ban"]').val(r.cuentas.nombre_ban);
                $('#form_edit input[name="moneda"]').val(r.cuentas.moneda);
                $('#form_edit input[name="num_cuenta"]').val(r.cuentas.num_cuenta);
                $('#form_edit input[name="cci"]').val(r.cuentas.cci);
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
                    url: "{{ route('admin.delete_cuentas') }}",
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
