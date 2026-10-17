
@extends('layouts.backend')

@section('content')
    {{-- Sales & Revenue Cards --}}
    <div class="container-fluid pt-4 px-3">
        <div class="row g-4">

            <div class="col-sm-6 col-xl-3">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4 h-100">
                    <i class="fa fa-chart-line fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Today Sales</p>
                        <h6 class="mb-0">{{ money($todaySales) }}</h6>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-xl-3">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4 h-100">
                    <i class="fa fa-chart-bar fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Total Sales</p>
                        <h6 class="mb-0">{{ money($totalSales) }}</h6>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-xl-3">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4 h-100">
                    <i class="fa fa-chart-area fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Today's Revenue</p>
                        <h6 class="mb-0">{{ money($todayRevenue) }}</h6>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-xl-3">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4 h-100">
                    <i class="fa fa-chart-pie fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Total Revenue</p>
                        <h6 class="mb-0">{{ money($totalRevenue) }}</h6>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Sales Charts --}}
    <div class="container-fluid pt-4 px-4 pb-5">
        <div class="row g-4">

            <div class="col-md-6">
                <div class="bg-light rounded-4 p-4 h-100">
                    <h6 class="mb-4">Sales — Last 7 Days</h6>
                    <div style="height: 300px;">
                        <canvas id="chart"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="bg-light rounded-4 p-4 h-100">
                    <h6 class="mb-4">Revenue — Last 7 Days</h6>
                    <div style="height: 300px;">
                        <canvas id="chart2"></canvas>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('script')
    {{-- Include Chart.js only if it is not already loaded in layouts.backend --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const labels = @json($chartLabels);
            const salesData = @json($salesChartData);
            const revenueData = @json($revenueChartData);

            const currencyFormatter = new Intl.NumberFormat('en-PK', {
                style: 'currency',
                currency: 'PKR',
                maximumFractionDigits: 2
            });

            const commonOptions = {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    intersect: false,
                    mode: 'index'
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom'
                    },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                return context.dataset.label + ': ' +
                                    currencyFormatter.format(context.parsed.y || 0);
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function (value) {
                                return currencyFormatter.format(value);
                            }
                        }
                    }
                }
            };

            new Chart(document.getElementById('chart'), {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Sales',
                        data: salesData,
                        borderColor: '#0d6efd',
                        backgroundColor: 'rgba(13, 110, 253, 0.12)',
                        fill: true,
                        tension: 0.35
                    }]
                },
                options: commonOptions
            });

            new Chart(document.getElementById('chart2'), {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Revenue',
                        data: revenueData,
                        backgroundColor: '#198754',
                        borderRadius: 5
                    }]
                },
                options: commonOptions
            });
        });
    </script>
@endpush
