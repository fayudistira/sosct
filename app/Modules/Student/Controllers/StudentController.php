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

    public function edit($id)
    {
        $student = $this->studentModel->find($id);

        if (!$student) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Student not found');
        }

        // Get profile details for name
        $profileModel = new \Modules\Account\Models\ProfileModel();
        $profile = $profileModel->find($student['profile_id']);

        $data = [
            'title' => 'Edit Student - ' . ($profile['full_name'] ?? 'Unknown'),
            'student' => $student,
            'profile' => $profile,
            'menuItems' => $this->loadModuleMenus(),
            'user' => auth()->user()
        ];

        return view('Modules\Student\Views\edit', $data);
    }

    public function update($id)
    {
        $student = $this->studentModel->find($id);

        if (!$student) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Student not found');
        }

        $data = [
            'status' => $this->request->getPost('status'),
            'gpa' => $this->request->getPost('gpa') ?: null,
            'total_credits' => $this->request->getPost('total_credits') ?: null,
            'graduation_date' => $this->request->getPost('graduation_date') ?: null,
            'graduation_gpa' => $this->request->getPost('graduation_gpa') ?: null,
        ];

        if ($this->studentModel->update($id, $data)) {
            return redirect()->to('/student')->with('success', 'Student updated successfully.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to update student: ' . implode(', ', $this->studentModel->errors()));
        }
    }

    public function promoteForm()
    {
        // Get all APPROVED admissions not yet promoted (no user/login account)
        // Pending admissions must be approved first before promotion
        $db = \Config\Database::connect();

        $admissions = $db->table('admissions a')
            ->select('a.*, p.full_name, p.email, prog.title as program_title')
            ->join('profiles p', 'p.id = a.profile_id', 'left')
            ->join('programs prog', 'prog.id = a.program_id', 'left')
            ->where('a.status', 'approved')
            ->where('p.user_id IS NULL')
            ->orderBy('a.created_at', 'DESC')
            ->get()
            ->getResultArray();

        $data = [
            'title' => 'Promote New Student',
            'admissions' => $admissions,
            'menuItems' => $this->loadModuleMenus(),
            'user' => auth()->user()
        ];

        return view('Modules\Student\Views\promote_form', $data);
    }

    public function doPromote()
    {
        $admissionId = $this->request->getPost('admission_id');

        if (!$admissionId) {
            return redirect()->back()->with('error', 'Please select an admission record.');
        }

        // Redirect to the admission module's promotion page
        return redirect()->to('/admission/promote/' . $admissionId);
    }
}
