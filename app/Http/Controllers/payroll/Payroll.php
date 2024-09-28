<?php

namespace App\Http\Controllers\payroll;

use App\Models\payroll\Run;
use Illuminate\Http\Request;
use App\Models\company\JobTypes;
use App\Models\company\Department;
use App\Http\Controllers\Controller;

class Payroll extends Controller
{
    public function index(Request $request)
    {
        $status = null;
        $run_search = null;
        $run_start = null;
        $run_end = null;
        $query = Run::where('co_id', Auth()->user()->company->co_id)
            ->orderBy('created_at', 'desc');

        if ($request->has('run-search') && !empty($request->input('run-search'))) {
            $query->where('payroll_run_id', 'like', '%' . $request->input('run-search') . '%');
            $run_search = $request->input('run-search');
        }

        if ($request->has('status') && !empty($request->input('status'))) {
            $query->where('status', 'like', '%' . $request->input('status') . '%');
            $status = $request->input('status');
        }

        if (
            $request->has('run_date_start') && $request->has('run_date_end') &&
            !empty($request->input('run_date_start')) && !empty($request->input('run_date_end'))
        ) {
            $query->whereBetween('run_date', [$request->input('run_date_start'), $request->input('run_date_end')]);
            $run_start = $request->input('run_date_start');
            $run_end = $request->input('run_date_end');
        }

        $runs = $query->get();

        return view('payroll.index', compact('runs', 'run_start', 'run_end', 'run_search', 'status'));
    }

    public function create()
    {
        $departments = Department::where('co_id', Auth()->user()->company->co_id)->get();
        $jts = JobTypes::where('co_id', Auth()->user()->company->co_id)->get();
        return view('payroll.create', compact('departments', 'jts'));
    }
}
