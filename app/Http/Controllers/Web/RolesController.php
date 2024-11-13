<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\RolesRequest;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;


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

public function update(RolesRequest $request, $id)
{
    $data = $request->validated();

    DB::beginTransaction();

    try {

        $role = Sentinel::getRoleRepository()->find($id);


        if (!$role) {
            return response()->json(['success' => false, 'message' => 'Role not found'], 404);
        }


        $role->slug = $data['slug'];
        $role->name = $data['name'];
        $role->permissions = $data['permissions'];
        $role->save();

        DB::commit();

        return response()->json(['success' => true, 'data' => $role], 200);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['success' => false, 'message' => 'Fallo la actualizacion del rol', 'error' => $e->getMessage()], 500);
    }
}

    public function store(RolesRequest $request)

    {
        $data = $request->validated();

        DB::beginTransaction();

        try {

            $roleData = [
                'slug' => $data['slug'],
                'name' => $data['name'],
            ];


            $role = Sentinel::getRoleRepository()->create($roleData);



            DB::commit();

            return response()->json(['success' => true, 'data' => $role], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Role creation failed', 'error' => $e->getMessage()], 500);
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





