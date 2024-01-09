<?php

namespace App\Http\Controllers\web\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        $month = $this->month();
        $title = "transaction";

        return view('v1.pages.transaction.index', compact(["month", "title"]));
    }
}
