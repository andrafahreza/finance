<?php

namespace App\Http\Controllers\web\v1;

use App\Http\Controllers\Controller;
use App\Models\Source;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;
use Yajra\DataTables\Facades\DataTables;

class SourceController extends Controller
{
    public function index()
    {
        return view('v1.pages.income.source.index', [
            'title' => 'source'
        ]);
    }

    public function list()
    {
        $data = Source::latest()->get();

        return DataTables::of($data)
            ->editColumn("name", function ($data) {
                return '<span class="badge bg-success">'.$data->name.'</span>';
            })
            ->editColumn("action", function ($data) {
                $urlEdit = route("source-get", ["id" => $data->id]);
                $urlDelete = route("source-delete", ["id" => $data->id]);
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
            ->rawColumns(["action", "name"])
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
                "name" => $request->name,
                "id_user" => Auth::user()->id
            ];

            $detect = Source::find($id);
            if (!$detect) {
                $detect = Source::create($set);
                if (!$detect->save()) {
                    throw new \Exception("Gagal menambah data");
                }

            } else {
                unset($set["id"]);
                if (!$detect->update($set)) {
                    throw new \Exception("Gagal memperbarui data");
                }
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
        $data = Source::find($id);
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
        $data = Source::find($id);
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
