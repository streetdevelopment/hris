<?php

namespace App\Http\Controllers\payroll;

use DateTime;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\attendance\AR;
use App\Models\company\Company;
use App\Models\PayrollDeduction;
use App\Http\Controllers\Controller;
use App\Models\payroll\Run as PayrollRun;
use Illuminate\Support\Facades\Validator;

class Run extends Controller
{
    public function submit(Request $request)
    {
        if ($request->input('hourly_pay') == "true") {
            $rules = [
                'pay_period_start' => 'required',
                'pay_period_end' => 'required'
            ];
        } else if ($request->input('semi_monthly_pay') == "true") {
            $rules = [
                'sm_year' => 'required',
                'sm_month' => 'required',
                'sm_period' => 'required'
            ];
        } else if ($request->input('monthly_pay') == "true") {
            $rules = [
                'm_year' => 'required',
                'm_month' => 'required'
            ];
        } else {
            $rules = [
                'target_job_types' => 'required'
            ];
        }
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'msgs' => $validator->errors()->toArray()
            ], 422);
        } else {
            if ($request->input('hourly_pay') == "true") {
                $run = PayrollRun::create([
                    'co_id' => $request->input('co_id'),
                    'pay_period_start' => $request->input('pay_period_start'),
                    'pay_period_end' => $request->input('pay_period_end'),
                    'status' => $request->input('mode') == 'draft' ? 'Draft' : 'Pending'
                ]);
            } else if ($request->input('semi_monthly_pay') == "true") {
                $year = (int) $request->input('sm_year');
                $monthName = $request->input('sm_month');
                $dateTime = DateTime::createFromFormat('F', $monthName);
                $month = $dateTime->format('m');
                $pay_period_start = sprintf('%d-%02d-01', $year, $month);
                $startDate = new DateTime($pay_period_start);
                $totalDays = $startDate->format('t');
                $midPoint = ceil($totalDays / 2);
                if ($request->input('sm_period') == "first_half") {
                    $pay_period_start = $startDate->format('Y-m-d');
                    $endDate = (clone $startDate)->modify('+' . ($midPoint - 1) . ' days');
                    $pay_period_end = $endDate->format('Y-m-d');
                } else {
                    $pay_period_start = (clone $startDate)->modify('+' . $midPoint . ' days')->format('Y-m-d');
                    $endDate = (clone $startDate)->modify('last day of this month');
                    $pay_period_end = $endDate->format('Y-m-d');
                }
                $run = PayrollRun::create([
                    'co_id' => $request->input('co_id'),
                    'pay_period_start' => $pay_period_start,
                    'pay_period_end' => $pay_period_end,
                    'status' => $request->input('mode') == 'draft' ? 'Draft' : 'Pending'
                ]);
            } else if ($request->input('monthly_pay') == "true") {
                $year = (int) $request->input('m_year');
                $monthName = $request->input('m_month');
                $dateTime = DateTime::createFromFormat('F', $monthName);
                $month = $dateTime->format('m');
                $pay_period_start = sprintf('%d-%02d-01', $year, $month);
                $startDate = new DateTime($pay_period_start);
                $endDate = clone $startDate;
                $endDate->modify('last day of this month');
                $pay_period_end = $endDate->format('Y-m-d');
                $run = PayrollRun::create([
                    'co_id' => $request->input('co_id'),
                    'pay_period_start' => $pay_period_start,
                    'pay_period_end' => $pay_period_end,
                    'status' => $request->input('mode') == 'draft' ? 'Draft' : 'Pending'
                ]);
            }
            if ($request->input('mode') == 'run') {
                return response()->json([
                    'run' => 'true',
                    'id' => $run->payroll_run_id
                ]);
            } else {
                return response()->json([
                    'fields' => $request->all()
                ]);
            }
        }
    }
    public function view($id)
    {
        $run = PayrollRun::find($id);
        $slips = $run->payslips;
        return view('payroll.view_run', compact('run', 'slips'));
    }
    public function process(Request $request)
    {
        $run = PayrollRun::find($request->input('id'));
        $pay_period_start = $run->pay_period_start;
        $pay_period_end = $run->pay_period_end;
        $company = Company::find($run->co_id);
        $policies = $company->policies;
        $data = $request->except('id');
        $results = $run->process($run->payroll_run_id, $data, $pay_period_start, $pay_period_end);
        if ($results['status'] == 'Processed') {
            $run->status = $results['status'];
            $run->run_date = $results['run_date'];
            $run->total_deductions = $results['total_deductions'];
            $run->total_gross_pay = $results['total_gross_pay'];
            $run->total_net_pay = $results['total_net_pay'];
            $run->save();

            if (count($results['deductions']) > 0) {
                foreach ($results['deductions'] as $key => $ded) {
                    $payroll_deduction = PayrollDeduction::create([
                        'payroll_run_id' => $run->payroll_run_id,
                        'ded_id' => $ded
                    ]);
                }
            }
        }
        $records = [];

        $loc_id = $run->payroll_run_id;

        $accumulatedRecords = [];
        return response()->json([
            'results' => $results,
            'location' => $loc_id
        ]);
        // Employee Loop
        // foreach ($data as $key => $value) {
        //     if ($value === 'on') {
        //         $emp = User::find($key);
        //         $recs = $emp->getARbetween($request->input('pay_period_start'), $request->input('pay_period_end'));

        //         foreach ($recs as $rec) {
        //             $empName = $emp->fullname();
        //             if (!isset($accumulatedRecords[$empName])) {
        //                 $accumulatedRecords[$empName] = [
        //                     'user' => [
        //                         'pi' => $emp->userPI,  // Personal Information
        //                         'ci' => $emp->userCI,  // Contact Information
        //                         'psi' => $emp->userPSI // Position Information
        //                     ]
        //                 ];
        //             }

        //             $date = $rec->date;

        //             if (!isset($accumulatedRecords[$empName])) {
        //                 $accumulatedRecords[$empName] = [];
        //             }

        //             if (!isset($accumulatedRecords[$empName][$date])) {
        //                 $accumulatedRecords[$empName][$date] = [];
        //             }

        //             if (!isset($accumulatedRecords[$empName][$date][$rec->att_rec_id])) {
        //                 $accumulatedRecords[$empName][$date][$rec->att_rec_id] = $rec;
        //             } else {
        //                 $accumulatedRecords[$empName][$date][$rec->att_rec_id]->hours += $rec->hours;
        //             }
        //         }
        //     }
        // }
        // Process accumulated records into separate payslips
        // run date
        // Total deductions
        // gross
        // NET
        // return view('payroll.run', compact('accumulatedRecords', 'run'))->render();
        // return response()->json([
        //     'accumulatedRecords' => $accumulatedRecords,
        //     'fields' => $request->all()
        // ]);
    }
}
