<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $teachers = Student::all();
        return response()->json($teachers);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:MALE,FEMALE,OTHER',
            'contact_person_name' => 'nullable|string|max:255',
            'contact_person_phone' => 'nullable|string|max:20',
            'enrollment_date' => 'nullable|date',
            'student_id_number' => 'nullable|string|max:255|unique:students,student_id_number',
            'class_id' => 'required|exists:classrooms,id',
        ]);

        $student = Student::create($validated);
        return response()->json([
            'message' => 'Data created successfully',
            'student' => [
                'id' => $student->id,
                'first_name' => $student->first_name,
                'last_name' => $student->last_name,
                'date_of_birth' => $student->date_of_birth,
                'gender' => $student->gender,
                'contact_person_name' => $student->contact_person_name,
                'contact_person_phone' => $student->contact_person_phone,
                'enrollment_date' => $student->enrollment_date,
                'student_id_number' => $student->student_id_number,
                'class_id' => $student->class_id,
                'created_at' => $student->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $student->updated_at->format('Y-m-d H:i:s'),
            ]
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        return response()->json([
            'message' => 'Data fetched successfully',
            'student' => [
                'id' => $student->id,
                'first_name' => $student->first_name,
                'last_name' => $student->last_name,
                'date_of_birth' => $student->date_of_birth,
                'gender' => $student->gender,
                'contact_person_name' => $student->contact_person_name,
                'contact_person_phone' => $student->contact_person_phone,
                'enrollment_date' => $student->enrollment_date,
                'student_id_number' => $student->student_id_number,
                'class_id' => $student->class_id,
                'created_at' => $student->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $student->updated_at->format('Y-m-d H:i:s'),
            ]
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'first_name' => 'string|max:255',
            'last_name' => 'string|max:255',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:MALE,FEMALE,OTHER',
            'contact_person_name' => 'nullable|string|max:255',
            'contact_person_phone' => 'nullable|string|max:20',
            'enrollment_date' => 'nullable|date',
            'student_id_number' => 'nullable|string|max:255|unique:students,student_id_number,' . $student->id,
            'class_id' => 'required|exists:classrooms,id',
        ]);

        $student->update($validated);
        return response()->json([
            'message' => 'Data updated successfully',
            'student' => [
                'id' => $student->id,
                'first_name' => $student->first_name,
                'last_name' => $student->last_name,
                'date_of_birth' => $student->date_of_birth,
                'gender' => $student->gender,
                'contact_person_name' => $student->contact_person_name,
                'contact_person_phone' => $student->contact_person_phone,
                'enrollment_date' => $student->enrollment_date,
                'student_id_number' => $student->student_id_number,
                'class_id' => $student->class_id,
                'created_at' => $student->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $student->updated_at->format('Y-m-d H:i:s'),
            ]
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        $student->delete();
        return response()->json(['message' => 'Data deleted successfully']);
    }
}
