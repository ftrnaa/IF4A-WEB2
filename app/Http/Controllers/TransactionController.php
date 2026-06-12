<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class TransactionController extends Controller
{
    public function index()
    {
        return "OK Transaction";
    }

    public function show($id)
    {
        return "Detail transaction: " . $id;
    }
}