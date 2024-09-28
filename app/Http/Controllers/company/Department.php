<?php

namespace App\Http\Controllers\company;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\attendance\AR;
use Illuminate\Validation\Rule;
use App\Models\company\Position;
use App\Rules\NoDepartmentInName;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\company\Department as Dep;
use Illuminate\Support\Facades\Validator;

class Department extends Controller
{
    public function index(Request $request)
    {
        $query = Dep::where('co_id', Auth()->user()->company->co_id);
        if ($request->has('dep-search') && !empty($request->input('dep-search'))) {
            $query->where('dep_name', 'like', '%' . $request->input('dep-search') . '%');
        }

        $departments = $query->get();
        return view('company.departments.index', compact('departments'));
    }
    public function view_employee($id)
    {
        $user = User::find($id);
        $pi = $user->userPI;
        $psi = $user->userPSI;
        $ci = $user->userCI;
        $pd = $user->userPD;
        $dts = Auth()->user()->company->documents;
        return view('company.departments.employee', compact('pi', 'psi', 'ci', 'pd', 'dts', 'user'));
    }
    public function view_attendance($id, Request $request)
    {
        $user = User::find($id);
        $from_date = '';
        $to_date = '';

        $query = AR::where('user_id', $user->id);

        if ($request->has('from_date') && $request->has('to_date')) {
            $from_date = Carbon::parse($request->input('from_date'))->startOfDay();
            $to_date = Carbon::parse($request->input('to_date'))->endOfDay();
            $from_date = Carbon::parse($from_date)->format('Y-m-d');
            $to_date = Carbon::parse($to_date)->format('Y-m-d');
            $query->whereBetween('date', [$from_date, $to_date]);
        }
        $query->orderBy('date', 'desc');
        $records = $query->get();

        return view('attendance.attendance.history', compact('user', 'records', 'from_date', 'to_date'));
    }
    public function create()
    {
        $users = User::all();
        return view('company.departments.create', compact('users'));
    }
    public function all()
    {
        $departments = DB::SELECT('SELECT * FROM department');
        return response()->json(['departments' => $departments]);
    }
    public function new(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'co_id' => 'required',
            'dep_name' => 'required',
            'location' => 'nullable',
            'phone_number' => 'required|numeric',
            'email_address' => 'required',
            'status' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'validation' => true,
                'messages' => $validator->errors()->toArray()
            ], 422);
        } else {
            $department = Dep::create([
                'co_id' => $request->input('co_id_val'),
                'dep_name' => $request->input('dep_name'),
                'location' => $request->input('location'),
                'phone_number' => $request->input('phone_number'),
                'email_address' => $request->input('email_address'),
                'status' => $request->input('status')
            ]);
            return response()->json([
                'name' => $request->input('dep_name'),
            ]);
        }
    }
    public function view($id)
    {
        $target = Dep::find($id);
        return view('company.departments.view', compact('target'));
    }
    public function edit($id)
    {
        $target = Dep::find($id);
        return view('company.departments.edit', compact('target'));
    }
    public function delete(Request $request)
    {
        $target = Dep::find($request->input('id'));
        if ($target->positions->count() > 1) {
            return response()->json([
                'msgs' => 'You cannot delete a department with employees'
            ], 422);
        } else {
            $target->delete();
            return response()->json([
                'route' => '{{route("company.departments.index")}}'
            ]);
        }
    }
    public function edit_submit(Request $request)
    {
        $dep = Dep::find($request->input('dep_id'));
        $similarity = 5;
        $dep->dep_name == $request->input('dep_name') ?  $similarity -= 1 : $similarity = $similarity;
        $dep->location == $request->input('location') ?  $similarity -= 1 : $similarity = $similarity;
        $dep->phone_number == $request->input('phone_number') ?  $similarity -= 1 : $similarity = $similarity;
        $dep->email_address == $request->input('email_address') ?  $similarity -= 1 : $similarity = $similarity;
        $dep->status == $request->input('status') ?  $similarity -= 1 : $similarity = $similarity;
        if ($similarity > 0) {
            $validator = Validator::make($request->all(), [
                'co_id' => 'required',
                'dep_name' => [
                    'required',
                    Rule::unique('department')->ignore($dep)
                ],
                'location' => 'nullable',
                'phone_number' => 'required|numeric',
                'email_address' => 'required',
                'status' => 'required'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'validation' => true,
                    'messages' => $validator->errors()->toArray()
                ], 422);
            } else {
                $dep->update([
                    'dep_name' => $request->input('dep_name'),
                    'location' => $request->input('location'),
                    'phone_number' => $request->input('phone_number'),
                    'email_address' => $request->input('email_address'),
                    'status' => $request->input('status')
                ]);
                return response()->json([
                    'name' => $dep->dep_name
                ]);
            }
        } else {
            return response()->json([
                'msg' => 'No changes detected'
            ], 422);
        }
    }
}
