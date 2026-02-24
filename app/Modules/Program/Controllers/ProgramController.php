<?php

namespace Modules\Program\Controllers;

use App\Controllers\BaseController;
use Modules\Program\Models\ProgramModel;

class ProgramController extends BaseController
{
    protected $programModel;

    public function __construct()
    {
        $this->programModel = new ProgramModel();
    }

    /**
     * Display list of programs
     */
    public function index()
    {
        $perPage = 10;
        $keyword = $this->request->getGet('search');
        $status = $this->request->getGet('status');
        $category = $this->request->getGet('category');

        $builder = $this->programModel;

        if ($keyword) {
            $builder = $builder->like('title', $keyword)
                ->orLike('description', $keyword)
                ->orLike('category', $keyword);
        }

        if ($status) {
            $builder = $builder->where('status', $status);
        }

        if ($category) {
            $builder = $builder->where('category', $category);
        }

        $programs = $builder->paginate($perPage);
        $pager = $builder->pager;

        return view('Modules\Program\Views\index', [
            'title' => 'Programs',
            'programs' => $programs,
            'pager' => $pager,
            'keyword' => $keyword,
            'status' => $status,
            'category' => $category,
            'menuItems' => $this->loadModuleMenus(),
            'user' => auth()->user()
        ]);
    }

    /**
     * Display program details
     */
    public function view($id)
    {
        $program = $this->programModel->find($id);

        if (!$program) {
            return redirect()->to('/program')->with('error', 'Program not found.');
        }

        return view('Modules\Program\Views\view', [
            'title' => 'Program Details',
            'program' => $program,
            'menuItems' => $this->loadModuleMenus(),
            'user' => auth()->user()
        ]);
    }

    /**
     * Show create form
     */
    public function create()
    {
        return view('Modules\Program\Views\create', [
            'title' => 'Create Program',
            'menuItems' => $this->loadModuleMenus(),
            'user' => auth()->user()
        ]);
    }

    /**
     * Store new program
     */
    public function store()
    {
        $data = [
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'features' => $this->request->getPost('features'),
            'facilities' => $this->request->getPost('facilities'),
            'extra_facilities' => $this->request->getPost('extra_facilities'),
            'registration_fee' => $this->request->getPost('registration_fee') ?: 0,
            'tuition_fee' => $this->request->getPost('tuition_fee') ?: 0,
            'discount' => $this->request->getPost('discount') ?: 0,
            'language' => $this->request->getPost('language'),
            'language_level' => $this->request->getPost('language_level'),
            'category' => $this->request->getPost('category'),
            'sub_category' => $this->request->getPost('sub_category'),
            'duration' => $this->request->getPost('duration'),
            'status' => $this->request->getPost('status') ?: 'active',
            'mode' => $this->request->getPost('mode') ?: 'offline'
        ];

        // Handle curriculum - filter out empty entries
        $curriculum = $this->request->getPost('curriculum');
        if (!empty($curriculum) && is_array($curriculum)) {
            $filteredCurriculum = [];
            foreach ($curriculum as $chapter) {
                if (!empty($chapter['chapter']) || !empty($chapter['description'])) {
                    $filteredCurriculum[] = [
                        'chapter' => trim($chapter['chapter'] ?? ''),
                        'description' => trim($chapter['description'] ?? '')
                    ];
                }
            }
            $data['curriculum'] = !empty($filteredCurriculum) ? json_encode($filteredCurriculum) : null;
        }

        // Handle thumbnail upload
        $thumbnail = $this->request->getFile('thumbnail');
        if ($thumbnail && $thumbnail->isValid() && !$thumbnail->hasMoved()) {
            $newName = $thumbnail->getRandomName();
            $thumbnail->move(FCPATH . 'uploads/programs/thumbs', $newName);
            $data['thumbnail'] = $newName;
        }

        if ($this->programModel->save($data)) {
            return redirect()->to('/program')->with('success', 'Program created successfully.');
        }

        return redirect()->back()->withInput()->with('errors', $this->programModel->errors());
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $program = $this->programModel->find($id);

        if (!$program) {
            return redirect()->to('/program')->with('error', 'Program not found.');
        }

        // Convert arrays back to multiline strings for textarea
        if (is_array($program['features'])) {
            $program['features'] = implode("\n", $program['features']);
        }
        if (is_array($program['facilities'])) {
            $program['facilities'] = implode("\n", $program['facilities']);
        }
        if (is_array($program['extra_facilities'])) {
            $program['extra_facilities'] = implode("\n", $program['extra_facilities']);
        }

        return view('Modules\Program\Views\edit', [
            'title' => 'Edit Program',
            'program' => $program,
            'menuItems' => $this->loadModuleMenus(),
            'user' => auth()->user()
        ]);
    }

    /**
     * Update program
     */
    public function update($id)
    {
        $program = $this->programModel->find($id);

        if (!$program) {
            return redirect()->to('/program')->with('error', 'Program not found.');
        }

        $data = [
            'id' => $id,
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'features' => $this->request->getPost('features'),
            'facilities' => $this->request->getPost('facilities'),
            'extra_facilities' => $this->request->getPost('extra_facilities'),
            'registration_fee' => $this->request->getPost('registration_fee') ?: 0,
            'tuition_fee' => $this->request->getPost('tuition_fee') ?: 0,
            'discount' => $this->request->getPost('discount') ?: 0,
            'language' => $this->request->getPost('language'),
            'language_level' => $this->request->getPost('language_level'),
            'category' => $this->request->getPost('category'),
            'sub_category' => $this->request->getPost('sub_category'),
            'duration' => $this->request->getPost('duration'),
            'status' => $this->request->getPost('status') ?: 'active',
            'mode' => $this->request->getPost('mode') ?: 'offline'
        ];

        // Handle curriculum - filter out empty entries
        $curriculum = $this->request->getPost('curriculum');
        if (!empty($curriculum) && is_array($curriculum)) {
            $filteredCurriculum = [];
            foreach ($curriculum as $chapter) {
                if (!empty($chapter['chapter']) || !empty($chapter['description'])) {
                    $filteredCurriculum[] = [
                        'chapter' => trim($chapter['chapter'] ?? ''),
                        'description' => trim($chapter['description'] ?? '')
                    ];
                }
            }
            $data['curriculum'] = !empty($filteredCurriculum) ? json_encode($filteredCurriculum) : null;
        }

        // Handle thumbnail upload
        $thumbnail = $this->request->getFile('thumbnail');
        if ($thumbnail && $thumbnail->isValid() && !$thumbnail->hasMoved()) {
            // Delete old thumbnail if exists
            if (!empty($program['thumbnail'])) {
                $oldFile = FCPATH . 'uploads/programs/thumbs/' . $program['thumbnail'];
                if (file_exists($oldFile)) {
                    unlink($oldFile);
                }
            }

            $newName = $thumbnail->getRandomName();
            $thumbnail->move(FCPATH . 'uploads/programs/thumbs', $newName);
            $data['thumbnail'] = $newName;
        }

        if ($this->programModel->save($data)) {
            return redirect()->to('/program')->with('success', 'Program updated successfully.');
        }

        return redirect()->back()->withInput()->with('errors', $this->programModel->errors());
    }

    /**
     * Delete program (soft delete)
     */
    public function delete($id)
    {
        $program = $this->programModel->find($id);

        if (!$program) {
            return redirect()->to('/program')->with('error', 'Program not found.');
        }

        if ($this->programModel->delete($id)) {
            return redirect()->to('/program')->with('success', 'Program deleted successfully.');
        }

        return redirect()->to('/program')->with('error', 'Failed to delete program.');
    }

    /**
     * Download Excel template for bulk upload
     */
    public function downloadTemplate()
    {
        $templatePath = FCPATH . 'templates/program_bulk_upload_template.xlsx';

        if (!file_exists($templatePath)) {
            return redirect()->back()->with('error', 'Template file not found.');
        }

        return $this->response->download($templatePath, null)->setFileName('program_bulk_upload_template.xlsx');
    }

    /**
     * Handle bulk upload of programs via Excel
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
                'title',
                'description',
                'language',
                'language_level',
                'category',
                'sub_category',
                'registration_fee',
                'tuition_fee',
                'discount',
                'status',
                'mode',
                'duration',
                'features',
                'facilities',
                'extra_facilities'
            ];

            $headerCheck = array_map('strtolower', array_map('trim', $headers));
            $missingHeaders = array_diff($expectedHeaders, $headerCheck);

            if (!empty($missingHeaders)) {
                return redirect()->back()
                    ->with('error', 'Invalid template format. Missing columns: ' . implode(', ', $missingHeaders));
            }

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

                // Extract data
                $data = [
                    'title' => trim($row[0] ?? ''),
                    'description' => trim($row[1] ?? ''),
                    'language' => trim($row[2] ?? ''),
                    'language_level' => trim($row[3] ?? ''),
                    'category' => trim($row[4] ?? ''),
                    'sub_category' => trim($row[5] ?? ''),
                    'registration_fee' => !empty($row[6]) ? floatval($row[6]) : 0,
                    'tuition_fee' => !empty($row[7]) ? floatval($row[7]) : 0,
                    'discount' => !empty($row[8]) ? floatval($row[8]) : 0,
                    'status' => !empty($row[9]) ? strtolower(trim($row[9])) : 'active',
                    'mode' => !empty($row[10]) ? strtolower(trim($row[10])) : 'offline',
                    'duration' => trim($row[11] ?? ''),
                    'features' => $this->parsePipeSeparated($row[12] ?? ''),
                    'facilities' => $this->parsePipeSeparated($row[13] ?? ''),
                    'extra_facilities' => $this->parsePipeSeparated($row[14] ?? ''),
                ];

                // Validate required fields
                if (empty($data['title'])) {
                    $errors[] = "Row $rowNumber: Title is required";
                    $rowNumber++;
                    continue;
                }

                // Validate status
                if (!in_array($data['status'], ['active', 'inactive'])) {
                    $errors[] = "Row $rowNumber: Status must be 'active' or 'inactive'";
                    $rowNumber++;
                    continue;
                }

                // Validate mode
                if (!empty($data['mode']) && !in_array($data['mode'], ['online', 'offline'])) {
                    $errors[] = "Row $rowNumber: Mode must be 'online' or 'offline'";
                    $rowNumber++;
                    continue;
                }

                // Validate language (optional but must be valid if provided)
                $validLanguages = ['Mandarin', 'Japanese', 'Korean', 'German', 'English', 'Other'];
                if (!empty($data['language']) && !in_array($data['language'], $validLanguages)) {
                    $errors[] = "Row $rowNumber: Language must be one of: " . implode(', ', $validLanguages);
                    $rowNumber++;
                    continue;
                }

                // Validate language_level (optional but must be valid if provided)
                $validLevels = ['Beginner', 'Intermediate', 'Advanced', 'All Levels'];
                if (!empty($data['language_level']) && !in_array($data['language_level'], $validLevels)) {
                    $errors[] = "Row $rowNumber: Language Level must be one of: " . implode(', ', $validLevels);
                    $rowNumber++;
                    continue;
                }

                // Validate discount
                if ($data['discount'] < 0 || $data['discount'] > 100) {
                    $errors[] = "Row $rowNumber: Discount must be between 0 and 100";
                    $rowNumber++;
                    continue;
                }

                // Validate fees
                if ($data['registration_fee'] < 0 || $data['tuition_fee'] < 0) {
                    $errors[] = "Row $rowNumber: Fees cannot be negative";
                    $rowNumber++;
                    continue;
                }

                // Try to save
                if ($this->programModel->save($data)) {
                    $successCount++;
                } else {
                    $modelErrors = $this->programModel->errors();
                    $errors[] = "Row $rowNumber: " . implode(', ', $modelErrors);
                }

                $rowNumber++;
            }

            // Prepare response message
            if ($successCount > 0 && empty($errors)) {
                return redirect()->to('/program')
                    ->with('success', "Successfully imported $successCount program(s).");
            } elseif ($successCount > 0 && !empty($errors)) {
                $errorMsg = "Imported $successCount program(s) with some errors:<br>" . implode('<br>', array_slice($errors, 0, 10));
                if (count($errors) > 10) {
                    $errorMsg .= '<br>... and ' . (count($errors) - 10) . ' more errors';
                }
                return redirect()->to('/program')->with('warning', $errorMsg);
            } else {
                $errorMsg = "Failed to import programs. Errors:<br>" . implode('<br>', array_slice($errors, 0, 10));
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
     * Parse pipe-separated string into array
     */
    private function parsePipeSeparated($value)
    {
        if (empty($value)) {
            return '';
        }

        $items = array_map('trim', explode('|', $value));
        return array_filter($items); // Remove empty items
    }
}
