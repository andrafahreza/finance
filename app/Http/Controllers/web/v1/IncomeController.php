<?php

namespace App\Http\Controllers\web\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IncomeController extends Controller
{
    public function index()
    {
        $month = $this->month();
        $title = "income";

        return view('v1.pages.income.index', compact(["month", "title"]));
    }
}
