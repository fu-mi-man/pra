<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function show()
    {
        $sumSizeByType = Image::getSumSizeByType(1);
        return $sumSizeByType;
    }
}
