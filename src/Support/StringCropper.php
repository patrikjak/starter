<?php

namespace Patrikjak\Starter\Support;

trait StringCropper
{
    private function getCroppedString(?string $string, int $length = 50, string $append = '...'): string
    {
        if ($string === null) {
            return '';
        }

        $appendLength = strlen($append);
        $totalLength = $length - $appendLength;

        if (strlen($string) < $totalLength) {
            return $string;
        }

        return sprintf('%s%s', substr($string, 0, $totalLength), $append);
    }
}