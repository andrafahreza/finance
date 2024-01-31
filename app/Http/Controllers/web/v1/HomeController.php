<?php

namespace App\Http\Controllers\web\v1;

use App\Http\Controllers\Controller;
use App\Models\Income;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        return view('v1.pages.home', [
            'title' => 'home'
        ]);
    }

    public function balance()
    {
        $user = Auth::user();

        $countIncome = Income::where('id_user', $user->id)->sum("value");
        $countTransaction = Transaction::where('id_user', $user->id)->sum("value");
        $balance = $countIncome - $countTransaction;

        return response()->json([
            'balance' => "Rp. " . number_format($balance)
        ]);
    }
}
