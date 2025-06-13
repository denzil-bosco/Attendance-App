<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $teachers = Classroom::all();
        return response()->json($teachers);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'grade' => 'required|string|max:50',
            'room_number' => 'required|string|max:50',
            'total_students' => 'nullable|integer',
            'teacher_id' => 'nullable|exists:teachers,id',
        ]);
        $classroom = Classroom::create($validated);
        return response()->json([
            'message' => 'Data created successfully',
            'classroom' => [
                'id' => $classroom->id,
                'grade' => $classroom->grade,
                'room_number' => $classroom->room_number,
                'total_students' => $classroom->total_students,
                'teacher_id' => $classroom->teacher_id,
                'created_at' => $classroom->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $classroom->updated_at->format('Y-m-d H:i:s'),
            ]
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Classroom $classroom)
    {
        return response()->json([
            'message' => 'Data fetched successfully',
            'classroom' => [
                'id' => $classroom->id,
                'grade' => $classroom->grade,
                'room_number' => $classroom->room_number,
                'total_students' => $classroom->total_students,
                'teacher_id' => $classroom->teacher_id,
                'created_at' => $classroom->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $classroom->updated_at->format('Y-m-d H:i:s'),
            ]
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Classroom $classroom)
    {
        $validated = $request->validate([
            'grade' => 'string|max:50',
            'room_number' => 'string|max:50',
            'total_students' => 'nullable|integer',
            'teacher_id' => 'nullable|exists:teachers,id',
        ]);

        $classroom->update($validated);
        return response()->json([
            'message' => 'Data updated successfully',
            'classroom' => [
                'id' => $classroom->id,
                'grade' => $classroom->grade,
                'room_number' => $classroom->room_number,
                'total_students' => $classroom->total_students,
                'teacher_id' => $classroom->teacher_id,
                'created_at' => $classroom->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $classroom->updated_at->format('Y-m-d H:i:s'),
            ]
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Classroom $classroom)
    {
        $classroom->delete();
        return response()->json(['message' => 'Data deleted successfully']);
    }
}
