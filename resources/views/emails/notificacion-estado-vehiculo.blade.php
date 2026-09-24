@component('mail::message')
# {{ $notificacion->titulo }}

Hola {{ $cliente->nombre }},

Te informamos que tu vehículo ha tenido un cambio de estado:

@component('mail::panel')
**Vehículo:** {{ $vehiculo->placa }} ({{ $vehiculo->marca }} {{ $vehiculo->modelo }} {{ $vehiculo->anio }})

**Estado Anterior:** {{ $notificacion->estado_anterior }}  
**Estado Actual:** {{ $notificacion->estado_nuevo }}

**Detalles:** {{ $notificacion->descripcion }}
@endcomponent

@if($orden)
**Orden de Trabajo:** #{{ $orden->id_orden }}  
**Fecha Ingreso:** {{ $orden->fecha_ingreso->format('d/m/Y') }}
@endif

Puedes ver más detalles y el progreso completo de tu vehículo en tu panel de cliente:

@component('mail::button', ['url' => route('cliente.notificaciones')])
Ver Mis Notificaciones
@endcomponent

@component('mail::button', ['url' => route('cliente.vehiculos.show', $vehiculo->id_vehiculo)])
Ver Vehículo
@endcomponent

---

Si tienes preguntas sobre el estado de tu vehículo, no dudes en contactarnos.

Gracias por confiar en nosotros,  
**El Equipo del Taller**

---

*Este es un mensaje automático. Por favor, no respondas a este correo.*

@endcomponent
