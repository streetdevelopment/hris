@extends('layouts.layout')
@section('title', 'HRIS - Payroll Runs')
@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Payroll Runs</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{auth()->user()->company->co_name}}</a></li>
                                <li class="breadcrumb-item active">Payroll Runs</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('payroll.index') }}" id='run-search-form' method="GET">
                                <div class="d-flex justify-content-between mb-2">
                                    <div>
                                        <div class='d-flex gap-2'>
                                            <input type="text" class="form-control bg-light" value="{{ Auth()->user()->company->co_name }}" readonly>
                                            <input type="number" step='0' class="form-control" id="run-search" name="run-search" autocomplete="off" placeholder="Search payroll run" value="{{ request('run-search') }}">
                                            <button type="button" id="clear-search" class="btn btn-sm btn-secondary" style="display: none;">
                                                <span class="bx bx-x mx-1"></span>
                                            </button>
                                            <a href="#collapseExample" data-bs-toggle="collapse" class="btn btn-sm w-50 btn-secondary d-flex align-items-center justify-content-center collapsed" aria-expanded="false">
                                                <i class="bx bx-filter-alt align-middle me-2"></i> Advanced
                                            </a>
                                        </div>
                                    </div>
                                    <div>
                                        <button type="submit" class='btn btn-md btn-primary'><span class="bx bx-search-alt me-1"></span>Filter</button>
                                        <a href="{{route('payroll.create')}}" class="btn btn-md btn-primary">Process Payroll</a>
                                        <a href="{{route('payroll.index')}}" class="btn btn-light"><i class="mdi mdi-refresh"></i></a>
                                    </div>
                                </div>
                                <div class="collapse my-3" id="collapseExample">
                                    <div>
                                        <div class="row g-3">
                                            <div class="col-xxl-4 col-lg-6">
                                                <label for="select-status" class="form-label fw-semibold">Status</label>
                                                <div class="position-relative">
                                                    <select name="status" id="select-status" class="form-control">
                                                        <option value="">None Selected</option>
                                                        <option value="Processed" {{$status == 'Processed' ? 'selected' : ''}}>Processed</option>
                                                        <option value="Pending" {{$status == 'Pending' ? 'selected' : ''}}>Pending</option>
                                                        <option value="Canceled" {{$status == 'Canceled' ? 'selected' : ''}}>Canceled</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-xxl-4 col-lg-6">
                                                <div class="row">
                                                    <div class="position-relative d-flex flex-column">
                                                        <div class="col-12">
                                                            <label for="qualificationInput" class="form-label fw-semibold">Run Date</label>
                                                        </div>
                                                        <div class='col-12 d-flex gap-3'>
                                                            <div class='d-flex align-items-center'>
                                                                <h6 class="mb-0">From</h6>
                                                            </div>
                                                            <div class="col-6">
                                                                <input type="date" value="{{$run_start}}" class="form-control" name="run_date_start" id="run_date_start" autocomplete="off">
                                                            </div>
                                                            <div class='d-flex align-items-center'>
                                                                <h6 class="mb-0">To</h6>
                                                            </div>
                                                            <div class="col-6">
                                                                <input type="date" value="{{$run_end}}" class="form-control" name="run_date_end" id="run_date_end" autocomplete="off">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div class="table-responsive">

                                <table class="table align-middle table-nowrap table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col" style="width: 70px;">#</th>
                                            <th scope="col">Run Date</th>
                                            <th scope="col">Pay Period Start</th>
                                            <th scope="col">Pay Period End</th>
                                            <th scope="col">Total Gross Pay</th>
                                            <th scope="col">Total NET Pay</th>
                                            <th scope="col">Status</th>
                                            <th scope='col'>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($runs as $run)
                                        <tr>
                                            <td>
                                                {{$loop->iteration}}
                                            </td>
                                            <td>
                                                {{$run->run_date ? \Carbon\Carbon::parse($run->run_date)->format('F j, Y') : 'N/A'}}
                                            </td>
                                            <td>
                                                {{$run->pay_period_start ? \Carbon\Carbon::parse($run->pay_period_start)->format('F j, Y') : 'N/A'}}
                                            </td>
                                            <td>
                                                {{$run->pay_period_end ? \Carbon\Carbon::parse($run->pay_period_end)->format('F j, Y') : 'N/A'}}
                                            </td>
                                            <td>
                                                ₱ {{$run->total_gross_pay ? $run->total_gross_pay : 'N/A'}}
                                            </td>
                                            <td>
                                                ₱ {{$run->total_net_pay ? $run->total_net_pay : 'N/A'}}
                                            </td>
                                            <td>
                                                <span class='badge {{$run->badge()}}'>{{$run->status}}</span>
                                            </td>
                                            <td>
                                                <a href="{{route('payroll.run.view', ['id' => $run->payroll_run_id])}}"><i class='mdi mdi-eye font-size-22 text-primary' style='cursor: pointer;'></i></a>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan='8' class='text-center'>No Records Found</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('more_scripts')
<script>
    document.getElementById('clear-search').addEventListener('click', function() {
        document.getElementById('run-search').value = '';

        document.getElementById('run_date_start').value = '';

        document.getElementById('run_date_end').value = '';

        document.getElementById('select-status').value = '';

        $('#clear-search').hide();
    });

    $('#run-search').change(function() {
        $(this).val() == '' ? $('#clear-search').hide() : $('#clear-search').show();
    });

    $('#run-search').val() == '' ? $('#clear-search').hide() : $('#clear-search').show();
</script>

@endsection