<?php



namespace Modules\Domain\Services;

use Jurosh\PDFMerge\PDFMerger;
use Exception;

class PdfMergerService
{
    public function merge(array $pdfPaths, string $outputPath): bool
    {
        try {
            $merger = new PDFMerger();

            foreach ($pdfPaths as $path) {
                $merger->addPDF($path, 'all');
            }

            $merger->merge('file', $outputPath);

            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
