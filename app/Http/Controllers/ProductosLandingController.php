<?php

namespace App\Http\Controllers;

use App\Models\Licencia;
use App\Models\PageView;
use App\Models\Producto;
use App\Models\User;
use App\Models\Venta;
use App\Traits\UserPermissions;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
class ProductosLandingController extends Controller
{

    public function index()
    {
        $pageName = 'Seccion Productos';
        $PageView = $this->pageView($pageName);

        $allProductos = Licencia::with(['marca','serie', 'categoria'])->get();

        $user = null;
        if (Auth::check()) {
            $email = Auth::user()->email;
            $user = User::where('email', $email)->first();
        }

        // Obtener carrito de sesión si existe
        $carrito = session()->get('carrito', []);

        // Formatear carrito
        $carrito = collect($carrito)->map(function ($producto) {
            return [
                'id' => $producto['id'],
                'nombre' => $producto['nombre'],
                'cantidad' => $producto['cantidad'],
                'precio' => $producto['precio'],
                'cantidadDisponible' => $producto['cantidadDisponible'] ?? 7,
                'isOferta' => $producto['isOferta'] ?? 0,
                'descuento' => $producto['descuento'] ?? 0,
            ];
        });

        return Inertia::render('LandingPage/Components/ProductosSeccion', [
            'productos' => $allProductos,
            'pageview' => $PageView,
            'user' => $user,
            'carrito' => $carrito, // 👈 Aquí lo pasas a Vue
        ]);
    }




    public function details($producto_id)
    {
        $producto = Licencia::find($producto_id);
        $producto->averageRating = $producto->averageRating();
        if (!$producto) {
            abort(404, 'Producto no encontrado');
        }
        $pageName = "Producto: " . $producto->nombre;
        $PageView = $this->pageView($pageName);

        $user = null;
        if (Auth::check()) {
            $email = Auth::user()->email;
            $user = User::where('email', $email)->first();
        }
        return Inertia::render('LandingPage/Components/ProductoDetails', [
            'producto' => $producto,
            'pageview' => $PageView,
            'user' =>$user
        ]);
    }

    public function misproductosdetails($producto_id)
    {
        $producto = Licencia::find($producto_id);
        if (!$producto) {
            abort(404, 'Producto no encontrado');
        }
        $pageName = "Producto: " . $producto->nombre;
        $PageView = $this->pageView($pageName);
        $user = null;
        if (Auth::check()) {
            $email = Auth::user()->email;
            $user = User::where('email', $email)->first();
        }
        return Inertia::render('LandingPage/Components/MiProductoDetails', [
            'producto' => $producto,
            'pageview' => $PageView,
            'user' =>$user
        ]);
    }



        public function pageView($pageName){
            $PageView = PageView::where('page_name', $pageName)->first();
            if (!$PageView) {
                PageView::create([
                    'page_name' => $pageName,
                    'visits' => 1,
                ]);
            } else {
                $PageView->increment('visits');
            }
            $PageView = PageView::where('page_name', $pageName)->first();
            return $PageView;
        }


        public function search(Request $request)
        {
            dd($request);
            $query = $request->input('query');
            $productos = Licencia::where('nombre', 'like', '%' . $query . '%')
                                 ->orWhereHas('categoria', function ($query) use ($request) {
                                     $query->where('nombre', 'like', '%' . $request->query . '%');
                                 })
                                 ->with('categoria')
                                 ->get();

            return response()->json([
                'productos' => $productos
            ]);
        }

        public function misproductos()
        {
            // Obtener el correo electrónico del usuario autenticado
            $email = Auth::user()->email;

            // Buscar el usuario por el email para obtener su ID
            $user = User::where('email', $email)->first();

            // Si no se encuentra el usuario, redirigir o manejar el error
            if (!$user) {
                // Puedes redirigir o mostrar un error
                return redirect()->route('login');
            }

            $ventas = $user->ventas()->with('detalleventa')->get();
              // dd($user);
            $productosCompradosIds = [];
            foreach ($ventas as $venta) {
                foreach ($venta->detalleventa as $detalle) {
                    // Agregar los IDs de los cursos comprados al array
                    $productosCompradosIds[] = $detalle->producto_id;
                }
            }
            $productos = LIcencia::whereIn('id', $productosCompradosIds)

            ->get(); // Paginar los resultados
            // Definir el nombre de la página
            $pageName = 'MisProductos-'.$email;
            $PageView = $this->pageView($pageName);

            // Devolver los productos comprados en la vista
            return Inertia::render('LandingPage/Components/MiProductosSeccion', [
                'productos' => $productos,
                'pageview' => $PageView,
                'user' => $user
            ]);
        }



}
