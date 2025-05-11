<script>
    $('body').on('click', '.btn-create', function() {
        event.preventDefault();
        $('#modalAddAjustes').modal('show');
    });

    $('body').on('click', '.btn-save', function(event) {
        event.preventDefault(); // Mueve esto al inicio del evento
        let form        = $('#form_save1').serialize(), // Serializa todo el formulario
            correo      = $('#form_save1 input[name="correo"]'),
            responsable = $('#form_save1 input[name="responsable"]');

        // Validación de campos vacíos
        if (correo.val() == '')
            correo.addClass('is-invalid');
        else
            correo.removeClass('is-invalid');

        if (responsable.val() == '')
            responsable.addClass('is-invalid');
        else
            responsable.removeClass('is-invalid');

        // Enviar solo si el correo no está vacío
        if (correo.val().trim() != ''&& responsable.val().trim() != '') {

            $.ajax({
                url: "{{ route('admin.save_correos') }}", // Asegúrate que la ruta esté bien definida
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
                    $('#modalAddAjustes').modal('hide');
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
            correo = $('#form_edit input[name="correo"]'),
            responsable = $('#form_edit input[name="responsable"]');

        if (correo.val().trim() == '')
            correo.addClass('is-invalid');
        else
            correo.removeClass('is-invalid');

        if (responsable.val().trim() == '')
            responsable.addClass('is-invalid');
        else
            responsable.removeClass('is-invalid');


        if (correo.val().trim() != '' && responsable.val().trim() != '')

        {
            $.ajax({
                url: "{{ route('admin.store_correos') }}",
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
            url: "{{ route('admin.detail_correos') }}",
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
                $('#form_edit input[name="id"]').val(r.ajustes.id);
                $('#form_edit input[name="correo"]').val(r.ajustes.correo);
                $('#form_edit input[name="responsable"]').val(r.ajustes.responsable);
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
                    url: "{{ route('admin.delete_correos') }}",
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


    document.getElementById('imageInput').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();

            reader.onload = function(e) {
                const img = document.getElementById('imagePreview');
                img.src = e.target.result;
                img.style.display = 'block'; // Muestra la imagen
            }

            reader.readAsDataURL(file);
        }
    });
</script>