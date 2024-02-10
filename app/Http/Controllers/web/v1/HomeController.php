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
        $title = "home";
        $user = Auth::user();

        //  ======================= Income =================
        $getIncome = Income::where('id_user', $user->id)->whereMonth('date', date('m'))->sum('value');
        $getIncomePast = Income::where('id_user', $user->id)->whereMonth('date', (date('m') - 1))->sum('value');
        $getCompareIncome = $getIncome - $getIncomePast;

        if ($getIncomePast < $getIncome) {
            $getCompareIncome = 0;
        }

        $income = [
            "total" => "Rp. ". number_format($getIncome),
            "comparePast" => "Rp. ". number_format($getCompareIncome),
            "percentage" => ($getCompareIncome) / ($getIncome) * 1
        ];

        // ===================== Transaction ==============
        $dataTransaction = Transaction::where('id_user', $user->id)->whereMonth('date', date('m'));

        $getTransaction = $dataTransaction->sum('value');
        $getTransactionPast = Transaction::where('id_user', $user->id)->whereMonth('date', (date('m') - 1))->sum('value');
        $getCompareTransaction = 0;

        if ($getTransactionPast > $getTransaction) {
            $getCompareTransaction = $getTransactionPast - $getTransaction;
        }

        $biggestTransaction = $dataTransaction->orderBy('value', 'desc')->first();

        $transaction = [
            "total" => "Rp. ". number_format($getTransaction),
            "comparePast" => "Rp. ". number_format($getCompareTransaction),
            "percentage" => ($getCompareTransaction) / ($getTransaction) * 1
        ];



        $data = [
            "income" => $income,
            "transaction" => $transaction,
            "biggestTransaction" => $biggestTransaction
        ];


        return view('v1.pages.home', compact([
            "title",
            "data"
        ]));
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
