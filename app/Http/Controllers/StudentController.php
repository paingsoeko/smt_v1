<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    // GET all students
    public function index()
    {
        $students = Student::all();
        return response()->json($students);
    }

    // GET a specific student by ID
    public function show($id)
    {
        $student = Student::find($id);

        if (!$student) {
            return response()->json(['message' => 'Student not found'], 404);
        }

        return response()->json($student);
    }

    // POST to create a new student
    public function store(Request $request)
    {
        $validated = $request->validate([
            'team_id' => 'required|integer',
            'name' => 'required|string',
        ]);

        $student = Student::create([
            'team_id' => 1,
            "name" => $validated['name'],
            'created_by' => 1
    ]);
        return response()->json($student, 201); // 201 Created
    }

    // PUT to update an existing student
    public function update(Request $request, $id)
    {
        $student = Student::find($id);

        if (!$student) {
            return response()->json(['message' => 'Student not found'], 404);
        }

        $validated = $request->validate([
            'team_id' => 'integer',
            'student_code' => 'string|unique:students,student_code,' . $id,
            'name' => 'string',
        ]);

        $student->update($validated);
        return response()->json($student);
    }

    // DELETE a student by ID
    public function destroy($id)
    {
        $student = Student::find($id);

        if (!$student) {
            return response()->json(['message' => 'Student not found'], 404);
        }

        $student->delete();
        return response()->json(['message' => 'Student deleted successfully']);
    }
}
