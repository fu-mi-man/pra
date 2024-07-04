<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CSVValidationController extends Controller
{
    public function validate(Request $request)
    {
        if ($request->hasFile('csv_file')) {
            return response()->json([
                'isValid' => true,
                'message' => 'File received successfully'
            ]);
        }

        return response()->json([
            'isValid' => false,
            'message' => 'No file received'
        ], 400);
    }
}
