<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\RolesRequest;
use App\Models\Role;
use Cartalyst\Sentinel\Sentinel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class RolesController extends Controller
{
    public function index(Request $request)
    {
        $orderBy = $request->input('sortBy', 'id');
        $order = $request->input('order', 'asc');
        $deleted = $request->input('deleted', 0);

        $queryBuilder = $deleted ? Role::onlyTrashed() : Role::withoutTrashed();

        if ($query = $request->input('query', false)) {
            $queryBuilder->where(function ($q) use ($query) {
                $q->where('slug', 'like', "%{$query}%")
                    ->orWhere('name', 'like', "%{$query}%");
            });
        }


        $roles = $queryBuilder
            ->orderBy($orderBy, $order)
            ->get(['slug', 'name', 'permissions']);

        return response()->success($roles);
    }

    public function show($id)
    {
        $role = Role::withTrashed()->find($id);

        if (!$role) {
            return response()->json(['success' => false, 'message' => 'Role not found'], 404);
        }

        return response()->json(['data' => $role]);
    }


    public function store(RolesRequest $request)
    {
        $data = $request->validated();
        try {

            $roleData = [
                'slug' => $data['slug'],
                'name' => $data['name'],
                'permissions' => $data['permissions']
            ];


            $role = Sentinel::registerAndActivate($roleData);


            DB::commit();

            return response()->success($role);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->unprocessable('Error', ['Error al crear el rol']);
        }
    }

    public function destroy($id)
    {
        if ($id == Auth::id()) {
            return response()->unprocessable('Error', ['No es posible eliminar su propio usuario.']);
        }

        $role = Role::withTrashed()->findOrfail($id);
        if ($role->deleted_at) {
            $role->restore();
        } else {
            $role->delete();
        }
        return response()->success(['result' => 'ok']);
    }


}





