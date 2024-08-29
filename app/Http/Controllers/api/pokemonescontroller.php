<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Pokemones;
use Illuminate\Support\Facades\Validator;

class PokemonesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $rows = (int)$request->input('rows', 3);
        $page = 1 + (int)$request->input('page', 0);

        \Illuminate\Pagination\Paginator::currentPageResolver(function() use ($page) {
            return $page;
        });

        $pokemones = Pokemones::paginate($rows);
        return response()->json([
            'estatus' => 1,
            'data' => $pokemones->items(),
            'total' => $pokemones->total()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'tipo' => 'required|string|max:255',
            'url_imagen' => 'required|string|max:255',
            'hp' => 'required|integer',
            'defensa' => 'required|integer',
            'ataque' => 'required|integer',
            'rapidez' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'estatus' => 0,
                'mensaje' => $validator->errors()
            ]);
        }

        /* Asignacion del id_user automáticamente del usuario autenticado */
        $data = $request->all();
        $data['id_user'] = $request->user()->id;

        /* Crea el el nuevo Pokémon */
        $pokemon = Pokemones::create($data);

        return response()->json([
            'estatus' => 1,
            'mensaje' => 'Pokémon creado exitosamente',
            'data' => $pokemon
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $pokemon = Pokemones::find($id);

        if (!$pokemon) {
            return response()->json([
                'estatus' => 0,
                'mensaje' => 'Pokémon no encontrado'
            ]);
        }

        return response()->json([
            'estatus' => 1,
            'data' => $pokemon
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $pokemon = Pokemones::find($id);

        if (!$pokemon) {
            return response()->json([
                'estatus' => 0,
                'mensaje' => 'Pokémon no encontrado'
            ]);
        }

        /* Verificacion para ver si el usuario autenticado es el creador del Pokémon */
        if ($pokemon->id_user !== $request->user()->id) {
            return response()->json([
                'estatus' => 0,
                'mensaje' => 'No tienes permiso para actualizar este Pokémon'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'nombre' => 'sometimes|required|string|max:255',
            'tipo' => 'sometimes|required|string|max:255',
            'url_imagen' => 'required|string|max:255',
            'hp' => 'sometimes|required|integer',
            'defensa' => 'sometimes|required|integer',
            'ataque' => 'sometimes|required|integer',
            'rapidez' => 'sometimes|required|integer',
            'id_user' => 'sometimes|required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'estatus' => 0,
                'mensaje' => $validator->errors()
            ]);
        }

        $pokemon->update($request->all());

        return response()->json([
            'estatus' => 1,
            'mensaje' => 'Pokémon actualizado exitosamente',
            'data' => $pokemon
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $pokemon = Pokemones::find($id);

        if (!$pokemon) {
            return response()->json([
                'estatus' => 0,
                'mensaje' => 'Pokémon no encontrado'
            ]);
        }

        /* Verificacion para ver si el usuario autenticado es el creador del Pokémon */
        if ($pokemon->id_user !== request()->user()->id) {
            return response()->json([
                'estatus' => 0,
                'mensaje' => 'No tienes permiso para eliminar este Pokémon'
            ]);
        }

        $pokemon->delete();

        return response()->json([
            'estatus' => 1,
            'mensaje' => 'Pokémon eliminado exitosamente'
        ]);
    }
}
