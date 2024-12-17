@component('mail::message')
# {{ $greeting }}

{{ $message }}

## Detalles de la reserva:
- Código: {{ $reserva->codigo }}
- Fecha: {{ $reserva->fecha }}
- Hora: {{ $reserva->hora }}
- Número de personas: {{ $reserva->num_personas }}
- Estado anterior: {{ $oldStatus }}
- Estado actual: {{ $reserva->status }}

@if($reserva->status === 'confirmed')
@component('mail::button', ['url' => config('app.url')])
Ver mi reserva
@endcomponent
@endif

Gracias,<br>
{{ config('app.name') }}
@endcomponent
