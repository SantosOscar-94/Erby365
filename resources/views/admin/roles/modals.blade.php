<div class="modal fade" id="modalAddRole" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form id="form_save" class="modal-content" onsubmit="event.preventDefault()">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="modalAddRoleTitle">Registrar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 mb-3">
                        <label for="name" class="form-label">Descripci&oacute;n</label>
                        <input type="text" id="name" class="form-control text-uppercase" name="name">
                        <div class="invalid-feedback">El campo no debe estar vacío.</div>
                    </div>

                    <div class="col-12 mb-3 d-flex flex-column gap-3">
                        <label class="form-label">Listado de Roles</label>
                        <div>
                            @php
                                // Agrupar los permisos por la primera palabra antes del primer punto
                                $groupedPermissions = [];
                                foreach ($permissions as $permission) {
                                    $groupKey = explode('.', $permission->name)[0];
                                    $groupedPermissions[$groupKey][] = $permission;
                                }
                            @endphp

                            <!-- Contenedor del acordeón -->
                            <div class="accordion" id="permissionsAccordion">
                                @foreach ($groupedPermissions as $group => $groupPermissions)
                                    @php
                                        $group2 = str_replace(' ', '-', $group);
                                    @endphp

                                    <!-- Elemento del acordeón para cada grupo -->
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="heading{{ $group2 }}">
                                            <button class="accordion-button collapsed" type="button" style="color: #7367f0"
                                                data-bs-toggle="collapse" data-bs-target="#collapse{{ $group2 }}"
                                                aria-expanded="false" aria-controls="collapse{{ $group2 }}">
                                                {{ ucfirst($group) }}
                                            </button>
                                        </h2>

                                        <div id="collapse{{ $group2 }}" class="accordion-collapse collapse"
                                            aria-labelledby="heading{{ $group2 }}"
                                            data-bs-parent="#permissionsAccordion">
                                            <div class="accordion-body">
                                                @foreach ($groupPermissions as $permission)
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            value="{{ $permission->id }}" name="permissions[]"
                                                            id="permission{{ $permission->id }}">
                                                        <label class="form-check-label"
                                                            for="permission{{ $permission->id }}">
                                                            {{ $permission->descripcion }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>


                    <div class="col-12 text-end">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button class="btn btn-primary btn-save">
                            <span class="text-save">Guardar</span>
                            <span class="spinner-border spinner-border-sm me-1 d-none text-saving" role="status"
                                aria-hidden="true"></span>
                            <span class="text-saving d-none">Guardando...</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modalEditRole" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form id="form_edit" class="modal-content" onsubmit="event.preventDefault()">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditRoleTitle">Actualizar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 mb-3">
                        <label for="descripcion" class="form-label">Descripci&oacute;n</label>
                        <input type="hidden" name="id">
                        <input type="text" id="name" class="form-control text-uppercase" name="name">
                        <div class="invalid-feedback">El campo no debe estar vacío.</div>
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label">Listado de Permisos</label>
                        <div id="wrapper_permissions"></div>
                    </div>

                    <div class="col-12 text-end">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button class="btn btn-primary btn-store">
                            <span class="text-store">Guardar</span>
                            <span class="spinner-border spinner-border-sm me-1 d-none text-storing" role="status"
                                aria-hidden="true"></span>
                            <span class="text-storing d-none">Guardando...</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
