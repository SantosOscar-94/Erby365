<script>
    $('body').on('click', '.btn-create-vehicle', function() {
        event.preventDefault();
        $('#modalAddVehicle').modal('show');
    });

    $('body').on('click', '#form_save_vehicle .btn-save', function(event) {
        event.preventDefault(); // Mueve esto al inicio del evento
        let form = $('#form_save_vehicle').serialize(), // Serializa todo el formulario
            num_placa = $('#form_save_vehicle input[name="num_placa"]'),
            tuc = $('#form_save_vehicle input[name="tuc"]'),
            autori_placa_principal = $('#form_save_vehicle input[name="autori_placa_principal"]'),
            placa_secundario = $('#form_save_vehicle input[name="placa_secundario"]'),
            tuc_placa_secundario = $('#form_save_vehicle input[name="tuc_placa_secundario"]'),
            autori_placa_secundario = $('#form_save_vehicle input[name="autori_placa_secundario"]'),
            modelo = $('#form_save_vehicle input[name="modelo"]'),
            marca = $('#form_save_vehicle input[name="marca"]');

        // Validación de campos vacíos
        if (num_placa.val() == '')
            num_placa.addClass('is-invalid');
        else
            num_placa.removeClass('is-invalid');

        if (tuc.val() == '')
            tuc.addClass('is-invalid');
        else
            tuc.removeClass('is-invalid');

        if (autori_placa_principal.val() == '')
            autori_placa_principal.addClass('is-invalid');
        else
            autori_placa_principal.removeClass('is-invalid');

        if (placa_secundario.val() == '')
            placa_secundario.addClass('is-invalid');
        else
            placa_secundario.removeClass('is-invalid');

        if (tuc_placa_secundario.val() == '')
            tuc_placa_secundario.addClass('is-invalid');
        else
            tuc_placa_secundario.removeClass('is-invalid');

        if (autori_placa_secundario.val() == '')
            autori_placa_secundario.addClass('is-invalid');
        else
            autori_placa_secundario.removeClass('is-invalid');

        if (modelo.val() == '')
            modelo.addClass('is-invalid');
        else
            modelo.removeClass('is-invalid');

        if (marca.val() == '')
            marca.addClass('is-invalid');
        else
            marca.removeClass('is-invalid');

        // Enviar solo si el correo no está vacío
        if (num_placa.val().trim() != '' && tuc_placa_secundario.val().trim() != '' && tuc.val().trim() != '' && placa_secundario.val().trim() != '' &&
            autori_placa_secundario.val().trim() != '' && modelo.val().trim() != '' && marca.val().trim() != '') {

            $.ajax({
                url: "{{ route('admin.save_vehiculos') }}", // Asegúrate que la ruta esté bien definida
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
                    $('#modalAddVehicle').modal('hide');
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
        num_placa = $('#form_edit input[name="num_placa"]'),
            tuc = $('#form_edit input[name="tuc"]'),
            autori_placa_principal = $('#form_edit input[name="autori_placa_principal"]'),
            placa_secundario = $('#form_edit input[name="placa_secundario"]'),
            tuc_placa_secundario = $('#form_edit input[name="tuc_placa_secundario"]'),
            autori_placa_secundario = $('#form_edit input[name="autori_placa_secundario"]'),
            modelo = $('#form_edit input[name="modelo"]'),
            marca = $('#form_edit input[name="marca"]');

        // Validación de campos vacíos
        if (num_placa.val() == '')
            num_placa.addClass('is-invalid');
        else
            num_placa.removeClass('is-invalid');

        if (tuc.val() == '')
            tuc.addClass('is-invalid');
        else
            tuc.removeClass('is-invalid');

        if (autori_placa_principal.val() == '')
            autori_placa_principal.addClass('is-invalid');
        else
            autori_placa_principal.removeClass('is-invalid');

        if (placa_secundario.val() == '')
            placa_secundario.addClass('is-invalid');
        else
            placa_secundario.removeClass('is-invalid');

        if (tuc_placa_secundario.val() == '')
            tuc_placa_secundario.addClass('is-invalid');
        else
            tuc_placa_secundario.removeClass('is-invalid');

        if (autori_placa_secundario.val() == '')
            autori_placa_secundario.addClass('is-invalid');
        else
            autori_placa_secundario.removeClass('is-invalid');

        if (modelo.val() == '')
            modelo.addClass('is-invalid');
        else
            modelo.removeClass('is-invalid');

        if (marca.val() == '')
            marca.addClass('is-invalid');
        else
            marca.removeClass('is-invalid');

        // Enviar solo si el correo no está vacío
        if (num_placa.val().trim() != '' && tuc_placa_secundario.val().trim() != '' && tuc.val().trim() != '' && placa_secundario.val().trim() != '' &&
            autori_placa_secundario.val().trim() != '' && modelo.val().trim() != '' && marca.val().trim() != '')

        {
            $.ajax({
                url: "{{ route('admin.store_vehiculos') }}",
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

                    $('#modalEditAjustes').modal('hide');
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
            url: "{{ route('admin.detail_vehiculos') }}",
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
                $('#form_edit input[name="id"]').val(r.vehiculos.id);
                $('#form_edit input[name="num_placa"]').val(r.vehiculos.num_placa);
                $('#form_edit input[name="tuc"]').val(r.vehiculos.tuc);
                $('#form_edit input[name="autori_placa_principal"]').val(r.vehiculos.autori_placa_principal);
                $('#form_edit input[name="placa_secundario"]').val(r.vehiculos.placa_secundario);
                $('#form_edit input[name="tuc_placa_secundario"]').val(r.vehiculos.tuc_placa_secundario);
                $('#form_edit input[name="autori_placa_secundario"]').val(r.vehiculos.autori_placa_secundario);
                $('#form_edit input[name="modelo"]').val(r.vehiculos.modelo);
                $('#form_edit input[name="marca"]').val(r.vehiculos.marca );
                $('#modalEditAjustes').modal('show');
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
                    url: "{{ route('admin.delete_vehiculos') }}",
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
