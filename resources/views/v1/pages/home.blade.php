@extends('v1.layouts.app')

@section('content')
    {{-- @dd(json_encode($chartBatangMonth)) --}}
    <!-- [ Main Content ] start -->
    <div class="pc-container">
        <div class="pc-content">
            <!-- [ breadcrumb ] start -->
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <ul class="breadcrumb mb-3">
                                <li class="breadcrumb-item" aria-current="page">Dashboard</li>
                            </ul>
                        </div>
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h2 class="mb-0">Dashboard</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->


            <!-- [ Main Content ] start -->
            <div class="row">
                <div class="col-md-6 col-xxl-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-9">
                                    <h6 class="mb-2 f-w-400 text-muted">Total Pemasukan Bulan Ini</h6>
                                    <h4 class="mb-3">Rp. {{ number_format($data['income']['total']) }} <span
                                            class="badge bg-light-success border border-success"><i
                                                class="ti ti-trending-up"></i>
                                            {{ round($data['income']['percentage'], 1) }}%</span></h4>
                                    <p class="mb-0 text-muted text-sm">Kamu mendapatkan <span
                                            class="text-success">{{ $data['income']['comparePast'] }}</span> lebih bulan ini
                                    </p>
                                </div>
                                <div class="col-3 text-end">
                                    <i class="ti ti-currency-dollar text-success f-36"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xxl-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-9">
                                    <h6 class="mb-2 f-w-400 text-muted">Total Pengeluaran Bulan Ini</h6>
                                    <h4 class="mb-3">{{ $data['transaction']['total'] }} <span
                                            class="badge bg-light-primary border border-primary">
                                            {{ round($data['transaction']['percentage'], 1) }}%</span></h4>
                                    <p class="mb-0 text-muted text-sm">Kamu menghemat <span
                                            class="text-success">{{ $data['transaction']['comparePast'] }}</span>
                                        bulan ini</p>
                                </div>
                                <div class="col-3 text-end">
                                    <i class="ti ti-trending-up text-danger f-36"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xxl-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-9">
                                    <h6 class="mb-2 f-w-400 text-muted">Pengeluaran Terbesar Bulan Ini</h6>
                                    <h4 class="mb-3 text-danger">Rp.
                                        {{ $data['biggestTransaction'] != null ? number_format($data['biggestTransaction']->value) : 0 }}
                                    </h4>
                                    <p class="mb-0 text-muted text-sm">Pengeluaran terbsear kamu dibulan ini adalah
                                        {{ $data['biggestTransaction'] != null ? $data['biggestTransaction']->note : '-' }}
                                    </p>
                                </div>
                                <div class="col-3 text-end">
                                    <i class="ti ti-chart-bar text-primary f-36"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xxl-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-9">
                                    <h6 class="mb-2 f-w-400 text-muted">Target</h6>
                                    <h4 class="mb-3">Rp. {{ number_format(500000) }}</h4>
                                    <div class="progress mb-4" style="height: 20px">
                                        <div class="progress-bar" role="progressbar" style="width: 25%" aria-valuenow="25"
                                            aria-valuemin="0" aria-valuemax="100">25%</div>
                                    </div>
                                </div>
                                <div class="col-3 text-end">
                                    <i class="ti ti-award text-secondary f-36"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-8 col-md-12">
                    <div class="card">
                        <div class="card-header pb-0 pt-2">
                            <ul class="nav nav-tabs analytics-tab" id="myTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="analytics-tab-1" data-bs-toggle="tab"
                                        data-bs-target="#data2024" type="button" role="tab" aria-controls="data2024"
                                        aria-selected="true">2024</button>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="tab-content mt-4" id="myTabContent">
                                        <div class="tab-pane fade show active" id="data2024" role="tabpanel"
                                            aria-labelledby="analytics-tab-1" tabindex="0">
                                            <div id="overview-chart-1"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-4 col-md-12">
                    <div class="card">
                        <div class="card-body pb-0">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h5 class="mb-0">History Pengeluaran</h5>
                            </div>
                        </div>
                        <ul class="list-group list-group-flush border-top-0">
                            @foreach ($data['historyPayment'] as $item)
                                <li class="list-group-item">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <div class="avtar avtar-s bg-light-secondary">
                                                <i class="fas fa-caret-up text-danger f-24"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 mx-2">
                                            <p class="mb-1">{{ $item->note }}</p>
                                            <h6 class="mb-0">- Rp. {{ number_format($item->value) }}<small
                                                    class="text-danger"> -
                                                    {{ round(($item->value / $data['income']['total']) * 100, 1) }}%</small>
                                            </h6>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                        <div class="card-footer">
                            <div class="d-grid">
                                <a href="{{ route('transaction') }}" class="btn btn-outline-secondary">lihat
                                    selengkapnya</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-6 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h5 class="mb-0">Pengguanaan Uang</h5>
                            </div>
                            <div class="my-3">
                                <div id="overview-product-graph"></div>
                            </div>
                            <div class="row g-3 text-center">
                                <div class="col-6 col-lg-4 col-xxl-4">
                                    <div class="overview-product-legends">
                                        <p class="text-dark mb-1"><span>Apps</span></p>
                                        <h6 class="mb-0">10+</h6>
                                    </div>
                                </div>
                                <div class="col-6 col-lg-4 col-xxl-4">
                                    <div class="overview-product-legends">
                                        <p class="text-dark mb-1"><span>Other</span></p>
                                        <h6 class="mb-0">5+</h6>
                                    </div>
                                </div>
                                <div class="col-6 col-lg-4 col-xxl-4">
                                    <div class="overview-product-legends">
                                        <p class="text-secondary mb-1"><span>Widgets</span></p>
                                        <h6 class="mb-0">150+</h6>
                                    </div>
                                </div>
                                <div class="col-6 col-lg-4 col-xxl-4">
                                    <div class="overview-product-legends">
                                        <p class="text-secondary mb-1"><span>Forms</span></p>
                                        <h6 class="mb-0">50+</h6>
                                    </div>
                                </div>
                                <div class="col-6 col-lg-4 col-xxl-4">
                                    <div class="overview-product-legends">
                                        <p class="text-primary mb-1"><span>Components</span></p>
                                        <h6 class="mb-0">200+</h6>
                                    </div>
                                </div>
                                <div class="col-6 col-lg-4 col-xxl-4">
                                    <div class="overview-product-legends">
                                        <p class="text-primary mb-1"><span>Pages</span></p>
                                        <h6 class="mb-0">150+</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ Main Content ] end -->
        </div>
    </div>
    <!-- [ Main Content ] end -->
@endsection

@push('script')
    <script src="../assets/js/plugins/apexcharts.min.js"></script>

    <script>
        'use strict';
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                floatchart();
            }, 500);
        });

        function floatchart() {
            (function() {
                var income = JSON.parse('{{ $income }}');
                var transaction = JSON.parse('{{ $transaction }}');
                var month = '{{ $month }}';
                month = month.replaceAll('&quot;', '"')
                month = JSON.parse(month);

                var options2024 = {
                    chart: {
                        height: 380,
                        type: 'bar',
                        toolbar: {
                            show: false,
                        }
                    },
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            columnWidth: '55%',
                            borderRadius: 4,
                            borderRadiusApplication: 'end',
                        }
                    },
                    legend: {
                        show: true,
                        position: 'top',
                        horizontalAlign: 'left',
                    },
                    dataLabels: {
                        enabled: false
                    },
                    colors: ['#4680FF', '#4680FF'],
                    stroke: {
                        show: true,
                        width: 3,
                        colors: ['transparent']
                    },
                    fill: {
                        colors: ['#4680FF', '#4680FF'],
                        opacity: [1, 0.5]
                    },
                    grid: {
                        strokeDashArray: 4
                    },
                    series: [{
                            name: 'Pemasukan',
                            data: income
                        },
                        {
                            name: 'Pengeluaran',
                            data: transaction
                        }
                    ],
                    xaxis: {
                        categories: month
                    },
                    tooltip: {
                        y: {
                            formatter: function(val) {
                                return 'Rp. ' + val;
                            }
                        }
                    }
                };
                var chart = new ApexCharts(document.querySelector("#overview-chart-1"), options2024);
                chart.render();
            })();
        }
    </script>
@endpush
