<?php

namespace App\Http\Controllers;

use Illuminate\Http\Rquest;

class StudentController extends Controller
{

    public function index()
    {
        $students = [
            [
                'name' => 'Dryhus',
                'major' => 'Hukum',
                'age' => 18,
                'courses' => ['Pemrograman Web', 'Basis Data 1', 'User Expreience'],
            ],
            [
                'name' => 'Akmal',
                'major' => 'AI ',
                'age' => 22,
                'courses' => ['Claude Code Prompting', 'Codex', 'Basic AI'],

            ],
        ];

        return view('students.index', compact('students'));
    }
}
