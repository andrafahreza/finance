<?php

namespace App\Http\Controllers\web\v1;

use App\Http\Controllers\Controller;
use App\Models\Income;
use App\Models\Source;
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
        $source = Source::get();

        return view('v1.pages.income.index', compact(["month", "title", "source"]));
    }

    public function list()
    {
        $data = Income::with('source')->latest()->get();

        return DataTables::of($data)
            ->editColumn("source", function ($data) {
                return '<span class="text-truncate w-100">'.$data->source->name."</span>";
            })
            ->editColumn("note", function ($data) {
                return '<span class="badge bg-success">'.$data->note.'</span>';
            })
            ->editColumn("income", function ($data) {
                $html = "";
                $html .= '<span class="text-end">Rp. '.number_format($data->value).'</span>';
                $html .= '<small class="text-success f-w-400">
                            <i class="ti ti-arrow-up"></i>
                        </small>';

                return $html;
            })
            ->editColumn("date", function($data) {
                return date('d-m-Y H:i:s', strtotime($data->created_at));
            })
            ->editColumn("action", function ($data) {
                $urlEdit = route("income-get", ["id" => $data->id]);
                $urlDelete = route("income-delete", ["id" => $data->id]);
                $html = '<button class="btn btn-icon btn-outline-primary m-1"
                    data-bs-toggle="tooltip" data-bs-placement="top"
                    data-bs-original-title="Edit" onclick="edit(\''.$urlEdit.'\')">
                    <i class="ti ti-pencil"></i>
                </button>
                <button class="btn btn-icon btn-outline-danger m-1" data-bs-toggle="tooltip"
                    data-bs-placement="top" data-bs-original-title="Delete" onclick="hapus(\''.$urlDelete.'\')">
                    <i class="ti ti-trash"></i>
                </button>';

                return $html;
            })
            ->rawColumns(["source", "note", "income", "action"])
            ->make(true);
    }

    public function save(Request $request, $id = null)
    {
        DB::beginTransaction();

        try {
            if ($id == null) {
                $id = Uuid::uuid4()->getHex();
            }

            $set = [
                "id" => $id,
                "id_user" => Auth::user()->id,
                "id_source" => $request->id_source,
                "value" => $request->value,
                "note" => $request->note,
            ];

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
            }

            $cekIincome = Income::where('id_user', Auth::user()->id)->sum('value');

            $user = User::find(Auth::user()->id);
            $user->balance = $user->balance + $request->value;

            if (!$user->update()) {
                throw new \Exception("Terjadi kesalahan dalam menambah balance");
            }

            DB::commit();

            return response()->json([
                'alert' => 1,
                'message' => "Data berhasil di update"
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

        try {
            if (!$data->delete()) {
                throw new \Exception("Gagal menghapus data");
            }

            return response()->json([
                'alert' => 1,
                'message' => "Berhasil menghapus data"
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
