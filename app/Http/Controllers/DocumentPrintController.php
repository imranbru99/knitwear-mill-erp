<?php

namespace App\Http\Controllers;

use App\Support\Integrations\FactoryCalendar;
use App\Support\Integrations\PdfPrinter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class DocumentPrintController extends Controller
{
    public function __invoke(string $type, int $id, PdfPrinter $printer, FactoryCalendar $calendar)
    {
        $class = 'App\\Models\\'.Str::studly(Str::singular($type));
        abort_unless(class_exists($class) && is_subclass_of($class, Model::class), 404);
        /** @var Model $record */
        $record = $class::query()->findOrFail($id);
        $date = $calendar->banglaDate(now()->toDateString());

        return $printer->download('pdf.document', [
            'title' => Str::headline($type),
            'record' => $record,
            'banglaDate' => $date,
        ], $type.'-'.$id.'.pdf');
    }
}
