<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Factura {{ $factura->numero_factura }}</title>
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
        }
        body {
            margin: 0;
            padding: 24px;
            color: #1e293b;
            font-size: 12px;
            line-height: 1.5;
        }
        .header {
            width: 100%;
            margin-bottom: 24px;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 16px;
        }
        .header table {
            width: 100%;
            border-collapse: collapse;
        }
        .company-title {
            font-size: 22px;
            font-weight: bold;
            color: #ea580c;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 0;
        }
        .company-subtitle {
            font-size: 11px;
            color: #64748b;
            margin-top: 4px;
        }
        .invoice-box {
            text-align: right;
        }
        .invoice-title {
            font-size: 18px;
            font-weight: bold;
            color: #0f172a;
            margin: 0;
        }
        .invoice-number {
            font-size: 16px;
            font-weight: bold;
            color: #ea580c;
            font-family: monospace;
        }
        .badge {
            display: inline-block;
            padding: 4px 10px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            border-radius: 9999px;
            margin-top: 4px;
        }
        .badge-pendiente { background-color: #fef3c7; color: #b45309; }
        .badge-pagada { background-color: #dcfce7; color: #15803d; }
        .badge-anulada { background-color: #fee2e2; color: #b91c1c; }

        .info-section {
            width: 100%;
            margin-bottom: 20px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
        }
        .info-card {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px 14px;
            vertical-align: top;
        }
        .info-card-title {
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            color: #475569;
            margin-bottom: 8px;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 4px;
        }
        .info-row {
            margin-bottom: 4px;
        }
        .info-label {
            color: #64748b;
            font-size: 11px;
        }
        .info-value {
            font-weight: 600;
            color: #0f172a;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .items-table th {
            background-color: #f1f5f9;
            color: #334155;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 8px 10px;
            border-bottom: 2px solid #cbd5e1;
            text-align: left;
        }
        .items-table td {
            padding: 8px 10px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 11px;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }

        .totals-table {
            width: 280px;
            float: right;
            border-collapse: collapse;
            margin-bottom: 24px;
        }
        .totals-table td {
            padding: 6px 10px;
            font-size: 11px;
        }
        .totals-table .total-row td {
            border-top: 2px solid #cbd5e1;
            font-size: 14px;
            font-weight: bold;
            color: #0f172a;
            padding-top: 8px;
        }
        .total-amount {
            color: #ea580c;
        }

        .clear {
            clear: both;
        }

        .payments-section {
            margin-top: 15px;
            margin-bottom: 20px;
        }
        .payments-title {
            font-size: 12px;
            font-weight: bold;
            color: #334155;
            margin-bottom: 6px;
        }

        .footer {
            margin-top: 30px;
            border-top: 1px solid #e2e8f0;
            padding-top: 12px;
            text-align: center;
            font-size: 10px;
            color: #94a3b8;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <div class="header">
        <table>
            <tr>
                <td style="width: 60%;">
                    <h1 class="company-title">TALLERPRO</h1>
                    <div class="company-subtitle">
                        Servicio Especializado de Latonería, Pintura y Mecánica General<br>
                        NIT: 900.123.456-7 | PBX: (601) 765-4321<br>
                        Calle Principal # 45-67, Bogotá D.C.<br>
                        contacto@tallerpro.com | www.tallerpro.com
                    </div>
                </td>
                <td style="width: 40%;" class="invoice-box">
                    <p class="invoice-title">FACTURA DE VENTA</p>
                    <p class="invoice-number">{{ $factura->numero_factura }}</p>
                    <p style="margin: 2px 0; color: #64748b; font-size: 11px;">
                        Fecha de Emisión: <strong>{{ $factura->fecha->format('d/m/Y') }}</strong>
                    </p>
                    @php
                        $estadoClass = match($factura->estado) {
                            'Pagada' => 'badge-pagada',
                            'Anulada' => 'badge-anulada',
                            default => 'badge-pendiente'
                        };
                    @endphp
                    <span class="badge {{ $estadoClass }}">{{ $factura->estado }}</span>
                </td>
            </tr>
        </table>
    </div>

    <!-- Client & Vehicle Info -->
    <div class="info-section">
        <table class="info-table">
            <tr>
                <td style="width: 49%;" class="info-card">
                    <div class="info-card-title">Datos del Cliente</div>
                    <div class="info-row">
                        <span class="info-label">Cliente:</span>
                        <span class="info-value">{{ $factura->cliente->usuario->nombre ?? 'N/A' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Documento / NIT:</span>
                        <span class="info-value">{{ $factura->cliente->documento ?? 'No registrado' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Correo:</span>
                        <span class="info-value">{{ $factura->cliente->usuario->correo ?? 'N/A' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Teléfono:</span>
                        <span class="info-value">{{ $factura->cliente->usuario->telefono ?? 'N/A' }}</span>
                    </div>
                    @if($factura->cliente->direccion)
                    <div class="info-row">
                        <span class="info-label">Dirección:</span>
                        <span class="info-value">{{ $factura->cliente->direccion }}</span>
                    </div>
                    @endif
                </td>
                <td style="width: 2%;"></td>
                <td style="width: 49%;" class="info-card">
                    <div class="info-card-title">Información del Servicio / Vehículo</div>
                    @php
                        $vehiculo = $factura->orden->vehiculo ?? ($factura->cotizacion->vehiculo ?? null);
                    @endphp
                    @if($vehiculo)
                        <div class="info-row">
                            <span class="info-label">Placa:</span>
                            <span class="info-value" style="font-family: monospace; font-size: 13px;">{{ $vehiculo->placa }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Vehículo:</span>
                            <span class="info-value">{{ $vehiculo->marca }} {{ $vehiculo->modelo }} ({{ $vehiculo->anio ?? 'N/A' }})</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Color / Tipo:</span>
                            <span class="info-value">{{ $vehiculo->color ?? 'N/A' }} {{ $vehiculo->tipo ? '- ' . $vehiculo->tipo : '' }}</span>
                        </div>
                    @endif
                    @if($factura->orden)
                        <div class="info-row">
                            <span class="info-label">Orden de Trabajo:</span>
                            <span class="info-value">#{{ $factura->orden->id_orden }}</span>
                        </div>
                    @endif
                    @if($factura->cotizacion)
                        <div class="info-row">
                            <span class="info-label">Cotización Asociada:</span>
                            <span class="info-value">#{{ $factura->cotizacion->id_cotizacion }}</span>
                        </div>
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <!-- Items Table -->
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 45%;">Descripción del Concepto / Ítem</th>
                <th style="width: 15%;" class="text-center">Tipo</th>
                <th style="width: 10%;" class="text-center">Cant.</th>
                <th style="width: 12%;" class="text-right">Vr. Unitario</th>
                <th style="width: 13%;" class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @php $itemIndex = 1; @endphp

            {{-- Si viene de Orden de Trabajo --}}
            @if($factura->orden)
                @foreach($factura->orden->servicios as $s)
                    <tr>
                        <td class="text-center">{{ $itemIndex++ }}</td>
                        <td>
                            <strong>{{ $s->servicio->nombre ?? 'Servicio de Taller' }}</strong>
                            @if($s->servicio && $s->servicio->descripcion)
                                <br><span style="color: #64748b; font-size: 10px;">{{ $s->servicio->descripcion }}</span>
                            @endif
                        </td>
                        <td class="text-center"><span style="color: #0284c7; font-weight: 600;">Servicio</span></td>
                        <td class="text-center">{{ $s->cantidad }}</td>
                        <td class="text-right">${{ number_format($s->precio, 2) }}</td>
                        <td class="text-right">${{ number_format($s->subtotal, 2) }}</td>
                    </tr>
                @endforeach

                @foreach($factura->orden->productos as $p)
                    <tr>
                        <td class="text-center">{{ $itemIndex++ }}</td>
                        <td>
                            <strong>{{ $p->producto->nombre ?? 'Repuesto / Producto' }}</strong>
                            @if($p->producto && $p->producto->marca)
                                <span style="color: #64748b; font-size: 10px;">({{ $p->producto->marca }})</span>
                            @endif
                        </td>
                        <td class="text-center"><span style="color: #16a34a; font-weight: 600;">Repuesto</span></td>
                        <td class="text-center">{{ $p->cantidad }}</td>
                        <td class="text-right">${{ number_format($p->precio_unitario, 2) }}</td>
                        <td class="text-right">${{ number_format($p->subtotal, 2) }}</td>
                    </tr>
                @endforeach

                @foreach($factura->orden->consumoMateriales as $m)
                    <tr>
                        <td class="text-center">{{ $itemIndex++ }}</td>
                        <td>
                            <strong>{{ $m->producto->nombre ?? 'Material Consumido' }}</strong>
                        </td>
                        <td class="text-center"><span style="color: #d97706; font-weight: 600;">Material</span></td>
                        <td class="text-center">{{ $m->cantidad }}</td>
                        <td class="text-right">${{ number_format($m->precio_unitario, 2) }}</td>
                        <td class="text-right">${{ number_format($m->subtotal, 2) }}</td>
                    </tr>
                @endforeach

            {{-- Si viene de Cotización --}}
            @elseif($factura->cotizacion)
                @foreach($factura->cotizacion->servicios as $s)
                    <tr>
                        <td class="text-center">{{ $itemIndex++ }}</td>
                        <td><strong>{{ $s->servicio->nombre ?? 'Servicio' }}</strong></td>
                        <td class="text-center"><span style="color: #0284c7; font-weight: 600;">Servicio</span></td>
                        <td class="text-center">{{ $s->cantidad }}</td>
                        <td class="text-right">${{ number_format($s->precio, 2) }}</td>
                        <td class="text-right">${{ number_format($s->subtotal, 2) }}</td>
                    </tr>
                @endforeach

                @foreach($factura->cotizacion->productos as $p)
                    <tr>
                        <td class="text-center">{{ $itemIndex++ }}</td>
                        <td><strong>{{ $p->producto->nombre ?? 'Producto' }}</strong></td>
                        <td class="text-center"><span style="color: #16a34a; font-weight: 600;">Repuesto</span></td>
                        <td class="text-center">{{ $p->cantidad }}</td>
                        <td class="text-right">${{ number_format($p->precio_unitario, 2) }}</td>
                        <td class="text-right">${{ number_format($p->subtotal, 2) }}</td>
                    </tr>
                @endforeach

            {{-- Ítem genérico de la factura si no tiene orden ni cotización vinculada con detalles --}}
            @else
                <tr>
                    <td class="text-center">1</td>
                    <td><strong>Servicios y Reparaciones de Taller</strong></td>
                    <td class="text-center">General</td>
                    <td class="text-center">1</td>
                    <td class="text-right">${{ number_format($factura->subtotal, 2) }}</td>
                    <td class="text-right">${{ number_format($factura->subtotal, 2) }}</td>
                </tr>
            @endif
        </tbody>
    </table>

    <!-- Totals -->
    <div>
        <table class="totals-table">
            <tr>
                <td class="info-label">Subtotal:</td>
                <td class="text-right font-semibold">${{ number_format($factura->subtotal, 2) }}</td>
            </tr>
            <tr>
                <td class="info-label">IVA (19%):</td>
                <td class="text-right font-semibold">${{ number_format($factura->impuesto, 2) }}</td>
            </tr>
            <tr class="total-row">
                <td>Total a Pagar:</td>
                <td class="text-right total-amount">${{ number_format($factura->total, 2) }}</td>
            </tr>
            @php
                $totalPagado = $factura->total_pagado;
                $saldo = $factura->saldo_pendiente;
            @endphp
            @if($totalPagado > 0)
            <tr>
                <td class="info-label" style="color: #16a34a;">Total Abonado:</td>
                <td class="text-right" style="color: #16a34a; font-weight: bold;">-${{ number_format($totalPagado, 2) }}</td>
            </tr>
            <tr style="border-top: 1px dashed #cbd5e1;">
                <td class="info-label" style="font-weight: bold; color: {{ $saldo > 0 ? '#b91c1c' : '#16a34a' }};">Saldo Pendiente:</td>
                <td class="text-right" style="font-weight: bold; color: {{ $saldo > 0 ? '#b91c1c' : '#16a34a' }};">${{ number_format($saldo, 2) }}</td>
            </tr>
            @endif
        </table>
    </div>
    <div class="clear"></div>

    <!-- Payments history if any -->
    @if($factura->pagos->isNotEmpty())
        <div class="payments-section">
            <div class="payments-title">Registro de Pagos y Abonos</div>
            <table class="items-table" style="margin-bottom: 10px;">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Método</th>
                        <th>Referencia</th>
                        <th class="text-right">Monto</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($factura->pagos as $pago)
                        <tr>
                            <td>{{ $pago->fecha_pago->format('d/m/Y') }}</td>
                            <td>{{ $pago->metodo_pago }}</td>
                            <td>{{ $pago->referencia ?? 'Sin ref.' }}</td>
                            <td class="text-right" style="color: #15803d; font-weight: bold;">${{ number_format($pago->monto, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <!-- Footer & Notes -->
    <div class="footer">
        <p style="margin: 0 0 4px 0;">Esta factura constituye título valor según la legislación vigente. Garantía de servicios: 30 días o 1.000 km.</p>
        <p style="margin: 0;">¡Gracias por confiar en TallerPro! Para consultas y soporte: soporte@tallerpro.com</p>
    </div>

</body>
</html>
