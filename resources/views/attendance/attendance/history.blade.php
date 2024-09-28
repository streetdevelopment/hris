@extends('layouts.layout')
@section('title', 'HRIS - Attendance History')
@section('more_links')
<style>
    .timeline-with-icons {
        border-left: 1px solid hsl(0, 0%, 90%);
        position: relative;
        list-style: none;
    }

    .timeline-with-icons .timeline-item {
        position: relative;
    }

    .timeline-with-icons .timeline-item:after {
        position: absolute;
        display: block;
        top: 0;
    }

    .timeline-with-icons .timeline-icon {
        position: absolute;
        left: -48px;
        background-color: hsl(217, 88.2%, 90%);
        color: hsl(217, 88.8%, 35.1%);
        border-radius: 50%;
        height: 31px;
        width: 31px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>
@endsection
@section('content')
<div class="main-content">

    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">History</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                                <li class="breadcrumb-item active">Attendance History</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <form action="{{Route('attendance.attendance.history')}}" id="history-search-form" method="GET">
                                    <div class="d-flex gap-2">
                                        <input type="text" class="form-control bg-light" value="Street Development" readonly="">
                                        <input type="text" class="form-control bg-light" value="{{$user->fullname()}}" readonly="">
                                        <p class='my-auto'><b>From</b></p>
                                        <input type="date" name='from_date' value="{{$from_date}}" class="form-control">
                                        <p class='my-auto'><b>To</b></p>
                                        <input type="date" name='to_date' value="{{$to_date}}" class="form-control">
                                        <button type="submit" class="btn btn-sm btn-primary"><span class="bx bx-search-alt mx-1"></span></button>
                                    </div>
                                </form>

                                <div>
                                    @if(Auth()->user()->id == $user->id)
                                    <a href="{{Route('attendance.attendance.index')}}" class="btn btn-primary">{{$user->hasTimedIn() ? 'Clock Out' : 'Clock In'}}</a>
                                    @endif
                                    <a href="{{Route('attendance.attendance.history')}}" class="btn btn-light ms-1"><i class="mdi mdi-refresh"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-9">
                    <div class="card p-4">
                        <div class="card-body">
                            <!-- Section: Timeline -->
                            <section>
                                <ul class="timeline-with-icons">
                                    @forelse($records as $record)
                                    <li class="timeline-item mb-5">
                                        <span class="timeline-icon">
                                            <i class="fas fa-arrow-alt-circle-up text-primary fa-lg fa-fw"></i>
                                        </span>

                                        <h5 class="fw-bold">You clocked in at <span class='text-success'>{{ \Carbon\Carbon::parse($record->time_in)->format('h:i A') }}</span></h5>
                                        <p class="text-muted mb-2 fw-bold">{{ \Carbon\Carbon::parse($record->date)->format('F j, Y') }}</p>
                                        @if(Auth()->user()->company->policies->enable_camera === 1)
                                        @if($record->time_in_image !== null)
                                        <img class='img-thumbnail mb-2' src="{{ asset('storage/attendance/' . $record->time_in_image) }}" alt="Time In Photo">
                                        @endif
                                        @endif
                                        <p class="text-warning">
                                            @if($record->status == 'late')
                                            You signed in {{ ucfirst($record->status) }}
                                            @endif
                                        </p>
                                    </li>
                                    @if(isset($record->time_out))
                                    <li class="timeline-item mb-5">
                                        <span class="timeline-icon">
                                            <i class="fas fa-arrow-alt-circle-down text-primary fa-lg fa-fw"></i>
                                        </span>

                                        <h5 class="fw-bold">You clocked out at <span class='text-danger'>{{ \Carbon\Carbon::parse($record->time_out)->format('h:i A') }}</span></h5>
                                        <p class="text-muted mb-2 fw-bold">{{ \Carbon\Carbon::parse($record->date)->format('F j, Y') }}</p>
                                        @if(Auth()->user()->company->policies->enable_camera === 1)
                                        @if($record->time_in_image !== null)
                                        <img class='img-thumbnail mb-2' src="{{ asset('storage/attendance/' . $record->time_out_image) }}" alt="Time Out Photo">
                                        @endif
                                        @endif
                                        <p class="text-muted">
                                            {{ $record->time_out_remark }}
                                        </p>
                                    </li>
                                    @endif
                                    @if($loop->last)
                                    <li class='timeline-item mb-5'>
                                        <span class="timeline-icon">
                                            <i class="fas fas fa-long-arrow-alt-up text-primary fa-lg fa-fw"></i>
                                        </span>
                                        <h5 class="fw-bold">Nothing Follows</h5>
                                    </li>
                                    @endif
                                    @empty
                                    <li class='timeline-item mb-5'>
                                        <span class="timeline-icon">
                                            <i class="fas fa-exclamation-triangle text-primary fa-lg fa-fw"></i>
                                        </span>
                                        <h5 class="fw-bold">No Records Found</h5>
                                    </li>
                                    @endforelse
                                </ul>
                            </section>
                            <!-- Section: Timeline -->
                        </div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="card" style='height: 500px;'>
                        <div class="card-body">
                            <p class='text-center'>Card for potential advertisements</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
@section('more_scripts')
@endsection