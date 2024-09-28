@extends('layouts.layout')
@section('title', 'HRIS - Leave Applications')
@section("content")
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Leave Applications</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">{{auth()->user()->company->co_name}}</a></li>
                                <li class="breadcrumb-item active">Leave Applications</li>
                            </ol>
                        </div>

                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table align-middle table-nowrap table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col" style="width: 70px;">#</th>
                                            <th scope="col">Employee</th>
                                            <th scope="col">Leave Type</th>
                                            <th scope="col">No. of Days</th>
                                            <th scope="col">Approver</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $count = 0; ?>
                                        @foreach($applications as $app)
                                        @forelse($app as $req)
                                        <tr>
                                            <td>
                                                <div class="avatar-xs">
                                                    @if($req->user->userPI->photo)
                                                    <img class="rounded-circle avatar-xs" src="{{asset('storage/' . $req->user->userPI->photo)}}" alt="">
                                                    @else
                                                    <span class="avatar-title rounded-circle">
                                                        {{$req->user->fullname()[0]}}
                                                    </span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <h5 class="font-size-14 mb-1"><a href="javascript: void(0);" class="text-dark">{{$req->user->fullname()}}</a></h5>
                                            </td>
                                            <td>{{$req->lt->name}}</td>
                                            <td>
                                                {{$req->numberOfDays()}} Days
                                            </td>
                                            <td>
                                                {{$req->approver->fullname()}}
                                            </td>
                                            <td>
                                                <span class="badge {{$req->badge()}}">{{$req->status ? $req->status : 'Waiting for Approval'}}</span>
                                            </td>
                                            <td>
                                                <a href="{{route('attendance.applications.view_leave', ['id' => $req->lv_req_id])}}"><i class="mdi mdi-eye font-size-22 my-auto text-dark"></i></a>
                                            </td>
                                        </tr>
                                        <?php $count += 1; ?>
                                        @empty
                                        @endforelse
                                        @endforeach
                                        @if($count === 0)
                                        <tr class='text-center'>
                                            <td colspan="7">No Applications Found</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <ul class="pagination pagination-rounded justify-content-center mt-4">
                                        <li class="page-item disabled">
                                            <a href="javascript: void(0);" class="page-link"><i class="mdi mdi-chevron-left"></i></a>
                                        </li>
                                        <li class="page-item">
                                            <a href="javascript: void(0);" class="page-link">1</a>
                                        </li>
                                        <li class="page-item active">
                                            <a href="javascript: void(0);" class="page-link">2</a>
                                        </li>
                                        <li class="page-item">
                                            <a href="javascript: void(0);" class="page-link">3</a>
                                        </li>
                                        <li class="page-item">
                                            <a href="javascript: void(0);" class="page-link">4</a>
                                        </li>
                                        <li class="page-item">
                                            <a href="javascript: void(0);" class="page-link">5</a>
                                        </li>
                                        <li class="page-item">
                                            <a href="javascript: void(0);" class="page-link"><i class="mdi mdi-chevron-right"></i></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection