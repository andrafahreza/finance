@extends('v1.layouts.app')

@section('content')
    <div class="pc-container">
        <div class="pc-content">
            <!-- [ breadcrumb ] start -->
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <ul class="breadcrumb mb-3">
                                <li class="breadcrumb-item" aria-current="page">Income</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->

            <div class="row">
                <div class="col-xl-12 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h5 class="mb-0">Income</h5>
                                <div class="button-filter d-flex">
                                    <select class="form-select w-auto m-2">
                                        <option selected="">Month</option>
                                        @foreach ($month as $key => $item)
                                            <option value="{{ $key + 1 }}"
                                                @if (date('m') == $key + 1) selected @endif>{{ $item }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <select class="form-select w-auto m-2">
                                        <option value="2024" @if (date('m') == 2024) selected @endif>2024
                                        </option>
                                        <option value="2025" @if (date('m') == 2025) selected @endif>2025
                                        </option>
                                        <option value="2026" @if (date('m') == 2026) selected @endif>2026
                                        </option>
                                        <option value="2027" @if (date('m') == 2027) selected @endif>2027
                                        </option>
                                    </select>
                                    <select class="form-select w-auto m-2">
                                        <option value="all" selected>Semua </option>
                                        <option>Gaji Pokok</option>
                                        <option>Freelance</option>
                                        <option>Lainnya</option>
                                    </select>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover" id="table">
                                    <thead>
                                        <tr>
                                            <th>Source</th>
                                            <th>Note</th>
                                            <th class="text-end">Income</th>
                                            <th>Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <h6 class="mb-2">
                                                    <span class="text-truncate w-100">Gaji Pokok</span>
                                                </h6>
                                            </td>
                                            <td><span class="badge bg-success">Pembayaran Gaji di Posi Bulan Januari</span>
                                            </td>
                                            <td class="text-end f-w-600">
                                                Rp. {{ number_format(3500000) }}
                                                <small class="text-success f-w-400">
                                                    <i class="ti ti-arrow-up"></i>
                                                </small>
                                            </td>
                                            <td class="f-w-600">{{ date('d-m-Y H:i:s') }}</td>
                                            <td class="f-w-600">
                                                <button class="btn btn-icon btn-outline-primary m-1"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-original-title="Edit">
                                                    <i class="ti ti-pencil"></i>
                                                </button>
                                                <button class="btn btn-icon btn-outline-danger m-1" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-original-title="Delete">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="../assets/js/plugins/simple-datatables.js"></script>
    <script>
        const dataTable = new simpleDatatables.DataTable('#table', {
            sortable: false,
            perPage: 5
        });
    </script>
@endpush
