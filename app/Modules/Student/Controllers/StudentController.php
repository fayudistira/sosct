<?php

namespace Modules\Student\Controllers;

use App\Controllers\BaseController;
use Modules\Student\Models\StudentModel;

class StudentController extends BaseController
{
    protected $studentModel;
    
    public function __construct()
    {
        $this->studentModel = new StudentModel();
    }
    
    public function index()
    {
        $data = [
            'title' => 'Student Management',
            'students' => $this->studentModel->getAllWithDetails(),
            'menuItems' => $this->loadModuleMenus(),
            'user' => auth()->user()
        ];
        
        return view('Modules\Student\Views\index', $data);
    }
    
    public function show($id)
    {
        $student = $this->studentModel->getStudentWithDetails($id);
        
        if (!$student) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Student not found');
        }
        
        $data = [
            'title' => 'Student Details - ' . $student['full_name'],
            'student' => $student,
            'menuItems' => $this->loadModuleMenus(),
            'user' => auth()->user()
        ];
        
        return view('Modules\Student\Views\view', $data);
    }
}
