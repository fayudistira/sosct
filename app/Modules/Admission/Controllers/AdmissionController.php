<?php

namespace Modules\Admission\Controllers;

use App\Controllers\BaseController;
use Modules\Admission\Models\AdmissionModel;

class AdmissionController extends BaseController
{
    protected $admissionModel;
    
    public function __construct()
    {
        $this->admissionModel = new AdmissionModel();
    }
    
    /**
     * List all admissions with pagination and stats
     * 
     * @return string
     */
    public function index(): string
    {
        $data['admissions'] = $this->admissionModel->getWithPagination(10);
        $data['pager'] = $this->admissionModel->pager;
        $data['statusCounts'] = $this->admissionModel->getStatusCounts();
        $data['menuItems'] = $this->loadModuleMenus();
        $data['user'] = auth()->user();
        
        return view('Modules\Admission\Views\index', $data);
    }
    
    /**
     * View admission details with file links
     * 
     * @param int $id Admission ID
     * @return string
     */
    public function view($id): string
    {
        $data['admission'] = $this->admissionModel->find($id);
        
        if (!$data['admission']) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Admission not found');
        }
        
        // Decode documents JSON
        if (!empty($data['admission']['documents'])) {
            $data['admission']['documents'] = json_decode($data['admission']['documents'], true);
        }
        
        $data['menuItems'] = $this->loadModuleMenus();
        $data['user'] = auth()->user();
        
        return view('Modules\Admission\Views\view', $data);
    }
    
    /**
     * Show create form for manual entry
     * 
     * @return string
     */
    public function create(): string
    {
        return view('Modules\Admission\Views\create', [
            'title' => 'Create Admission',
            'menuItems' => $this->loadModuleMenus(),
            'user' => auth()->user()
        ]);
    }

    
    /**
     * Store new admission with file uploads
     * 
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function store()
    {
        $data = $this->request->getPost();
        
        // Generate registration number if not provided
        if (empty($data['registration_number'])) {
            $data['registration_number'] = $this->admissionModel->generateRegistrationNumber();
        }
        
        // Set default values
        $data['application_date'] = $data['application_date'] ?? date('Y-m-d');
        $data['status'] = $data['status'] ?? 'pending';
        
        // Handle photo upload
        $photo = $this->request->getFile('photo');
        if ($photo && $photo->isValid() && !$photo->hasMoved()) {
            $photoName = $photo->getRandomName();
            $photo->move(WRITEPATH . 'uploads/admissions/photos', $photoName);
            $data['photo'] = $photoName;
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
        
        if (!empty($documentNames)) {
            $data['documents'] = json_encode($documentNames);
        }
        
        if (!$this->admissionModel->save($data)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->admissionModel->errors());
        }
        
        return redirect()->to('/admission')
            ->with('success', 'Admission created successfully.');
    }
    
    /**
     * Show edit form
     * 
     * @param int $id Admission ID
     * @return string
     */
    public function edit($id): string
    {
        $data['admission'] = $this->admissionModel->find($id);
        
        if (!$data['admission']) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Admission not found');
        }
        
        $data['menuItems'] = $this->loadModuleMenus();
        $data['user'] = auth()->user();
        
        return view('Modules\Admission\Views\edit', $data);
    }
    
    /**
     * Update admission with file handling
     * 
     * @param int $id Admission ID
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function update($id)
    {
        $data = $this->request->getPost();
        
        // Get existing admission
        $existing = $this->admissionModel->find($id);
        if (!$existing) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Admission not found');
        }
        
        // Handle photo upload (if new photo provided)
        $photo = $this->request->getFile('photo');
        if ($photo && $photo->isValid() && !$photo->hasMoved()) {
            $photoName = $photo->getRandomName();
            $photo->move(WRITEPATH . 'uploads/admissions/photos', $photoName);
            $data['photo'] = $photoName;
            
            // Delete old photo if exists
            if (!empty($existing['photo'])) {
                $oldPhotoPath = WRITEPATH . 'uploads/admissions/photos/' . $existing['photo'];
                if (file_exists($oldPhotoPath)) {
                    unlink($oldPhotoPath);
                }
            }
        }
        
        // Handle documents upload (if new documents provided)
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
            
            if (!empty($documentNames)) {
                $data['documents'] = json_encode($documentNames);
            }
        }
        
        if (!$this->admissionModel->update($id, $data)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->admissionModel->errors());
        }
        
        return redirect()->to('/admission')
            ->with('success', 'Admission updated successfully.');
    }
    
    /**
     * Soft delete admission
     * 
     * @param int $id Admission ID
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function delete($id)
    {
        $this->admissionModel->delete($id);
        
        return redirect()->to('/admission')
            ->with('success', 'Admission deleted successfully.');
    }
    
    /**
     * Search admissions
     * 
     * @return string
     */
    public function search()
    {
        $keyword = $this->request->getGet('keyword');
        $data['admissions'] = $this->admissionModel->searchAdmissions($keyword);
        $data['statusCounts'] = $this->admissionModel->getStatusCounts();
        $data['keyword'] = $keyword;
        $data['menuItems'] = $this->loadModuleMenus();
        $data['user'] = auth()->user();
        
        return view('Modules\Admission\Views\index', $data);
    }
    
    /**
     * Download document file
     * 
     * @param int $id Admission ID
     * @param string $filename Document filename
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function downloadDocument($id, $filename)
    {
        $admission = $this->admissionModel->find($id);
        
        if (!$admission) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Admission not found');
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
