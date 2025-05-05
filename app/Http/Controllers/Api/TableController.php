<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TableController extends Controller
{
    public function index()
    {
        $tables = Table::all();
        return response()->json([
            'tables' => $tables,
            'status' => 200,
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'table_number' => 'required|string|unique:tables,table_number|max:10',
            'capacity' => 'required|integer|min:1',
            'location' => 'nullable|string|max:100',
            'status' => 'required|in:available,reserved,occupied',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error en la validación de los datos',
                'errors' => $validator->errors(),
                'status' => 400,
            ], 400);
        }

        $table = Table::create($validator->validated());

        return response()->json([
            'message' => 'Mesa creada exitosamente',
            'table' => $table,
            'status' => 201,
        ], 201);
    }

    public function show($id)
    {
        $table = Table::find($id);

        if (!$table) {
            return response()->json([
                'message' => 'Mesa no encontrada',
                'status' => 404,
            ], 404);
        }

        return response()->json([
            'table' => $table,
            'status' => 200,
        ], 200);
    }

    public function destroy($id)
    {
        $table = Table::find($id);

        if (!$table) {
            return response()->json([
                'message' => 'Mesa no encontrada',
                'status' => 404,
            ], 404);
        }

        $table->delete();

        return response()->json([
            'message' => 'Mesa eliminada exitosamente',
            'status' => 200,
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $table = Table::find($id);

        if (!$table) {
            return response()->json([
                'message' => 'Mesa no encontrada',
                'status' => 404,
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'table_number' => 'required|string|max:10|unique:tables,table_number,' . $table->id,
            'capacity' => 'required|integer|min:1',
            'location' => 'nullable|string|max:100',
            'status' => 'required|in:available,reserved,occupied',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error en la validación de los datos',
                'errors' => $validator->errors(),
                'status' => 400,
            ], 400);
        }

        $table->update($validator->validated());

        return response()->json([
            'message' => 'Mesa actualizada exitosamente',
            'table' => $table,
            'status' => 200,
        ], 200);
    }

    public function updatePartial(Request $request, $id)
    {
        $table = Table::find($id);

        if (!$table) {
            return response()->json([
                'message' => 'Mesa no encontrada',
                'status' => 404,
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'table_number' => 'sometimes|required|string|max:10|unique:tables,table_number,' . $table->id,
            'capacity' => 'sometimes|required|integer|min:1',
            'location' => 'sometimes|nullable|string|max:100',
            'status' => 'sometimes|required|in:available,reserved,occupied',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error en la validación de los datos',
                'errors' => $validator->errors(),
                'status' => 400,
            ], 400);
        }

        if ($request->has('table_number')) $table->table_number = $request->table_number;
        if ($request->has('capacity')) $table->capacity = $request->capacity;
        if ($request->has('location')) $table->location = $request->location;
        if ($request->has('status')) $table->status = $request->status;

        $table->save();

        return response()->json([
            'message' => 'Mesa actualizada exitosamente (p)',
            'table' => $table,
            'status' => 200,
        ], 200);
    }
}
