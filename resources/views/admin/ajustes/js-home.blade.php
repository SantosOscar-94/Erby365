<script>
    $('#form_info').on('submit', function(e) {
        e.preventDefault();
        console.log('hola')
        // Crea un objeto FormData
        let formData = new FormData(this);

        $.ajax({
            url: "{{ route('admin.ajustes.save') }}",
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


    //Imagen de portada 

  /*  function previewImage(event) {
        const file = event.target.files[0];
        const preview = document.getElementById('preview');
        const imagePreviewContainer = document.getElementById('image-preview');

        // Validar si se ha seleccionado un archivo
        if (file) {
            const img = new Image();
            img.src = URL.createObjectURL(file);

            img.onload = function() {
                const width = img.width;
                const height = img.height;

                // Verificar el tamaño de la imagen
                if (width === 318 && height === 159) {
                    // Si la imagen tiene el tamaño correcto, mostrarla
                    preview.src = img.src;
                    preview.style.display = 'block';
                    imagePreviewContainer.style.display = 'block';
                } else {
                    // Si la imagen no tiene el tamaño correcto, ocultar y mostrar un mensaje
                    preview.style.display = 'none';
                    alert('La imagen debe tener un tamaño de 318 x 159 píxeles.'); // Mensaje de error
                }
            };

            img.onerror = function() {
                // Manejo de errores si no se puede cargar la imagen
                alert('El archivo seleccionado no es una imagen válida.');
            };
        } else {
            // Si no se selecciona ningún archivo, ocultar la previsualización
            preview.style.display = 'none';
            imagePreviewContainer.style.display = 'none';
        }
    }*/
</script>
