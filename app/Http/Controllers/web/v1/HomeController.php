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
            "total" => $getIncome,
            "comparePast" => "Rp. ". number_format($getCompareIncome),
            "percentage" => $getCompareIncome > 0 ? ($getCompareIncome) / ($getIncome) * 100 : 0
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
            "percentage" => $getCompareTransaction > 0 ? ($getCompareTransaction) / ($getTransaction) * 100 : 0
        ];

        // ========== Payment History ==================
        $historyPayment = Transaction::where('id_user', $user->id)->whereMonth('date', date('m'))->orderBy('date', 'desc')->limit(5)->get();

        $data = [
            "income" => $income,
            "transaction" => $transaction,
            "biggestTransaction" => $biggestTransaction,
            "historyPayment" => $historyPayment
        ];

        $income = array();
        $transaction = array();
        $month = array();
        foreach ($this->month() as $key => $value) {
            $getIncome = (int)Income::where('id_user', $user->id)->whereMonth('date', $key + 1)->sum('value');
            $getTransaction = (int)Transaction::where('id_user', $user->id)->whereMonth('date', $key + 1)->sum('value');
            if ($getIncome == 0 && $getTransaction == 0) {
                continue;
            }

            $income[] = $getIncome;
            $transaction[] = $getTransaction;
            array_push($month, substr($value, 0, 3));
        }

        $income = json_encode($income);
        $transaction = json_encode($transaction);
        $month = json_encode($month);

        return view('v1.pages.home', compact([
            "title",
            "data",
            "income",
            "transaction",
            "month"
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
