<?php

namespace App\Services;

/**
 * Servicio PDF liviano usando la vista HTML del navegador.
 * Las vistas con @section('pdf') se renderizan como HTML imprimible.
 * No requiere dependencias externas.
 */
class PdfService
{
    /**
     * Retorna una response con headers para descarga del HTML como PDF.
     * El navegador lo imprime/descarga al abrir la URL con ?pdf=1
     */
    public static function streamView(string $view, array $data = [], string $filename = 'documento.pdf'): \Illuminate\Http\Response
    {
        $html = view($view, $data)->render();

        return response($html, 200, [
            'Content-Type'        => 'text/html; charset=UTF-8',
            'X-Suggested-Filename'=> $filename,
        ]);
    }
}
