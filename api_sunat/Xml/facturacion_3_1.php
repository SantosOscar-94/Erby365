<?php

require_once '../Funciones/nletras.php';

class cpeFacturacionUBL3_1
{
    function Registrar_FacturacionUBL3_1($json_fact, $emp)
    {
        // Información de la empresa
        $data   = [];
        foreach ($emp as $cabezera) {
            $RucEmpresa           = $cabezera['rucEmisor'];
            $NameEmpresa          = $cabezera['razEmisor'];
            $DireccionEmpresa     = $cabezera['direccionEmisor'];
            $PaisEmpresa          = $cabezera['paisEmisor'];
            $UbigeoEmpresa        = $cabezera['ubigeoEmisor'];
            $DepartamentoEmpresa  = $cabezera['depEmisor'];
            $ProvinciaEmpresa     = $cabezera['provEmisor'];
            $DistritoEmpresa      = $cabezera['distEmisor'];
            $ComercialEmpresa     = $cabezera['comercialEmisor'];
            $UrbanizacionEmpresa  = $cabezera['urbEmisor'];
            $LocalEmpresa         = $cabezera['localEmisor'];
        }

        // Variables para la detracción
        foreach ($json_fact as $cabezera) {
            $ImporteTotal = $cabezera['mtoTotal'];
            $CuentaBancoNacion = $cabezera['CuentaDetraccion']; // Número de cuenta en el Banco de la Nación
            $PorcentajeDetraccion = $cabezera['PorcentajeDetraccion'] ?? 10; // Porcentaje de detracción aplicado
            $MontoDetraccion = $cabezera['mtoDetraccion'] ?? 70;
            $serieComp = $cabezera['serieComp'];
            $numeroComp = $cabezera['numeroComp'];
            $NumComprobante = $serieComp . "-" . $numeroComp;
        }

        // Generación del XML
        $xml = new DomDocument('1.0', 'utf-8');
        $Invoice = $xml->createElement('Invoice');
        $Invoice = $xml->appendChild($Invoice);

        // Agregar los namespaces
        $Invoice->setAttribute('xmlns', 'urn:oasis:names:specification:ubl:schema:xsd:Invoice-2');
        $Invoice->setAttribute('xmlns:cac', 'urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2');
        $Invoice->setAttribute('xmlns:cbc', 'urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2');
        $Invoice->setAttribute('xmlns:ds', 'http://www.w3.org/2000/09/xmldsig#'); // Namespace para firma

        // Agregar la sección de detracciones
        $CACPaymentTerms = $xml->createElement('cac:PaymentTerms');
        $CACPaymentTerms = $Invoice->appendChild($CACPaymentTerms);

        $CBCID = $xml->createElement('cbc:ID', 'Detraccion');
        $CBCID = $CACPaymentTerms->appendChild($CBCID);

        $CBCPaymentPercent = $xml->createElement('cbc:PaymentPercent', number_format($PorcentajeDetraccion, 2));
        $CBCPaymentPercent = $CACPaymentTerms->appendChild($CBCPaymentPercent);

        $CBCAmt = $xml->createElement('cbc:Amount', number_format($MontoDetraccion, 2, '.', ''));
        $CBCAmt->setAttribute('currencyID', 'PEN');
        $CBCAmt = $CACPaymentTerms->appendChild($CBCAmt);

        $CACPayeeFinancialAccount = $xml->createElement('cac:PayeeFinancialAccount');
        $CACPayeeFinancialAccount = $CACPaymentTerms->appendChild($CACPayeeFinancialAccount);

        $CBCAccountID = $xml->createElement('cbc:ID', $CuentaBancoNacion);
        $CBCAccountID = $CACPayeeFinancialAccount->appendChild($CBCAccountID);

        // Agregar el nodo Signature
        $Signature = $xml->createElement('ds:Signature');
        $Signature = $Invoice->appendChild($Signature);

        $SignedInfo = $xml->createElement('ds:SignedInfo');
        $SignedInfo = $Signature->appendChild($SignedInfo);

        $CanonicalizationMethod = $xml->createElement('ds:CanonicalizationMethod');
        $CanonicalizationMethod->setAttribute('Algorithm', 'http://www.w3.org/TR/2001/REC-xml-c14n-20010315');
        $SignedInfo->appendChild($CanonicalizationMethod);

        $SignatureMethod = $xml->createElement('ds:SignatureMethod');
        $SignatureMethod->setAttribute('Algorithm', 'http://www.w3.org/2000/09/xmldsig#rsa-sha1');
        $SignedInfo->appendChild($SignatureMethod);

        $Reference = $xml->createElement('ds:Reference');
        $Reference->setAttribute('URI', '');
        $SignedInfo->appendChild($Reference);

        $Transforms = $xml->createElement('ds:Transforms');
        $Reference->appendChild($Transforms);

        $Transform = $xml->createElement('ds:Transform');
        $Transform->setAttribute('Algorithm', 'http://www.w3.org/2000/09/xmldsig#enveloped-signature');
        $Transforms->appendChild($Transform);

        $DigestMethod = $xml->createElement('ds:DigestMethod');
        $DigestMethod->setAttribute('Algorithm', 'http://www.w3.org/2000/09/xmldsig#sha1');
        $Reference->appendChild($DigestMethod);

        $DigestValue = $xml->createElement('ds:DigestValue', 'Ov0JBy06IBvRDRWQcAqx62ebHA8=');  // Valor digestible (ejemplo)
        $Reference->appendChild($DigestValue);

        $SignatureValue = $xml->createElement('ds:SignatureValue', 'RT77OjCWWE7ZM318lXpoWtu9oS2yysADohIHB9Lh5XFg550ggbf7RFCyWCzbPnAuFCY8uOUYDf3OEqwuDazJ0Xr9fa5JpHsSTe0ccUbLa0rN8i07Ia9svf4pQyTQ1EYHlckqlBtMnhyrQFlleasvgWOJ1Cf9k6LE82912C+rI8ejtst/qqTY01CnUgCc4cNbj1hwTs3uAwZ37783JHUiezkhH4HDbg+WD29mtOkrjcWfmONcpvHVeRcLgGLD17hSD5gM6t/VI7mD8STztjr8T9fob6UTkTDHCE3TCTT7My6pxXNFwXyZU5NQUE8UJpyyF1BqERYrgebSPJfWZ0Iv9w==');
        $Signature->appendChild($SignatureValue);

        $KeyInfo = $xml->createElement('ds:KeyInfo');
        $KeyInfo = $Signature->appendChild($KeyInfo);

        $X509Data = $xml->createElement('ds:X509Data');
        $X509Data = $KeyInfo->appendChild($X509Data);

        // Valor de X509Certificate, ahora correctamente dentro de una variable PHP
        $x509Certificate = 'MIIIuTCCBqGgAwIBAgIUHIzUZnr4dfk9JRuVxEycExxciTIwDQYJKoZIhvcNAQELBQAwbzELMAkGA1UEBhMCUEUxPDA6BgNVBAoMM1JlZ2lzdHJvIE5hY2lvbmFsIGRlIElkZW50aWZpY2FjacOzbiB5IEVzdGFkbyBDaXZpbDEiMCAGA1UEAwwZRUNFUC1SRU5JRUMgQ0EgQ2xhc3MgMSBJSTAeFw0yNDA1MTcyMzIwMThaFw0yNzA1MTcyMzIwMThaMIIBGDELMAkGA1UEBhMCUEUxEjAQBgNVBAgMCUxJTUEtTElNQTEfMB0GA1UEBwwWU0FOIEpVQU4gREUgTFVSSUdBTkNITzEtMCsGA1UECgwkREUgTE9TIFNBTlRPUyBTSEFQSUFNQSBPU0NBUiBFTElTQkFOMRowGAYDVQRhDBFOVFJQRS0xMDc1MzMzMDQ1OTEhMB8GA1UECwwYRVJFUF9TVU5BVF8yMDI0MDAwNTI1OTkwMRQwEgYDVQQLDAsxMDc1MzMzMDQ1OTFQME4GA1UEAwxHfHxVU08gVFJJQlVUQVJJT3x8IERFIExPUyBTQU5UT1MgU0hBUElBTUEgT1NDQVIgRUxJU0JBTiBDRFQgMTA3NTMzMzA0NTkwggEiMA0GCSqGSIb3DQEBAQUAA4IBDwAwggEKAoIBAQC1g0y8071GqY2x0HjJsYvj4BRezLPZDh7+ekQuTtz1327Nvc4vbz+I/oRrHDRcsiDCAqi9n4ivAkhE5LBfn06a7zViWhZQ3VV74Z3ReCVOGpg/iVNtzMFLrPZp7ovTmE/OPagD6inQvfFH7+Ges3me+evj3bnZNwkZNZjn6UbrvbLZu5ad332nWSm17aEw/+Us/ap3bTazR6XFlLWudt6yh+eSPJUUKjNY1vSEgMx7Uc+T8C4mPThdtPu9AbxIshT0DsTbsUeUwIxrWIFbVA4zvpezHKOKIU95a3SaEUJJ15/RNlQWAPqfatY8ui8dlQRgWf73COAuPndwbkEQ0OQTAgMBAAGjggOgMIIDnDAMBgNVHRMBAf8EAjAAMB8GA1UdIwQYMBaAFMx9H1biib++Q+2V38FGe79L/d0lMHAGCCsGAQUFBwEBBGQwYjA5BggrBgEFBQcwAoYtaHR0cDovL2NydC5yZW5pZWMuZ29iLnBlL3Jvb3QzL2NhY2xhc3MxaWkuY3J0MCUGCCsGAQUFBzABhhlodHRwOi8vb2NzcC5yZW5pZWMuZ29iLnBlMIICNwYDVR0gBIICLjCCAiowdwYRKwYBBAGCk2QCAQMBAGWHaAAwYjAxBggrBgEFBQcCARYlaHR0cHM6Ly93d3cucmVuaWVjLmdvYi5wZS9yZXBvc2l0b3J5LzAtBggrBgEFBQcCARYhUG9s7XRpY2EgR2VuZXJhbCBkZSBDZXJ0aWZpY2FjafNuMIHEBhErBgEEAYKTZAIBAwEAZ4doADCBrjAyBggrBgEFBQcCARYmaHR0cHM6Ly9wa2kucmVuaWVjLmdvYi5wZS9yZXBvc2l0b3Jpby8weAYIKwYBBQUHAgIwbB5qAEQAZQBjAGwAYQByAGEAYwBpAPMAbgAgAGQAZQAgAFAAcgDhAGMAdABpAGMAYQBzACAAZABlACAAQwBlAHIAdABpAGYAaQBjAGEAYwBpAPMAbgAgAEUAQwBFAFAALQBSAEUATgBJAEUAQzCB5wYRKwYBBAGCk2QCAQMBAWeHcwMwgdEwgc4GCCsGAQUFBwICMIHBHoG+AEMAZQByAHQAaQBmAGkAYQdAamZGckgGHMiDFtUNih7dFfOkAACT8HCHkT5gxg==';
        
        $X509Certificate = $xml->createElement('ds:X509Certificate', $x509Certificate);
        
        // Aquí, luego de que el valor de $x509Certificate se inserta, puedes continuar como lo hacías para el resto del XML
        $X509Data = $xml->createElement('ds:X509Data');
        $X509Data->appendChild($X509Certificate);
        
        $KeyInfo = $xml->createElement('ds:KeyInfo');
        $KeyInfo->appendChild($X509Data);
        $Signature->appendChild($KeyInfo);

        // Guardar XML generado
        $xml->formatOutput = true;
        $strings_xml = $xml->saveXML();
        $file = '../Xml/xml-no-firmados/' . $RucEmpresa . '-36-' . $NumComprobante . '.xml';
        $xml->save($file);

        if (file_exists($file)) {
            $r[0] = 'Registrado';
        } else {
            $r[0] = 'Error';
        }

        return $r;
    }
}
