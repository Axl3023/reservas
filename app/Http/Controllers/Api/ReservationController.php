<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = Reservation::with(['table', 'employee'])->get();
        return response()->json([
            'reservations' => $reservations,
            'status' => 200,
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_name' => 'required|string|max:100',
            'customer_phone' => 'required|string|max:20',
            'customer_email' => 'nullable|email',
            'reservation_date' => 'required|date',
            'reservation_time' => 'required|date_format:H:i',
            'guest_count' => 'required|integer|min:1',
            'table_id' => 'required|exists:tables,id',
            'employee_id' => 'required|exists:employees,id',
            'status' => 'in:pending,confirmed,cancelled,completed',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error en la validación de los datos',
                'errors' => $validator->errors(),
                'status' => 400,
            ], 400);
        }

        $reservation = Reservation::create($validator->validated());

        return response()->json([
            'message' => 'Reserva creada exitosamente',
            'reservation' => $reservation,
            'status' => 201,
        ], 201);
    }

    public function show($id)
    {
        $reservation = Reservation::with(['table', 'employee'])->find($id);

        if (!$reservation) {
            return response()->json([
                'message' => 'Reserva no encontrada',
                'status' => 404,
            ], 404);
        }

        return response()->json([
            'reservation' => $reservation,
            'status' => 200,
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $reservation = Reservation::find($id);

        if (!$reservation) {
            return response()->json([
                'message' => 'Reserva no encontrada',
                'status' => 404,
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'customer_name' => 'required|string|max:100',
            'customer_phone' => 'required|string|max:20',
            'customer_email' => 'nullable|email',
            'reservation_date' => 'required|date',
            'reservation_time' => 'required|date_format:H:i',
            'guest_count' => 'required|integer|min:1',
            'table_id' => 'required|exists:tables,id',
            'employee_id' => 'required|exists:employees,id',
            'status' => 'required|in:pending,confirmed,cancelled,completed',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error en la validación de los datos',
                'errors' => $validator->errors(),
                'status' => 400,
            ], 400);
        }

        $reservation->update($validator->validated());

        return response()->json([
            'message' => 'Reserva actualizada exitosamente',
            'reservation' => $reservation,
            'status' => 200,
        ], 200);
    }

    public function updatePartial(Request $request, $id)
    {
        $reservation = Reservation::find($id);

        if (!$reservation) {
            return response()->json([
                'message' => 'Reserva no encontrada',
                'status' => 404,
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'customer_name' => 'sometimes|required|string|max:100',
            'customer_phone' => 'sometimes|required|string|max:20',
            'customer_email' => 'sometimes|nullable|email',
            'reservation_date' => 'sometimes|required|date',
            'reservation_time' => 'sometimes|required|date_format:H:i',
            'guest_count' => 'sometimes|required|integer|min:1',
            'table_id' => 'sometimes|nullable|exists:tables,id',
            'employee_id' => 'sometimes|nullable|exists:employees,id',
            'status' => 'sometimes|required|in:pending,confirmed,cancelled,completed',
            'notes' => 'sometimes|nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error en la validación de los datos',
                'errors' => $validator->errors(),
                'status' => 400,
            ], 400);
        }

        $reservation->fill($validator->validated())->save();

        return response()->json([
            'message' => 'Reserva actualizada exitosamente (p)',
            'reservation' => $reservation,
            'status' => 200,
        ], 200);
    }

    public function destroy($id)
    {
        $reservation = Reservation::find($id);

        if (!$reservation) {
            return response()->json([
                'message' => 'Reserva no encontrada',
                'status' => 404,
            ], 404);
        }

        $reservation->delete();

        return response()->json([
            'message' => 'Reserva eliminada exitosamente',
            'status' => 200,
        ], 200);
    }
}
