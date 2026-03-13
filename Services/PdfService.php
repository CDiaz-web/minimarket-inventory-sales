<?php

namespace Services;

use Dompdf\Dompdf;
use Dompdf\Options;
// portrait = vertical
// landscape = horizontal

class PdfService {

    public static function generar($html, $nombreArchivo = 'reporte.pdf',$orientacion = 'portrait')
    {
        if (ob_get_length()) {
            ob_end_clean();
        }


        $options = new Options();
        $options->set('pdfBackend', 'CPDF');

        $dompdf = new Dompdf($options);
        // $dompdf = new Dompdf();

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', $orientacion);

        $dompdf->render();

        $canvas = $dompdf->getCanvas();

        $canvas->page_script(function ($pageNumber, $pageCount, $canvas, $fontMetrics) {

            $font = $fontMetrics->getFont("Helvetica", "normal");

            // tamaño real de página
            $width  = $canvas->get_width();
            $height = $canvas->get_height();

            // posición centrada abajo
            $x = ($width / 2) - 40;
            $y = $height - 30;

            $canvas->text(
                $x,
                $y,
                "Página $pageNumber de $pageCount",
                $font,
                9
            );
        });

        $dompdf->stream($nombreArchivo, [
            "Attachment" => true
        ]);

        exit; 

    }
}
