<?php

namespace App\Http\Controllers\company;

use Illuminate\Http\Request;
use App\Models\profiling\PSI;
use App\Models\company\Position;
use App\Models\company\Department;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class Setup extends Controller
{
    public function initial_profiling($user_id)
    {
        if (Auth()->user()->company->setup('profile')->status == 'Completed') {
            return redirect()->route('dashboard')->with('msg', "You've already setup your admin account profile");
        }
        $departments = Department::where('co_id', Auth()->user()->company->co_id)->get();
        return view('company.setup.initial_profiling', compact('user_id', 'departments'));
    }
    public function validate_fields(Request $request)
    {
        if ($request->input('existing_department') == 'new') {
            $validator = Validator::make($request->all(), [
                'dep_name' => 'required',
                'dep_status' => 'required',
                'phone_number' => 'required',
                'dep_email_address' => 'required'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'msgs' => $validator->errors()->toArray()
                ]);
            } else {
                return response()->json([
                    'status' => true
                ]);
            }
        } else {
            return response()->json([
                'status' => true
            ]);
        }
    }
    public function submit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'jt_id' => 'required',
            'salary' => 'required',
            'date_hired' => 'required',
            'status' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'msgs' => $validator->errors()->toArray()
            ], 422);
        } else {
            if ($request->input('existing_department') == 'new') {
                $department = Department::create([
                    'co_id' => Auth()->user()->company->co_id,
                    'dep_name' => $request->input('dep_name'),
                    'location' => $request->input('location'),
                    'phone_number' => $request->input('phone_number'),
                    'email_address' => $request->input('dep_email_address'),
                    'status' => $request->input('dep_status'),
                ]);
            } else {
                $dep = Department::find($request->input('existing_department'));
            }
            $position = Position::create([
                'dep_id' => isset($dep) ? $dep->dep_id : $department->dep_id,
                'jt_id' => $request->input('jt_id'),
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'salary' => $request->input('salary'),
                'status' => $request->input('status')
            ]);
            $psi = PSI::create([
                'user_id' => Auth()->user()->id,
                'pos_id' => $position->pos_id,
                'date_hired' => $request->input('date_hired'),
                'status' => 'Active'
            ]);
            return response()->json([
                'fields' => $request->all()
            ]);
        }
    }
}
