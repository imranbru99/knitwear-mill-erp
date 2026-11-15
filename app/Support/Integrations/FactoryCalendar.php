<?php

namespace App\Support\Integrations;

use Tarikh\Tarikh;

class FactoryCalendar
{
    public function today(): Tarikh
    {
        return Tarikh::today('Asia/Dhaka');
    }

    public function parse(string $date): Tarikh
    {
        return Tarikh::parse($date, 'Asia/Dhaka');
    }

    public function financialYear(?Tarikh $date = null): string
    {
        $date ??= Tarikh::now('Asia/Dhaka');
        $year = (int) $date->format('Y');
        $month = (int) $date->format('n');
        $start = $month >= 7 ? $year : $year - 1;

        return substr((string) $start, -2).substr((string) ($start + 1), -2);
    }

    public function banglaDate(string $date): string
    {
        $parsed = $this->parse($date);
        $formatted = $parsed->format('d M Y');
        if (class_exists(\IntlDateFormatter::class)) {
            $fmt = new \IntlDateFormatter('bn_BD', \IntlDateFormatter::LONG, \IntlDateFormatter::NONE, 'Asia/Dhaka');
            $bangla = $fmt->format(new \DateTimeImmutable($parsed->toDateString()));
            if (is_string($bangla) && $bangla !== '') {
                return $bangla;
            }
        }

        return strtr($formatted, ['0' => '০', '1' => '১', '2' => '২', '3' => '৩', '4' => '৪', '5' => '৫', '6' => '৬', '7' => '৭', '8' => '৮', '9' => '৯']);
    }
}
