<?php

namespace Modules\Classroom\Controllers;

use Modules\Classroom\Models\ClassroomModel;
use Modules\Admission\Models\AdmissionModel;
use App\Controllers\BaseController;

class ClassroomController extends BaseController
{
    protected $classroomModel;
    protected $admissionModel;

    public function __construct()
    {
        $this->classroomModel = new ClassroomModel();
        $this->admissionModel = new AdmissionModel();
    }

    public function index()
    {
        return view('Modules\Classroom\Views\index', [
            'title'      => 'Classrooms',
            'classrooms' => $this->classroomModel->findAll(),
            'menu'       => [
                'index'  => base_url('classroom'),
                'create' => base_url('classroom/create'),
            ]
        ]);
    }

    public function create()
    {
        $staffModel = new \Modules\Employee\Models\StaffModel();
        $instructors = $staffModel->getInstructors();

        return view('Modules\Classroom\Views\form', [
            'title'      => 'Create Classroom',
            'admissions' => $this->admissionModel->getAllWithDetails(),
            'instructors' => $instructors,
            'action'     => base_url('classroom/store'),
            'method'     => 'post',
            'menu'       => ['index' => base_url('classroom')]
        ]);
    }

    public function store()
    {
        $data = $this->request->getPost();

        // Handle Schedule JSON
        $schedule = [];
        if (isset($data['schedule_subject'])) {
            foreach ($data['schedule_subject'] as $i => $subject) {
                if (!empty($subject)) {
                    $schedule[$subject] = [
                        'instructor' => $data['schedule_instructor'][$i] ?? '',
                        'time'       => $data['schedule_time'][$i] ?? ''
                    ];
                }
            }
        }
        $data['schedule'] = json_encode($schedule);

        // Handle Members JSON
        if (isset($data['members'])) {
            $data['members'] = json_encode($data['members']);
        } else {
            $data['members'] = json_encode([]);
        }

        if (!$this->classroomModel->save($data)) {
            return redirect()->back()->withInput()->with('errors', $this->classroomModel->errors());
        }

        return redirect()->to(base_url('classroom'))->with('success', 'Classroom created successfully.');
    }

    public function show(int $id)
    {
        $classroom = $this->classroomModel->find($id);
        if (!$classroom) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Decode JSON data
        $classroom['schedule'] = json_decode($classroom['schedule'] ?? '[]', true);
        $classroom['members'] = json_decode($classroom['members'] ?? '[]', true);

        // Fetch member details if needed
        $memberDetails = [];
        if (!empty($classroom['members'])) {
            $memberDetails = $this->admissionModel
                ->whereIn('registration_number', $classroom['members'])
                ->join('profiles', 'profiles.id = admissions.profile_id')
                ->select('admissions.registration_number, profiles.full_name')
                ->findAll();
        }

        return view('Modules\Classroom\Views\view', [
            'title'     => 'Classroom Details',
            'classroom' => $classroom,
            'members'   => $memberDetails,
            'menu'      => [
                'index' => base_url('classroom'),
                'edit'  => base_url('classroom/' . $id . '/edit'),
            ]
        ]);
    }

    public function edit(int $id)
    {
        $classroom = $this->classroomModel->find($id);
        if (!$classroom) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Decode JSON data for the form
        $classroom['schedule'] = json_decode($classroom['schedule'] ?? '[]', true);
        $classroom['members'] = json_decode($classroom['members'] ?? '[]', true);

        $staffModel = new \Modules\Employee\Models\StaffModel();
        $instructors = $staffModel->getInstructors();

        return view('Modules\Classroom\Views\form', [
            'title'      => 'Edit Classroom',
            'classroom'  => $classroom,
            'admissions' => $this->admissionModel->getAllWithDetails(),
            'instructors' => $instructors,
            'action'     => base_url('classroom/update/' . $id),
            'method'     => 'post',
            'menu'       => [
                'index'  => base_url('classroom'),
                'detail' => base_url('classroom/' . $id),
            ]
        ]);
    }

    public function update(int $id)
    {
        $data = $this->request->getPost();
        $data['id'] = $id;

        // Handle Schedule JSON
        $schedule = [];
        if (isset($data['schedule_subject'])) {
            foreach ($data['schedule_subject'] as $i => $subject) {
                if (!empty($subject)) {
                    $schedule[$subject] = [
                        'instructor' => $data['schedule_instructor'][$i] ?? '',
                        'time'       => $data['schedule_time'][$i] ?? ''
                    ];
                }
            }
        }
        $data['schedule'] = json_encode($schedule);

        // Handle Members JSON
        if (isset($data['members'])) {
            $data['members'] = json_encode($data['members']);
        } else {
            $data['members'] = json_encode([]); // Clear members if none selected
        }

        if (!$this->classroomModel->save($data)) {
            return redirect()->back()->withInput()->with('errors', $this->classroomModel->errors());
        }

        return redirect()->to(base_url('classroom'))->with('success', 'Classroom updated successfully.');
    }

    public function delete(int $id)
    {
        if ($this->classroomModel->delete($id)) {
            return redirect()->to(base_url('classroom'))->with('success', 'Classroom deleted successfully.');
        }

        return redirect()->back()->with('error', 'Failed to delete classroom.');
    }
}
