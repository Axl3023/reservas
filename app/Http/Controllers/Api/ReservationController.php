<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Table;

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
    public function reservar(Request $request)
    {
        // Validar los datos que se envían en la solicitud
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:100',
            'hora' => 'required|date_format:H:i',
            'reserva_fecha' => 'required|date',
            'reserva_invitados' => 'required|integer|min:1',
            'mesa' => 'required|exists:tables,id',  // Validar que el ID de mesa exista
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error en la validación de los datos',
                'errors' => $validator->errors(),
                'status' => 400,
            ], 400);
        }
    
        // Crear la reserva sin pasar el employee_id
        $reservation = Reservation::create([
            'customer_name' => $request->nombre,
            'reservation_time' => $request->hora,
            'reservation_date' => $request->reserva_fecha,
            'guest_count' => $request->reserva_invitados,
            'table_id' => $request->mesa,
            'status' => 'pending', // Estado inicial
            // No se pasa employee_id
        ]);
    
        // Respuesta similar a la de Node.js
        return response()->json([
            'data' => 'RESERVA CREADA EXITOSAMENTE COLEGA'
        ]);
    }
    
    // En ReservationController.php

public function respuesta(Request $request)
{
    // Obtener los datos del request
    $data = $request->validate([
        'nombre' => 'required|string|max:100',
        'hora' => 'required|date_format:H:i',
        'reserva_fecha' => 'required|date',
        'reserva_invitados' => 'required|integer|min:1',
    ]);

    $reservasCoinciden = Reservation::where('reservation_date', $data['reserva_fecha'])
        ->where('reservation_time', $data['hora'])
        ->get();

    // Filtrar las mesas ocupadas en ese horario
    $mesasOcupadas = $reservasCoinciden->pluck('table_id')->toArray();

    // Buscar primera mesa disponible
    $mesaDisponible = Table::whereNotIn('id', $mesasOcupadas)
        ->where('capacity', '>=', $data['reserva_invitados'])
        ->first();

    if ($mesaDisponible) {
        return response()->json([
            'asignado' => true,
            'mesa' => $mesaDisponible->id
        ]);
    } else {
        return response()->json([
            'asignado' => false
        ]);
    }
}

public function respuestaVerdadero()
{
    return response('El cliente debe de confirmar la respuesta');
}
// En ReservationController.php

public function respuestaFalso(Request $request)
{
    $data = $request->validate([
        'hora' => 'required|date_format:H:i',
        'reserva_fecha' => 'required|date',
        'reserva_invitados' => 'required|integer|min:1',
    ]);

    $horaBase = (int)explode(":", $data['hora'])[0];

    $horaNegativa = null;
    $horaPositiva = null;

    // Buscar hacia atrás
    for ($i = 1; $i <= 23; $i++) {
        $nuevaHora = $horaBase - $i;
        if ($nuevaHora < 0) break;
        $disponible = $this->verificarDisponibilidad($nuevaHora, $data['reserva_fecha'], $data['reserva_invitados']);
        if ($disponible) {
            $horaNegativa = $disponible;
            break;
        }
    }

    // Buscar hacia adelante
    for ($i = 1; $i <= 23; $i++) {
        $nuevaHora = $horaBase + $i;
        if ($nuevaHora > 23) break;
        $disponible = $this->verificarDisponibilidad($nuevaHora, $data['reserva_fecha'], $data['reserva_invitados']);
        if ($disponible) {
            $horaPositiva = $disponible;
            break;
        }
    }

    $sugerencias = [];

    if ($horaNegativa) {
        $sugerencias[] = [
            'hora' => $horaNegativa,
            'reserva_fecha' => $data['reserva_fecha']
        ];
    }

    if ($horaPositiva) {
        $sugerencias[] = [
            'hora' => $horaPositiva,
            'reserva_fecha' => $data['reserva_fecha']
        ];
    }

    return response()->json(['sugerencias' => $sugerencias]);
}

private function verificarDisponibilidad($hora, $fecha, $invitados)
{
    $horaStr = str_pad($hora, 2, '0', STR_PAD_LEFT) . ":00";

    // Filtrar reservas por fecha y hora exactas
    $reservasEnHora = Reservation::where('reservation_date', $fecha)
        ->where('reservation_time', $horaStr)
        ->get();

    // Filtrar las mesas ocupadas en ese horario
    $mesasOcupadas = $reservasEnHora->pluck('table_id')->toArray();

    // Buscar mesas disponibles
    $mesasDisponibles = Table::whereNotIn('id', $mesasOcupadas)
        ->where('capacity', '>=', $invitados)
        ->get();

    return $mesasDisponibles->isEmpty() ? null : $horaStr;
}


}
