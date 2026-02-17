<?php

namespace Modules\Dormitory\Controllers;

use App\Controllers\BaseController;
use Modules\Dormitory\Models\DormitoryModel;
use Modules\Dormitory\Models\DormitoryAssignmentModel;
use Modules\Student\Models\StudentModel;

class DormitoryController extends BaseController
{
    protected $dormitoryModel;
    protected $assignmentModel;

    public function __construct()
    {
        $this->dormitoryModel = new DormitoryModel();
        $this->assignmentModel = new DormitoryAssignmentModel();
    }

    /**
     * Display list of dormitories
     */
    public function index()
    {
        $dormitories = $this->dormitoryModel->getAllWithOccupancy();

        return view('Modules\Dormitory\Views\index', [
            'title'       => 'Dormitory Management',
            'dormitories' => $dormitories,
            'menu'        => [
                'index'  => base_url('dormitory'),
                'create' => base_url('dormitory/create'),
            ],
        ]);
    }

    /**
     * Display create form
     */
    public function create()
    {
        return view('Modules\Dormitory\Views\create', [
            'title'  => 'Add New Dormitory',
            'action' => base_url('dormitory/store'),
            'method' => 'post',
            'menu'   => ['index' => base_url('dormitory')],
        ]);
    }

    /**
     * Store new dormitory
     */
    public function store()
    {
        $data = $this->request->getPost();

        // Handle facilities input
        if (isset($data['facilities']) && is_string($data['facilities'])) {
            $data['facilities'] = array_filter(array_map('trim', explode("\n", $data['facilities'])));
        }

        // Handle gallery upload
        $gallery = $this->handleGalleryUpload();
        if (!empty($gallery)) {
            $data['gallery'] = $gallery;
        }

        if (!$this->dormitoryModel->save($data)) {
            return redirect()->back()->withInput()->with('errors', $this->dormitoryModel->errors());
        }

        return redirect()->to(base_url('dormitory'))->with('success', 'Dormitory created successfully.');
    }

    /**
     * Display dormitory details
     */
    public function show(string $id)
    {
        $dormitory = $this->dormitoryModel->getWithOccupancy($id);
        
        if (!$dormitory) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Get assignments
        $assignments = $this->assignmentModel->getAssignmentsByDormitory($id);

        return view('Modules\Dormitory\Views\view', [
            'title'       => 'Dormitory Details',
            'dormitory'   => $dormitory,
            'assignments' => $assignments,
            'menu'        => [
                'index' => base_url('dormitory'),
                'edit'  => base_url('dormitory/edit/' . $id),
            ],
        ]);
    }

    /**
     * Display edit form
     */
    public function edit(string $id)
    {
        $dormitory = $this->dormitoryModel->find($id);
        
        if (!$dormitory) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('Modules\Dormitory\Views\edit', [
            'title'      => 'Edit Dormitory',
            'dormitory'  => $dormitory,
            'action'     => base_url('dormitory/update/' . $id),
            'method'     => 'post',
            'menu'       => [
                'index' => base_url('dormitory'),
                'view'  => base_url('dormitory/show/' . $id),
            ],
        ]);
    }

    /**
     * Update dormitory
     */
    public function update(string $id)
    {
        $data = $this->request->getPost();
        $data['id'] = $id;

        // Handle facilities input
        if (isset($data['facilities']) && is_string($data['facilities'])) {
            $data['facilities'] = array_filter(array_map('trim', explode("\n", $data['facilities'])));
        }

        // Handle gallery upload
        $gallery = $this->handleGalleryUpload($id);
        if (!empty($gallery)) {
            // Merge with existing gallery
            $existing = $this->dormitoryModel->find($id);
            $existingGallery = $existing['gallery'] ?? [];
            $data['gallery'] = array_merge($existingGallery, $gallery);
        }

        // Handle gallery removal
        if ($this->request->getPost('remove_images')) {
            $removeImages = $this->request->getPost('remove_images');
            $existing = $this->dormitoryModel->find($id);
            $existingGallery = $existing['gallery'] ?? [];
            $data['gallery'] = array_values(array_diff($existingGallery, $removeImages));
        }

        if (!$this->dormitoryModel->save($data)) {
            return redirect()->back()->withInput()->with('errors', $this->dormitoryModel->errors());
        }

        return redirect()->to(base_url('dormitory/show/' . $id))->with('success', 'Dormitory updated successfully.');
    }

    /**
     * Delete dormitory
     */
    public function delete(string $id)
    {
        if ($this->dormitoryModel->delete($id)) {
            return redirect()->to(base_url('dormitory'))->with('success', 'Dormitory deleted successfully.');
        }

        return redirect()->back()->with('error', 'Failed to delete dormitory.');
    }

    /**
     * Display assignment form
     */
    public function assignments(string $id)
    {
        $dormitory = $this->dormitoryModel->getWithOccupancy($id);
        
        if (!$dormitory) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Get active students for assignment
        $studentModel = new StudentModel();
        $students = $studentModel->select('students.id, students.student_number, profiles.full_name')
            ->join('profiles', 'profiles.id = students.profile_id', 'left')
            ->where('students.status', 'active')
            ->orderBy('profiles.full_name', 'ASC')
            ->findAll();

        // Get current assignments
        $assignments = $this->assignmentModel->getActiveAssignments($id);

        return view('Modules\Dormitory\Views\assignments', [
            'title'       => 'Manage Assignments',
            'dormitory'   => $dormitory,
            'students'    => $students,
            'assignments' => $assignments,
            'menu'        => [
                'index' => base_url('dormitory'),
                'view'  => base_url('dormitory/show/' . $id),
            ],
        ]);
    }

    /**
     * Assign student to dormitory
     */
    public function assign(string $id)
    {
        $studentId = $this->request->getPost('student_id');
        $startDate = $this->request->getPost('start_date');
        $endDate = $this->request->getPost('end_date') ?: null;
        $notes = $this->request->getPost('notes');

        $dormitory = $this->dormitoryModel->getWithOccupancy($id);
        
        if (!$dormitory) {
            return redirect()->back()->with('error', 'Dormitory not found.');
        }

        // Check capacity
        if ($dormitory['available_beds'] <= 0) {
            return redirect()->back()->with('error', 'No available beds in this dormitory.');
        }

        if ($this->assignmentModel->assignStudent($id, $studentId, $startDate, $notes, $endDate)) {
            // Update dormitory status if full
            $newOccupied = $this->assignmentModel->getOccupiedBedsCount($id);
            if ($newOccupied >= $dormitory['room_capacity']) {
                $this->dormitoryModel->update($id, ['status' => 'full']);
            }
            
            return redirect()->back()->with('success', 'Student assigned successfully.');
        }

        return redirect()->back()->with('error', 'Failed to assign student.');
    }

    /**
     * Remove student from dormitory
     */
    public function unassign(string $assignmentId)
    {
        $assignment = $this->assignmentModel->find($assignmentId);
        
        if (!$assignment) {
            return redirect()->back()->with('error', 'Assignment not found.');
        }

        $dormitoryId = $assignment['dormitory_id'];

        if ($this->assignmentModel->unassignStudent($assignmentId)) {
            // Update dormitory status if was full
            $dormitory = $this->dormitoryModel->getWithOccupancy($dormitoryId);
            if ($dormitory && $dormitory['status'] === 'full' && $dormitory['available_beds'] > 0) {
                $this->dormitoryModel->update($dormitoryId, ['status' => 'available']);
            }
            
            return redirect()->back()->with('success', 'Student removed from dormitory.');
        }

        return redirect()->back()->with('error', 'Failed to remove student.');
    }

    /**
     * Handle gallery image upload
     */
    protected function handleGalleryUpload(?string $id = null): array
    {
        $files = $this->request->getFiles();
        $gallery = [];

        if (!isset($files['gallery'])) {
            return $gallery;
        }

        $uploadPath = FCPATH . 'uploads/dormitories/';
        
        // Create directory if not exists
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        foreach ($files['gallery'] as $file) {
            if ($file->isValid() && !$file->hasMoved()) {
                // Validate file
                $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
                if (!in_array($file->getMimeType(), $allowedTypes)) {
                    continue;
                }

                // Max 5MB
                if ($file->getSize() > 5 * 1024 * 1024) {
                    continue;
                }

                // Generate unique filename
                $newName = $file->getRandomName();
                
                // Move file
                if ($file->move($uploadPath, $newName)) {
                    $gallery[] = 'dormitories/' . $newName;
                }
            }
        }

        return $gallery;
    }

    /**
     * Search students and display their dormitory assignment
     */
    public function searchStudent()
    {
        $search = $this->request->getGet('q');
        $results = [];

        if (!empty($search)) {
            $results = $this->assignmentModel->searchStudentWithAssignment($search);
        }

        return view('Modules\Dormitory\Views\search', [
            'title'   => 'Search Student Dormitory',
            'search'  => $search,
            'results' => $results,
            'menu'    => ['index' => base_url('dormitory')],
        ]);
    }

    /**
     * View student dormitory assignment details
     */
    public function studentAssignment(int $studentId)
    {
        $student = $this->assignmentModel->getStudentWithAssignment($studentId);

        if (!$student) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('Modules\Dormitory\Views\student_assignment', [
            'title'   => 'Student Dormitory Assignment',
            'student' => $student,
            'menu'    => ['index' => base_url('dormitory')],
        ]);
    }
}
