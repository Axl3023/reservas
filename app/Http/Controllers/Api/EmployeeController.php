<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::all();
        $data = [
            'employee' => $employees,
            'status' => 200,
        ];
        return response()->json($data,200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'email' => 'required|email|unique:employees,email',
            'phone' => 'nullable|string|max:20',
            'position' => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error en la validacion de datos',
                'errors' => $validator->errors(),
                'status' => 400,
            ], 400);
        }

        $employee = Employee::create($validator->validated());

        return response()->json([
            'message' => 'Empleado creado exitosamente',
            'employee' => $employee,
            'status' => 201,
        ], 201);
    }

    public function show($id)
    {
        $employee = Employee::find($id);

        if (!$employee) {
            $data = [
                'message' => 'Empleado no encontrado',
                'status' => 404,
            ];
            return response()->json($data, 404);
        }

        $data = [
            'employee' => $employee,
            'status' => 200,
        ];

        return response()->json($data, 200);
    }

    public function destroy($id)
    {
        $employee = Employee::find($id);

        if (!$employee) {
            $data = [
                'message' => 'Empleado no encontrado',
                'status' => 404,
            ];
            return response()->json($data, 404);
        }

        $employee->delete();

        $data = [
            'message' => 'Empleado eliminado exitosamente',
            'status' => 200,
        ];

        return response()->json($data, 200);
    }

    public function update(Request $request, $id)
{
    $employee = Employee::find($id);

    if (!$employee) {
        $data = [
            'message' => 'Empleado no encontrado',
            'status' => 404,
        ];
        return response()->json($data, 404);
    }

    $validator = Validator::make($request->all(), [
        'first_name' => 'required|string|max:100',
        'last_name' => 'nullable|string|max:100',
        'email' => 'required|email|unique:employees,email,' . $employee->id,
        'phone' => 'nullable|string|max:20',
        'position' => 'nullable|string|max:100',
    ]);

    if ($validator->fails()) {
        $data = [
            'message' => 'Error en la validacion de los datos',
            'errors' => $validator->errors(),
            'status' => 400,
        ];
        return response()->json($data, 400);
    }

    $employee->first_name = $request->first_name;
    $employee->last_name = $request->last_name;
    $employee->email = $request->email;
    $employee->phone = $request->phone;
    $employee->position = $request->position;
    $employee->save();

    $data = [
        'message' => 'Empleado actualizado exitosamente',
        'employee' => $employee,
        'status' => 200,
    ];

    return response()->json($data, 200);
}

public function updatePartial(Request $request, $id)
{
    $employee = Employee::find($id);

    if (!$employee) {
        return response()->json([
            'message' => 'Employee not found',
            'status' => 404,
        ], 404);
    }

    // Validación solo de los campos presentes
    $validator = Validator::make($request->all(), [
        'first_name' => 'sometimes|required|string|max:100',
        'last_name' => 'sometimes|nullable|string|max:100',
        'email' => 'sometimes|required|email|unique:employees,email,' . $employee->id,
        'phone' => 'sometimes|nullable|string|max:20',
        'position' => 'sometimes|nullable|string|max:100',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'message' => 'Error en la validacion de los datos',
            'errors' => $validator->errors(),
            'status' => 400,
        ], 400);
    }

    // Solo actualiza los campos enviados
    if ($request->has('first_name')) {
        $employee->first_name = $request->first_name;
    }

    if ($request->has('last_name')) {
        $employee->last_name = $request->last_name;
    }

    if ($request->has('email')) {
        $employee->email = $request->email;
    }

    if ($request->has('phone')) {
        $employee->phone = $request->phone;
    }

    if ($request->has('position')) {
        $employee->position = $request->position;
    }

    $employee->save();

    return response()->json([
        'message' => 'Empleado actualizado exitosamente (p)',
        'employee' => $employee,
        'status' => 200,
    ], 200);
}
}
