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
            'category' => $category
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
            'program' => $program
        ]);
    }
    
    /**
     * Show create form
     */
    public function create()
    {
        return view('Modules\Program\Views\create', [
            'title' => 'Create Program'
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
            'category' => $this->request->getPost('category'),
            'sub_category' => $this->request->getPost('sub_category'),
            'status' => $this->request->getPost('status') ?: 'active'
        ];
        
        // Handle thumbnail upload
        $thumbnail = $this->request->getFile('thumbnail');
        if ($thumbnail && $thumbnail->isValid() && !$thumbnail->hasMoved()) {
            $newName = $thumbnail->getRandomName();
            $thumbnail->move(WRITEPATH . 'uploads/programs/thumbs', $newName);
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
            'program' => $program
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
            'category' => $this->request->getPost('category'),
            'sub_category' => $this->request->getPost('sub_category'),
            'status' => $this->request->getPost('status') ?: 'active'
        ];
        
        // Handle thumbnail upload
        $thumbnail = $this->request->getFile('thumbnail');
        if ($thumbnail && $thumbnail->isValid() && !$thumbnail->hasMoved()) {
            // Delete old thumbnail if exists
            if (!empty($program['thumbnail'])) {
                $oldFile = WRITEPATH . 'uploads/programs/thumbs/' . $program['thumbnail'];
                if (file_exists($oldFile)) {
                    unlink($oldFile);
                }
            }
            
            $newName = $thumbnail->getRandomName();
            $thumbnail->move(WRITEPATH . 'uploads/programs/thumbs', $newName);
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
}
