<?php

namespace App\Http\Controllers\profiling;

use App\Models\User;
use App\Mail\PostMail;
use App\Models\profiling\CI;
use App\Models\profiling\PI;
use Illuminate\Http\Request;
use App\Models\profiling\PSI;
use App\Models\company\JobTypes;
use App\Models\company\Position;
use App\Models\company\Department;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class Employee extends Controller
{
    public function index(Request $request)
    {
        $co_id = Auth()->user()->company->co_id;
        $departments = Auth()->user()->company->departments;

        $department_filter = $request->input('department_filter', null);
        $sex_filter = $request->input('sex_filter', null);
        $status_filter = $request->input('status_filter', null);
        $employee_search = $request->input('employee_search', null);

        $query = User::where('users.co_id', $co_id) // Specify table for co_id
            ->join('emp_pi', 'users.id', '=', 'emp_pi.user_id')
            ->join('emp_psi', 'users.id', '=', 'emp_psi.user_id')
            ->join('position', 'emp_psi.pos_id', '=', 'position.pos_id')
            ->join('department', 'position.dep_id', '=', 'department.dep_id');

        if ($department_filter) {
            $query->where('department.dep_id', $department_filter);
        }
        if ($sex_filter) {
            $query->where('emp_pi.sex', $sex_filter);
        }
        if ($status_filter) {
            $query->where('emp_psi.status', $status_filter);
        }
        if ($employee_search) {
            $searchTerms = explode(' ', $employee_search);
            $query->where(function ($q) use ($searchTerms) {
                foreach ($searchTerms as $term) {
                    $q->where(function ($q) use ($term) {
                        $q->where('emp_pi.first_name', 'like', '%' . $term . '%')
                            ->orWhere('emp_pi.middle_name', 'like', '%' . $term . '%')
                            ->orWhere('emp_pi.last_name', 'like', '%' . $term . '%');
                    });
                }
            })
                ->orWhere('users.id', $employee_search);
        }

        $employees = $query->get();

        return view('profiling.employees.index', compact('employees', 'departments', 'department_filter', 'sex_filter', 'status_filter', 'employee_search'));
    }
    public function create()
    {
        $co_id = Auth()->user()->company->co_id;
        $dps = Department::all()->where('co_id', $co_id);
        $jts = JobTypes::all()->where('co_id', $co_id);
        return view('profiling.employees.create', compact('jts', 'dps'));
    }
    public function email(Request $request)
    {
        Mail::to($request->input('recipient'))->send(new PostMail([
            'sender' => $request->input('sender'),
            'recipient' => $request->input('recipient'),
            'message' => $request->input('message')
        ]));

        return response()->json([
            'recipient' => $request->input('recipient')
        ]);
    }
    public function edit($id)
    {
        $user = User::find($id);
        $co_id = Auth()->user()->company->co_id;
        $dps = Department::all()->where('co_id', $co_id);
        $jts = JobTypes::all()->where('co_id', $co_id);
        return view('profiling.profile.edit', compact('jts', 'dps', 'user'));
    }
    public function edit_submit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'sex' => 'required',
            'date_of_birth' => 'required',
            'contact_number' => 'required|numeric',
            'email_address' => 'required',
            'permanent_address' => 'required',
            'title' => 'required',
            'date_hired' => 'required',
            'status' => 'required',
            'jt_id' => 'required',
            'salary' => 'required',
            'username' => 'required',
            'dep_id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'msgs' => $validator->errors()->toArray()
            ], 422);
        } else {
            $user = User::find($request->input('user_id'));
            $user->username = $request->input('username');
            $user->email = $request->input('email_address');
            if ($request->input('password')) {
                $password = $request->input('password');
            } else {
                $password = $user->password;
            }
            $user->password = $password;
            $user->role = $request->input('role');
            $user->save();
            $pi = PI::where('user_id', $user->id)->first();
            $pi->date_of_birth = $request->input('date_of_birth');
            $pi->first_name = $request->input('first_name');
            $pi->middle_name = $request->input('middle_name');
            $pi->last_name = $request->input('last_name');
            $pi->nationality = $request->input('nationality');
            $pi->sex = $request->input('sex');
            if ($request->hasFile('photo')) {
                if ($user->userPI->photo) {
                    Storage::disk('public')->delete($user->userPI->photo);
                }

                $photoPath = $request->file('photo')->store('employee_photos', 'public');
                $piData['photo'] = $photoPath;
                $pi->photo = $piData['photo'];
            }
            $pi->save();
            $ci = CI::where('user_id', $user->id)->first();
            $ci->contact_number = $request->input('contact_number');
            $ci->email_address = $request->input('email_address');
            $ci->permanent_address = $request->input('permanent_address');
            $ci->current_address = $request->input('current_address');
            $ci->ec_name = $request->input('ec_name');
            $ci->ec_relation = $request->input('ec_relation');
            $ci->ec_contact_number = $request->input('ec_contact_number');
            $ci->save();
            if (Auth()->user()->role == 'admin') {
                $psi = PSI::where('user_id', $user->id)->first();
                $psi->date_hired = $request->input('date_hired');
                $psi->status = $request->input('status');
                $psi->save();
                $position = Position::where('pos_id', $request->input('pos_id'))->first();
                $position->dep_id = $request->input('dep_id');
                $position->jt_id = $request->input('jt_id');
                $position->title = $request->input('title');
                $position->description = $request->input('description');
                $position->salary = $request->input('salary');
                $position->save();
            }
            return response()->json([
                'name' => $user->fullname()
            ]);
        }
    }
    public function submit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'sex' => 'required',
            'date_of_birth' => 'required',
            'contact_number' => 'required|numeric',
            'email_address' => 'required',
            'permanent_address' => 'required',
            'title' => 'required',
            'date_hired' => 'required',
            'status' => 'required',
            'jt_id' => 'required',
            'salary' => 'required',
            'username' => 'required',
            'password' => 'required',
            'dep_id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'msgs' => $validator->errors()->toArray()
            ], 422);
        } else {
            $user = User::create([
                'username' => $request->input('username'),
                'email' => $request->input('email_address'),
                'password' => $request->input('password'),
                'co_id' => $request->input('co_id'),
                'role' => $request->input('role')
            ]);
            // Prepare the data for PI model
            $piData = [
                'user_id' => $user->id,
                'first_name' => $request->input('first_name'),
                'middle_name' => $request->input('middle_name'),
                'last_name' => $request->input('last_name'),
                'sex' => $request->input('sex'),
                'date_of_birth' => $request->input('date_of_birth'),
                'nationality' => $request->input('nationality')
            ];

            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('employee_photos', 'public');
                $piData['photo'] = $photoPath;
            }

            $pi = PI::create($piData);
            $ci = CI::create([
                'user_id' => $user->id,
                'contact_number' => $request->input('contact_number'),
                'email_address' => $request->input('email_address'),
                'permanent_address' => $request->input('permanent_address'),
                'current_address' => $request->input('current_address'),
                'ec_name' => $request->input('ec_name'),
                'ec_relation' => $request->input('ec_relation'),
                'ec_contact_number' => $request->input('ec_contact_number')
            ]);
            $position = Position::create([
                'dep_id' => $request->input('dep_id'),
                'jt_id' => $request->input('jt_id'),
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'salary' => $request->input('salary'),
                'status' => $request->input('status')
            ]);
            $psi = PSI::create([
                'user_id' => $user->id,
                'pos_id' => $position->pos_id,
                'date_hired' => $request->input('date_hired'),
                'status' => $request->input('status')
            ]);
            Mail::to($ci->email_address)->send(new PostMail([
                'sender' => Auth()->user()->first_name,
                'onboarding' => true,
                'recipient' => $pi->first_name,
                'company' => Auth()->user()->company->co_name,
                'username' => $user->username,
                'password' => $request->input('plain-password')
            ]));
            return response()->json([
                'name' => $user->fullname()
            ]);
        }
    }
}
