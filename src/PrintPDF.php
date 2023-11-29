<?php

namespace Marifhasan\PrintPDF;

use Illuminate\Support\Facades\Storage;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;
use Mpdf\Mpdf;

class PrintPDF
{
    private static $fileName;

    private static $pdf;

    private function __construct()
    {
    }

    public static function make(object $pdfObject, string $fileName)
    {
        $defaultConfig = (new ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        $options = [
            'mode' => 'utf-8',
            'format' => 'A4',
            'orientation' => 'P',
            'debug' => true,
            'allow_output_buffering' => true,
            'tempDir' => '/temp',
            'margin_top' => 24,
            'margin_right' => 12,
            'margin_footer' => 12,
            'margin_left' => 12,
            'default_font' => 'freeserif',
            'default_font_size' => 11,
        ];

        $options = array_merge($options, $pdfObject->options());

        $pdf = new Mpdf($options);

        if (method_exists($pdfObject, 'headerView')) {
            $pdf->SetHTMLHeader($pdfObject->headerView());
        }

        if (method_exists($pdfObject, 'footerView')) {
            $pdf->SetHTMLFooter($pdfObject->footerView());
        }

        $pdf->WriteHTML($pdfObject->view());

        $pdf->showImageErrors = true;
        $pdf->defaultheaderline = 0;
        $pdf->defaultfooterline = 0;

        self::$fileName = $fileName;
        self::$pdf = $pdf;

        return new self;
    }

    public function inline()
    {
        return self::$pdf->Output(self::$fileName, 'I');
    }

    public function download()
    {
        return self::$pdf->Output(self::$fileName, 'D');
    }

    public function file()
    {
        return self::$pdf->Output(self::$fileName, 'F');
    }

    public function string()
    {
        return self::$pdf->Output(self::$fileName, 'S');
    }
}
