<?php

namespace App\Http\Controllers\attendance;

use Carbon\Carbon;
use App\Models\User;
use App\Models\profiling\PD;
use Illuminate\Http\Request;
use App\Models\attendance\OT;
use App\Models\attendance\TC;
use App\Models\company\LeaveType;
use App\Http\Controllers\Controller;
use App\Models\company\LeaveTypeReq;
use App\Models\attendance\Leave as LT;
use App\Models\attendance\Leave as Liv;
use Illuminate\Support\Facades\Validator;

class Leave extends Controller
{
    public function index()
    {
        $user = Auth()->user();
        $approvers = User::where('co_id', $user->company->co_id)->where('id', '!=', $user->id)->get();
        $lts = LeaveType::where('co_id', $user->company->co_id)->get();
        return view('attendance.leave.index', compact('user', 'approvers', 'lts'));
    }
    public function requirements(Request $request)
    {
        $user = Auth()->user();
        $requirements = LeaveType::where('co_id', $user->company->co_id)->where('lt_id', $request->input('id'))->first();
        $reqs = $requirements->ltr;
        foreach ($reqs as $req) {
            $req = $req->dt;
        }
        return response()->json([
            'requirements' => $reqs
        ]);
    }
    public function submit(Request $request)
    {
        $user = Auth()->user();
        $rules = [
            'start_date' => 'required',
            'end_date' => 'required',
            'approver_id' => 'required',
            's_approver_id' => 'required',
            'message' => 'required'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'msgs' => $validator->errors()->toArray()
            ], 422);
        } else {
            $credits = Auth()->user()->leave_credit;
            $startDate = Carbon::parse($request->start_date);
            $endDate = Carbon::parse($request->end_date);
            $noOfDays = $startDate->diffInDays($endDate) + 1;
            if ($noOfDays > $credits) {
                return response()->json([
                    'notEnoughCredit' => true
                ], 422);
            }
            $application = Liv::create([
                'user_id' => $request->input('user_id'),
                'lt_id' => $request->input('lt_id'),
                'approver_id' => $request->input('approver_id'),
                's_approver_id' => $request->input('s_approver_id'),
                'start_date' => $request->input('start_date'),
                'end_date' => $request->input('end_date'),
                'message' => $request->input('message'),
                'status' => 'Waiting Approval'
            ]);
            $lt = LeaveType::where('co_id', $user->company->co_id)
                ->where('lt_id', $application->lt_id)->first();
            $requirements = $lt->ltr;
            foreach ($requirements as $req) {
                $photoPath = $request->file($req->dt->dt_id)->store('leave_requirement', 'public');
                $fullPath = storage_path('app/public/' . $photoPath);
                $fileSize = filesize($fullPath);
                $leave_req = PD::create([
                    'user_id' => $application->user_id,
                    'dt_id' => $req->dt->dt_id,
                    'filepath' => $photoPath,
                    'filesize' => $fileSize,
                    'leave_req' => $application->lv_req_id
                ]);
            }
            return response()->json([
                'application' => $application
            ]);
        }
    }
    public function overtime()
    {
        $user = Auth()->user();
        $approvers = User::where('co_id', $user->company->co_id)->where('id', '!=', $user->id)->get();
        return view('attendance.requests.overtime', compact('user', 'approvers'));
    }
    public function submit_ot(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'start' => 'required',
            'end' => 'required',
            'approver_id' => 'required',
            's_approver_id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'msgs' => $validator->errors()->toArray()
            ], 422);
        } else {
            $start = Carbon::parse($request->input('start'));
            $end = Carbon::parse($request->input('end'));

            if ($end->lt($start)) {
                return response()->json([
                    'inv_time' => true
                ], 422);
            }
            $ot = OT::create([
                'user_id' => $request->input('user_id'),
                'approver_id' => $request->input('approver_id'),
                's_approver_id' => $request->input('s_approver_id'),
                'start' => $request->input('start'),
                'end' => $request->input('end'),
                'status' => 'Waiting Approval',
                'reason' => $request->input('reason')
            ]);
            return response()->json([
                'fields' => $request->all()
            ]);
        }
    }
    public function time_correction()
    {
        $user = Auth()->user();
        $approvers = User::where('co_id', $user->company->co_id)->where('id', '!=', $user->id)->get();
        return view('attendance.requests.time_correction', compact('user', 'approvers'));
    }
    public function submit_tc(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'approver_id' => 'required',
            's_approver_id' => 'required',
            'original_clock_in' => 'required',
            'corrected_clock_in' => 'required',
            'original_clock_out' => 'required',
            'corrected_clock_out' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'msgs' => $validator->errors()->toArray()
            ], 422);
        } else {
            $tc = TC::create([
                'user_id' => $request->input('user_id'),
                'approver_id' => $request->input('approver_id'),
                's_approver_id' => $request->input('s_approver_id'),
                'original_clock_in' => $request->input('original_clock_in'),
                'corrected_clock_in' => $request->input('corrected_clock_in'),
                'original_clock_out' => $request->input('original_clock_out'),
                'corrected_clock_out' => $request->input('corrected_clock_out'),
                'status' => 'Waiting Approval',
                'reason' => $request->input('reason')
            ]);
            return response()->json([
                'fields' => $request->all()
            ]);
        }
    }
}
