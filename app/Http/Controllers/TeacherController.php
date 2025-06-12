<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = Teacher::all();
        return response()->json($teachers);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:teachers,email',
            'phone_number' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'status' => 'in:ACTIVE,INACTIVE',
            'password' => 'required|string|min:6',
        ]);

        $validated['password'] = bcrypt($validated['password']);

        $teacher = Teacher::create($validated);
        return response()->json([
            'message' => 'Data created successfully',
            'teacher' => [
                'id' => $teacher->id,
                'name' => $teacher->name,
                'phone_number' => $teacher->phone_number,
                'email' => $teacher->email,
                'subject' => $teacher->subject,
                'status' => $teacher->status,
                'created_at' => $teacher->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $teacher->updated_at->format('Y-m-d H:i:s'),
            ]
        ], 201);
    }

    public function show(Teacher $teacher)
    {
        return response()->json([
            'message' => 'Data fetched successfully',
            'teacher' => [
                'id' => $teacher->id,
                'name' => $teacher->name,
                'phone_number' => $teacher->phone_number,
                'email' => $teacher->email,
                'subject' => $teacher->subject,
                'status' => $teacher->status,
                'created_at' => $teacher->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $teacher->updated_at->format('Y-m-d H:i:s'),
            ]
        ], 200);
    }

    public function update(Request $request, Teacher $teacher)
    {
        $validated = $request->validate([
            'name' => 'string|max:255',
            'email' => 'email|unique:teachers,email,' . $teacher->id,
            'phone_number' => 'nullable|string|max:20',
            'subject' => 'string|max:255',
            'status' => 'in:ACTIVE,INACTIVE',
            'password' => 'nullable|string|min:6',
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        }

        $teacher->update($validated);
        return response()->json([
            'message' => 'Data updated successfully',
            'teacher' => [
                'id' => $teacher->id,
                'name' => $teacher->name,
                'phone_number' => $teacher->phone_number,
                'email' => $teacher->email,
                'subject' => $teacher->subject,
                'status' => $teacher->status,
                'created_at' => $teacher->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $teacher->updated_at->format('Y-m-d H:i:s'),
            ]
        ], 200);
    }

    public function destroy(Teacher $teacher)
    {
        $teacher->delete();
        return response()->json(['message' => 'Data deleted successfully'], 204);
    }
}
