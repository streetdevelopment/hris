<?php

namespace App\Http\Controllers\company;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\company\Deductions as Deds;

class Deductions extends Controller
{
    public function index()
    {
        $deductions = Deds::where('co_id', Auth()->user()->company->co_id)->get();
        return view('company.deductions.index', compact('deductions'));
    }
    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'deduction_type' => 'required',
            'value' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'msgs' => $validator->errors()->toArray()
            ], 422);
        } else {
            $deduction = Deds::create([
                'co_id' => $request->input('co_id'),
                'deduction_type' => $request->input('deduction_type'),
                'description' => $request->input('description'),
                'value' => $request->input('value'),
                'unit' => '₱'
            ]);
            return response()->json([
                'fields' => $request->all()
            ]);
        }
    }
    public function reload()
    {
        $deductions = Deds::where('co_id', Auth()->user()->company->co_id)->get();
        return view('company.deductions.list', compact('deductions'))->render();
    }
    public function delete(Request $request)
    {
        $deduction = Deds::find($request->input('id'));
        $deduction->delete();
        return response()->json([
            'name' => $deduction->deduction_type
        ]);
    }
    public function find(Request $request)
    {
        $target = Deds::find($request->input('id'));
        return response()->json([
            'deduction' => $target
        ]);
    }
    public function edit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'msgs' => $validator->errors()->toArray()
            ], 422);
        } else {
            $target = Deds::find($request->input('modal_id'));
            $target->status = $request->input('status');
            $target->save();
            return response()->json([
                'name' => $target->deduction_type
            ]);
        }
    }
}
