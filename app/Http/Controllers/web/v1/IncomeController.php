<?php

namespace App\Http\Controllers\web\v1;

use App\Http\Controllers\Controller;
use App\Models\Income;
use App\Models\Source;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;
use Yajra\DataTables\Facades\DataTables;

class IncomeController extends Controller
{
    public function index()
    {
        $month = $this->month();
        $title = "income";
        $source = Source::where('id_user', Auth::user()->id)->get();

        return view('v1.pages.income.index', compact(["month", "title", "source"]));
    }

    public function list(Request $request)
    {
        $data = Income::with('source')
            ->where(function($query) use($request) {
                $year = $request->year != null ? $request->year : date('Y');
                $query->whereYear('date', $year);
            })
            ->where(function($query) use($request) {
                $month = $request->month != null ? $request->month : date('m');
                $query->whereMonth('date', $month);
            })
            ->where(function($query) use($request) {
                if ($request->source && $request->source != "all") {
                    $query->where('id_source', $request->source);
                }
            })
            ->where('id_user', Auth::user()->id)
            ->latest()
            ->get();

        return DataTables::of($data)
            ->editColumn("source", function ($data) {
                if ($data->source) {
                    return '<span class="badge bg-success">'.$data->source->name."</span>";
                }

                return "Transfer";
            })
            ->editColumn("note", function ($data) {
                return '<span class="text-truncate w-100">'.$data->note.'</span>';
            })
            ->editColumn("income", function ($data) {
                $html = "";
                $html .= '<div class="text-end text-success">Rp. '.number_format($data->value);
                $html .= '<small class="text-success f-w-400">
                            <i class="ti ti-arrow-up"></i>
                        </small> </div>';

                return $html;
            })
            ->editColumn("date", function($data) {
                return date('d-m-Y H:i', strtotime($data->date));
            })
            ->editColumn("action", function ($data) {
                $urlEdit = route("income-get", ["id" => $data->id]);
                $urlDelete = route("income-delete", ["id" => $data->id]);
                $html = "";

                if ($data->source) {
                    $html .= '<button class="btn btn-icon btn-outline-primary m-1"
                        data-bs-toggle="tooltip" data-bs-placement="top"
                        data-bs-original-title="Edit" onclick="edit(\''.$urlEdit.'\')">
                        <i class="ti ti-pencil"></i>
                    </button>
                    <button class="btn btn-icon btn-outline-danger m-1" data-bs-toggle="tooltip"
                        data-bs-placement="top" data-bs-original-title="Delete" onclick="hapus(\''.$urlDelete.'\')">
                        <i class="ti ti-trash"></i>
                    </button>';
                }

                return $html;
            })
            ->rawColumns(["source", "note", "income", "action"])
            ->make(true);
    }

    public function save(Request $request, $id = null)
    {
        DB::beginTransaction();

        try {
            $user = User::find(Auth::user()->id);

            if ($id == null) {
                $id = Uuid::uuid4()->getHex();
            }

            $replaceValue = str_replace(',','',$request->value);
            $value = (int)str_replace('.','',$replaceValue);

            $set = [
                "id" => $id,
                "id_user" => Auth::user()->id,
                "id_source" => $request->id_source,
                "value" => $value,
                "note" => $request->note,
                "date" => $request->date,
            ];

            $countBalance = $user->balance + $value;

            $detect = Income::find($id);
            if (!$detect) {
                $detect = Income::create($set);

                if (!$detect->save()) {
                    throw new \Exception("Gagal menambah data");
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
        $data = Income::find($id);
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
        $data = Income::find($id);
        if ($data == null || $id == null) {
            abort(404);
        }

        DB::beginTransaction();

        try {
            $user = User::find(Auth::user()->id);
            $balance = $user->balance - $data->value;
            $user->balance = $balance;

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
