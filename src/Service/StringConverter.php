<?php

namespace App\Service;

class StringConverter
{
    public function cp1250utf8(string $in): string
    {
        return iconv('cp1250', 'utf-8', $in);
    }

    public function firstLast(string $in): string
    {
        return trim($in, "'");
    }
}
