<?php

namespace Modules\Inventory\Controllers;

use App\Controllers\BaseController;
use Modules\Inventory\Models\ItemModel;
use Modules\Inventory\Models\CategoryModel;
use Modules\Inventory\Models\LocationModel;
use Modules\Program\Models\ProgramModel;

class ItemController extends BaseController
{
    protected $itemModel;
    protected $categoryModel;
    protected $locationModel;
    protected $programModel;

    public function __construct()
    {
        $this->itemModel = new ItemModel();
        $this->categoryModel = new CategoryModel();
        $this->locationModel = new LocationModel();
        $this->programModel = new ProgramModel();
    }

    /**
     * List all items
     */
    public function index()
    {
        $page = (int) ($this->request->getGet('page') ?? 1);
        $perPage = 20;
        $search = $this->request->getGet('search');
        $category = $this->request->getGet('category');
        $location = $this->request->getGet('location');
        $status = $this->request->getGet('status');
        $sort = $this->request->getGet('sort') ?? 'created_at';
        $order = $this->request->getGet('order') ?? 'desc';

        // Validate sort column
        $allowedSorts = ['name', 'item_code', 'current_stock', 'created_at', 'updated_at', 'selling_price'];
        if (!in_array($sort, $allowedSorts)) {
            $sort = 'created_at';
        }
        if (!in_array($order, ['asc', 'desc'])) {
            $order = 'desc';
        }

        $builder = $this->itemModel->builder();
        
        if ($search) {
            $builder->like('name', $search)
                    ->orLike('item_code', $search)
                    ->orLike('barcode', $search);
        }
        
        if ($category) {
            $builder->where('category_id', $category);
        }
        
        if ($location) {
            $builder->where('location_id', $location);
        }
        
        if ($status) {
            $builder->where('status', $status);
        } else {
            $builder->where('status', 'active');
        }

        // Apply sorting
        $builder->orderBy($sort, $order);

        $data = [
            'items' => $this->itemModel->paginate($perPage, 'default', $page),
            'pager' => $this->itemModel->pager,
            'categories' => $this->categoryModel->findAll(),
            'locations' => $this->locationModel->findAll(),
            'programs' => $this->programModel->where('status', 'active')->findAll(),
            'search' => $search,
            'selectedCategory' => $category,
            'selectedLocation' => $location,
            'selectedStatus' => $status,
            'sort' => $sort,
            'order' => $order
        ];

        // Index categories and locations by ID for easy lookup in view
        $categoryList = [];
        foreach ($this->categoryModel->findAll() as $cat) {
            $categoryList[$cat['id']] = $cat;
        }
        $locationList = [];
        foreach ($this->locationModel->findAll() as $loc) {
            $locationList[$loc['id']] = $loc;
        }
        $data['categories'] = $categoryList;
        $data['locations'] = $locationList;
        
        // Also keep original arrays for filter dropdowns
        $data['categoryList'] = $this->categoryModel->findAll();
        $data['locationList'] = $this->locationModel->findAll();

        return view('Modules\Inventory\Views\items\index', $data);
    }

    /**
     * Show create form
     */
    public function create()
    {
        $data = [
            'categories' => $this->categoryModel->findAll(),
            'locations' => $this->locationModel->findAll(),
            'programs' => $this->programModel->where('status', 'active')->findAll(),
            'units' => $this->itemModel->getUnits(),
            'itemCode' => $this->itemModel->generateItemCode()
        ];

        return view('Modules\Inventory\Views\items\create', $data);
    }

    /**
     * Store new item
     */
    public function store()
    {
        $data = $this->request->getPost();

        // Generate ID if not provided
        if (empty($data['id'])) {
            $data['id'] = uuid_v4();
        }

        // Generate item code if not provided
        if (empty($data['item_code'])) {
            $data['item_code'] = $this->itemModel->generateItemCode();
        }

        // Handle barcode - remove if empty to avoid unique constraint errors
        if (empty($data['barcode'])) {
            unset($data['barcode']);
        }

        // Handle specifications JSON
        if (isset($data['specifications']) && is_array($data['specifications'])) {
            $data['specifications'] = json_encode($data['specifications']);
        }

        // Handle picture uploads
        $pictures = $this->handlePictureUploads($data['id']);
        if ($pictures) {
            $data['pictures'] = json_encode($pictures);
        }

        if ($this->itemModel->insert($data)) {
            return redirect()->to('/inventory/items')->with('success', 'Item created successfully');
        }

        return redirect()->back()->withInput()->with('error', 'Failed to create item: ' . implode(', ', $this->itemModel->errors()));
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $item = $this->itemModel->find($id);
        
        if (!$item) {
            return redirect()->to('/inventory/items')->with('error', 'Item not found');
        }

        // Decode specifications
        if ($item['specifications']) {
            $item['specifications'] = json_decode($item['specifications'], true);
        }

        $data = [
            'item' => $item,
            'categories' => $this->categoryModel->findAll(),
            'locations' => $this->locationModel->findAll(),
            'programs' => $this->programModel->where('status', 'active')->findAll(),
            'units' => $this->itemModel->getUnits()
        ];

        return view('Modules\Inventory\Views\items\edit', $data);
    }

    /**
     * Update item
     */
    public function update($id)
    {
        $data = $this->request->getPost();

        // Include ID for validation context
        $data['id'] = $id;

        // Handle barcode - remove if empty to avoid unique constraint errors
        if (isset($data['barcode']) && empty($data['barcode'])) {
            unset($data['barcode']);
        }

        // Handle specifications JSON
        if (isset($data['specifications']) && is_array($data['specifications'])) {
            $data['specifications'] = json_encode($data['specifications']);
        }

        // Handle picture uploads
        $pictures = $this->handlePictureUploads($id);
        if ($pictures || isset($data['delete_pictures'])) {
            $existingItem = $this->itemModel->find($id);
            $existingPictures = isset($existingItem['pictures']) && $existingItem['pictures'] ? json_decode($existingItem['pictures'], true) : [];

            // Remove deleted pictures
            if (!empty($data['delete_pictures'])) {
                $deletePictures = json_decode($data['delete_pictures'], true);
                $existingPictures = array_diff($existingPictures, $deletePictures);

                // Delete files from server
                foreach ($deletePictures as $pic) {
                    $filePath = FCPATH . $pic;
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                }
            }

            // Merge with new pictures
            if ($pictures) {
                $existingPictures = array_merge($existingPictures, $pictures);
            }

            $data['pictures'] = json_encode($existingPictures);
        }

        // Remove temporary fields
        unset($data['delete_pictures']);

        if ($this->itemModel->update($id, $data)) {
            return redirect()->to('/inventory/items')->with('success', 'Item updated successfully');
        }

        return redirect()->back()->withInput()->with('error', 'Failed to update item: ' . implode(', ', $this->itemModel->errors()));
    }

    /**
     * View item details
     */
    public function view($id)
    {
        $item = $this->itemModel->find($id);
        
        if (!$item) {
            return redirect()->to('/inventory/items')->with('error', 'Item not found');
        }

        // Decode specifications
        if ($item['specifications']) {
            $item['specifications'] = json_decode($item['specifications'], true);
        }

        // Get related data
        $category = $item['category_id'] ? $this->categoryModel->find($item['category_id']) : null;
        $location = $item['location_id'] ? $this->locationModel->find($item['location_id']) : null;
        
        // Get movement history
        $movementModel = new \Modules\Inventory\Models\MovementModel();
        $movements = $movementModel->where('item_id', $id)
                                   ->orderBy('movement_date', 'DESC')
                                   ->limit(10)
                                   ->findAll();

        $data = [
            'item' => $item,
            'category' => $category,
            'location' => $location,
            'movements' => $movements
        ];

        return view('Modules\Inventory\Views\items\view', $data);
    }

    /**
     * Generate barcode view
     */
    public function barcode($id)
    {
        $item = $this->itemModel->find($id);
        
        if (!$item) {
            return redirect()->to('/inventory/items')->with('error', 'Item not found');
        }

        $data = [
            'item' => $item
        ];

        return view('Modules\Inventory\Views\items\barcode', $data);
    }

    /**
     * Delete item (soft delete)
     */
    public function delete($id)
    {
        if ($this->itemModel->delete($id)) {
            return redirect()->to('/inventory/items')->with('success', 'Item deleted successfully');
        }

        return redirect()->back()->with('error', 'Failed to delete item');
    }

    /**
     * Search items (AJAX)
     */
    public function search()
    {
        $keyword = $this->request->getGet('q');
        
        if (!$keyword) {
            return json_encode([]);
        }

        $items = $this->itemModel->search($keyword);
        
        return json_encode($items);
    }

    /**
     * Show bulk upload form
     */
    public function upload()
    {
        return view('Modules\Inventory\Views\items\upload');
    }

    /**
     * Download Excel template
     */
    public function template()
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set headers
        $headers = [
            'A1' => 'item_code',
            'B1' => 'barcode',
            'C1' => 'name',
            'D1' => 'description',
            'E1' => 'category_name',
            'F1' => 'location_name',
            'G1' => 'unit',
            'H1' => 'purchase_price',
            'I1' => 'selling_price',
            'J1' => 'current_stock',
            'K1' => 'minimum_stock',
            'L1' => 'maximum_stock',
            'M1' => 'supplier_id',
            'N1' => 'supplier_name',
            'O1' => 'status'
        ];

        foreach ($headers as $cell => $value) {
            $sheet->setCellValue($cell, $value);
        }

        // Add sample data
        $sampleData = [
            ['INV001', '1234567890123', 'Buku Tulis 100 Hlm', 'Buku tulis berkualitas tinggi', 'Alat Tulis', 'Gudang Utama', 'piece', '5000', '7500', '100', '10', '500', 'SUP001', 'Toko ABC', 'active'],
            ['INV002', '', 'Pena Biru', 'Pena biru standar', 'Alat Tulis', 'Gudang Utama', 'piece', '2000', '3500', '50', '5', '200', 'SUP001', 'Toko ABC', 'active'],
        ];

        $row = 2;
        foreach ($sampleData as $data) {
            $col = 'A';
            foreach ($data as $value) {
                $sheet->setCellValue($col . $row, $value);
                $col++;
            }
            $row++;
        }

        // Auto-size columns
        foreach (range('A', 'O') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Set header row bold
        $sheet->getStyle('A1:O1')->getFont()->setBold(true);

        // Download file
        $filename = 'inventory_template.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }

    /**
     * Process bulk upload
     */
    public function processUpload()
    {
        $file = $this->request->getFile('excel_file');

        if (!$file->isValid()) {
            return redirect()->back()->with('error', 'File tidak valid');
        }

        $extension = $file->getClientExtension();
        if (!in_array($extension, ['xlsx', 'xls'])) {
            return redirect()->back()->with('error', 'Format file harus Excel (.xlsx atau .xls)');
        }

        $updateExisting = $this->request->getPost('update_existing') === '1';

        try {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getTempName());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

            if (empty($rows)) {
                return redirect()->back()->with('error', 'File Excel kosong');
            }

            // Get headers from first row
            $headers = array_map('strtolower', array_map('trim', $rows[0]));
            array_shift($rows); // Remove header row

            // Get existing categories and locations for mapping
            $categories = $this->categoryModel->findAll();
            $locations = $this->locationModel->findAll();
            $categoryMap = array_column($categories, 'id', 'name');
            $locationMap = array_column($locations, 'id', 'name');

            $results = [
                'success' => 0,
                'skipped' => 0,
                'errors' => 0,
                'error_details' => []
            ];

            $db = \Config\Database::connect();
            $db->transStart();

            foreach ($rows as $index => $row) {
                if (empty(array_filter($row))) {
                    continue; // Skip empty rows
                }

                $rowNum = $index + 2; // Excel row number (1-based + header)
                $data = array_combine($headers, $row);

                // Validate required field
                if (empty($data['name'])) {
                    $results['errors']++;
                    $results['error_details'][] = [
                        'row' => $rowNum,
                        'name' => '-',
                        'message' => 'Nama item wajib diisi'
                    ];
                    continue;
                }

                // Check if item exists
                $itemCode = $data['item_code'] ?? '';
                $existingItem = null;

                if (!empty($itemCode)) {
                    $existingItem = $this->itemModel->where('item_code', $itemCode)->first();
                }

                if ($existingItem) {
                    if ($updateExisting) {
                        // Update existing item
                        $updateData = $this->prepareItemData($data, $categoryMap, $locationMap);
                        unset($updateData['item_code']); // Don't update item_code
                        $this->itemModel->update($existingItem['id'], $updateData);
                        $results['success']++;
                    } else {
                        // Skip existing item
                        $results['skipped']++;
                    }
                    continue;
                }

                // Create new item
                $itemData = $this->prepareItemData($data, $categoryMap, $locationMap);
                
                // Generate item code if not provided
                if (empty($itemData['item_code'])) {
                    $itemData['item_code'] = $this->itemModel->generateItemCode();
                }

                // Generate UUID
                $itemData['id'] = uuid_v4();

                try {
                    $this->itemModel->insert($itemData);
                    $results['success']++;
                } catch (\Exception $e) {
                    $results['errors']++;
                    $results['error_details'][] = [
                        'row' => $rowNum,
                        'name' => $data['name'],
                        'message' => $e->getMessage()
                    ];
                }
            }

            $db->transComplete();

            return view('Modules\Inventory\Views\items\upload', [
                'results' => $results
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Excel Upload Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memproses file: ' . $e->getMessage());
        }
    }

    /**
     * Prepare item data from Excel row
     */
    private function prepareItemData(array $data, array $categoryMap, array $locationMap): array
    {
        $itemData = [];

        // Map fields
        $fieldMap = [
            'item_code' => 'item_code',
            'barcode' => 'barcode',
            'name' => 'name',
            'description' => 'description',
            'unit' => 'unit',
            'purchase_price' => 'purchase_price',
            'selling_price' => 'selling_price',
            'current_stock' => 'current_stock',
            'minimum_stock' => 'minimum_stock',
            'maximum_stock' => 'maximum_stock',
            'supplier_id' => 'supplier_id',
            'supplier_name' => 'supplier_name',
            'status' => 'status'
        ];

        foreach ($fieldMap as $excelField => $dbField) {
            if (isset($data[$excelField]) && $data[$excelField] !== '') {
                $itemData[$dbField] = $data[$excelField];
            }
        }

        // Map category by name
        if (!empty($data['category_name']) && isset($categoryMap[$data['category_name']])) {
            $itemData['category_id'] = $categoryMap[$data['category_name']];
        }

        // Map location by name
        if (!empty($data['location_name']) && isset($locationMap[$data['location_name']])) {
            $itemData['location_id'] = $locationMap[$data['location_name']];
        }

        // Set default values
        $itemData['status'] = $itemData['status'] ?? 'active';
        $itemData['unit'] = $itemData['unit'] ?? 'piece';
        $itemData['current_stock'] = (int) ($itemData['current_stock'] ?? 0);
        $itemData['minimum_stock'] = (int) ($itemData['minimum_stock'] ?? 0);
        $itemData['maximum_stock'] = (int) ($itemData['maximum_stock'] ?? 0);

        return $itemData;
    }

    /**
     * Handle multiple picture uploads
     */
    private function handlePictureUploads(string $itemId): array
    {
        $pictures = [];
        $files = $this->request->getFiles();

        if (!isset($files['pictures'])) {
            return $pictures;
        }

        $uploadPath = FCPATH . 'uploads/items/' . $itemId . '/';

        // Create directory if it doesn't exist
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        foreach ($files['pictures'] as $file) {
            if ($file->isValid() && !$file->hasMoved()) {
                $newName = $file->getRandomName();
                $file->move($uploadPath, $newName);
                $pictures[] = 'uploads/items/' . $itemId . '/' . $newName;
            }
        }

        return $pictures;
    }
}
