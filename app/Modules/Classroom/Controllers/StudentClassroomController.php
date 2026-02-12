<?php

namespace Modules\Classroom\Controllers;

use App\Controllers\BaseController;
use Modules\Classroom\Models\ClassroomModel;
use Modules\Admission\Models\AdmissionModel;

/**
 * Student Classroom Controller
 * Allows students to view their enrolled class and schedule
 */
class StudentClassroomController extends BaseController
{
    protected $classroomModel;
    protected $admissionModel;

    public function __construct()
    {
        $this->classroomModel = new ClassroomModel();
        $this->admissionModel = new AdmissionModel();
    }

    /**
     * Get registration number for the logged-in user
     */
    protected function getStudentRegistrationNumber(): ?string
    {
        $user = auth()->user();
        if (!$user) {
            return null;
        }

        $db = \Config\Database::connect();
        $result = $db->table('profiles')
            ->select('admissions.registration_number')
            ->join('admissions', 'admissions.profile_id = profiles.id', 'left')
            ->where('profiles.user_id', $user->id)
            ->where('admissions.status', 'approved')
            ->get()
            ->getRowArray();

        return $result['registration_number'] ?? null;
    }

    /**
     * Display student's enrolled class
     */
    public function myClass()
    {
        // Check authentication
        if (!auth()->loggedIn()) {
            return redirect()->to('/login')->with('error', 'Please login to view your class.');
        }

        $registrationNumber = $this->getStudentRegistrationNumber();

        if (!$registrationNumber) {
            if (auth()->user()->inGroup('student')) {
                return view('Modules\Classroom\Views\student\pending_approval', [
                    'title' => 'Pending Approval',
                    'message' => 'Your admission is still pending approval. Please contact the administration for more information.'
                ]);
            }

            return redirect()->to('/dashboard')->with('error', 'You do not have an approved admission.');
        }

        // Find classroom that contains this student
        $classrooms = $this->classroomModel->where('status', 'active')->findAll();
        $enrolledClassroom = null;

        foreach ($classrooms as $classroom) {
            $members = json_decode($classroom['members'] ?? '[]', true);
            if (in_array($registrationNumber, $members)) {
                $enrolledClassroom = $classroom;
                break;
            }
        }

        // Get student details
        $student = $this->admissionModel->select('
                admissions.registration_number,
                profiles.full_name,
                profiles.email,
                profiles.phone,
                programs.title as program_title,
                programs.category as program_category
            ')
            ->join('profiles', 'profiles.id = admissions.profile_id', 'left')
            ->join('programs', 'programs.id = admissions.program_id', 'left')
            ->where('admissions.registration_number', $registrationNumber)
            ->first();

        if (!$enrolledClassroom) {
            return view('Modules\Classroom\Views\student\no_class', [
                'title' => 'My Class',
                'student' => $student,
                'registration_number' => $registrationNumber,
                'menuItems' => $this->loadModuleMenus(),
                'user' => auth()->user()
            ]);
        }

        // Decode schedule
        $enrolledClassroom['schedule'] = json_decode($enrolledClassroom['schedule'] ?? '[]', true);

        // Get instructor details if available
        $instructorDetails = [];
        $db = \Config\Database::connect();
        if (!empty($enrolledClassroom['schedule'])) {
            foreach ($enrolledClassroom['schedule'] as $subject => $details) {
                $instructorName = $details['instructor'] ?? '';
                if (!empty($instructorName)) {
                    // Try to find instructor profile
                    $instructorProfile = $db->table('profiles')
                        ->select('profiles.*')
                        ->like('profiles.full_name', $instructorName, 'both')
                        ->get()
                        ->getRowArray();

                    $instructorDetails[$subject] = $instructorProfile;
                }
            }
        }

        return view('Modules\Classroom\Views\student\my_class', [
            'title' => 'My Class',
            'classroom' => $enrolledClassroom,
            'student' => $student,
            'registration_number' => $registrationNumber,
            'instructorDetails' => $instructorDetails,
            'menuItems' => $this->loadModuleMenus(),
            'user' => auth()->user()
        ]);
    }

    /**
     * Get student's class summary for dashboard widget
     */
    public function myClassSummary()
    {
        if (!auth()->loggedIn()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Not authenticated'
            ]);
        }

        $registrationNumber = $this->getStudentRegistrationNumber();

        if (!$registrationNumber) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Not a student'
            ]);
        }

        // Find classroom that contains this student
        $classrooms = $this->classroomModel->where('status', 'active')->findAll();
        $enrolledClassroom = null;

        foreach ($classrooms as $classroom) {
            $members = json_decode($classroom['members'] ?? '[]', true);
            if (in_array($registrationNumber, $members)) {
                $enrolledClassroom = $classroom;
                break;
            }
        }

        if (!$enrolledClassroom) {
            return $this->response->setJSON([
                'success' => true,
                'data' => [
                    'has_class' => false,
                    'message' => 'No class assigned yet'
                ]
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'data' => [
                'has_class' => true,
                'classroom_id' => $enrolledClassroom['id'],
                'title' => $enrolledClassroom['title'],
                'batch' => $enrolledClassroom['batch'],
                'grade' => $enrolledClassroom['grade'],
                'program' => $enrolledClassroom['program'],
                'start_date' => $enrolledClassroom['start_date'],
                'end_date' => $enrolledClassroom['end_date'],
                'member_count' => count(json_decode($enrolledClassroom['members'] ?? '[]', true)),
                'schedule_count' => count(json_decode($enrolledClassroom['schedule'] ?? '[]', true))
            ]
        ]);
    }
}
