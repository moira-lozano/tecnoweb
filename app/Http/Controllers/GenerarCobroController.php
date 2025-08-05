<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Models\Venta;
use App\Models\Pago;
use App\Models\DetalleVenta;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class GenerarCobroController extends Controller
{
    // GENERAR COBRO
    public function generarCobro(Request $request)
    {
        // ✅ VALIDACIÓN DE DATOS
        $request->validate([
            'name' => 'required|string|max:255',
            'cedula' => 'required|string|max:20',
            'tcCorreo' => 'required|email',
            'tnTelefono' => 'required|string',
            'tcDireccion' => 'required|string',
            'tcRazonSocial' => 'required|string',
            'tnMonto' => 'required|numeric|min:0.01',
            'tnTipoServicio' => 'required|in:1,2,3',
            'taPedidoDetalle' => $request->input('taPedidoDetalle', []),
        ]);

        // Log para debugging
        Log::info('🔍 Datos recibidos:', $request->all());

        do {
            $nroPago = rand(100000, 999999);
            $existe = Pago::where('id', $nroPago)->exists();
        } while ($existe);

        try {
            $lcComerceID           = "d029fa3a95e174a19934857f535eb9427d967218a36ea014b70ad704bc6c8d1c";
            $lnMoneda              = 1;
            $lnTelefono            = $request->tnTelefono;
            $lcNombreUsuario       = $request->tcRazonSocial;
            $lnDireccion           = $request->tcDireccion;
            $lcNroPago             = $nroPago;
            $lnMontoClienteEmpresa = $request->tnMonto;
            $lcCorreo              = $request->tcCorreo;
            $lcUrlCallBack         = route('admin.pagos.callback');
            $lcUrlReturn           = "";
            $laPedidoDetalle       = $request->taPedidoDetalle;
            $lcUrl                 = "";

            $loClient = new Client();

            if ($request->tnTipoServicio == 1) {
                $lcUrl = "https://serviciostigomoney.pagofacil.com.bo/api/servicio/generarqrv2";
            } elseif ($request->tnTipoServicio == 2) {
                $lcUrl = "https://serviciostigomoney.pagofacil.com.bo/api/servicio/realizarpagotigomoneyv2";
            }

            // ✅ PAGO EN EFECTIVO - SECCIÓN CORREGIDA
            if ($request->tnTipoServicio == 3) {
                Log::info('💰 Procesando pago en efectivo');
                
                // ✅ CREAR O BUSCAR PERSONA (no solo usuario)
                $persona = \DB::table('personas')->where('email', $request->tcCorreo)->first();
                
                if (!$persona) {
                    // Crear en tabla personas
                    $personaId = \DB::table('personas')->insertGetId([
                        'nombre' => $request->name,
                        'cedula' => $request->cedula,
                        'celular' => $request->tnTelefono,
                        'direccion' => $request->tcDireccion,
                        'email' => $request->tcCorreo,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    Log::info('👤 Persona creada:', ['persona_id' => $personaId]);
                } else {
                    $personaId = $persona->id;
                    Log::info('👤 Persona existente encontrada:', ['persona_id' => $personaId]);
                }

                // También crear usuario si no existe (para otras funcionalidades)
                $user = User::where('email', $request->tcCorreo)->first();
                if (!$user) {
                    $user = User::create([
                        'name' => $request->name,
                        'cedula' => $request->cedula,
                        'celular' => $request->tnTelefono,
                        'direccion' => $request->tcDireccion,
                        'email' => $request->tcCorreo,
                        'password' => bcrypt('defaultpassword123'),
                    ]);
                    
                    \DB::table('role_users')->insert([
                        'user_id' => $user->id,
                        'role_id' => 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                // Crear nuevo ID de venta
                do {
                    $nroVenta = rand(100000, 999999);
                    $ventaExistente = Venta::where('id', $nroVenta)->exists();
                } while ($ventaExistente);

                // Crear registro de Pago
                $pago = Pago::create([
                    'id' => $nroPago,
                    'venta_id' => $nroVenta,
                    'fechapago' => now(),
                    'estado' => 1,
                    'metodopago' => 3, // Efectivo
                ]);
                Log::info('💳 Pago creado:', ['pago_id' => $pago->id]);

                // ✅ CREAR REGISTRO DE VENTA CON PERSONA_ID
                $vendedorId = 1; // 👈 ID fijo del vendedor (empresa) - ajusta según tu caso
                
                $venta = Venta::create([
                    'id' => $nroVenta,
                    'comprador_id' => $personaId, // Cliente que compra
                    'vendedor_id' => $vendedorId,  // Empresa que vende
                    'montototal' => $request->tnMonto,
                    'estado' => 1, // Pendiente hasta pagar
                ]);
                Log::info('🛒 Venta creada:', ['venta_id' => $venta->id]);

                // ✅ CREAR DETALLES DE VENTA - MEJORADO
                foreach ($request->taPedidoDetalle as $detalle) {
                    // Calcular total si no viene
                    $total = isset($detalle['total']) 
                        ? $detalle['total'] 
                        : ($detalle['cantidad'] * ($detalle['precio_unitario'] ?? $detalle['precio'] ?? 0));

                    $detalleVenta = DetalleVenta::create([
                        'venta_id' => $nroVenta,
                        'licencia_id' => $detalle['id'],
                        'cantidad' => $detalle['cantidad'],
                        'total' => $total,
                    ]);
                    
                    Log::info('📦 Detalle creado:', [
                        'licencia_id' => $detalle['id'],
                        'cantidad' => $detalle['cantidad'],
                        'total' => $total
                    ]);
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Venta registrada con éxito para pago en efectivo.',
                    'venta_id' => $nroVenta,
                    'pago_id' => $nroPago,
                    'persona_id' => $personaId,
                ]);
            }

            // ✅ QR Y TIGO MONEY (código existente mejorado)
            $laHeader = [
                'Accept' => 'application/json'
            ];

            $laBody = [
                "tcCommerceID"          => $lcComerceID,
                "tnMoneda"              => $lnMoneda,
                "tnTelefono"            => $lnTelefono,
                'tcNombreUsuario'       => $lcNombreUsuario,
                'tcDireccion'           => $lnDireccion,
                'tcNroPago'             => $lcNroPago,
                "tnMontoClienteEmpresa" => $lnMontoClienteEmpresa,
                "tcCorreo"              => $lcCorreo,
                'tcUrlCallBack'         => $lcUrlCallBack,
                "tcUrlReturn"           => $lcUrlReturn,
            ];

            $loResponse = $loClient->post($lcUrl, [
                'headers' => $laHeader,
                'json' => $laBody
            ]);

            $laResult = json_decode($loResponse->getBody()->getContents());

            // ✅ LOGGING MEJORADO PARA DEBUGGING
            Log::info('🌐 Respuesta API completa:', [
                'response' => $laResult,
                'values' => $laResult->values ?? 'No existe',
                'tipo_servicio' => $request->tnTipoServicio
            ]);

            if ($request->tnTipoServicio == 1) {
                // ✅ VALIDACIÓN MEJORADA PARA QR
                if (!isset($laResult->values) || empty($laResult->values)) {
                    Log::error('❌ Error QR: No se recibió values en la respuesta');
                    return response()->json([
                        'success' => false,
                        'message' => 'Error: La API de QR no devolvió datos válidos',
                        'api_response' => $laResult
                    ], 500);
                }

                // Verificar si contiene punto y coma
                if (strpos($laResult->values, ';') === false) {
                    Log::error('❌ Error QR: values no contiene separador ";"', [
                        'values' => $laResult->values
                    ]);
                    return response()->json([
                        'success' => false,
                        'message' => 'Error: Formato de respuesta QR inválido',
                        'received_values' => $laResult->values
                    ], 500);
                }

                // ✅ SEPARACIÓN SEGURA CON VALIDACIÓN
                $valuesArray = explode(";", $laResult->values);
                
                if (count($valuesArray) < 2) {
                    Log::error('❌ Error QR: values no tiene suficientes elementos', [
                        'values' => $laResult->values,
                        'array_count' => count($valuesArray)
                    ]);
                    return response()->json([
                        'success' => false,
                        'message' => 'Error: Respuesta QR incompleta',
                        'values_array' => $valuesArray
                    ], 500);
                }

                $nroTransaccion = $valuesArray[0]; // Primer elemento
                $laValues = $valuesArray[1];       // Segundo elemento

                Log::info('✅ QR procesado correctamente:', [
                    'nro_transaccion' => $nroTransaccion,
                    'values_length' => strlen($laValues)
                ]);

                // Buscar usuario por correo
                $user = User::where('email', $request->tcCorreo)->first();
                if (!$user) {
                    $user = User::create([
                        'name' => $request->name,
                        'cedula' => $request->cedula,
                        'celular' => $request->tnTelefono,
                        'email' => $request->tcCorreo,
                        'password' => bcrypt('defaultpassword123'),
                    ]);
                    
                    // ✅ INSERCIÓN MANUAL EN TABLA PIVOT PARA QR
                    \DB::table('role_users')->insert([
                        'user_id' => $user->id,
                        'role_id' => 1, // Cliente
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                Pago::create([
                    'id' => $nroPago,
                    'venta_id' => $nroTransaccion,
                    'fechapago' => now(),
                    'estado' => 1,
                    'metodopago' => 4, // QR
                ]);

                Venta::create([
                    'id' => $nroTransaccion,
                    'comprador_id' => $user->id, // 👈 Usar el usuario encontrado/creado
                    'vendedor_id' => 1, // ID fijo del vendedor (empresa)
                    'fecha' => now(),
                    'metodopago' => 4, // QR
                    'montototal' => $request->tnMonto,
                    'estado' => 1,
                ]);

                foreach ($laPedidoDetalle as $detalle) {
                    $total = isset($detalle['total']) 
                        ? $detalle['total'] 
                        : ($detalle['cantidad'] * ($detalle['precio_unitario'] ?? $detalle['precio'] ?? 0));

                    DetalleVenta::create([
                        'venta_id' => $nroTransaccion,
                        'licencia_id' => $detalle['id'],
                        'cantidad' => $detalle['cantidad'],
                        'total' => $total,
                    ]);
                }

                // ✅ VALIDACIÓN DE QR IMAGE
                $qrData = json_decode($laValues);
                if (!$qrData || !isset($qrData->qrImage)) {
                    Log::error('❌ Error: No se pudo obtener qrImage', [
                        'laValues' => $laValues
                    ]);
                    return response()->json([
                        'success' => false,
                        'message' => 'Error: No se pudo generar la imagen QR',
                        'qr_data' => $laValues
                    ], 500);
                }

                $laQrImage = "data:image/png;base64," . $qrData->qrImage;

                return response()->json([
                    'success' => true,
                    'qrImage' => $laQrImage,
                    'nroTransaccion' => $nroTransaccion,
                ]);

            } elseif ($request->tnTipoServicio == 2) {
                // ✅ VALIDACIÓN PARA TIGO MONEY
                if (!isset($laResult->values) || empty($laResult->values)) {
                    Log::error('❌ Error Tigo Money: No se recibió values en la respuesta');
                    return response()->json([
                        'success' => false,
                        'message' => 'Error: La API de Tigo Money no devolvió datos válidos',
                        'api_response' => $laResult
                    ], 500);
                }

                // Crear o buscar usuario
                $user = User::where('email', $request->tcCorreo)->first();
                if (!$user) {
                    $user = User::create([
                        'name' => $request->name,
                        'cedula' => $request->cedula,
                        'celular' => $request->tnTelefono,
                        'email' => $request->tcCorreo,
                        'password' => bcrypt('defaultpassword123'),
                    ]);
                    
                    // ✅ INSERCIÓN MANUAL EN TABLA PIVOT PARA TIGO MONEY
                    \DB::table('role_users')->insert([
                        'user_id' => $user->id,
                        'role_id' => 1, // Cliente
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                $venta = Venta::create([
                    'id' => $laResult->values,
                    'comprador_id' => $user->id, // 👈 Corregido: era user_id
                    'vendedor_id' => 1, // ID fijo del vendedor (empresa)
                    'fecha' => now(),
                    'metodopago' => 2, // Tigo Money
                    'montototal' => $request->tnMonto,
                    'estado' => 1,
                ]);

                foreach ($laPedidoDetalle as $detalle) {
                    $total = isset($detalle['total']) 
                        ? $detalle['total'] 
                        : ($detalle['cantidad'] * ($detalle['precio_unitario'] ?? $detalle['precio'] ?? 0));

                    DetalleVenta::create([
                        'venta_id' => $laResult->values,
                        'licencia_id' => $detalle['id'], // 👈 Corregido: era producto_id
                        'cantidad' => $detalle['cantidad'],
                        'total' => $total,
                    ]);
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Pago Tigo Money procesado correctamente',
                    'transaccion_id' => $laResult->values
                ]);
            }

        } catch (\Throwable $th) {
            Log::error('❌ Error en generarCobro:', [
                'message' => $th->getMessage(),
                'line' => $th->getLine(),
                'file' => $th->getFile(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor: ' . $th->getMessage(),
                'error_line' => $th->getLine()
            ], 500);
        }
    }

    // ✅ MÉTODO TEMPORAL PARA DEBUGGING - Agrega esto a tu GenerarCobroController
    public function debug(Request $request)
    {
        try {
            Log::info('🔍 Debug - Datos recibidos:', $request->all());
            
            // Verificar estructura de taPedidoDetalle
            if ($request->has('taPedidoDetalle')) {
                Log::info('📦 Estructura de taPedidoDetalle:', $request->taPedidoDetalle);
                foreach ($request->taPedidoDetalle as $index => $detalle) {
                    Log::info("📋 Detalle {$index}:", $detalle);
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Debug completado - revisa los logs',
                'data_received' => $request->all()
            ]);
            
        } catch (\Exception $e) {
            Log::error('❌ Error en debug:', [
                'message' => $e->getMessage(),
                'line' => $e->getLine()
            ]);
            
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // ✅ MÉTODO PARA PROBAR DIRECTAMENTE LA API QR
public function verComprobante($id)
{
    try {
        // Cargar la venta con su detalle
        $venta = Venta::with('detalleVenta')->find($id);

        if (!$venta) {
            return response()->json(['error' => 'Venta no encontrada'], 404);
        }

        // Obtener el usuario
        $usuario = User::find($venta->id_usuario);

        // Obtener el pago (opcional)
        $pago = Pago::where('id_venta', $venta->id)->first();

        // Preparar respuesta
        return response()->json([
            'venta' => $venta,
            'detalle' => $venta->detalleVenta,  // Aquí accedemos a la relación
            'usuario' => $usuario,
            'pago' => $pago
        ]);
    } catch (\Exception $e) {
        Log::error('Error al obtener comprobante: ' . $e->getMessage());
        return response()->json(['error' => 'Error interno del servidor'], 500);
    }
}

}