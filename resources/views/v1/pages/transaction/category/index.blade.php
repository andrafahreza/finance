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
                                <li class="breadcrumb-item" aria-current="page">Kategori</li>
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
                                <h5 class="mb-0">Data Sumber Dana</h5>
                                <div class="button-filter d-flex">
                                    <button type="button" class="btn btn-primary m-b-20" data-bs-toggle="modal"
                                        data-bs-target="#add" id="btnAdd">+ Tambah</button>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover" id="table" style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th>Nama Kategori</th>
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
                    <h5 class="modal-title" id="modalAdd">Tambah Data Kategori</h5>
                </div>
                <form method="POST" action="{{ route('category-save') }}" id="formAdd">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label text-lg-end">Kategori</label>
                            <div class="col-lg-6">
                                <input type="text" name="name" id="category" class="form-control" required>
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

        let column_table = [
            {
                data: 'name',
                name: 'name'
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
            ajax: {
                url: "{{ route('category-list') }}",
                method: "post",
                data: function(d) {
                    d._token = $('meta[name="csrf-token"]').attr('content');
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

        $('#btnAdd').click(function() {
            $("#formAdd")[0].reset();
            $("#formAdd").attr("action", "{{ route('category-save') }}");
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

                        console.log(response);

                        const data = response.data;
                        $('#formAdd')[0].reset();
                        $('#formAdd').attr("action", "{{ route('category-save') }}" + "/" + data.id);
                        $('#category').val(data.name);
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
                }
            })
        }
    </script>
@endpush
