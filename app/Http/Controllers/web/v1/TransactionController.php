<?php

namespace App\Http\Controllers\web\v1;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Income;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;
use Yajra\DataTables\Facades\DataTables;

class TransactionController extends Controller
{
    public function index()
    {
        $month = $this->month();
        $title = "transaction";
        $category = Category::where('id_user', Auth::user()->id)->get();
        $subAdmin = User::where('role', 'sub_admin')->get();

        return view('v1.pages.transaction.index', compact(["month", "title", "category", "subAdmin"]));
    }

    public function list(Request $request)
    {
        $data = Transaction::with('category')
            ->where(function($query) use($request) {
                $year = $request->year != null ? $request->year : date('Y');
                $query->whereYear('date', $year);
            })
            ->where(function($query) use($request) {
                $month = $request->month != null ? $request->month : date('m');
                $query->whereMonth('date', $month);
            })
            ->where(function($query) use($request) {
                if ($request->category && $request->category != "all") {
                    $query->where('id_category', $request->category);
                }
            })
            ->where('id_user', Auth::user()->id)
            ->latest()
            ->get();

        return DataTables::of($data)
            ->editColumn("category", function ($data) {
                return '<span class="text-truncate w-100">'.$data->category->name."</span>";
            })
            ->editColumn("note", function ($data) {
                return '<span class="badge bg-danger">'.$data->note.'</span>';
            })
            ->editColumn("transaction", function ($data) {
                $html = "";
                $html .= '<div class="text-end text-danger">Rp. '.number_format($data->value);
                $html .= '<small class="text-danger f-w-400">
                            <i class="ti ti-arrow-down"></i>
                        </small> </div>';

                return $html;
            })
            ->editColumn("date", function($data) {
                return date('d-m-Y H:i', strtotime($data->date));
            })
            ->editColumn("action", function ($data) {
                $urlEdit = route("transaction-get", ["id" => $data->id]);
                $urlDelete = route("transaction-delete", ["id" => $data->id]);
                $html = "";

                if (!$data->to_user) {
                    $html .= '<button class="btn btn-icon btn-outline-primary m-1"
                        data-bs-toggle="tooltip" data-bs-placement="top"
                        data-bs-original-title="Edit" onclick="edit(\''.$urlEdit.'\')">
                        <i class="ti ti-pencil"></i>
                    </button>';
                }

                $html .= '<button class="btn btn-icon btn-outline-danger m-1" data-bs-toggle="tooltip"
                    data-bs-placement="top" data-bs-original-title="Delete" onclick="hapus(\''.$urlDelete.'\')">
                    <i class="ti ti-trash"></i>
                </button>';

                return $html;
            })
            ->rawColumns(["category", "note", "transaction", "action"])
            ->make(true);
    }

    public function save(Request $request, $id = null)
    {
        DB::beginTransaction();

        try {
            $user = User::find(Auth::user()->id);
            $replaceValue = str_replace(',','',$request->value);
            $value = (int)str_replace('.','',$replaceValue);

            if ($value > $user->balance) {
                throw new \Exception("Balance tidak cukup");
            }

            // Transfer to other
            $idIncome = Uuid::uuid4()->getHex();
            if ($request->sub_admin) {
                $setTf = [
                    "id" => $idIncome,
                    "id_user" => $request->sub_admin,
                    "value" => $value,
                    "note" => "Ditransfer oleh: " . Auth::user()->name,
                    "date" => $request->date
                ];

                $income = Income::create($setTf);
                if (!$income->save()) {
                    throw new \Exception("Gagal transfer ke user lain");
                }

                $checkIncome = Income::where('id_user', $request->sub_admin)->sum('value');
                $checkTransaction = Transaction::where('id_user', $request->sub_admin)->sum('value');
                $countBalanceSub = ($checkIncome - $checkTransaction);

                $userTf = User::find($request->sub_admin);
                $userTf->balance = $countBalanceSub;

                if (!$userTf->update()) {
                    throw new \Exception("Terjadi kesalahan dalam memperbarui balance");
                }
            }
            // END SEND TO OTHER

            if ($id == null) {
                $id = Uuid::uuid4()->getHex();
            }

            $set = [
                "id" => $id,
                "id_user" => Auth::user()->id,
                "to_user" => $request->sub_admin,
                "id_category" => $request->id_category,
                "value" => $value,
                "note" => $request->note,
                "date" => $request->date,
                "id_income" => $request->sub_admin != null ? $idIncome : null
            ];

            $countBalance = $user->balance - $value;

            $detect = Transaction::find($id);
            if (!$detect) {
                $detect = Transaction::create($set);

                if (!$detect->save()) {
                    throw new \Exception("Gagal menambahkan data");
                }
            } else {
                unset($set["id"]);

                if (!$detect->update($set)) {
                    throw new \Exception("Gagal memperbarui data");
                }

                $checkIncome = Income::where('id_user', Auth::user()->id)->sum('value');
                $checkTransaction = Transaction::where('id_user', Auth::user()->id)->sum('value');
                $countBalance = ($checkIncome - $checkTransaction);
            }

            $user->balance = $countBalance;

            if (!$user->update()) {
                throw new \Exception("Terjadi kesalahan dalam menambah balance");
            }

            DB::commit();

            return response()->json([
                'alert' => 1,
                'message' => "Data berhasil di update",
                'balance' => number_format($countBalance)
            ]);

        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'alert' => 0,
                'message' => "Keterangan: " . $th->getMessage()
            ]);
        }
    }

    public function getData($id = null)
    {
        $data = Transaction::find($id);
        if ($data == null || $id == null) {
            abort(404);
        }

        try {
            return response()->json([
                'alert' => 1,
                'data' => $data
            ]);
        } catch (\Throwable $th) {
            $message = $th->getMessage();
            return response()->json([
                'alert' => 0,
                'message' => "An error occurred: $message"
            ]);
        }
    }

    public function delete($id = null)
    {
        $data = Transaction::find($id);
        if ($data == null || $id == null) {
            abort(404);
        }

        DB::beginTransaction();

        try {
            $user = User::find(Auth::user()->id);
            $balance = $user->balance + $data->value;
            $user->balance = $balance;

            if ($data->to_user) {
                $income = Income::find($data->id_income);
                $userTf = User::find($income->id_user);

                if (!$income->delete()) {
                    throw new \Exception("Terjadi kesalahan dalam menghapus pendapatan user lain");
                }

                $checkIncome = Income::where('id_user', $userTf->id)->sum('value');
                $checkTransaction = Transaction::where('id_user', $userTf->id)->sum('value');
                $countBalance = ($checkIncome - $checkTransaction);

                $userTf->balance = $countBalance;
                if (!$userTf->update()) {
                    throw new \Exception("Terjadi kesalahan dalam memperbarui balance user");
                }
            }

            if (!$data->delete() || !$user->update()) {
                throw new \Exception("Gagal menghapus data");
            }

            DB::commit();

            return response()->json([
                'alert' => 1,
                'message' => "Berhasil menghapus data",
                'balance' => $balance
            ]);
        } catch (\Throwable $th) {
            $message = $th->getMessage();
            return response()->json([
                'alert' => 0,
                'message' => "Keterangan: $message"
            ]);
        }
    }
}
