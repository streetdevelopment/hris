<?php

namespace App\Http\Controllers\company;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\company\Document as DT;

class Document extends Controller
{
    public function index()
    {
        $dts = DT::all()->where('co_id', Auth()->user()->company->co_id);
        return view('company.documents.index', compact('dts'));
    }
    public function reload()
    {
        $dts = DT::all()->where('co_id', Auth()->user()->company->co_id);
        return view('company.documents.list', compact('dts'))->render();
    }
    public function delete(Request $request)
    {
        $target = DT::find($request->input('id'));
        $target->delete();
        return response()->json([
            'name' => $target->dt_name
        ]);
    }
    public function find(Request $request)
    {
        $target = DT::find($request->input('id'));
        return response()->json([
            'target' => $target
        ]);
    }
    public function edit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'modal_dt_name' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'msgs' => $validator->errors()->toArray()
            ], 422);
        } else {
            $target = DT::find($request->input('modal_dt_id'));
            $target->dt_name = $request->input('modal_dt_name');
            $target->save();
            return response()->json([
                'name' => $target->dt_name
            ]);
        }
    }
    public function submit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'dt_name' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'msgs' => $validator->errors()->toArray()
            ], 422);
        } else {
            $doc = DT::create([
                'co_id' => $request->input('co_id'),
                'dt_name' => $request->input('dt_name')
            ]);
            return response()->json([
                'name' => $request->input('dt_name')
            ]);
        }
    }
}
