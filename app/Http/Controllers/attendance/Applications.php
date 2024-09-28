<?php

namespace App\Http\Controllers\attendance;

use Carbon\Carbon;
use App\Models\User;
use App\Models\profiling\PD;
use Illuminate\Http\Request;
use App\Models\attendance\OT;
use App\Models\attendance\Leave;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class Applications extends Controller
{
    public function leave()
    {
        $users = Auth()->user()->company->users;
        $applications = [];
        foreach ($users as $user) {
            array_push($applications, $user->leaveRequests);
        }
        return view('attendance.applications.leave', compact('applications'));
    }
    public function edit_leave_credits(Request $request)
    {
        $target = User::find($request->input('employee_id'));
        $validator = Validator::make($request->all(), [
            'employee_leave_credit' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'msgs' => $validator->errors()->toArray()
            ], 422);
        } elseif ($target->leave_credit == $request->input('employee_leave_credit')) {
            return response()->json([
                'same_lc' => true
            ], 422);
        } else {
            $target->leave_credit = $request->input('employee_leave_credit');
            $target->save();
            return response()->json([
                'success' => true
            ]);
        }
    }
    public function view_leave($id)
    {
        $application = Leave::find($id);
        return view('attendance.applications.view_leave', compact('application'));
    }
    public function leave_credits()
    {
        $employees = User::where('co_id', Auth()->user()->company->co_id)->get();
        return view('attendance.applications.leave_credits', compact('employees'));
    }
    public function overtime()
    {
        $users = Auth()->user()->company->users;
        $applications = [];
        foreach ($users as $user) {
            array_push($applications, $user->overtimeRequests);
        }
        return view('attendance.applications.overtime', compact('applications'));
    }
    public function view_overtime($id)
    {
        $application = OT::find($id);
        return view('attendance.applications.view_overtime', compact('application'));
    }
    public function time_correction()
    {
        return view('attendance.applications.time_correction');
    }
    public function download(Request $request)
    {
        $file = PD::find($request->input('id'));

        $filePath = $file->filepath;

        if (!Storage::disk('public')->exists($filePath)) {
            return response()->json(['error' => 'File does not exist'], 404);
        }

        $pathOfFile = storage_path('app/public/' . $filePath);

        return response()->download($pathOfFile);
    }
    public function view(Request $request)
    {
        $file = PD::find($request->input('id'));

        if (!$file) {
            return response()->json(['error' => 'File not found'], 404);
        }

        $filePath = storage_path('app/public/' . $file->filepath);

        if (!file_exists($filePath)) {
            return response()->json(['error' => 'File does not exist'], 404);
        }

        return response()->file($filePath);
    }
    public function approve(Request $request)
    {
        if ($request->input('ot')) {
            $target = OT::find($request->input('id'));
            $target->status = 'Approved';
            $target->date_approved = Carbon::now();
            $target->save();
            return response()->json([
                'success' => $target
            ]);
        } else {
            $target = Leave::find($request->input('id'));
            $target->status = 'Approved';
            $target->date_approved = Carbon::now();
            $user = $target->user;
            if ($user->leave_credit >= $target->numberOfDays()) {
                $user->leave_credit -= $target->numberOfDays();
                $user->save();
                $target->save();
                return response()->json([
                    'success' => true
                ]);
            } else {
                return response()->json([
                    'no_credit' => true
                ], 422);
            }
        }
    }
    public function reject(Request $request)
    {
        if ($request->input('ot')) {
            $target = OT::find($request->input('id'));
            $target->status = 'Rejected';
            $target->save();
            return response()->json([
                'success' => $target
            ]);
        } else {
            $target = Leave::find($request->input('id'));
            $target->status = 'Rejected';
            $target->save();
            return response()->json([
                'success' => true
            ]);
        }
    }
}
