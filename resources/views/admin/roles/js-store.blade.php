<script>
    $('body').on('click', '.btn-create', function() {
        event.preventDefault();
        $('#modalAddRole').modal('show');
    });

    $('body').on('click', '.btn-save', function() {
        event.preventDefault();
        let form = $('#form_save').serialize(),
            name = $('#form_save input[name="name"]');

        if (name.val() == '')
            name.addClass('is-invalid');
        else
            name.removeClass('is-invalid');

        if (name.val().trim() != '') {
            $.ajax({
                url: "{{ route('admin.save_role') }}",
                method: 'POST',
                data: form,
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
                        toast_msg(r.msg, r.type);
                        return;
                    }

                    $('#modalAddRole').modal('hide');
                    $('#form_save').trigger('reset');
                    $('.btn-save').prop('disabled', false);
                    $('.text-save').removeClass('d-none');
                    $('.text-saving').addClass('d-none');
                    toast_msg(r.msg, r.type);
                    reload_table();
                },
                dataType: 'json'
            });
            return;
        }
    });

    $('body').on('click', '.btn-detail', function(event) {
        event.preventDefault();
        let id = $(this).data('id');
        $.ajax({
            url: "{{ route('admin.detail_role') }}",
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

                $('#modalEditRole input[name="id"]').val(r.data.role.id);
                $('#modalEditRole input[name="name"]').val(r.data.role.name);

                // Organizar los permisos en grupos
                let groupedPermissions = {};
                $.each(r.data.permissions, function(index, permission) {
                    let groupKey = permission.name.split('.')[0];
                    if (!groupedPermissions[groupKey]) {
                        groupedPermissions[groupKey] = [];
                    }
                    groupedPermissions[groupKey].push(permission);
                });

                // Generar el HTML para el acordeón
                let html_permissions = '<div class="accordion" id="permissionsAccordion">';
                $.each(groupedPermissions, function(group, permissions) {
                    let groupId = group.replace(/\s+/g,
                    '-'); // Reemplazar espacios con guiones
                    html_permissions += `
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading${groupId}">
                            <button class="accordion-button collapsed" style="color: #7367f0" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapse${groupId}" aria-expanded="false"
                                    aria-controls="collapse${groupId}">
                                ${group.charAt(0).toUpperCase() + group.slice(1)}
                            </button>
                        </h2>
                        <div id="collapse${groupId}" class="accordion-collapse collapse"
                             aria-labelledby="heading${groupId}" data-bs-parent="#permissionsAccordion">
                            <div class="accordion-body">`;

                    // Agregar cada permiso dentro del grupo
                    $.each(permissions, function(i, permission) {
                        let isChecked = r.data.selPermissions.includes(permission
                            .id) ? 'checked' : '';
                        html_permissions += `
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="${permission.id}"
                                   name="permissions[]" id="permissions_${permission.id}" ${isChecked}>
                            <label class="form-check-label" for="permissions_${permission.id}">
                                ${permission.descripcion}
                            </label>
                        </div>`;
                    });

                    html_permissions += `
                            </div>
                        </div>
                    </div>`;
                });
                html_permissions += '</div>';

                // Insertar el acordeón en el modal
                $('#modalEditRole #wrapper_permissions').html(html_permissions);
                $('#modalEditRole').modal('show');
            },
            dataType: 'json'
        });
    });


    $('body').on('click', '.btn-store', function() {
        event.preventDefault();
        let form = $('#form_edit').serialize(),
            name = $('#form_edit input[name="name"]');

        if (name.val() == '')
            name.addClass('is-invalid');
        else
            name.removeClass('is-invalid');

        if (name.val().trim() != '') {
            $.ajax({
                url: "{{ route('admin.store_role') }}",
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

                    $('.btn-store').prop('disabled', false);
                    $('.text-store').removeClass('d-none');
                    $('.text-storing').addClass('d-none');
                    toast_msg(r.msg, r.type);
                    reload_table();
                },
                dataType: 'json'
            });
            return;
        }
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
                    url: "{{ route('admin.delete_role') }}",
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
