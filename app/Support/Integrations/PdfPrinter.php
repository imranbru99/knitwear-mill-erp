<?php

namespace App\Support\Integrations;

use ImranDev\UnicodePdf\Facades\UnicodePdf;
use Illuminate\Contracts\View\View;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class PdfPrinter
{
    public function download(string $view, array $data, string $filename): Response
    {
        try {
            return UnicodePdf::preset('bengali')->loadView($view, $data)->a4()->download($filename);
        } catch (Throwable) {
            /** @var View $html */
            $html = view($view, $data);

            return response($html->render(), 200, ['Content-Type' => 'text/html; charset=UTF-8']);
        }
    }
}
