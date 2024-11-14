<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HuespedesController extends Controller
{
    public function index(Request $request)
    {
        $orderBy = $request->input('sortBy', 'huespedes.id');
        $order = $request->input('order', 'asc');
        $query = $request->input('query', '');


        $queryBuilder = DB::table('huespedes')
            ->select('huespedes.*');


        if ($query) {
            $queryBuilder->where(function ($q) use ($query) {
                $q->where('huespedes.nombre', 'like', "%{$query}%")
                    ->orWhere('huespedes.apellido_paterno', 'like', "%{$query}%")
                    ->orWhere('huespedes.telefono', 'like', "%{$query}%");
            });
        }


        $huespedes = $queryBuilder->orderBy($orderBy, $order)->get();

        return response()->json(['success' => true, 'data' => $huespedes]);



    }
}



