<?php

namespace App\Services;

use Dompdf\Dompdf;
use setasign\Fpdi\Fpdi;

class MedicalPDFManager
{
    public static function generateEachPDF($applications, $center_name, $username, $iteration, $pdf_view = 'union-account.render.application-list-pdf'): void
    {
        $html = view($pdf_view, compact('applications', 'center_name', 'iteration'))->render();

        $pdf = new Dompdf();
        $pdf->loadHtml($html);
        $pdf->setPaper('A4', 'landscape');
        $pdf->render();

        static $page = 1;
        $path = "app/public/medical/{$username}";

        if (!file_exists(storage_path($path))) {
            mkdir(storage_path($path), 0777, true);
        }

        file_put_contents(storage_path("{$path}/pdf_page_{$page}.pdf"), $pdf->output());
        $page++;
    }

    public static function combinePDF($target_directory, $center_name, $date): void
    {
        $pdf = new Fpdi();

        // Directory where individual PDFs are saved
        $directory = storage_path(str_replace('\\','/', $target_directory));

        // Get all PDF files that match the naming pattern
        $files = glob($directory.'/'.'pdf_page_*.pdf'); // Adjust the naming pattern if necessary

        // Sort files to ensure correct order
        sort($files);

        foreach ($files as $file) {
            // Add each PDF to the combined document
            $pageCount = $pdf->setSourceFile($file);
            for ($page = 1; $page <= $pageCount; $page++) {
                $templateId = $pdf->importPage($page);
                $pdf->AddPage('landscape', 'A4');
                $pdf->useTemplate($templateId);
            }
        }

        // Save the combined PDF
        $combinedPdfPath = $center_name.'-'.$date.'.pdf';
        $pdf->Output($combinedPdfPath, 'D');

        // Delete old PDF files
        self::deleteOldPDF($files);
    }

    public static function deleteOldPDF($files)
    {
        foreach ($files as $file) {
            unlink($file);
        }
    }
}
