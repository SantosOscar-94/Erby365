<script>
    $('body').on('click', '.btn-create-startPoint', function() {
        event.preventDefault();
        $('#modalAddStartPoint').modal('show');
    });

    $('body').on('click', '.btn-save', function(event) {
        event.preventDefault(); // Mueve esto al inicio del evento
        let form        = $('#form_save_startPoint').serialize(), // Serializa todo el formulario
        direccion      = $('#form_save_startPoint input[name="direccion"]'),
        ubigeo = $('#form_save_startPoint input[name="ubigeo"]');

        // Validación de campos vacíos
        if (direccion.val() == '')
        direccion.addClass('is-invalid');
        else
        direccion.removeClass('is-invalid');

        if (ubigeo.val() == '')
        ubigeo.addClass('is-invalid');
        else
        ubigeo.removeClass('is-invalid');

        // Enviar solo si el direccion no está vacío
        if (direccion.val().trim() != ''&& ubigeo.val().trim() != '') {

            $.ajax({
                url: "{{ route('admin.save_dire_partida') }}", // Asegúrate que la ruta esté bien definida
                method: 'POST',
                data: form, // Enviar el formulario serializado
                beforeSend: function() {
                    $('#form_save_startPoint .btn-save').prop('disabled', true);
                    $('#form_save_startPoint .text-saving').removeClass('d-none');
                    $('#form_save_startPoint .text-save').addClass('d-none');
                },
                success: function(r) {
                    if (!r.status) {
                        $('#form_save_startPoint .btn-save').prop('disabled', false);
                        $('#form_save_startPoint .text-saving').addClass('d-none');
                        $('#form_save_startPoint .text-save').removeClass('d-none');
                        toast_msg(r.msg, r.type); // Mensaje de error
                        return;
                    }

                    // Resetear el formulario y ocultar el modal
                    $('#modalAddStartPoint').modal('hide');
                    $('#form_save_startPoint ').trigger('reset');
                    $('#form_save_startPoint .btn-save').prop('disabled', false);
                    $('#form_save_startPoint .text-save').removeClass('d-none');
                    $('#form_save_startPoint .text-saving').addClass('d-none');
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
        direccion = $('#form_edit input[name="direccion"]'),
        ubigeo = $('#form_edit input[name="ubigeo"]');

        if (direccion.val().trim() == '')
        direccion.addClass('is-invalid');
        else
        direccion.removeClass('is-invalid');

        if (ubigeo.val().trim() == '')
        ubigeo.addClass('is-invalid');
        else
        ubigeo.removeClass('is-invalid');


        if (direccion.val().trim() != '' && ubigeo.val().trim() != '')

        {
            $.ajax({
                url: "{{ route('admin.store_dire_partida') }}",
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
            url: "{{ route('admin.detail_dire_partida') }}",
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
                $('#form_edit input[name="id"]').val(r.direccion_partida.id);
                $('#form_edit input[name="direccion"]').val(r.direccion_partida.direccion);
                $('#form_edit input[name="ubigeo"]').val(r.direccion_partida.ubigeo);
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
                    url: "{{ route('admin.delete_dire_partida') }}",
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
