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
                                <li class="breadcrumb-item">Pengeluaran</li>
                                <li class="breadcrumb-item" aria-current="page">Data Pengeluaran</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->

            <div class="row">
                <div class="col-xl-12 col-md-12">
                    <button type="button" class="btn btn-primary m-b-20" data-bs-toggle="modal" data-bs-target="#add"
                        id="btnAdd">+ Tambah</button>
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex flex-sm-row flex-column align-items-center justify-content-between mb-3">
                                <h5 class="mb-0">Data Pengeluaran</h5>
                                <div class="button-filter d-flex flex-sm-row flex-column">
                                    <div class="row">
                                        <select class="form-select w-auto m-2" id="filterMonth">
                                            @foreach ($month as $key => $item)
                                                <option value="{{ $key + 1 }}"
                                                    @if (date('m') == $key + 1) selected @endif>{{ $item }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <select class="form-select w-auto m-2" id="filterYear">
                                            <option value="2024" @if (date('m') == 2024) selected @endif>2024
                                            </option>
                                            <option value="2025" @if (date('m') == 2025) selected @endif>2025
                                            </option>
                                            <option value="2026" @if (date('m') == 2026) selected @endif>2026
                                            </option>
                                            <option value="2027" @if (date('m') == 2027) selected @endif>2027
                                            </option>
                                        </select>
                                    </div>
                                    <select class="form-select w-auto m-2" id="filterSource">
                                        <option value="all">Semua </option>
                                        @foreach ($category as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover" id="table">
                                    <thead>
                                        <tr>
                                            <th>Kategori</th>
                                            <th>Catatan</th>
                                            <th class="text-end">Jumlah</th>
                                            <th>Tanggal</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="add" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modalAdd" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAdd">Tambah Pengeluaran</h5>
                </div>
                <form method="POST" action="{{ route('source-save') }}" id="formAdd">
                    @csrf
                    <div class="modal-body">
                        @if (auth()->user()->role == 'admin')
                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label text-lg-end">Transfer ke user</label>
                                <div class="col-lg-6">
                                    <select class="form-control" name="sub_admin" id="sub_admin">
                                        <option value="">Pilih User</option>
                                        @foreach ($subAdmin as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label text-lg-end">Kategori <span class="text-danger">*</span></label>
                            <div class="col-lg-6">
                                <select class="form-control" name="id_category" id="id_category" required>
                                    <option value="">Pilih Kategori</option>
                                    @foreach ($category as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label text-lg-end">Jumlah Pengeluaran <span class="text-danger">*</span></label>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" name="value" id="value">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label text-lg-end">Tanggal <span class="text-danger">*</span></label>
                            <div class="col-lg-6">
                                <input type="datetime-local" class="form-control" name="date" id="date">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label text-lg-end">Catatan <span class="text-danger">*</span></label>
                            <div class="col-lg-6">
                                <textarea class="form-control" name="note" id="note" required></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="../assets/css/plugins/notifier.css">
    <link rel="stylesheet" href="../assets/css/plugins/dataTables.bootstrap5.min.css">

    <style>
        .notifier-container {
            z-index: 10000 !important;
        }
    </style>
@endpush

@push('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="../assets/js/plugins/notifier.js"></script>
    <script src="../assets/js/notification.js"></script>
    <script src="../assets/js/plugins/jquery.dataTables.min.js"></script>
    <script src="../assets/js/plugins/dataTables.bootstrap5.min.js"></script>
    <script src="../assets/js/plugins/sweetalert2.all.min.js"></script>

    <script>
        var table;

        let column_table = [{
                data: 'category',
                name: 'category',
                searchable: false
            },
            {
                data: 'note',
                name: 'note',
                orderable: false,
            },
            {
                data: 'transaction',
                name: 'transaction'
            },
            {
                data: 'date',
                name: 'date'
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            }
        ];

        table = $('#table').DataTable({
            stateSave: true,
            responsive: true,
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: {
                url: "{{ route('transaction-list') }}",
                method: "post",
                data: function(d) {
                    d._token = $('meta[name="csrf-token"]').attr('content');
                    d.month = $('#filterMonth').val();
                    d.year = $('#filterYear').val();
                    d.source = $('#filterSource').val();
                }
            },
            columns: column_table,
            'columnDefs': [{
                'target': column_table.length - 1,
                'createdCell': function(td, cellData, rowData, row, col) {
                    $(td).attr('nowrap', true);
                }
            }]
        });

        $("#value").on('keyup', function(evt) {
            if (evt.which != 110) { //not a fullstop
                var n = parseFloat($(this).val().replace(/\,/g, ''), 10);
                if (isNaN(n)) {
                    $(this).val(0);
                } else {
                    $(this).val(n.toLocaleString());
                }
            }
        });

        $('#btnAdd').click(function() {
            $("#formAdd")[0].reset();
            $("#formAdd").attr("action", "{{ route('transaction-save') }}");
        });

        $('#formAdd').submit(function(e) {
            e.preventDefault();

            const url = $(this).attr("action");
            const formData = new FormData(this);

            $.ajax({
                type: "post",
                url: url,
                data: formData,
                contentType: false,
                processData: false,
                dataType: "JSON",
                success: function(response) {
                    var title = "";
                    var icon = "";

                    if (response.alert == '1') {
                        title = "Berhasil";
                        icon = "success";

                        $('#add').modal('hide');
                        $('#formAdd')[0].reset();
                    } else {
                        title = "Error !";
                        icon = "danger";
                    }

                    getResponse(title, response.message, icon);
                    table.ajax.reload(null, false);

                    $('#balanceUser').html("Rp. " + response.balance);
                },
                error: function(response) {
                    getResponse("Error !", response.message, "danger");
                }
            });
        });

        function edit(url) {
            $.ajax({
                type: "get",
                url: url,
                dataType: "JSON",
                success: function(response) {
                    if (response.alert == '1') {
                        $('#add').modal('toggle');

                        const data = response.data;
                        $('#formAdd')[0].reset();
                        $('#formAdd').attr("action", "{{ route('transaction-save') }}" + "/" + data.id);
                        $('#id_category').val(data.id_category).change();
                        $('#value').val(data.value);
                        $('#date').val(data.created_at);
                        $('#note').val(data.note);
                    } else {
                        getresponse("Error !", response.message, "danger");
                    }
                },
                error: function(response) {
                    getresponse("Error !", response.message, "danger");
                }
            });
        }

        function hapus(url) {
            Swal.fire({
                title: "Peringatan!",
                html: "Apakah yakin ingin menghapus ini?",
                icon: "warning",
                showCancelButton: true,
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return fetch(url)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(response.statusText);
                            }
                            return response.json()
                        })
                        .then(data => {
                            if (data.result == "error") {
                                Swal.showValidationMessage(data.title);
                            }
                        })
                        .catch(error => {
                            console.error(error);
                            Swal.showValidationMessage("An error occurred in delete data");
                        })
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.value) {
                    getResponse("Berhasil", "Berhasil menghapus data", "success");
                    table.ajax.reload(null, false);

                    $.ajax({
                        type: "get",
                        url: "{{ route('count-balance') }}",
                        dataType: "JSON",
                        success: function(response) {
                            $('#balanceUser').html(response.balance);
                        },
                        error: function(response) {
                            getresponse("Error !", response.message, "danger");
                        }
                    });
                }
            })
        }

        $('#filterMonth, #filterYear, #filterSource').on('change', function() {
            table.ajax.reload();
        });
    </script>
@endpush
