<script>
    $('body').on('click', '.btn-print', function() {
        event.preventDefault();
        let id = $(this).data('id');
        $.ajax({
            url: "{{ route('admin.print_quote') }}",
            method: "POST",
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
                let pdf                 =   `{{ asset('files/quotes/${r.pdf}') }}`;
                var iframe              = document.createElement('iframe');
                iframe.style.display    = "none";
                iframe.src              = pdf;
                document.body.appendChild(iframe);
                iframe.contentWindow.focus();
                iframe.contentWindow.print();
            },
            dataType: "json"
        });
    });

    $('body').on('click', '.btn-download', function()
    {
        event.preventDefault();
        let id              = $(this).data('id');
        base_url            = "{{ url('/') }}",
        url_print           = `${base_url}/download-quote/${id}`;
        window.open(url_print);
    });

    $('body').on('click', '.btn-open-whatsapp', function()
    {
        let id = $(this).data('id');
        $('#modalSendWpp .btn-whatsapp').attr('id', id);
        $('#modalSendWpp .btn-whatsapp').attr('type_document', 'quote');
        $('#modalSendWpp').modal('show');
    });

    $('body').on('click', '#modalSendWpp .btn-whatsapp', function()
    {
        event.preventDefault();
        let id              = $(this).attr('id'),
            type_document   = $(this).attr('type_document'),
            input__phone    = $('#modalSendWpp input[name="input__phone"]').val(),
            html            = '';

        $.ajax({
            url     : "{{ route('admin.send_voucher') }}",
            method  : "POST",
            data    : {
                '_token'        : "{{ csrf_token() }}",
                id              : id,
                input__phone    : input__phone,
                type_document   : type_document
            },
            beforeSend   : function(){
                $('#modalSendWpp .text-send').addClass('d-none');
                $('#modalSendWpp .text-sending').removeClass('d-none');
            },
            success : function(r)
            {
                if(!r.status)
                {
                    $('#modalSendWpp .text-send').removeClass('d-none');
                    $('#modalSendWpp .text-sending').addClass('d-none');
                    toast_msg(r.msg, r.type);
                    return;
                }

                $('#modalSendWpp .text-send').removeClass('d-none');
                $('#modalSendWpp .text-sending').addClass('d-none');
                $('#modalSendWpp input[name="input__phone"]').val("");
                toast_msg(r.msg, r.type);
            },
            dataType: "json"
        });
    });

    $('body').on('click', '.btn-confirm', function() {
        event.preventDefault();
        let id = $(this).data('id'),
        idtipo_comprobante  = $(this).data('idtipo_comprobante');
        Swal.fire({
            title: 'Generar Comprobante',
            text: "¿Desea generar el comprobante de esta cotización?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Si, generar',
            cancelButtonText: 'Cancelar',
            customClass: {
                confirmButton: 'btn btn-primary',
                cancelButton: 'btn btn-outline-danger ml-1'
            },
            buttonsStyling: false
        }).then(function (result) {
            if (result.value) 
            {
                block_content('#layout-content');
                $.ajax({
                    url         : "{{ route('admin.gen_quote_voucher') }}",
                    method      : 'POST',
                    data        : {
                        '_token': "{{ csrf_token() }}",
                        id      : id,
                        idtipo_comprobante: idtipo_comprobante
                    },
                    success     : function(r){
                        if(!r.status)
                        {
                            close_block('#layout-content');
                            toast_msg(r.msg, r.type);
                            return;
                        }

                        reload_table();
                        send_data_sunat(r.id, r.pdf);
                    },
                    dataType    : 'json'
                });
            }
        });
    });

    function send_data_sunat(id, ticket)
    {
        $.ajax({
            url             : "{{ route('admin.send_bf') }}",
            method          : "POST",
            data            : {
                '_token'    : "{{ csrf_token() }}",
                id          : id
            },
            success         : function(r){
                if(!r.status){}

                let ip          = r.empresa.url_api,
                    api         = "Api/index.php",
                    datosJSON   = JSON.stringify(r.data);
                    datosJSON   = unescape(encodeURIComponent(datosJSON)),
                    idfactura   = parseInt(r.idfactura);

                    $.ajax({    
                        url         : ip + api,
                        method      : 'POST',
                        data        : {datosJSON},
                    }).done(function(res){
                        close_block('#layout-content');
                        if (res.trim() == "No se registró") 
                        {
                            toast_msg('El número de comprobante electrónico esta duplicado, revise la base de datos', 'error');
                            return;
                        }

                        let respuesta_sunat = JSON.parse(res),
                            estado_conexion = JSON.parse(respuesta_sunat).status;
                         
                        let pdf = `{{ asset('files/billings/ticket/${ticket}') }}`;
                        var iframe = document.createElement('iframe');
                        iframe.style.display = "none";
                        iframe.src = pdf;
                        document.body.appendChild(iframe);
                        iframe.contentWindow.focus();
                        iframe.contentWindow.print();
                        load_alerts();
                        if(estado_conexion != false)
                        {
                            update_cdr(idfactura);
                        }
                    }).fail(function(jqxhr, textStatus, error){
                        close_block('#layout-content');
                        let pdf = `{{ asset('files/billings/ticket/${ticket}') }}`;
                        var iframe = document.createElement('iframe');
                        iframe.style.display = "none";
                        iframe.src = pdf;
                        document.body.appendChild(iframe);
                        iframe.contentWindow.focus();
                        iframe.contentWindow.print();
                        load_alerts();
                    });
            },
            dataType        : "json"
        });
    }

    function update_cdr(idfactura)
    {
        let resp = '';
        $.ajax({
            url     : "{{ route('admin.update_cdr_bf') }}",
            method  : 'POST',
            data    : {
                '_token'   : "{{ csrf_token() }}",
                idfactura  : idfactura
            },
            success : function(r){},
            dataType : 'json'
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        window.usd = false;
        fecha = new Date();
        url = "https://apiperu.dev/api/tipo_de_cambio";
        data = {
            fecha: `${fecha.getFullYear()}-${(fecha.getMonth() + 1).toString().padStart(2, '0')}-${fecha.getDate().toString().padStart(2, '0')}`,
            moneda: "USD",
        };

        authorizationToken = "a3e20be04068cd29796811fcdc6a4e79c32124c84fbe072e54afcb4b1d28ea86"; // Replace with your actual token

        fetch(url, {
            method: "POST",
            headers: {
                "Accept": "application/json",
                "Content-Type": "application/json",
                Authorization: `Bearer ${authorizationToken}`,
            },
            body: JSON.stringify(data),
        })
        .then((response) => response.json())
        .then((data) => {
            console.log(data);

            console.log("Cambio: " + data.data.purchase);
            window.cambio = data.data.purchase;
            inputCambio = document.getElementById('tipo_cambio');
            inputCambio.value = "S/ " + data.data.purchase;
        })
        .catch((error) => {
            window.cambio = 3600;
            inputCambio = document.getElementById('tipo_cambio');
            inputCambio.value = "S/ 3600";
            console.error(error);
        });

        // $.ajax({
        //     url: "https://v6.exchangerate-api.com/v6/f9cb370829872ab4c1e4c4aa/latest/USD", 
        //     method: "GET", 
        //     data: {

        //     },
        //     success: function(response) {
        //         //console.log(response);
        //         console.log("Cambio: " + response.conversion_rates.PEN);
        //         window.cambio = response.conversion_rates.PEN;
        //         inputCambio = document.getElementById('tipo_cambio');
        //         inputCambio.value = window.cambio;
        //     },
        //     error: function(error) {
        //         console.error(error);
        //     }
        // });

        // select = document.getElementById('s_tipo_precio');
        // select.addEventListener('change', function() {
        //     selectedOption = this.options[select.selectedIndex];
        //     value = selectedOption.value;
            
        //     if (value == 0) {
        //         elementosConItem = document.querySelectorAll('[id^="itemPrice"]');

        //         elementosConItem.forEach((elemento) => {
        //             precio = parseFloat(elemento.getAttribute('data-precio'));
                    
        //             elemento.innerHTML = "S/" + decimales(precio);
        //         });
        //     }else if (value == 1) {
        //         elementosConItem = document.querySelectorAll('[id^="itemPrice"]');

        //         elementosConItem.forEach((elemento) => {
        //             precio = parseFloat(elemento.getAttribute('data-precio2'));

        //             elemento.innerHTML = "S/" + decimales(precio);
        //         });
        //     } else {
        //         elementosConItem = document.querySelectorAll('[id^="itemPrice"]');

        //         elementosConItem.forEach((elemento) => {
        //             precio = parseFloat(elemento.getAttribute('data-precio3'));

        //             elemento.innerHTML = "S/" + decimales(precio);
        //         });
        //     }
        // })

        $('body').on('click', '#btnBuscarFacturas', function() {
            event.preventDefault();
            //alert("En proceso...")
            //return;

            identificacion = $('#identificacion').val()
            fechaDesde = $('#fechaDesde').val()
            fechaHasta = $('#fechaHasta').val()
            tipoComprobante = $('#tipoComprobante').val()
            product = $('#product').val()
            //console.log("{{ csrf_token() }}");
            
            $.ajax({
                url: "{{ route('admin.userBillings') }}",
                method: 'POST',
                data: {
                    '_token': "{{ csrf_token() }}",
                    identificacion: identificacion,
                    fechaDesde: fechaDesde,
                    fechaHasta: fechaHasta,
                    tipoComprobante: tipoComprobante,
                    product: product
                },
                success: function(r) {
                    //bills = JSON.parse(r.bills[0])
                    //console.log(r.bills[0]);
                    $('#bills').html(r.html_bills);
                },
                dataType: 'json'
            }).fail(function(error) {
                // Handle the error here
                console.error('Error:', error.responseText);
                // Perform error-specific actions
            });
            return;
        });
        
        $('body').on('click', '#btn_modalventas', function(e) {
            e.preventDefault();
            today = new Date().toISOString().slice(0, 10);
            
            $('#fechaDesde').val(today);

            today = new Date();
            today.setDate(today.getDate() + 1);
            tomorrow = today.toISOString().slice(0, 10);
            
            $('#tipoComprobante').val('todos');
            $('#product').val('0');
            $('#fechaHasta').val(tomorrow);

            //listarComprobante();
        });

       

        $('body').on('click', '#btn_tipo_precio', function(e) {
            e.preventDefault();

            Swal.fire({
                title:  'Tipos de precios',
                text:   'Elige el tipo de precio a aplicar',
                icon:   'info',
                showCancelButton: true,
                confirmButtonText:  'Precio publico',
                denyButtonText:     'Precio al por mayor',
                cancelButtonText:   'Precio distribuidor',
                customClass: {
                    confirmButton:  'btn btn-primary',
                    denyButton:     'btn btn-primary',
                    cancelButton:   'btn btn-primary',
                },
                buttonsStyling: false,
                backdrop: true,
                allowOutsideClick: false
            }).then((result) => {
                console.log(result);
                if (result.isConfirmed) {
                    window.tipoCobro = 1;
                    elementosConItem = document.querySelectorAll('[id^="itemPrice"]');

                    elementosConItem.forEach((elemento) => {
                        window.precio = parseFloat(elemento.getAttribute('data-precio'));
                        
                        elemento.innerHTML = "S/ " + decimales(window.precio);
                    });

                    $("#kindPrice").html("Precio público");
                }else if (result.isDenied) {
                    window.tipoCobro = 2;
                    elementosConItem = document.querySelectorAll('[id^="itemPrice"]');

                    elementosConItem.forEach((elemento) => {
                        window.precio = parseFloat(elemento.getAttribute('data-precio2'));

                        elemento.innerHTML = "S/ " + decimales(window.precio);
                    });

                    $("#kindPrice").html("Precio al por mayor");
                }else if (result.isDismissed) {
                    window.tipoCobro = 3;
                    elementosConItem = document.querySelectorAll('[id^="itemPrice"]');

                    elementosConItem.forEach((elemento) => {
                        window.precio = parseFloat(elemento.getAttribute('data-precio3'));

                        elemento.innerHTML = "S/ " + decimales(window.precio);
                    });

                    $("#kindPrice").html("Precio distribuidor");
                }
            });
        });

        $('body').on('click', '#btn_cambioMoneda', function(e) {
            if (window.usd) {
                window.usd = false
                
                if (window.tipoCobro == 1) {
                    elementosConItem = document.querySelectorAll('[id^="itemPrice"]');

                    elementosConItem.forEach((elemento) => {
                        window.precio = parseFloat(elemento.getAttribute('data-precio'));
                        
                        elemento.innerHTML = "S/ " + decimales(window.precio);
                    });
                }else if (window.tipoCobro == 2) {
                    elementosConItem = document.querySelectorAll('[id^="itemPrice"]');

                    elementosConItem.forEach((elemento) => {
                        window.precio = parseFloat(elemento.getAttribute('data-precio2'));

                        elemento.innerHTML = "S/ " + decimales(window.precio);
                    });
                }else if (window.tipoCobro == 3) {
                    elementosConItem = document.querySelectorAll('[id^="itemPrice"]');

                    elementosConItem.forEach((elemento) => {
                        window.precio = parseFloat(elemento.getAttribute('data-precio3'));

                        elemento.innerHTML = "S/ " + decimales(window.precio);
                    });
                }else{
                    elementosConItem = document.querySelectorAll('[id^="itemPrice"]');

                    elementosConItem.forEach((elemento) => {
                        window.precio = parseFloat(elemento.getAttribute('data-precio'));
                        
                        elemento.innerHTML = "S/ " + decimales(window.precio);
                    });
                }
                
                $("input[name='precio']").each(function() {
                    precioSoles = $(this).data('precio');
                    precioSoles = parseFloat(precioSoles);

                    if (!isNaN(precioSoles)) {
                        $(this).val(precioSoles.toFixed(2));
                    } else{
                        alert("Error al realizar el cambio de moneda.")
                    }
                });

                $("span.total").each(function() {
                    precioSoles = parseFloat($(this).data('total'));
                    if (!isNaN(precioSoles)) {
                        $(this).html(precioSoles.toFixed(2)); 
                    } else {
                        alert("Error al realizar el cambio de moneda.")
                    }
                });

                $("span.gravadas").each(function() {
                    precioSoles = parseFloat($(this).data('valor'));
                    if (!isNaN(precioSoles)) {
                        $(this).html(precioSoles.toFixed(2)); 
                    } else {
                        alert("Error al realizar el cambio de moneda.")
                    }
                });

                $("span.igv").each(function() {
                    precioSoles = parseFloat($(this).data('valor'));
                    if (!isNaN(precioSoles)) {
                        $(this).html(precioSoles.toFixed(2)); 
                    } else {
                        alert("Error al realizar el cambio de moneda.")
                    }
                });

                $("span.total2").each(function() {
                    precioSoles = parseFloat($(this).data('valor'));
                    if (!isNaN(precioSoles)) {
                        $(this).html(precioSoles.toFixed(2)); 
                    } else {
                        alert("Error al realizar el cambio de moneda.")
                    }
                });

                $("span[name='moneda']").each(function() {
                    $(this).html("S/ ");
                });

                $("#btn_cambioMoneda").html("<i class=\"fa-solid fa-dollar-sign fa-2x\"></i>");
                $("#btn_cambioMoneda").removeClass("btn-warning");
                $("#btn_cambioMoneda").removeClass("btn-success");
                $("#btn_cambioMoneda").addClass("btn-success");
                
            } else {

                window.usd = true;
                
                if (window.tipoCobro == 1) {
                    elementosConItem = document.querySelectorAll('[id^="itemPrice"]');

                    elementosConItem.forEach((elemento) => {
                        window.precio = parseFloat(elemento.getAttribute('data-precio'));
                        
                        elemento.innerHTML = "$ " + decimales((window.precio / window.cambio));
                    });
                }else if (window.tipoCobro == 2) {
                    elementosConItem = document.querySelectorAll('[id^="itemPrice"]');

                    elementosConItem.forEach((elemento) => {
                        window.precio = parseFloat(elemento.getAttribute('data-precio2'));

                        elemento.innerHTML = "$ " + decimales((window.precio / window.cambio));
                    });
                }else if (window.tipoCobro == 3) {
                    elementosConItem = document.querySelectorAll('[id^="itemPrice"]');

                    elementosConItem.forEach((elemento) => {
                        window.precio = parseFloat(elemento.getAttribute('data-precio3'));

                        elemento.innerHTML = "$ " + decimales((window.precio / window.cambio));
                    });
                }else{
                    elementosConItem = document.querySelectorAll('[id^="itemPrice"]');

                    elementosConItem.forEach((elemento) => {
                        window.precio = parseFloat(elemento.getAttribute('data-precio'));
                        
                        elemento.innerHTML = "$ " + decimales((window.precio / window.cambio));
                    });
                }
                
                $("input[name='precio']").each(function() {
                    precioSoles = parseFloat($(this).data('precio'));

                    if (!isNaN(precioSoles)) {
                        precioUSD = (precioSoles / window.cambio);

                        $(this).val(precioUSD.toFixed(2));
                    } else {
                        alert("Error al realizar el cambio de moneda.")
                    }
                });

                $("span.total").each(function() {
                    precioSoles = parseFloat($(this).data('total'));

                    if (!isNaN(precioSoles)) {
                        precioUSD = precioSoles / window.cambio;

                        $(this).html(precioUSD.toFixed(2)); 
                    } else {
                        alert("Error al realizar el cambio de moneda.")
                    }
                });

                $("span.gravadas").each(function() {
                    precioSoles = parseFloat($(this).data('valor'));

                    if (!isNaN(precioSoles)) {
                        precioUSD = precioSoles / window.cambio;

                        $(this).html(precioUSD.toFixed(2)); 
                    } else {
                        alert("Error al realizar el cambio de moneda.")
                    }
                });

                $("span.igv").each(function() {
                    precioSoles = parseFloat($(this).data('valor'));

                    if (!isNaN(precioSoles)) {
                        precioUSD = precioSoles / window.cambio;

                        $(this).html(precioUSD.toFixed(2)); 
                    } else {
                        alert("Error al realizar el cambio de moneda.")
                    }
                });
                
                $("span.total2").each(function() {
                    precioSoles = parseFloat($(this).data('valor'));

                    if (!isNaN(precioSoles)) {
                        precioUSD = precioSoles / window.cambio;

                        $(this).html(precioUSD.toFixed(2)); 
                    } else {
                        alert("Error al realizar el cambio de moneda.")
                    }
                });

                $("span[name='moneda']").each(function() {
                    $(this).html("$ ");
                });

                $("#btn_cambioMoneda").html("<b  style=\"font-size: 24px;\">S/</b>");
                $("#btn_cambioMoneda").removeClass("btn-success");
                $("#btn_cambioMoneda").addClass("btn-warning");
            }
            
        });
    });
</script>