# Design: Modular ERP Backend

## 1. Architecture Overview

### 1.1 System Architecture
The application follows a modular HMVC (Hierarchical Model-View-Controller) architecture where each module is self-contained with its own MVC components. This design promotes:
- **Separation of concerns**: Each module handles specific business logic
- **Reusability**: Modules can be reused across projects
- **Maintainability**: Changes to one module don't affect others
- **Scalability**: New modules can be added without modifying existing code

### 1.2 Directory Structure
```
app/
├── Modules/
│   ├── Frontend/
│   │   ├── Config/
│   │   │   └── Routes.php
│   │   ├── Controllers/
│   │   │   └── PageController.php
│   │   └── Views/
│   │       ├── layout.php
│   │       ├── home.php
│   │       ├── about.php
│   │       ├── contact.php
│   │       ├── apply.php
│   │       └── apply_success.php
│   ├── Dashboard/
│   │   ├── Config/
│   │   │   └── Routes.php
│   │   ├── Controllers/
│   │   │   └── DashboardController.php
│   │   └── Views/
│   │       ├── layout.php
│   │       └── index.php
│   └── Admission/
│       ├── Config/
│       │   └── Routes.php
│       ├── Controllers/
│       │   ├── AdmissionController.php
│       │   └── Api/
│       │       └── AdmissionApiController.php
│       ├── Models/
│       │   └── AdmissionModel.php
│       └── Views/
│           ├── index.php
│           ├── create.php
│           ├── edit.php
│           └── view.php
├── Config/
│   ├── Autoload.php (modified)
│   └── Routes.php (modified)
└── Database/
    └── Migrations/
        └── 2024-01-29-000001_create_admissions_table.php

writable/
└── uploads/
    └── admissions/
        ├── photos/
        └── documents/
```

## 2. Module Design

### Overview
The system has three main modules with distinct purposes:

1. **Frontend Module**: Public-facing pages including admission application form
2. **Dashboard Module**: Authenticated user hub with permission-based navigation
3. **Admission Module**: Staff interface for managing admission applications

**Admission Workflow**:
1. Student visits `/apply` (Frontend) and submits application
2. Application saved with status "pending"
3. Staff logs in and accesses `/admission` (Admission Module)
4. Staff reviews, edits, and updates status (approved/rejected)

### 2.1 Frontend Module

**Purpose**: Serve public-facing static pages and public admission application form

**Components**:

#### Controllers
- `PageController`: Single controller handling all static pages and public forms

**Methods**:
```php
class PageController extends BaseController
{
    protected $admissionModel;
    
    public function __construct()
    {
        $this->admissionModel = new \Modules\Admission\Models\AdmissionModel();
    }
    
    public function home(): string
    {
        return view('Modules\Frontend\Views\home', [
            'title' => 'Home'
        ]);
    }
    
    public function about(): string
    {
        return view('Modules\Frontend\Views\about', [
            'title' => 'About Us'
        ]);
    }
    
    public function contact(): string
    {
        return view('Modules\Frontend\Views\contact', [
            'title' => 'Contact Us'
        ]);
    }
    
    public function apply(): string
    {
        return view('Modules\Frontend\Views\apply', [
            'title' => 'Apply for Admission'
        ]);
    }
    
    public function submitApplication()
    {
        // Validate input
        $rules = [
            'full_name' => 'required|min_length[3]|max_length[100]',
            'gender' => 'required|in_list[Male,Female]',
            'place_of_birth' => 'required|min_length[3]|max_length[100]',
            'date_of_birth' => 'required|valid_date',
            'religion' => 'required|min_length[3]|max_length[50]',
            'phone' => 'required|regex_match[/^[0-9]{10,15}$/]',
            'email' => 'required|valid_email|is_unique[admissions.email]',
            'street_address' => 'required|min_length[5]',
            'district' => 'required|min_length[3]',
            'regency' => 'required|min_length[3]',
            'province' => 'required|min_length[3]',
            'emergency_contact_name' => 'required|min_length[3]|max_length[100]',
            'emergency_contact_phone' => 'required|regex_match[/^[0-9]{10,15}$/]',
            'emergency_contact_relation' => 'required|min_length[3]|max_length[50]',
            'father_name' => 'required|min_length[3]|max_length[100]',
            'mother_name' => 'required|min_length[3]|max_length[100]',
            'course' => 'required|min_length[3]',
            'photo' => 'uploaded[photo]|max_size[photo,2048]|is_image[photo]|mime_in[photo,image/jpg,image/jpeg,image/png]',
            'documents.*' => 'max_size[documents,5120]|ext_in[documents,pdf,doc,docx]',
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }
        
        // Handle photo upload
        $photo = $this->request->getFile('photo');
        $photoName = null;
        
        if ($photo && $photo->isValid() && !$photo->hasMoved()) {
            $photoName = $photo->getRandomName();
            $photo->move(WRITEPATH . 'uploads/admissions/photos', $photoName);
        }
        
        // Handle documents upload
        $documents = $this->request->getFileMultiple('documents');
        $documentNames = [];
        
        if ($documents) {
            foreach ($documents as $doc) {
                if ($doc->isValid() && !$doc->hasMoved()) {
                    $docName = $doc->getRandomName();
                    $doc->move(WRITEPATH . 'uploads/admissions/documents', $docName);
                    $documentNames[] = $docName;
                }
            }
        }
        
        // Prepare data
        $data = [
            'registration_number' => $this->admissionModel->generateRegistrationNumber(),
            'full_name' => $this->request->getPost('full_name'),
            'nickname' => $this->request->getPost('nickname'),
            'gender' => $this->request->getPost('gender'),
            'place_of_birth' => $this->request->getPost('place_of_birth'),
            'date_of_birth' => $this->request->getPost('date_of_birth'),
            'religion' => $this->request->getPost('religion'),
            'citizen_id' => $this->request->getPost('citizen_id'),
            'phone' => $this->request->getPost('phone'),
            'email' => $this->request->getPost('email'),
            'street_address' => $this->request->getPost('street_address'),
            'district' => $this->request->getPost('district'),
            'regency' => $this->request->getPost('regency'),
            'province' => $this->request->getPost('province'),
            'postal_code' => $this->request->getPost('postal_code'),
            'emergency_contact_name' => $this->request->getPost('emergency_contact_name'),
            'emergency_contact_phone' => $this->request->getPost('emergency_contact_phone'),
            'emergency_contact_relation' => $this->request->getPost('emergency_contact_relation'),
            'father_name' => $this->request->getPost('father_name'),
            'mother_name' => $this->request->getPost('mother_name'),
            'course' => $this->request->getPost('course'),
            'notes' => $this->request->getPost('notes'),
            'photo' => $photoName,
            'documents' => !empty($documentNames) ? json_encode($documentNames) : null,
            'status' => 'pending',
            'application_date' => date('Y-m-d'),
        ];
        
        // Save application
        if (!$this->admissionModel->save($data)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to submit application. Please try again.');
        }
        
        // Get the registration number for confirmation
        $registrationNumber = $data['registration_number'];
        
        return redirect()->to('/apply/success')
            ->with('success', 'Your application has been submitted successfully!')
            ->with('registration_number', $registrationNumber);
    }
    
    public function applySuccess(): string
    {
        return view('Modules\Frontend\Views\apply_success', [
            'title' => 'Application Submitted'
        ]);
    }
}
```

#### Views
- Shared layout with header, footer, navigation
- Individual page views extending the layout
- `apply.php`: Public admission application form
- `apply_success.php`: Success confirmation page
- Responsive design using CSS framework (Bootstrap/Tailwind)

#### Routes
```php
// Modules/Frontend/Config/Routes.php
$routes->get('/', 'Modules\Frontend\Controllers\PageController::home');
$routes->get('about', 'Modules\Frontend\Controllers\PageController::about');
$routes->get('contact', 'Modules\Frontend\Controllers\PageController::contact');
$routes->get('apply', 'Modules\Frontend\Controllers\PageController::apply');
$routes->post('apply/submit', 'Modules\Frontend\Controllers\PageController::submitApplication');
$routes->get('apply/success', 'Modules\Frontend\Controllers\PageController::applySuccess');
```

### 2.2 Dashboard Module

**Purpose**: Central hub for authenticated users with permission-based navigation

**Components**:

#### Controllers
- `DashboardController`: Main dashboard logic

**Methods**:
```php
class DashboardController extends BaseController
{
    public function index(): string
    {
        $user = auth()->user();
        
        // Get available modules based on Shield permissions
        $availableModules = $this->getAvailableModules($user);
        
        return view('Modules\Dashboard\Views\index', [
            'user' => $user,
            'modules' => $availableModules
        ]);
    }
    
    private function getAvailableModules($user): array
    {
        $modules = [];
        
        // Check permissions using Shield's built-in can() method
        if ($user->can('admission.manage')) {
            $modules[] = [
                'name' => 'Admission',
                'url' => '/admission',
                'icon' => 'users',
                'description' => 'Manage student admissions'
            ];
        }
        
        // Add more modules based on permissions
        // Example: if ($user->can('courses.manage')) { ... }
        
        return $modules;
    }
}
```

#### Routes
```php
// Modules/Dashboard/Config/Routes.php
$routes->group('dashboard', ['filter' => 'session'], function($routes) {
    $routes->get('/', 'Modules\Dashboard\Controllers\DashboardController::index');
});
```

### 2.3 Admission Module

**Purpose**: Manage student admission applications (staff/admin interface)

**Components**:

#### Models
- `AdmissionModel`: Database operations for admissions

**Properties & Methods**:
```php
class AdmissionModel extends Model
{
    protected $table = 'admissions';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $allowedFields = [
        'registration_number',
        'full_name',
        'nickname',
        'gender',
        'place_of_birth',
        'date_of_birth',
        'religion',
        'citizen_id',
        'phone',
        'email',
        'street_address',
        'district',
        'regency',
        'province',
        'postal_code',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relation',
        'father_name',
        'mother_name',
        'course',
        'status',
        'application_date',
        'photo',
        'documents',
        'notes'
    ];
    
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
    
    protected $validationRules = [
        'registration_number' => 'required|is_unique[admissions.registration_number,id,{id}]',
        'full_name' => 'required|min_length[3]|max_length[100]',
        'gender' => 'required|in_list[Male,Female]',
        'place_of_birth' => 'required|min_length[3]|max_length[100]',
        'date_of_birth' => 'required|valid_date',
        'religion' => 'required|min_length[3]|max_length[50]',
        'phone' => 'required|regex_match[/^[0-9]{10,15}$/]',
        'email' => 'required|valid_email|is_unique[admissions.email,id,{id}]',
        'street_address' => 'required|min_length[5]',
        'district' => 'required|min_length[3]',
        'regency' => 'required|min_length[3]',
        'province' => 'required|min_length[3]',
        'emergency_contact_name' => 'required|min_length[3]|max_length[100]',
        'emergency_contact_phone' => 'required|regex_match[/^[0-9]{10,15}$/]',
        'emergency_contact_relation' => 'required|min_length[3]|max_length[50]',
        'father_name' => 'required|min_length[3]|max_length[100]',
        'mother_name' => 'required|min_length[3]|max_length[100]',
        'course' => 'required|min_length[3]',
        'status' => 'required|in_list[pending,approved,rejected]'
    ];
    
    protected $validationMessages = [
        'email' => [
            'is_unique' => 'This email is already registered.'
        ],
        'registration_number' => [
            'is_unique' => 'This registration number already exists.'
        ]
    ];
    
    /**
     * Generate unique registration number
     * Format: REG-YYYY-NNNN (e.g., REG-2024-0001)
     */
    public function generateRegistrationNumber(): string
    {
        $year = date('Y');
        $prefix = "REG-{$year}-";
        
        // Get the last registration number for current year
        $lastRecord = $this->like('registration_number', $prefix)
                          ->orderBy('id', 'DESC')
                          ->first();
        
        if ($lastRecord) {
            // Extract the number part and increment
            $lastNumber = (int) substr($lastRecord['registration_number'], -4);
            $newNumber = $lastNumber + 1;
        } else {
            // First registration of the year
            $newNumber = 1;
        }
        
        // Format with leading zeros (4 digits)
        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
    
    protected $validationMessages = [
        'email' => [
            'is_unique' => 'This email is already registered.'
        ]
    ];
    
    public function getWithPagination(int $perPage = 10)
    {
        return $this->orderBy('created_at', 'DESC')->paginate($perPage);
    }
    
    public function searchAdmissions(string $keyword)
    {
        return $this->like('registration_number', $keyword)
                    ->orLike('full_name', $keyword)
                    ->orLike('email', $keyword)
                    ->orLike('course', $keyword)
                    ->orLike('phone', $keyword)
                    ->findAll();
    }
    
    public function filterByStatus(string $status)
    {
        return $this->where('status', $status)->findAll();
    }
    
    public function getStatusCounts(): array
    {
        return [
            'pending' => $this->where('status', 'pending')->countAllResults(),
            'approved' => $this->where('status', 'approved')->countAllResults(),
            'rejected' => $this->where('status', 'rejected')->countAllResults(),
            'total' => $this->countAllResults(false)
        ];
    }
}
```

#### Controllers
- `AdmissionController`: Handles all admission management operations (staff only)

**Methods**:
```php
class AdmissionController extends BaseController
{
    protected $admissionModel;
    
    public function __construct()
    {
        $this->admissionModel = new AdmissionModel();
    }
    
    public function index(): string
    {
        $data['admissions'] = $this->admissionModel->getWithPagination(10);
        $data['pager'] = $this->admissionModel->pager;
        $data['statusCounts'] = $this->admissionModel->getStatusCounts();
        
        return view('Modules\Admission\Views\index', $data);
    }
    
    public function create(): string
    {
        // Staff can manually create admission records
        return view('Modules\Admission\Views\create');
    }
    
    public function store()
    {
        $data = $this->request->getPost();
        $data['application_date'] = $data['application_date'] ?? date('Y-m-d');
        
        if (!$this->admissionModel->save($data)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->admissionModel->errors());
        }
        
        return redirect()->to('/admission')
            ->with('success', 'Admission created successfully.');
    }
    
    public function edit($id): string
    {
        $data['admission'] = $this->admissionModel->find($id);
        
        if (!$data['admission']) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
        
        return view('Modules\Admission\Views\edit', $data);
    }
    
    public function update($id)
    {
        $data = $this->request->getPost();
        
        if (!$this->admissionModel->update($id, $data)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->admissionModel->errors());
        }
        
        return redirect()->to('/admission')
            ->with('success', 'Admission updated successfully.');
    }
    
    public function delete($id)
    {
        $this->admissionModel->delete($id);
        
        return redirect()->to('/admission')
            ->with('success', 'Admission deleted successfully.');
    }
    
    public function search()
    {
        $keyword = $this->request->getGet('keyword');
        $data['admissions'] = $this->admissionModel->searchAdmissions($keyword);
        $data['statusCounts'] = $this->admissionModel->getStatusCounts();
        
        return view('Modules\Admission\Views\index', $data);
    }
    
    public function view($id): string
    {
        $data['admission'] = $this->admissionModel->find($id);
        
        if (!$data['admission']) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
        
        // Decode documents JSON
        if (!empty($data['admission']['documents'])) {
            $data['admission']['documents'] = json_decode($data['admission']['documents'], true);
        }
        
        return view('Modules\Admission\Views\view', $data);
    }
    
    public function downloadDocument($id, $filename)
    {
        $admission = $this->admissionModel->find($id);
        
        if (!$admission) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
        
        $documents = json_decode($admission['documents'], true) ?? [];
        
        if (!in_array($filename, $documents)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Document not found');
        }
        
        $filepath = WRITEPATH . 'uploads/admissions/documents/' . $filename;
        
        if (!file_exists($filepath)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('File not found');
        }
        
        return $this->response->download($filepath, null);
    }
}
```

#### Database Migration
```php
// app/Database/Migrations/2024-01-29-000001_create_admissions_table.php
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAdmissionsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'registration_number' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'unique' => true,
            ],
            'full_name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'nickname' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'gender' => [
                'type' => 'ENUM',
                'constraint' => ['Male', 'Female'],
            ],
            'place_of_birth' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'date_of_birth' => [
                'type' => 'DATE',
            ],
            'religion' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'citizen_id' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
            ],
            'phone' => [
                'type' => 'VARCHAR',
                'constraint' => 15,
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'unique' => true,
            ],
            'street_address' => [
                'type' => 'TEXT',
            ],
            'district' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'regency' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'province' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'postal_code' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
                'null' => true,
            ],
            'emergency_contact_name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'emergency_contact_phone' => [
                'type' => 'VARCHAR',
                'constraint' => 15,
            ],
            'emergency_contact_relation' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'father_name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'mother_name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'course' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'approved', 'rejected'],
                'default' => 'pending',
            ],
            'application_date' => [
                'type' => 'DATE',
            ],
            'photo' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'documents' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'JSON array of document filenames',
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        
        $this->forge->addKey('id', true);
        $this->forge->addKey('registration_number');
        $this->forge->addKey('email');
        $this->forge->addKey('status');
        $this->forge->addKey('deleted_at');
        $this->forge->createTable('admissions');
    }
    
    public function down()
    {
        $this->forge->dropTable('admissions');
    }
}
```

**Note**: All migrations are stored in `app/Database/Migrations/` directory, not within individual modules.

#### Routes
```php
// Modules/Admission/Config/Routes.php
$routes->group('admission', ['filter' => 'permission:admission.manage'], function($routes) {
    $routes->get('/', 'Modules\Admission\Controllers\AdmissionController::index');
    $routes->get('view/(:num)', 'Modules\Admission\Controllers\AdmissionController::view/$1');
    $routes->get('download/(:num)/(:any)', 'Modules\Admission\Controllers\AdmissionController::downloadDocument/$1/$2');
    $routes->get('create', 'Modules\Admission\Controllers\AdmissionController::create');
    $routes->post('store', 'Modules\Admission\Controllers\AdmissionController::store');
    $routes->get('edit/(:num)', 'Modules\Admission\Controllers\AdmissionController::edit/$1');
    $routes->post('update/(:num)', 'Modules\Admission\Controllers\AdmissionController::update/$1');
    $routes->delete('delete/(:num)', 'Modules\Admission\Controllers\AdmissionController::delete/$1');
    $routes->get('search', 'Modules\Admission\Controllers\AdmissionController::search');
});
```

**Note**: Shield's `permission` filter is used to protect routes. The filter checks if the authenticated user has the specified permission.

**Key Differences from Frontend**:
- **Frontend**: Public application form (`/apply`) - students submit applications
- **Admission Module**: Staff management interface (`/admission`) - staff review, edit, approve/reject applications
- **API Module**: RESTful API (`/api/admissions`) - programmatic access for mobile apps and external systems
- All use the same `AdmissionModel` but serve different purposes

### 2.4 API Module (Admission API)

**Purpose**: RESTful API for programmatic access to admission data

**Components**:

#### Controllers
- `AdmissionApiController`: Handles all API requests

**Methods**:
```php
namespace Modules\Admission\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use Modules\Admission\Models\AdmissionModel;

class AdmissionApiController extends ResourceController
{
    protected $modelName = 'Modules\Admission\Models\AdmissionModel';
    protected $format = 'json';
    
    /**
     * GET /api/admissions
     * List all admissions with pagination
     */
    public function index()
    {
        $page = $this->request->getGet('page') ?? 1;
        $perPage = $this->request->getGet('per_page') ?? 10;
        
        $admissions = $this->model->paginate($perPage);
        $pager = $this->model->pager;
        
        return $this->respond([
            'status' => 'success',
            'data' => $admissions,
            'pagination' => [
                'current_page' => $pager->getCurrentPage(),
                'total_pages' => $pager->getPageCount(),
                'per_page' => $perPage,
                'total' => $pager->getTotal()
            ]
        ]);
    }
    
    /**
     * GET /api/admissions/{id}
     * Get single admission details
     */
    public function show($id = null)
    {
        $admission = $this->model->find($id);
        
        if (!$admission) {
            return $this->failNotFound('Admission not found');
        }
        
        // Decode documents JSON
        if (!empty($admission['documents'])) {
            $admission['documents'] = json_decode($admission['documents'], true);
        }
        
        return $this->respond([
            'status' => 'success',
            'data' => $admission
        ]);
    }
    
    /**
     * POST /api/admissions
     * Create new admission
     */
    public function create()
    {
        $data = $this->request->getJSON(true);
        
        // Generate registration number
        $data['registration_number'] = $this->model->generateRegistrationNumber();
        $data['status'] = $data['status'] ?? 'pending';
        $data['application_date'] = $data['application_date'] ?? date('Y-m-d');
        
        if (!$this->model->save($data)) {
            return $this->failValidationErrors($this->model->errors());
        }
        
        $id = $this->model->getInsertID();
        $admission = $this->model->find($id);
        
        return $this->respondCreated([
            'status' => 'success',
            'message' => 'Admission created successfully',
            'data' => $admission
        ]);
    }
    
    /**
     * PUT /api/admissions/{id}
     * Update admission
     */
    public function update($id = null)
    {
        $admission = $this->model->find($id);
        
        if (!$admission) {
            return $this->failNotFound('Admission not found');
        }
        
        $data = $this->request->getJSON(true);
        
        if (!$this->model->update($id, $data)) {
            return $this->failValidationErrors($this->model->errors());
        }
        
        $updated = $this->model->find($id);
        
        return $this->respond([
            'status' => 'success',
            'message' => 'Admission updated successfully',
            'data' => $updated
        ]);
    }
    
    /**
     * DELETE /api/admissions/{id}
     * Delete admission (soft delete)
     */
    public function delete($id = null)
    {
        $admission = $this->model->find($id);
        
        if (!$admission) {
            return $this->failNotFound('Admission not found');
        }
        
        $this->model->delete($id);
        
        return $this->respondDeleted([
            'status' => 'success',
            'message' => 'Admission deleted successfully'
        ]);
    }
    
    /**
     * GET /api/admissions/search?q={keyword}
     * Search admissions
     */
    public function search()
    {
        $keyword = $this->request->getGet('q');
        
        if (!$keyword) {
            return $this->fail('Search keyword is required');
        }
        
        $results = $this->model->searchAdmissions($keyword);
        
        return $this->respond([
            'status' => 'success',
            'data' => $results,
            'count' => count($results)
        ]);
    }
    
    /**
     * GET /api/admissions/filter?status={status}
     * Filter admissions by status
     */
    public function filter()
    {
        $status = $this->request->getGet('status');
        
        if (!$status) {
            return $this->fail('Status parameter is required');
        }
        
        if (!in_array($status, ['pending', 'approved', 'rejected'])) {
            return $this->fail('Invalid status value');
        }
        
        $results = $this->model->filterByStatus($status);
        
        return $this->respond([
            'status' => 'success',
            'data' => $results,
            'count' => count($results)
        ]);
    }
}
```

#### Routes
```php
// Modules/Admission/Config/Routes.php

// Web Routes (Staff Interface)
$routes->group('admission', ['filter' => 'permission:admission.manage'], function($routes) {
    $routes->get('/', 'Modules\Admission\Controllers\AdmissionController::index');
    $routes->get('view/(:num)', 'Modules\Admission\Controllers\AdmissionController::view/$1');
    $routes->get('download/(:num)/(:any)', 'Modules\Admission\Controllers\AdmissionController::downloadDocument/$1/$2');
    $routes->get('create', 'Modules\Admission\Controllers\AdmissionController::create');
    $routes->post('store', 'Modules\Admission\Controllers\AdmissionController::store');
    $routes->get('edit/(:num)', 'Modules\Admission\Controllers\AdmissionController::edit/$1');
    $routes->post('update/(:num)', 'Modules\Admission\Controllers\AdmissionController::update/$1');
    $routes->delete('delete/(:num)', 'Modules\Admission\Controllers\AdmissionController::delete/$1');
    $routes->get('search', 'Modules\Admission\Controllers\AdmissionController::search');
});

// API Routes (RESTful)
$routes->group('api', ['filter' => 'tokens'], function($routes) {
    $routes->resource('admissions', [
        'controller' => 'Modules\Admission\Controllers\Api\AdmissionApiController',
        'only' => ['index', 'show', 'create', 'update', 'delete']
    ]);
    
    $routes->get('admissions/search', 'Modules\Admission\Controllers\Api\AdmissionApiController::search');
    $routes->get('admissions/filter', 'Modules\Admission\Controllers\Api\AdmissionApiController::filter');
});
```

**API Response Format**:

Success Response:
```json
{
    "status": "success",
    "data": { ... },
    "message": "Operation completed successfully"
}
```

Error Response:
```json
{
    "status": "error",
    "message": "Error description",
    "errors": {
        "field": ["Validation error message"]
    }
}
```

**Authentication**:
- Uses Shield's token-based authentication
- Requires `Authorization: Bearer {token}` header
- Token obtained through Shield's login endpoint

**HTTP Status Codes**:
- 200: Success (GET, PUT)
- 201: Created (POST)
- 204: Deleted (DELETE)
- 400: Bad Request
- 401: Unauthorized
- 404: Not Found
- 422: Validation Error
- 500: Server Error

## 3. Core Configuration

### 3.1 Autoload Configuration

Modify `app/Config/Autoload.php` to enable module autoloading:

```php
public $psr4 = [
    APP_NAMESPACE => APPPATH,
    'Modules'      => APPPATH . 'Modules',
];
```

### 3.2 Routes Configuration

Modify `app/Config/Routes.php` to automatically load module routes using `scandir()`:

```php
<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// Auto-Load Modules' Routes
$modulesPath = APPPATH . 'Modules/';
if (is_dir($modulesPath)) {
    foreach (scandir($modulesPath) as $module) {
        if ($module === '.' || $module === '..') {
            continue;
        }
        $routesPath = $modulesPath . $module . '/Config/Routes.php';
        if (file_exists($routesPath)) {
            include $routesPath;
        }
    }
}
```

**Benefits**:
- Automatically discovers and loads routes from all modules
- No need to manually register each module
- New modules are automatically included when added to `app/Modules/`
- Maintains clean separation of route definitions per module

### 3.3 Shield Filters

Shield provides built-in filters for authentication and authorization:

**Available Filters**:
- `session`: Checks if user is logged in (session-based auth)
- `tokens`: Checks if request has valid API token
- `permission:{permission_name}`: Checks if user has specific permission
- `group:{group_name}`: Checks if user belongs to specific group

**Usage Examples**:
```php
// Require authentication
$routes->group('dashboard', ['filter' => 'session'], function($routes) {
    // Routes here require login
});

// Require specific permission
$routes->group('admission', ['filter' => 'permission:admission.manage'], function($routes) {
    // Routes here require admission.manage permission
});

// Require specific group
$routes->group('admin', ['filter' => 'group:admin'], function($routes) {
    // Routes here require admin group membership
});
```

**Permission Checking in Controllers**:
```php
// Check if user has permission
if (auth()->user()->can('admission.manage')) {
    // User has permission
}

// Check if user belongs to group
if (auth()->user()->inGroup('admin')) {
    // User is admin
}
```

## 4. Authentication & Authorization

### 4.1 Shield Integration

The application uses CodeIgniter Shield for authentication and authorization. Shield provides:

**Built-in Features**:
- Session-based and token-based authentication
- User groups and permissions system
- Password hashing and validation
- Remember me functionality
- Password reset capability
- Route filters for authentication and authorization

**Auth Groups**:
- `superadmin`: Full system access
- `admin`: Administrative access to all modules
- `staff`: Limited access based on assigned permissions
- `user`: Basic authenticated user access

**Permissions**:
- `admission.manage`: Full CRUD access to admission module
- `admission.view`: Read-only access to admission module
- `dashboard.access`: Access to dashboard

### 4.2 Permission Seeder

Create a seeder to populate initial permissions and groups:

```php
// app/Database/Seeds/PermissionSeeder.php
namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\Shield\Entities\User;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        // Get the authorization provider
        $authorize = service('authorization');
        
        // Create permissions
        $permissions = [
            'dashboard.access' => 'Access to dashboard',
            'admission.manage' => 'Manage admissions (CRUD)',
            'admission.view' => 'View admissions (read-only)',
        ];
        
        foreach ($permissions as $name => $description) {
            $authorize->createPermission($name, $description);
        }
        
        // Assign permissions to groups
        $authorize->addPermissionToGroup('admission.manage', 'admin');
        $authorize->addPermissionToGroup('admission.view', 'staff');
        $authorize->addPermissionToGroup('dashboard.access', 'admin');
        $authorize->addPermissionToGroup('dashboard.access', 'staff');
    }
}
```

## 5. Database Schema

### 5.1 Admissions Table

```sql
CREATE TABLE `admissions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `registration_number` varchar(20) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `nickname` varchar(50) DEFAULT NULL,
  `gender` enum('Male','Female') NOT NULL,
  `place_of_birth` varchar(100) NOT NULL,
  `date_of_birth` date NOT NULL,
  `religion` varchar(50) NOT NULL,
  `citizen_id` varchar(20) DEFAULT NULL,
  `phone` varchar(15) NOT NULL,
  `email` varchar(100) NOT NULL,
  `street_address` text NOT NULL,
  `district` varchar(100) NOT NULL,
  `regency` varchar(100) NOT NULL,
  `province` varchar(100) NOT NULL,
  `postal_code` varchar(10) DEFAULT NULL,
  `emergency_contact_name` varchar(100) NOT NULL,
  `emergency_contact_phone` varchar(15) NOT NULL,
  `emergency_contact_relation` varchar(50) NOT NULL,
  `father_name` varchar(100) NOT NULL,
  `mother_name` varchar(100) NOT NULL,
  `course` varchar(100) NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `application_date` date NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `documents` text COMMENT 'JSON array of document filenames',
  `notes` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `registration_number` (`registration_number`),
  UNIQUE KEY `email` (`email`),
  KEY `status` (`status`),
  KEY `deleted_at` (`deleted_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

## 6. View Layer Design

### 6.1 File Upload Storage

**Upload Directory Structure**:
```
writable/uploads/admissions/
├── photos/          # Student profile photos
└── documents/       # Supporting documents (transcripts, certificates, etc.)
```

**File Naming Convention**:
- Use `getRandomName()` to generate unique filenames
- Prevents filename conflicts and directory traversal attacks
- Original filename is not preserved (security measure)

**File Access**:
- Photos: Displayed in admission views (staff only)
- Documents: Download-only via authenticated route
- Direct file access is prevented (files stored in `writable/` directory)

**File Validation**:
- **Photos**: jpg, jpeg, png | Max 2MB | Required
- **Documents**: pdf, doc, docx | Max 5MB each | Max 3 files | Optional
- MIME type validation in addition to extension check
- File size validation before upload

**Storage in Database**:
- `photo` column: Single filename (VARCHAR)
- `documents` column: JSON array of filenames (TEXT)

### 6.2 Layout Structure

Each module has its own layout file with shared components:

**Frontend Layout**:
- Public header with navigation
- Main content area
- Public footer

**Dashboard/Admin Layout**:
- Authenticated header with user menu
- Sidebar with module navigation (filtered by permissions)
- Main content area
- Footer

### 6.2 View Components

**Reusable Components**:
- Alert messages (success, error, info)
- Form elements with validation display
- File upload inputs with preview
- Data tables with pagination
- Modal dialogs
- Breadcrumbs
- File download links

## 7. Security Considerations

### 7.1 CSRF Protection
- Enabled globally in `app/Config/Security.php`
- All forms include CSRF token: `<?= csrf_field() ?>`

### 7.2 Input Validation
- Server-side validation in models
- Client-side validation in views (optional)
- XSS filtering enabled

### 7.3 SQL Injection Prevention
- Use Query Builder exclusively
- Parameterized queries for all database operations
- No raw SQL queries with user input

### 7.4 File Upload Security
- Validate file types (extension and MIME type)
- Enforce file size limits
- Use random filenames to prevent overwrites
- Store files outside public directory (`writable/uploads/`)
- Validate file content (not just extension)
- Prevent directory traversal attacks
- Limit number of files per upload

### 7.5 Authentication
- Shield handles password hashing (Argon2id by default, bcrypt fallback)
- Session-based authentication with secure session handling
- Remember me functionality with secure tokens
- Password reset with time-limited tokens
- Built-in protection against timing attacks

## 8. Error Handling

### 8.1 Exception Handling
- Custom 404 pages for each module
- Graceful error messages for users
- Detailed error logging for developers

### 8.2 Validation Errors
- Display validation errors above forms
- Preserve user input on validation failure
- Field-specific error messages

## 9. Testing Strategy

### 9.1 Unit Tests
- Test model methods (CRUD operations)
- Test helper functions
- Test validation rules

### 9.2 Integration Tests
- Test controller methods
- Test authentication flows
- Test permission checks

### 9.3 Property-Based Tests
Property-based testing will be used to verify correctness properties across the system.

**Testing Framework**: PHPUnit with additional property-based testing library (if available for PHP)

**Correctness Properties**:

#### Property 1: Admission Data Integrity
**Validates: Requirements 2.4**

For any admission record created or updated, the data stored in the database must match the validated input data.

```php
// Property: ∀ admission data d, if save(d) succeeds, then find(id) returns d
function testAdmissionDataIntegrity($admissionData)
{
    // Given valid admission data
    $model = new AdmissionModel();
    
    // When we save it
    $id = $model->insert($admissionData);
    
    // Then retrieving it returns the same data
    $retrieved = $model->find($id);
    
    assert($retrieved['student_name'] === $admissionData['student_name']);
    assert($retrieved['email'] === $admissionData['email']);
    assert($retrieved['phone'] === $admissionData['phone']);
    assert($retrieved['course'] === $admissionData['course']);
}
```

#### Property 2: Permission-Based Access Control
**Validates: Requirements 2.3**

For any user without a specific permission, attempting to access a protected resource must result in denial.

```php
// Property: ∀ user u, permission p, if !u.hasPermission(p), then access(resource_requiring_p) fails
function testPermissionDenial($user, $permission, $resource)
{
    // Given a user without permission
    assert(!$user->can($permission));
    
    // When they attempt to access a protected resource
    $response = $this->actingAs($user)->get($resource);
    
    // Then access is denied
    assert($response->getStatusCode() === 302); // Redirect
    assert(strpos($response->getHeaderLine('Location'), 'dashboard') !== false);
}
```

#### Property 3: Soft Delete Preservation
**Validates: Requirements 3.3**

For any admission record that is soft-deleted, the record must remain in the database with a deleted_at timestamp.

```php
// Property: ∀ admission a, if delete(a.id), then a exists in DB with deleted_at != null
function testSoftDeletePreservation($admissionId)
{
    // Given an existing admission
    $model = new AdmissionModel();
    $admission = $model->find($admissionId);
    assert($admission !== null);
    
    // When we soft delete it
    $model->delete($admissionId);
    
    // Then it's not in normal queries
    assert($model->find($admissionId) === null);
    
    // But exists in database with deleted_at
    $deleted = $model->withDeleted()->find($admissionId);
    assert($deleted !== null);
    assert($deleted['deleted_at'] !== null);
}
```

#### Property 4: Email Uniqueness Constraint
**Validates: Requirements 2.4**

For any two admission records, they cannot have the same email address.

```php
// Property: ∀ admissions a1, a2, if a1.email === a2.email, then save(a2) fails
function testEmailUniqueness($email)
{
    $model = new AdmissionModel();
    
    // Given an admission with an email
    $data1 = [
        'student_name' => 'Student One',
        'email' => $email,
        'phone' => '1234567890',
        'course' => 'Computer Science',
        'status' => 'pending',
        'application_date' => date('Y-m-d')
    ];
    $model->insert($data1);
    
    // When we try to create another with same email
    $data2 = $data1;
    $data2['student_name'] = 'Student Two';
    $result = $model->insert($data2);
    
    // Then it fails
    assert($result === false);
    assert(!empty($model->errors()));
}
```

#### Property 5: Module Route Isolation
**Validates: Requirements 2.1**

For any module, its routes must be properly namespaced and not conflict with other modules.

```php
// Property: ∀ modules m1, m2, routes(m1) ∩ routes(m2) = ∅
function testModuleRouteIsolation()
{
    $routes = service('routes');
    $allRoutes = $routes->getRoutes();
    
    $moduleRoutes = [
        'frontend' => [],
        'dashboard' => [],
        'admission' => []
    ];
    
    // Collect routes by module
    foreach ($allRoutes as $route => $handler) {
        if (strpos($handler, 'Frontend') !== false) {
            $moduleRoutes['frontend'][] = $route;
        } elseif (strpos($handler, 'Dashboard') !== false) {
            $moduleRoutes['dashboard'][] = $route;
        } elseif (strpos($handler, 'Admission') !== false) {
            $moduleRoutes['admission'][] = $route;
        }
    }
    
    // Assert no overlaps
    $frontend = $moduleRoutes['frontend'];
    $dashboard = $moduleRoutes['dashboard'];
    $admission = $moduleRoutes['admission'];
    
    assert(empty(array_intersect($frontend, $dashboard)));
    assert(empty(array_intersect($frontend, $admission)));
    assert(empty(array_intersect($dashboard, $admission)));
}
```

## 10. Performance Optimization

### 10.1 Database Optimization
- Proper indexing on frequently queried columns
- Use of pagination for large datasets
- Eager loading for related data

### 10.2 Caching Strategy
- Cache static content (frontend pages)
- Cache user permissions
- Cache module configurations

### 10.3 Query Optimization
- Use select() to fetch only needed columns
- Avoid N+1 query problems
- Use database query caching where appropriate

## 11. Future Enhancements

### 11.1 Planned Features
- Advanced reporting and analytics
- Email notifications for admission status changes
- Image cropping/resizing functionality
- Bulk file operations
- Multi-language support
- Audit logging for all CRUD operations
- Webhook notifications for status changes
- API rate limiting and throttling
- API versioning (v2, v3)
- GraphQL API endpoint

### 11.2 Additional Modules
- Student Management
- Course Management
- Fee Management
- Attendance Tracking
- Examination Module
- Library Management

## 12. Development Guidelines

### 12.1 Module Generator Command

A custom Spark command `make:module` is available to quickly scaffold new modules with proper structure.

**Usage**:
```bash
php spark make:module ModuleName
```

**Features**:
- Automatically creates module directory structure
- Generates Controller, Routes, Views, and Layout
- Optional CRUD methods generation
- Optional Model generation
- Automatically registers module namespace in `app/Config/Autoload.php`
- Creates necessary folders: Config, Controllers, Models, Views, Database/Migrations, Database/Seeds

**Interactive Prompts**:
1. **Generate CRUD?** (yes/no) - Adds create, store, show, edit, update, delete methods
2. **Add Model?** (yes/no) - Creates a model file with basic structure
3. **Model name** - If adding model, specify the model class name

**Example**:
```bash
php spark make:module Student
# Generate CRUD? (yes/no) [no]: yes
# Add Model? (yes/no) [no]: yes
# Model name (ex: UserModel): StudentModel
```

**Generated Structure**:
```
app/Modules/Student/
├── Config/
│   └── Routes.php
├── Controllers/
│   └── StudentController.php
├── Models/
│   └── StudentModel.php (if requested)
├── Views/
│   ├── layouts/
│   │   └── student_layout.php
│   ├── index.php
│   ├── create.php (if CRUD)
│   ├── edit.php (if CRUD)
│   └── detail.php (if CRUD)
└── Database/
    ├── Migrations/
    └── Seeds/
```

**Auto-Registration**:
The command automatically adds the module namespace to `app/Config/Autoload.php`:
```php
public $psr4 = [
    APP_NAMESPACE => APPPATH,
    'Modules' => APPPATH . 'Modules',
    'Modules\Student' => APPPATH . 'Modules/Student',
];
```

### 12.2 Coding Standards
- Follow PSR-12 coding standards
- Use meaningful variable and function names
- Add PHPDoc comments for all classes and methods
- Keep methods small and focused (single responsibility)

### 12.3 Git Workflow
- Feature branches for new modules
- Pull requests for code review
- Semantic commit messages
- Tag releases with version numbers

### 12.4 Documentation
- README.md for each module
- API documentation for public methods
- Database schema documentation
- Deployment instructions

## 13. Deployment Considerations

### 13.1 Environment Configuration
- Separate .env files for development, staging, production
- Database credentials management
- Debug mode disabled in production
- Error logging configuration

### 13.2 Server Requirements
- PHP 8.1 or higher
- MySQL 5.7+ or MariaDB 10.3+
- Apache/Nginx with mod_rewrite
- Composer for dependency management

### 13.3 Migration Strategy
- Run migrations in order
- Seed initial data (permissions, admin user)
- Test all modules after deployment
- Backup database before migrations
