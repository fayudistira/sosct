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

    /**
     * Download Excel template for bulk upload
     */
    public function downloadTemplate()
    {
        $templatePath = FCPATH . 'templates/dormitory_bulk_upload_template.xlsx';

        if (!file_exists($templatePath)) {
            return redirect()->back()->with('error', 'Template file not found.');
        }

        return $this->response->download($templatePath, null)->setFileName('dormitory_bulk_upload_template.xlsx');
    }

    /**
     * Handle bulk upload of dormitories via Excel
     */
    public function bulkUpload()
    {
        // Validate file upload
        $validationRules = [
            'excel_file' => [
                'uploaded[excel_file]',
                'max_size[excel_file,5120]',
                'ext_in[excel_file,xlsx,xls]'
            ]
        ];

        if (!$this->validate($validationRules)) {
            return redirect()->back()
                ->with('error', 'Invalid file. Please upload a valid Excel file (.xlsx or .xls) with maximum size of 5MB.');
        }

        $file = $this->request->getFile('excel_file');

        if (!$file->isValid()) {
            return redirect()->back()->with('error', 'File upload failed. Please try again.');
        }

        try {
            // Load the Excel file
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getTempName());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            // Remove header row
            $headers = array_shift($rows);

            // Validate headers
            $expectedHeaders = [
                'room_name',
                'location',
                'map_url',
                'room_capacity',
                'status',
                'facilities',
                'note'
            ];

            $headerCheck = array_map('strtolower', array_map('trim', $headers));
            $missingHeaders = array_diff($expectedHeaders, $headerCheck);

            if (!empty($missingHeaders)) {
                return redirect()->back()
                    ->with('error', 'Invalid template format. Missing columns: ' . implode(', ', $missingHeaders));
            }

            // Get column indices
            $columnMap = array_flip($headerCheck);

            // Process rows
            $successCount = 0;
            $errors = [];
            $rowNumber = 2; // Start from 2 (1 is header)

            foreach ($rows as $row) {
                // Skip empty rows
                if (empty(array_filter($row))) {
                    $rowNumber++;
                    continue;
                }

                // Extract data using column map
                $data = [
                    'room_name'     => trim($row[$columnMap['room_name']] ?? ''),
                    'location'      => trim($row[$columnMap['location']] ?? ''),
                    'map_url'       => trim($row[$columnMap['map_url']] ?? ''),
                    'room_capacity' => !empty($row[$columnMap['room_capacity']]) ? (int) $row[$columnMap['room_capacity']] : 1,
                    'status'        => !empty($row[$columnMap['status']]) ? strtolower(trim($row[$columnMap['status']])) : 'available',
                    'facilities'    => $this->parseNewlineSeparated($row[$columnMap['facilities']] ?? ''),
                    'note'          => trim($row[$columnMap['note']] ?? ''),
                ];

                // Validate required fields
                if (empty($data['room_name'])) {
                    $errors[] = "Row $rowNumber: Room Name is required";
                    $rowNumber++;
                    continue;
                }

                if (empty($data['location'])) {
                    $errors[] = "Row $rowNumber: Location is required";
                    $rowNumber++;
                    continue;
                }

                // Validate status
                $validStatuses = ['available', 'full', 'maintenance', 'inactive'];
                if (!in_array($data['status'], $validStatuses)) {
                    $errors[] = "Row $rowNumber: Status must be one of: " . implode(', ', $validStatuses);
                    $rowNumber++;
                    continue;
                }

                // Validate room capacity
                if ($data['room_capacity'] < 1) {
                    $errors[] = "Row $rowNumber: Room Capacity must be at least 1";
                    $rowNumber++;
                    continue;
                }

                // Validate map_url if provided
                if (!empty($data['map_url']) && !filter_var($data['map_url'], FILTER_VALIDATE_URL)) {
                    $errors[] = "Row $rowNumber: Map URL must be a valid URL";
                    $rowNumber++;
                    continue;
                }

                // Try to save
                if ($this->dormitoryModel->save($data)) {
                    $successCount++;
                } else {
                    $modelErrors = $this->dormitoryModel->errors();
                    $errors[] = "Row $rowNumber: " . implode(', ', $modelErrors);
                }

                $rowNumber++;
            }

            // Prepare response message
            if ($successCount > 0 && empty($errors)) {
                return redirect()->to(base_url('dormitory'))
                    ->with('success', "Successfully imported $successCount dormitor(y/ies).");
            } elseif ($successCount > 0 && !empty($errors)) {
                $errorMsg = "Imported $successCount dormitor(y/ies) with some errors:<br>" . implode('<br>', array_slice($errors, 0, 10));
                if (count($errors) > 10) {
                    $errorMsg .= '<br>... and ' . (count($errors) - 10) . ' more errors';
                }
                return redirect()->to(base_url('dormitory'))->with('warning', $errorMsg);
            } else {
                $errorMsg = "Failed to import dormitories. Errors:<br>" . implode('<br>', array_slice($errors, 0, 10));
                if (count($errors) > 10) {
                    $errorMsg .= '<br>... and ' . (count($errors) - 10) . ' more errors';
                }
                return redirect()->back()->with('error', $errorMsg);
            }
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error processing file: ' . $e->getMessage());
        }
    }

    /**
     * Parse newline-separated string into array
     */
    private function parseNewlineSeparated($value)
    {
        if (empty($value)) {
            return [];
        }

        // Handle both newline and pipe separators
        $items = array_filter(array_map('trim', preg_split('/[\n|]+/', $value)));
        return $items;
    }
}
