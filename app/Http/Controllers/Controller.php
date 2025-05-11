<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\District;
use App\Models\Product;
use App\Models\Province;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Http;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function verify__client($dni_ruc)
    {
        $token  = 'NYb2b6pXGk6ZKn15DxVF3cJyax60jDcbQoCCVOoJ7ZGhHltMGp';
        $data   = null;

        if(strlen($dni_ruc) == 8)
        {
            // $curl = curl_init();
            // curl_setopt_array($curl, array(
            // CURLOPT_URL => 'https://api.factiliza.com/pe/v1/dni/info/' . $dni_ruc,
            // CURLOPT_RETURNTRANSFER => true,
            // CURLOPT_ENCODING => '',
            // CURLOPT_MAXREDIRS => 10,
            // CURLOPT_TIMEOUT => 0,
            // CURLOPT_FOLLOWLOCATION => true,
            // CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            // CURLOPT_CUSTOMREQUEST => 'GET',
            // CURLOPT_HTTPHEADER => array(
            //     'Authorization: Bearer ' . $token
            //     ),
            // ));

            // $response   = curl_exec($curl);
            // $data       = json_decode($response);
            // curl_close($curl);

            $params = json_encode(['dni' => $dni_ruc]);
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://apiperu.info/api/dni/$dni_ruc",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_SSL_VERIFYPEER => false,
                // CURLOPT_POSTFIELDS => $params,
                CURLOPT_HTTPHEADER => [
                    'Accept: application/json',
                    'Content-Type: application/json',
                    'Authorization: Bearer '.$token
                ],
            ));
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            if ($err) {
                $data       = json_decode($response);
            } else {
                $data       = json_decode($response);
            }
        }
        else
        {
            // $curl = curl_init();
            // curl_setopt_array($curl, array(
            // CURLOPT_URL => 'https://api.factiliza.com/pe/v1/ruc/info/' . $dni_ruc,
            // CURLOPT_RETURNTRANSFER => true,
            // CURLOPT_ENCODING => '',
            // CURLOPT_MAXREDIRS => 10,
            // CURLOPT_TIMEOUT => 0,
            // CURLOPT_FOLLOWLOCATION => true,
            // CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            // CURLOPT_CUSTOMREQUEST => 'GET',
            // CURLOPT_HTTPHEADER => array(
            //     'Authorization: Bearer ' . $token
            //     ),
            // ));

            // $response   = curl_exec($curl);
            // $data       = json_decode($response);
            // curl_close($curl);

            $params = json_encode(['ruc' => $dni_ruc]);
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => "https://apiperu.info/api/ruc/$dni_ruc",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_SSL_VERIFYPEER => false,
                // CURLOPT_POSTFIELDS => $params,
                CURLOPT_HTTPHEADER => [
                    'Accept: application/json',
                    'Content-Type: application/json',
                    'Authorization: Bearer '.$token
                ],
            ]);
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            if ($err) {
                $data       = json_decode($response);
            } else {
                $data       = json_decode($response);
            }
        }

        return $data;
    }

    public function get_ubigeo($ubigeo)
    {
        $data                   = [];
        $distrito               = District::where('codigo', $ubigeo)->first();
        $data['distrito']       = $distrito->descripcion;
        $provincia              = Province::where('codigo', $distrito->provincia_codigo)
                                    ->where('departamento_codigo', $distrito->departamento_codigo)
                                    ->first();
        $data['provincia']      = $provincia->descripcion;
        $departamento           = Department::where('codigo', $distrito->departamento_codigo)->first();
        $data['departamento']   = $departamento->descripcion;
        return $data;
    }

    public function redondeado($numero, $decimales = 2)
    {
        $factor = pow(10, $decimales);
        return (round($numero*$factor)/$factor);
    }


    public function send_msg_wpp($telefono, $header, $mensaje)
    {
        $curl           = curl_init();
        $token          =  'EAALJZBqbD308BOyrwiFI6CKiZANi0ZCATzmmNOp91BzFmPe1hNZCEamTJoO6bC3uuhNC1oxakq2O9Sp8UaB3vkA6MdFq8Dw4qZANZBn7l3BUbHGwp1b3c6tJ58WFZCoNe2hrj4iTzM6EUb5vsrQDmbVo2nebZAEeJ2p8AtTJmYtOijKtknoZBzvLnwCU0wDE06cU6dGCqgVdQfjoS288ZA5kyflAZDZD';

        curl_setopt_array($curl, array(
       // CURLOPT_URL => 'https://graph.facebook.com/v18.0/162704683603332/messages',
          CURLOPT_URL => 'https://graph.facebook.com/v19.0/344044482128758/messages',
     //   https://graph.facebook.com/v19.0/344044482128758/messages
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{
            "messaging_product": "whatsapp",
            "to": "+51'. $telefono .'",
            "type": "template",
            "template": {
                "name": "send_voucher1",
                "language": {
                    "code": "es"
                },
                "components": [

                    {
                      "type": "body",
                      "parameters": [
                         {
                            "type": "text",
                            "text": "'. $header .'"
                          },
                        {
                          "type": "text",
                          "text": "'. $mensaje .'"
                        }
                      ]
                    }
                ]
            }
        }',
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $token
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }
}
