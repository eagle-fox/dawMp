<?php

namespace App\Controllers;

class PruebasController extends Controller
{
    public function index(): void
    {
        response()->json([
            'message' => 'Hola Yaison output'
        ]);
    }
}
