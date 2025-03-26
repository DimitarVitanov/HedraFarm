<?php

namespace App\Http\Helpers;

class ClientHelper
{
    public static function tidyName($name)
    {
        return ucwords(str_replace('_', ' ', $name));
    }
}
