<?php

namespace App\Services\Production;

class KnittingMetrics
{
    public function efficiency(int $pieces, float $smv, int $attendedMinutes): float
    {
        if ($attendedMinutes <= 0 || $smv <= 0) {
            return 0.0;
        }

        return round((($pieces * $smv) / $attendedMinutes) * 100, 2);
    }

    public function oee(float $availability, float $performance, float $quality): float
    {
        return round($availability * $performance * $quality, 4);
    }
}
