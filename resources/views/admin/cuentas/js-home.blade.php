<script>
    $('#form_info').on('submit', function(e) {
        e.preventDefault();
        console.log('hola')
        // Crea un objeto FormData
        let formData = new FormData(this);

        $.ajax({
            url: "{{ route('admin.cuentas.save') }}",
            method: 'POST',
            data: formData,
            contentType: false, // Necesario para enviar FormData
            processData: false, // Necesario para enviar FormData
            success: function(response) {
                console.log('Respuesta exitosa:', response);
            },
            error: function(error) {
                console.log('Error:', error);
            }
        });
    });



</script>
