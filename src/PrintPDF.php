<?php

namespace Marifhasan\PrintPDF;

use Mpdf\Mpdf;

class PrintPDF
{
    private static $fileName;

    private static $pdf;

    private function __construct()
    {
    }

    public static function make(object $pdfObject)
    {
        $options = [
            'mode' => 'utf-8',
            'format' => 'A4',
            'orientation' => 'P',
            'debug' => config('app.debug'), // ignore the error it will be found on project
            'allow_output_buffering' => config('app.debug'), // ignore the error it will be found on project
            'tempDir' => storage_path('printpdf/temp'), // ignore the error it will be found on project
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

		if (method_exists($pdfObject, 'lastFooterView')) {
            $pdf->SetHTMLFooter($pdfObject->lastFooterView());
        }

        $pdf->showImageErrors = true;
        $pdf->defaultheaderline = 0;
        $pdf->defaultfooterline = 0;

		if (method_exists($pdfObject, 'fileName')) {
            self::$fileName = $pdfObject->fileName();
        }

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
