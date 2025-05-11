<script>
    $('body').on('click', '.btn-save', function()
    {
        event.preventDefault();
        
        cant    = [];
        price   = [];
            
        $("input[name='cant']").each(function() {
            cant.push({
                cant: $(this).val(),
                id: $(this).data('id')
            });
        });
        
        $("input[name='price']").each(function() {
            price.push({
                price: $(this).val(),
                id: $(this).data('id')
            });
        });
        
        var cantJSON = 'cant=' + JSON.stringify(cant);
        var priceJSON = 'price=' + JSON.stringify(price);

        
        let form    = $('#form_save_credit_note').serialize() + "&" + cantJSON + "&" + priceJSON,
            motivo  = $('input[name="motivo"]');
            
        //console.log(form);
        
            if(motivo.val().trim() == '')
                motivo.addClass('is-invalid');
            else
                motivo.removeClass('is-invalid');

            if(motivo.val().trim() != '')
            {
                $.ajax({
                    url         : "{{ route('admin.save_nc') }}",
                    method      : 'POST',
                    data        : form,
                    beforeSend  : function(){
                        $('.btn-save').prop('disabled', true);
                        $('.text-save').addClass('d-none');
                        $('.text-saving').removeClass('d-none');
                    },
                    success     : function(r){
                        if(!r.status)
                        {
                            $('.btn-save').prop('disabled', false);
                            $('.text-save').removeClass('d-none');
                            $('.text-saving').addClass('d-none');
                            toast_msg(r.msg, r.type);
                            return; 
                        }

                        $('.btn-save').prop('disabled', false);
                        $('.text-save').removeClass('d-none');
                        $('.text-saving').addClass('d-none');
                        send_nc(r.id, r.idrelacionado,r.pdf);
                    },
                    dataType    : 'json'
                });
            }
    });

    function send_nc(id, idrelacionado, ticket)
    {
        $.ajax({
            url         : "{{ route('admin.send_nc') }}",
            method      : 'POST',
            data        : {'_token' : "{{ csrf_token() }}", id : id},
            beforeSend  : function(){
                $('.btn-save').prop('disabled', true);
                $('.text-save').addClass('d-none');
                $('.text-saving').removeClass('d-none');
            },
            success     : function(r){
                if(!r.status){}

                $('.btn-save').prop('disabled', false);
                $('.text-save').removeClass('d-none');
                $('.text-saving').addClass('d-none');
                
                let ip          = r.empresa.url_api,
                    api         = "Api/index.php",
                    datosJSON   = JSON.stringify(r.data);
                    datosJSON   = unescape(encodeURIComponent(datosJSON)),
                    idfactura   = parseInt(r.idfactura);

                    $.ajax({    
                        url         : ip + api,
                        method      : 'POST',
                        data        : {datosJSON},
                        beforeSend  : function(){
                            $('.btn-save').prop('disabled', true);
                            $('.text-save').addClass('d-none');
                            $('.text-saving').removeClass('d-none');
                    },
                    }).done(function(res){

                        $('.btn-save').prop('disabled', false);
                        $('.text-save').removeClass('d-none');
                        $('.text-saving').addClass('d-none');
                        if (res.trim() == "No se registró") 
                        {
                            toast_msg('El número de comprobante electrónico esta duplicado, revise la base de datos', 'error');
                            return;
                        }

                        load_alerts();
                        let respuesta_sunat = JSON.parse(res),
                            estado_conexion = JSON.parse(respuesta_sunat).status;
                        let pdf = `{{ asset('files/billings/ticket/${ticket}') }}`;
                        var iframe = document.createElement('iframe');
                        iframe.style.display = "none";
                        iframe.src = pdf;
                        document.body.appendChild(iframe);
                        iframe.contentWindow.focus();
                        iframe.contentWindow.print();
                        if(estado_conexion != false)
                        {
                            update_cdr_nc(id, idrelacionado);
                        }
                    }).fail(function(jqxhr, textStatus, error){
                        $('.btn-save').prop('disabled', false);
                        $('.text-save').removeClass('d-none');
                        $('.text-saving').addClass('d-none');
                        load_alerts();
                        let pdf = `{{ asset('files/billings/ticket/${ticket}') }}`;
                        var iframe = document.createElement('iframe');
                        iframe.style.display = "none";
                        iframe.src = pdf;
                        document.body.appendChild(iframe);
                        iframe.contentWindow.focus();
                        iframe.contentWindow.print();
                    });
            },
            dataType    : 'json'
        });
    }

    function update_cdr_nc(id, idrelacionado)
    {
        $.ajax({
            url     : "{{ route('admin.update_cdr_nc') }}",
            method  : 'POST',
            data    : {
                '_token'    : "{{ csrf_token() }}",
                id   : id,
                idrelacionado: idrelacionado
            },
            success : function(r)
            {
                return true;
            },
            dataType : 'json'
        });
    }

    function totalPrice(total) {
        if ($('#igv').data('igv') > 0) {
            $('#grabada').text((total - (total * 0.18)).toFixed(2));
            $('#igv').text((total * 0.18).toFixed(2));
        } else {
            $('#grabada').text(total.toFixed(2));
        }
        
        $('#total').text(total.toFixed(2));
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        select   = document.getElementById('tipo_nc');
        cont = false;
        select.addEventListener('change', function() {
             selectedOption = this.options[select.selectedIndex];
             value = selectedOption.value;
             $("#global_discount").prop("disabled", true);
             $("input[name='cant']").prop("disabled", true);
             $("input[name='price']").prop("disabled", true);
             
             if(cont){
                window.location.href = window.location.href;
             }else{
                cont = true;
             }
            
             if (value == 4) { //descuento global
                $("#global_discount").prop("disabled", false);
             }else if (value == 5) { //descuento por item
                $("input[name='price']").prop("disabled", false);
             }else if (value == 7) { //devolución por item
                $("input[name='cant']").prop("disabled", false);
            }
        });

        $("#global_discount").on('change', function() {
            total   = $('#total').data('total') - $(this).val();
            
            totalPrice(total);
        });
        
        $("input[name='cant']").on('change', function() {
            total = 0;
            
            $("input[name='cant']").each(function() {
                cantidad = $(this).val();
                precio   = $(this).data('price');
                total   += (cantidad * precio);
            });
            
            totalPrice(total);
        });
        
        $("input[name='price']").on('change', function() {
            total = 0;
            
            $("input[name='price']").each(function() {
                precio      = $(this).val();
                cantidad    = $(this).data('cant');
                total   += (cantidad * precio);
            });
            
            totalPrice(total);
        });
    });
</script>