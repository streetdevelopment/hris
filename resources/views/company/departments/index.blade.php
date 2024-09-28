@section('title', 'Departments - List')
@extends('layouts.layout')
@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="containter-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Departments</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active">Departments</li>
                            </ol>
                        </div>

                    </div>
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <form action="{{ route('company.departments.index') }}" id='dep-search-form' method="GET">
                                    <div class='d-flex gap-2'>
                                        <input type="text" class="form-control bg-light" value="{{ Auth()->user()->company->co_name }}" readonly>
                                        <input type="text" class="form-control" id="dep-search" name="dep-search" autocomplete="off" placeholder="Search department" value="{{ request('dep-search') }}">
                                        <button type="button" id="clear-search" class="btn btn-sm btn-secondary" style="display: none;">
                                            <span class="bx bx-x mx-1"></span>
                                        </button>
                                        <button type="submit" class='btn btn-sm btn-primary'><span class="bx bx-search-alt mx-1"></span></button>
                                    </div>
                                </form>

                                <div>
                                    @if(Auth()->user()->role == 'admin')
                                    <a href="{{route('company.departments.create')}}" class="btn btn-primary">Add New</a>
                                    @endif
                                    <a href="{{route('company.departments.index')}}" class="btn btn-light ms-1"><i class="mdi mdi-refresh"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @forelse($departments as $dep)
                <div class="col-xl-4 col-sm-6 mt-2">
                    <div class="card" style="width: 100%; height: 100%;">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="flex-grow-1 overflow-hidden">
                                    @if(request('dep-search'))
                                    <p>Showing results for "{{ request('dep-search') }}"</p>
                                    @endif
                                    <h5 class="text-truncate font-size-15"><a href="{{route('company.departments.view', ['id' => $dep->dep_id])}}" class="text-dark">{{$dep->dep_name}} Department</a></h5>
                                    <p class="text-muted mb-4">{{$dep->email_address}}</p>
                                    <div class="avatar-group">
                                        @forelse($dep->positions as $pos)
                                        <div class="avatar-group-item">
                                            @if($pos->PSI->user->userPI->photo)
                                            <a href="{{route('company.departments.employee.view', ['id' => $pos->PSI->user->id])}}" class="d-inline-block">
                                                <img src="{{asset('storage/' . $pos->PSI->user->userPI->photo)}}" alt="" class="rounded-circle avatar-xs">
                                            </a>
                                            @else
                                            <div class="avatar-xs">
                                                <span class="avatar-title rounded-circle bg-success text-white font-size-16">
                                                    {{$pos->PSI->user->fullname()[0]}}
                                                </span>
                                            </div>
                                            @endif
                                        </div>
                                        @empty
                                        <p class='font-style-italic'>No employees at the moment</p>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="px-4 py-3 border-top">
                            <ul class="list-inline mb-0">
                                <li class="list-inline-item me-3">
                                    <span class="badge {{$dep->badge()}}">{{$dep->status}}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                @empty
                <h4 class='text-center'>No departments found</h4>
                @endforelse
            </div>
        </div>
    </div>
</div>
</div>
@endsection
@section('more_scripts')
<script>
    document.getElementById('clear-search').addEventListener('click', function() {
        document.getElementById('dep-search').value = '';
        $('#clear-search').hide()
    });
    $('#dep-search').change(function() {
        $(this).val() == '' ? $('#clear-search').hide() : $('#clear-search').show()
    })
    $('#dep-search').val() == '' ? $('#clear-search').hide() : $('#clear-search').show()
</script>
@endsection