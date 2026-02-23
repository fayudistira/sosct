<?php

namespace Modules\Tools\Hanzi\Controllers;

use App\Controllers\BaseController;
use Modules\Tools\Hanzi\Models\HanziModel;
use CodeIgniter\HTTP\ResponseInterface;

class HanziController extends BaseController
{
    protected $hanziModel;

    public function __construct()
    {
        $this->hanziModel = new HanziModel();
    }

    /**
     * Display list of hanzi
     */
    public function index()
    {
        $user = auth()->user();
        $menuItems = $this->loadModuleMenus();
        
        $category = $this->request->getGet('category');
        $search = $this->request->getGet('search');

        $builder = $this->hanziModel->builder();

        if ($category) {
            $builder->where('category', $category);
        }

        if ($search) {
            $builder->groupStart();
            $builder->like('hanzi', $search);
            $builder->orLike('pinyin', $search);
            $builder->groupEnd();
        }

        $perPage = 20;
        $hanzi = $this->hanziModel->paginate($perPage);
        $pager = $this->hanziModel->pager;
        $categories = $this->hanziModel->getCategoriesWithCount();

        $data = [
            'title' => 'Hanzi Flashcard Manager',
            'user' => $user,
            'menuItems' => $menuItems,
            'hanzi' => $hanzi,
            'pager' => $pager,
            'categories' => $categories,
            'currentCategory' => $category,
            'search' => $search,
        ];

        return view('Modules\Tools\Hanzi\Views\index', $data);
    }

    /**
     * Display create form
     */
    public function create()
    {
        $user = auth()->user();
        $menuItems = $this->loadModuleMenus();
        
        $data = [
            'title' => 'Add New Hanzi',
            'user' => $user,
            'menuItems' => $menuItems,
            'categories' => ['HSK1', 'HSK2', 'HSK3', 'HSK4', 'HSK5', 'HSK6', 'OTHER'],
        ];

        return view('Modules\Tools\Hanzi\Views\create', $data);
    }

    /**
     * Store new hanzi
     */
    public function store()
    {
        $rules = [
            'hanzi' => 'required|max_length[50]|is_unique[hanzi.hanzi]',
            'pinyin' => 'required|max_length[100]',
            'category' => 'required|in_list[HSK1,HSK2,HSK3,HSK4,HSK5,HSK6,OTHER]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $translation = [
            'en' => $this->request->getPost('translation_en'),
            'id' => $this->request->getPost('translation_id'),
        ];

        $example = [
            'en' => $this->request->getPost('example_en'),
            'id' => $this->request->getPost('example_id'),
        ];

        $data = [
            'hanzi' => $this->request->getPost('hanzi'),
            'pinyin' => $this->request->getPost('pinyin'),
            'category' => $this->request->getPost('category'),
            'translation' => json_encode($translation),
            'example' => json_encode($example),
            'stroke_count' => $this->request->getPost('stroke_count') ?: null,
            'frequency' => $this->request->getPost('frequency') ?: null,
        ];

        if ($this->hanziModel->insert($data)) {
            return redirect()->to('/tools/hanzi')->with('success', 'Hanzi added successfully');
        }

        return redirect()->back()->withInput()->with('error', 'Failed to add hanzi');
    }

    /**
     * Display edit form
     */
    public function edit($id)
    {
        $user = auth()->user();
        $menuItems = $this->loadModuleMenus();
        
        $hanzi = $this->hanziModel->find($id);

        if (!$hanzi) {
            return redirect()->to('/tools/hanzi')->with('error', 'Hanzi not found');
        }

        $data = [
            'title' => 'Edit Hanzi',
            'user' => $user,
            'menuItems' => $menuItems,
            'hanzi' => $hanzi,
            'categories' => ['HSK1', 'HSK2', 'HSK3', 'HSK4', 'HSK5', 'HSK6', 'OTHER'],
        ];

        return view('Modules\Tools\Hanzi\Views\edit', $data);
    }

    /**
     * Update hanzi
     */
    public function update($id)
    {
        $hanzi = $this->hanziModel->find($id);

        if (!$hanzi) {
            return redirect()->to('/tools/hanzi')->with('error', 'Hanzi not found');
        }

        $rules = [
            'hanzi' => "required|max_length[50]|is_unique[hanzi.hanzi,id,{$id}]",
            'pinyin' => 'required|max_length[100]',
            'category' => 'required|in_list[HSK1,HSK2,HSK3,HSK4,HSK5,HSK6,OTHER]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $translation = [
            'en' => $this->request->getPost('translation_en'),
            'id' => $this->request->getPost('translation_id'),
        ];

        $example = [
            'en' => $this->request->getPost('example_en'),
            'id' => $this->request->getPost('example_id'),
        ];

        $data = [
            'hanzi' => $this->request->getPost('hanzi'),
            'pinyin' => $this->request->getPost('pinyin'),
            'category' => $this->request->getPost('category'),
            'translation' => json_encode($translation),
            'example' => json_encode($example),
            'stroke_count' => $this->request->getPost('stroke_count') ?: null,
            'frequency' => $this->request->getPost('frequency') ?: null,
        ];

        if ($this->hanziModel->update($id, $data)) {
            return redirect()->to('/tools/hanzi')->with('success', 'Hanzi updated successfully');
        }

        return redirect()->back()->withInput()->with('error', 'Failed to update hanzi');
    }

    /**
     * Delete hanzi
     */
    public function delete($id)
    {
        $hanzi = $this->hanziModel->find($id);

        if (!$hanzi) {
            return redirect()->to('/tools/hanzi')->with('error', 'Hanzi not found');
        }

        if ($this->hanziModel->delete($id)) {
            return redirect()->to('/tools/hanzi')->with('success', 'Hanzi deleted successfully');
        }

        return redirect()->to('/tools/hanzi')->with('error', 'Failed to delete hanzi');
    }

    /**
     * Display bulk upload form
     */
    public function bulkUpload()
    {
        $user = auth()->user();
        $menuItems = $this->loadModuleMenus();
        
        $data = [
            'title' => 'Bulk Upload Hanzi',
            'user' => $user,
            'menuItems' => $menuItems,
        ];

        return view('Modules\Tools\Hanzi\Views\bulk_upload', $data);
    }

    /**
     * Process bulk upload
     */
    public function processBulkUpload()
    {
        $file = $this->request->getFile('csv_file');

        if (!$file || !$file->isValid()) {
            return redirect()->back()->with('error', 'Please select a valid file');
        }

        $ext = strtolower($file->getExtension());

        if (!in_array($ext, ['csv', 'json', 'xlsx', 'xls'])) {
            return redirect()->back()->with('error', 'Only CSV, JSON, and Excel files are supported');
        }

        $data = [];

        if ($ext === 'csv') {
            $data = $this->parseCsv($file);
        } elseif ($ext === 'json') {
            $data = $this->parseJson($file);
        } elseif (in_array($ext, ['xlsx', 'xls'])) {
            $data = $this->parseExcel($file);
        }

        if (empty($data)) {
            return redirect()->back()->with('error', 'No valid data found in file');
        }

        $successCount = 0;
        $errorCount = 0;

        foreach ($data as $item) {
            // Validate required fields
            if (empty($item['hanzi']) || empty($item['pinyin'])) {
                $errorCount++;
                continue;
            }

            // Prepare translation
            $translation = [
                'en' => $item['translation_en'] ?? $item['translation']['en'] ?? '',
                'id' => $item['translation_id'] ?? $item['translation']['id'] ?? '',
            ];

            // Prepare example
            $example = [
                'en' => $item['example_en'] ?? $item['example']['en'] ?? '',
                'id' => $item['example_id'] ?? $item['example']['id'] ?? '',
            ];

            $hanziData = [
                'hanzi' => $item['hanzi'],
                'pinyin' => $item['pinyin'],
                'category' => $item['category'] ?? 'OTHER',
                'translation' => json_encode($translation),
                'example' => json_encode($example),
                'stroke_count' => $item['stroke_count'] ?? null,
                'frequency' => $item['frequency'] ?? null,
            ];

            // Check if exists
            $existing = $this->hanziModel->where('hanzi', $item['hanzi'])->first();

            try {
                if ($existing) {
                    $this->hanziModel->update($existing->id, $hanziData);
                } else {
                    $this->hanziModel->insert($hanziData);
                }
                $successCount++;
            } catch (\Exception $e) {
                $errorCount++;
            }
        }

        return redirect()->to('/tools/hanzi')->with('success', "Bulk upload completed. Success: {$successCount}, Errors: {$errorCount}");
    }

    /**
     * Parse CSV file
     */
    private function parseCsv($file): array
    {
        $data = [];
        $filepath = $file->getTempName();
        $handle = fopen($filepath, 'r');

        if ($handle === false) {
            return $data;
        }

        // Get headers
        $headers = fgetcsv($handle, 1000, ',');

        if ($headers === false) {
            fclose($handle);
            return $data;
        }

        // Normalize headers
        $headers = array_map('strtolower', array_map('trim', $headers));

        while (($row = fgetcsv($handle, 1000, ',')) !== false) {
            if (count($row) === count($headers)) {
                $data[] = array_combine($headers, $row);
            }
        }

        fclose($handle);
        return $data;
    }

    /**
     * Parse JSON file
     */
    private function parseJson($file): array
    {
        $content = file_get_contents($file->getTempName());
        $data = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return [];
        }

        // Handle both array of objects and single object
        return isset($data[0]) ? $data : [$data];
    }

    /**
     * Parse Excel file (xlsx, xls)
     */
    private function parseExcel($file): array
    {
        try {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getTempName());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();
            
            if (count($rows) < 2) {
                return [];
            }
            
            // Get headers from first row
            $headers = array_map('strtolower', array_map('trim', $rows[0]));
            $data = [];
            
            // Process data rows
            for ($i = 1; $i < count($rows); $i++) {
                $row = $rows[$i];
                if (count($row) === count($headers)) {
                    $rowData = array_combine($headers, $row);
                    // Filter out empty rows
                    if (!empty(array_filter($rowData))) {
                        $data[] = $rowData;
                    }
                }
            }
            
            return $data;
        } catch (\Exception $e) {
            log_message('error', 'Excel parse error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Display flashcards
     */
    public function flashcards()
    {
        $user = auth()->user();
        $menuItems = $this->loadModuleMenus();
        
        $category = $this->request->getGet('category');
        $limit = (int) $this->request->getGet('limit') ?: 10;

        $hanzi = $this->hanziModel->getRandomForFlashcards($limit, $category);

        $data = [
            'title' => 'Hanzi Flashcards',
            'user' => $user,
            'menuItems' => $menuItems,
            'hanzi' => $hanzi,
            'categories' => ['HSK1', 'HSK2', 'HSK3', 'HSK4', 'HSK5', 'HSK6', 'OTHER'],
            'currentCategory' => $category,
            'limit' => $limit,
        ];

        return view('Modules\Tools\Hanzi\Views\flashcards', $data);
    }

    /**
     * API: Get hanzi for flashcards (AJAX)
     */
    public function apiGetFlashcards()
    {
        $category = $this->request->getGet('category');
        $limit = (int) $this->request->getGet('limit') ?: 10;

        $hanzi = $this->hanziModel->getRandomForFlashcards($limit, $category);

        return $this->response->setJSON([
            'success' => true,
            'data' => $hanzi,
        ]);
    }

    /**
     * API: Get all hanzi (AJAX)
     */
    public function apiIndex()
    {
        $category = $this->request->getGet('category');
        $search = $this->request->getGet('search');

        $builder = $this->hanziModel->builder();

        if ($category) {
            $builder->where('category', $category);
        }

        if ($search) {
            $builder->groupStart();
            $builder->like('hanzi', $search);
            $builder->orLike('pinyin', $search);
            $builder->groupEnd();
        }

        $hanzi = $builder->limit(50)->get()->getResult();

        return $this->response->setJSON([
            'success' => true,
            'data' => $hanzi,
        ]);
    }

    /**
     * API: Delete hanzi (AJAX)
     */
    public function apiDelete($id)
    {
        $hanzi = $this->hanziModel->find($id);

        if (!$hanzi) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Hanzi not found',
            ])->setStatusCode(404);
        }

        if ($this->hanziModel->delete($id)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Hanzi deleted successfully',
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Failed to delete hanzi',
        ])->setStatusCode(500);
    }
}
