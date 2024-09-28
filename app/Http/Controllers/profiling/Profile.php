<?php

namespace App\Http\Controllers\profiling;

use App\Models\profiling\PD;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class Profile extends Controller
{
    public function index()
    {
        $user = Auth()->user();
        $pi = $user->userPI;
        $psi = $user->userPSI;
        $ci = $user->userCI;
        $pd = $user->userPD;
        $dts = Auth()->user()->company->documents;
        return view('profiling.profile.index', compact('user', 'pi', 'psi', 'ci', 'pd', 'dts'));
    }
    public function pd_create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'dt_id' => 'required',
            'filepath' => 'required|file|mimes:pdf,docx|max:3072',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'msgs' => $validator->errors()->toArray()
            ], 422);
        } else {
            $file = $request->file('filepath');
            $path = $file->store('uploads', 'public');

            $pd = PD::create([
                'user_id' => $request->input('user_id'),
                'dt_id' => $request->input('dt_id'),
                'filepath' => $path,
                'filesize' => $file->getSize()
            ]);
            return response()->json([
                'name' => $pd->filename
            ]);
        }
    }
}
