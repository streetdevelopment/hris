<?php

namespace App\Http\Controllers\attendance;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\attendance\AR;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class Attendance extends Controller
{
    public function index()
    {
        $user = Auth()->user();
        return view('attendance.attendance.index', compact('user'));
    }
    public function upload(Request $request)
    {
        $mode = $request->input('mode');
        $imageData = $request->input('imageData');

        $record = User::find(Auth()->user()->id)->latestRecordToday();

        $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $imageData));

        $filename = 'captured_image_' . time() . '.jpg';

        Storage::disk('public')->put('Attendance/' . $filename, $imageData);

        if ($mode == 'time_in') {
            $record->time_in_image = $filename;
            $record->save();
            return response()->json(['success' => true]);
        } elseif ($mode == 'time_out') {
            $record->time_out_image = $filename;
            $record->save();
            return response()->json(['success' => true]);
        }
    }
    public function time()
    {
        $time = Carbon::now()->format('h:i A');
        return view('employee.attendance.components.time', compact('time'))->render();
    }
    public function in(Request $request)
    {
        $user = Auth()->user();

        $record = new AR();
        $record->date = Carbon::now()->format('Y-m-d');
        $record->time_in = Carbon::now()->toDateTimeString();
        $record->user_id = $user->id;
        $record->time_in_remark = $request->input('time_in_remark');

        $temp = Carbon::parse($record->time_in)->format('H:i:s');
        $morning_in = Auth()->user()->company->policies->grace_period;

        if (User::find($user->id)->hasRecordToday()) {
            $record->status = "In";
        } else {
            if (Carbon::parse($temp)->gt(Carbon::parse($morning_in))) {
                if ($user->userPSI->position->JT->pay_rate == 'Fixed')
                    $record->status = "Late";
                else {
                    $record->status = 'In';
                }
            } else {
                $record->status = "On Time";
            }
        }
        $record->save();
        return response()->json(['success' => true]);
    }
    public function out(Request $request)
    {
        $user = Auth()->user();
        $record = User::find($user->id)->latestRecordToday();
        $record->time_out = Carbon::now()->toDateTimeString();
        $record->time_out_remark = $request->input('time_out_remark');
        $record->save();
    }
    public function history(Request $request)
    {
        $user = Auth()->user();
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
}
