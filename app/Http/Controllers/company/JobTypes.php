<?php

namespace App\Http\Controllers\company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\company\JobTypes as JT;

class JobTypes extends Controller
{
    public function index()
    {
        $jts = JT::all()->where('co_id', Auth()->user()->company->co_id);
        return view('company.jobtypes.index', compact('jts'));
    }
    public function create()
    {
        return view('company.jobtypes.create');
    }
    public function edit($id)
    {
        $jt = JT::find($id);
        return view('company.jobtypes.edit', compact('jt'));
    }
    public function edit_submit(Request $request)
    {
        $rules = ['jt_name' => 'required', 'pay_rate' => 'required', 'status' => 'required'];
        if ($request->input('fixed_schedule')) {
            $rules['start_time'] = 'required';
            $rules['end_time'] = 'required';
        }
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'msgs' => $validator->errors()->toArray()
            ]);
        } else {
            $jt = JT::find($request->input('jt_id'));
            $jt->jt_name = $request->input('jt_name');
            $jt->start_time = $request->input('start_time');
            $jt->end_time = $request->input('end_time');
            $jt->pay_rate = $request->input('pay_rate');
            $jt->status = $request->input('status');
            $jt->save();
            return response()->json([
                'jt_name' => $request->input('jt_name')
            ]);
        }
    }
    public function delete($id)
    {
        $target = JT::find($id);
        $target->delete();
        return response()->json([
            'jt_name' => $target->jt_name
        ]);
    }
    public function submit(Request $request)
    {
        $rules = ['jt_name' => 'required', 'pay_rate' => 'required', 'status' => 'required'];
        if ($request->input('fixed_schedule')) {
            $rules['start_time'] = 'required';
            $rules['end_time'] = 'required';
        }
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'msgs' => $validator->errors()->toArray()
            ]);
        } else {
            $jobtype = JT::create([
                'co_id' => Auth()->user()->company->co_id,
                'jt_name' => $request->input('jt_name'),
                'work_on_sat' => $request->input('work_on_sat') ? 1 : 0,
                'work_on_sun' => $request->input('work_on_sun') ? 1 : 0,
                'status' => $request->input('status'),
                'pay_rate' => $request->input('pay_rate')
            ]);
            if ($request->input('fixed_schedule')) {
                $jobtype->fixed_schedule = 1;
                $jobtype->start_time = $request->input('start_time');
                $jobtype->end_time = $request->input('end_time');
            } else {
                $jobtype->fixed_schedule = 0;
                $jobtype->start_time = null;
                $jobtype->end_time = null;
            }
            $jobtype->save();
            return response()->json([
                'jt_name' => $request->input('jt_name')
            ]);
        }
    }
}
