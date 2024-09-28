<?php

namespace App\Http\Controllers\company;

use Illuminate\Http\Request;
use App\Models\company\Policies;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class Policy extends Controller
{
    public function index()
    {
        $setup = Auth()->user()->company->policies ? 1 : 0;
        if ($setup === 1) {
            $policies = Policies::find(Auth()->user()->company->policies->att_pol_id);
            return view('company.policies.index', compact('setup', 'policies'));
        }
        return view('company.policies.index', compact('setup'));
    }
    public function submit(Request $request)
    {
        $user = auth()->user();
        $setup = $user->company->policies ? 1 : 0;

        $validator = Validator::make($request->all(), [
            'grace_period' => 'required',
            'yearly_leave_credit' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'msgs' => $validator->errors()->toArray()
            ], 422);
        }

        if ($setup === 0) {
            $policy = Policies::create([
                'co_id' => $user->company->co_id,
                'grace_period' => $request->input('grace_period'),
                'yearly_leave_credit' => $request->input('yearly_leave_credit'),
                'enable_auto_time_out' => $request->input('enable_auto_time_out') ? 1 : 0,
                'enable_camera' => $request->input('enable_camera') ? 1 : 0,
                'enable_gps' => $request->input('enable_gps') ? 1 : 0,
                'enable_overtime' => $request->input('enable_overtime') ? 1 : 0,
                'enable_time_correction_request' => $request->input('enable_time_correction_request') ? 1 : 0,
                'late_deduction' => $request->input('late_deduction')
            ]);
            $verb = 'created';
        } else {
            $policy = $user->company->policies;
            $policy->update([
                'grace_period' => $request->input('grace_period'),
                'yearly_leave_credit' => $request->input('yearly_leave_credit'),
                'enable_auto_time_out' => $request->input('enable_auto_time_out') ? 1 : 0,
                'enable_camera' => $request->input('enable_camera') ? 1 : 0,
                'enable_gps' => $request->input('enable_gps') ? 1 : 0,
                'enable_overtime' => $request->input('enable_overtime') ? 1 : 0,
                'late_deduction' => $request->input('late_deduction'),
                'enable_time_correction_request' => $request->input('enable_time_correction_request') ? 1 : 0
            ]);
            $verb = 'updated';
        }

        return response()->json([
            'verb' => $verb
        ]);
    }
}
