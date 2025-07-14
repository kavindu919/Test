<?php

namespace App\Http\Controllers;

use App\Models\StudentModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StudentController extends Controller
{
    /**
     * Function for get all students details
     */
    public function getAllStudents(Request $request)
    {
        $request->validate([
            'search' => 'nullable|string',
        ]);
        $search = $request->search;
        try {
            $data = StudentModel::select('first_name', 'last_name', 'email', 'phone', 'gender', 'birthdate')
                ->when($search, function ($query, $search) {
                    return $query->where('first_name', 'LIKE', '%' . $search . '%')
                        ->orWhere('last_name', 'LIKE', '%' . $search . '%')
                        ->orWhere('email', 'LIKE', '%' . $search . '%');
                })
                ->get();
            return response()->json(['status' => true, 'messaage' => 'Student Data Retrived Successfully', 'data' => $data], 200);
        } catch (\Throwable $th) {
            Log::error('error happend', ['error' => $th->getMessage()]);
            return response()->json(['status' => false, 'messaage' => 'Faild to Fetch Data'], 500);
        }
    }
    /**
     * Function for get single students details
     */
    public function getSingleStudent(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:students,id',
        ]);
        $student = StudentModel::where('id', $request->id)->first();
        if (!$student) {
            return response()->json(['status' => false, 'messaage' => 'Faild to Find Record'], 404);
        }
        try {
            $data = StudentModel::select('first_name', 'last_name', 'email', 'phone', 'gender', 'birthdate')
                ->where('id', $request->id)
                ->get();
            return response()->json(['status' => true, 'messaage' => 'Student Data Retrived Successfully', 'data' => $data], 200);
        } catch (\Throwable $th) {
            Log::error('error happend', ['error' => $th->getMessage()]);
            return response()->json(['status' => false, 'messaage' => 'Faild to Fetch Data'], 500);
        }
    }

    /**
     * Function for add students
     */
    public function addStudents(Request $request)
    {
        $validate = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'gender' => 'required|in:male,female,other',
            'birthdate' => 'required|date',
        ]);

        try {
            StudentModel::create($validate);
            return response()->json(['status' => true, 'messaage' => 'Student Data Added Successfully'], 200);
        } catch (\Throwable $th) {
            Log::error('error happend', ['error' => $th->getMessage()]);
            return response()->json(['status' => false, 'messaage' => 'Faild to Fetch Data'], 500);
        }
    }
    /**
     * Function for edit students
     */
    public function editStudents(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:students,id',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'phone' => 'required|string',
            'gender' => 'required|in:male,female,other',
            'birthdate' => 'required|date',
        ]);

        try {

            $student = StudentModel::where('id', $request->id)->first();
            if (!$student) {
                return response()->json(['status' => false, 'messaage' => 'Faild to Find Record'], 404);
            }
            $student->first_name = $request->first_name;
            $student->last_name = $request->last_name;
            $student->phone = $request->phone;
            $student->gender = $request->gender;
            $student->birthdate = $request->birthdate;
            $student->save();
            return response()->json(['status' => true, 'messaage' => 'Student Data Updated Successfully'], 200);
        } catch (\Throwable $th) {
            Log::error('error happend', ['error' => $th->getMessage()]);
            return response()->json(['status' => false, 'messaage' => 'Faild to Fetch Data'], 500);
        }
    }

    /**
     * Function for soft Delete student
     */
    public function softDeleteStudent(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:students,id',
        ]);
        try {
            $student = StudentModel::where('id', $request->id)->first();
            if (!$student) {
                return response()->json(['status' => false, 'messaage' => 'Faild to Find Record'], 404);
            }
            $student->update([
                'soft_delete' => true
            ]);
            return response()->json(['status' => true, 'messaage' => 'Student Deleted Successfully'], 200);
        } catch (\Throwable $th) {
            Log::error('error happend', ['error' => $th->getMessage()]);
            return response()->json(['status' => false, 'messaage' => 'Faild to Fetch Data'], 500);
        }
    }
}
