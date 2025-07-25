<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

use App\Models\Pago;
use App\Models\Venta;
use Illuminate\Support\Facades\Log;

class ConsultarAdminController extends Controller{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request){
        Log::debug($request);
        $venta_id = $request->input('venta_id');
        $ventaEnCurso = Venta::findOrFail($venta_id);

                $idUltimaVenta = $venta_id;//$ultimaVenta->id;
                $loClientEstado = new Client();
                $lcUrlEstadoTransaccion = "https://serviciostigomoney.pagofacil.com.bo/api/servicio/consultartransaccion";
                $laHeaderEstadoTransaccion = [
                    'Accept' => 'application/json'
                ];

                $laBodyEstadoTransaccion = [
                    "TransaccionDePago" => $idUltimaVenta
                ];
                $loEstadoTransaccion = $loClientEstado->post($lcUrlEstadoTransaccion, [
                    'headers' => $laHeaderEstadoTransaccion,
                    'json' => $laBodyEstadoTransaccion
                ]);

               // Decode the JSON response
                      $laResultEstadoTransaccion = json_decode($loEstadoTransaccion->getBody()->getContents());
                    if ($laResultEstadoTransaccion && isset($laResultEstadoTransaccion->values->messageEstado)) {
                            $cadenaCompleta = $laResultEstadoTransaccion->values->messageEstado;
                                $elementos = explode(' - ', $cadenaCompleta);
                                if (count($elementos) >= 2) {
                                    $textoExtraido = $elementos[0] . '-' . $elementos[1];
                                    if ($textoExtraido == "COMPLETADO-PROCESADO") {
                                        $venta = $ventaEnCurso;
                                        $venta->estado = 2;
                                        $venta->update();

                                        $pago = Pago::findOrFail($venta->pago_id);
                                        $pago->estado = 2;
                                        $pago->update();
                                    }
                                    return $textoExtraido;

                                } else {
                                    return "Error";
                                }
                            }
                              return "ENTROOO";
                            }
                      //  }
    //}

}
