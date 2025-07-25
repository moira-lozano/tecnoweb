<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Reporte;
use App\Models\Venta;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Mail\ReporteDeVentas;
use App\Models\Licencia;
use App\Traits\UserPermissions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


class ReporteController extends Controller
{

    /**
     * Display a listing of the resource.
     */
  public function index()
{
    // Contar el número total de licencias
    $totalLicencias = Licencia::count();

    // Sumar el monto total de ventas
    $ventasTotales = Venta::sum('montototal');

    // Obtener las ventas agrupadas por mes y sumar el monto total por mes
  $ventasPorMes = Venta::selectRaw("EXTRACT(MONTH FROM created_at) as mes, SUM(montototal) as monto_total")
    ->groupBy('mes')
    ->orderBy('mes')
    ->get();


    // Obtener las licencias vendidas y su cantidad
    $licenciasVendidas = Licencia::select('licencias.nombre', DB::raw('SUM(detalle_ventas.cantidad) as cantidad'))
        ->join('detalle_ventas', 'licencias.id', '=', 'detalle_ventas.licencia_id')
        ->groupBy('licencias.id', 'licencias.nombre')
        ->get();

    return Inertia::render('GestionarReportesEstadisticas/Reportes/index', [
        'totalProductos' => $totalLicencias,
        'ventasTotales' => $ventasTotales,
        'ventasPorMes' => $ventasPorMes,
        'licenciasVendidas' => $licenciasVendidas,

    ]);
}




    public function sendReport(Request $request)
    {
        // Validar las entradas
        $request->validate([
            'destinatario' => 'required|email',
            'asunto' => 'required|string|max:255',
            'mensaje' => 'required|string',
        ]);

        $mail = new PHPMailer(true);

        $destinatario = $request->input('destinatario');
        $asunto = $request->input('asunto');
        $mensaje = $request->input('mensaje');

        try {
            // Configuración del servidor SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Cambia esto por tu servidor SMTP
            $mail->SMTPAuth = true;   // Activar autenticación SMTP
            $mail->Username = 'bclintonxd@gmail.com';  // Tu nombre de usuario SMTP
            $mail->Password = 'iqgxxfiysfbcksak';              // Tu contraseña SMTP
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;  // Usar TLS para una conexión segura
            $mail->Port = 587;  // Puerto para conexiones seguras (587 recomendado)

            // Datos del remitente
            $mail->setFrom('bclintonxd@gmail.com', 'moira');

            // Destinatarios
            $mail->addAddress($destinatario);

            // Contenido del correo
            $mail->isHTML(true);  // Correo en formato HTML
            $mail->Subject = $asunto;
            $mail->Body    = $mensaje;
            $mail->AltBody = strip_tags($mensaje);  // Texto plano como alternativa para clientes que no soportan HTML

            // Enviar el correo
            if ($mail->send()) {
                return redirect()->route('admin.reportes');
               // return response()->json(['message' => 'El correo ha sido enviado correctamente.']);
            }
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al enviar el correo: ' . $mail->ErrorInfo], 500);
        }
    }




}
