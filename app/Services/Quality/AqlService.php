<?php

namespace App\Services\Quality;

class AqlService
{
    /**
     * ISO 2859-1 general inspection level II sample size, single sampling.
     *
     * @var array<int, int>
     */
    private array $sampleSizes = [
        2 => 2, 9 => 3, 16 => 5, 26 => 8, 51 => 13, 91 => 20, 151 => 32,
        281 => 50, 501 => 80, 1201 => 125, 3201 => 200, 10001 => 315, 35001 => 500,
    ];

    /**
     * Accept numbers for normal inspection. Reject is accept + 1.
     *
     * @var array<int, array<string, int>>
     */
    private array $accept = [
        2 => ['2.5' => 0, '4.0' => 0],
        3 => ['2.5' => 0, '4.0' => 0],
        5 => ['2.5' => 0, '4.0' => 0],
        8 => ['2.5' => 0, '4.0' => 1],
        13 => ['2.5' => 1, '4.0' => 1],
        20 => ['2.5' => 1, '4.0' => 2],
        32 => ['2.5' => 2, '4.0' => 3],
        50 => ['2.5' => 3, '4.0' => 5],
        80 => ['2.5' => 5, '4.0' => 7],
        125 => ['2.5' => 7, '4.0' => 10],
        200 => ['2.5' => 10, '4.0' => 14],
        315 => ['2.5' => 14, '4.0' => 21],
        500 => ['2.5' => 21, '4.0' => 21],
    ];

    public function sampleSize(int $lotSize): int
    {
        $size = 2;
        foreach ($this->sampleSizes as $from => $sample) {
            if ($lotSize >= $from) {
                $size = $sample;
            }
        }

        return min($size, max($lotSize, 1));
    }

    public function acceptNumber(int $sampleSize, float $aql): int
    {
        $key = number_format($aql, 1, '.', '');
        if (! isset($this->accept[$sampleSize][$key])) {
            return max(0, (int) floor($sampleSize * $aql / 100));
        }

        return $this->accept[$sampleSize][$key];
    }

    /**
     * @return array{sample_size: int, major_accept: int, minor_accept: int, result: string}
     */
    public function judge(int $lotSize, float $aqlMajor, float $aqlMinor, int $majorFound, int $minorFound, int $criticalFound): array
    {
        $sample = $this->sampleSize($lotSize);
        $majorAccept = $this->acceptNumber($sample, $aqlMajor);
        $minorAccept = $this->acceptNumber($sample, $aqlMinor);
        $failed = $criticalFound > 0 || $majorFound > $majorAccept || $minorFound > $minorAccept;

        return [
            'sample_size' => $sample,
            'major_accept' => $majorAccept,
            'minor_accept' => $minorAccept,
            'result' => $failed ? 'fail' : 'pass',
        ];
    }
}
