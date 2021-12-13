<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Templates;
use Illuminate\Http\Request;

class TemplatesController extends Controller
{
    public function index()
    {
        $templates = Templates::get();

        return response()->json([
            'data' => $templates
        ]);
    }
}
