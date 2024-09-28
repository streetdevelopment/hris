<?php

namespace App\Http\Controllers\company;

use Illuminate\Http\Request;
use App\Models\company\LeaveType;
use App\Http\Controllers\Controller;
use App\Models\company\LeaveTypeReq;
use App\Models\company\Document as DT;
use App\Models\company\LeaveType as LT;
use Illuminate\Support\Facades\Validator;

class Leave extends Controller
{
    public function index()
    {
        $records = Auth()->user()->company->leavetypes;
        return view('company.leavetypes.index', compact('records'));
    }
    public function create()
    {
        $dts = DT::all()->where('co_id', Auth()->user()->company->co_id);
        return view('company.leavetypes.create', compact('dts'));
    }
    public function edit($id)
    {
        $lt = LeaveType::find($id);
        $ltrs = $lt->ltr;
        $dts = DT::all()->where('co_id', Auth()->user()->company->co_id);
        return view('company.leavetypes.view', compact('lt', 'ltrs', 'dts'));
    }
    public function edit_submit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'requirements' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'msgs' => $validator->errors()->toArray()
            ], 422);
        } else {
            $lt = LeaveType::find($request->input('lt_id'));
            $lt->name = $request->input('name');
            $lt->description = $request->input('description');
            foreach ($lt->ltr as $ltr) {
                $ltr->delete();
            }
            foreach ($request->input('requirements') as $req) {
                $ltr = LeaveTypeReq::create([
                    'lt_id' => $lt->lt_id,
                    'dt_id' => $req
                ]);
            }
            $lt->save();
            return response()->json([
                'name' => $lt->name
            ]);
        }
    }
    public function delete($id)
    {
        $lt = LeaveType::find($id);
        foreach ($lt->ltr as $ltr) {
            $ltr->delete();
        }
        $lt->delete();
        return redirect()->route('company.leavetypes.index');
    }
    public function submit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'requirements' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'msgs' => $validator->errors()->toArray()
            ], 422);
        } else {
            $lt = LeaveType::create([
                'co_id' => Auth()->user()->company->co_id,
                'name' => $request->input('name'),
                'description' => $request->input('description')
            ]);
            foreach ($request->input('requirements') as $req) {
                $ltr = LeaveTypeReq::create([
                    'lt_id' => $lt->lt_id,
                    'dt_id' => $req
                ]);
            }
            return response()->json([
                'name' => $lt->name
            ]);
        }
    }
}
