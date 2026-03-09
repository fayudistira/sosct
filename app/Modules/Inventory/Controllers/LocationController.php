<?php

namespace Modules\Inventory\Controllers;

use App\Controllers\BaseController;
use Modules\Inventory\Models\LocationModel;

class LocationController extends BaseController
{
    protected $locationModel;

    public function __construct()
    {
        $this->locationModel = new LocationModel();
    }

    /**
     * List all locations
     */
    public function index()
    {
        $locations = $this->locationModel->orderBy('name', 'ASC')->findAll();
        
        $data = [
            'locations' => $locations
        ];

        return view('Modules\Inventory\Views\locations\index', $data);
    }

    /**
     * Show create form
     */
    public function create()
    {
        $data = [
            'types' => ['storage' => 'Storage', 'warehouse' => 'Warehouse', 'room' => 'Room']
        ];

        return view('Modules\Inventory\Views\locations\create', $data);
    }

    /**
     * Store new location
     */
    public function store()
    {
        $data = $this->request->getPost();
        
        // Generate ID if not provided
        if (empty($data['id'])) {
            $data['id'] = uuid_v4();
        }

        // Handle is_default
        if (isset($data['is_default'])) {
            $data['is_default'] = 1;
            // Unset other defaults
            $this->locationModel->where('is_default', true)->set(['is_default' => false])->update();
        } else {
            $data['is_default'] = 0;
        }

        if ($this->locationModel->insert($data)) {
            return redirect()->to('/inventory/locations')->with('success', 'Location created successfully');
        }

        return redirect()->back()->withInput()->with('error', 'Failed to create location: ' . implode(', ', $this->locationModel->errors()));
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $location = $this->locationModel->find($id);
        
        if (!$location) {
            return redirect()->to('/inventory/locations')->with('error', 'Location not found');
        }

        $data = [
            'location' => $location,
            'types' => $this->locationModel->getTypes()
        ];

        return view('Modules\Inventory\Views\locations\edit', $data);
    }

    /**
     * Update location
     */
    public function update($id)
    {
        $data = $this->request->getPost();

        // Handle is_default
        if (isset($data['is_default'])) {
            $data['is_default'] = true;
            // Unset other defaults
            $this->locationModel->where('is_default', true)->where('id !=', $id)->set(['is_default' => false])->update();
        } else {
            $data['is_default'] = false;
        }

        if ($this->locationModel->update($id, $data)) {
            return redirect()->to('/inventory/locations')->with('success', 'Location updated successfully');
        }

        return redirect()->back()->withInput()->with('error', 'Failed to update location: ' . implode(', ', $this->locationModel->errors()));
    }

    /**
     * Delete location
     */
    public function delete($id)
    {
        // Check if location has items
        $itemModel = new \Modules\Inventory\Models\ItemModel();
        $items = $itemModel->where('location_id', $id)->countAllResults();
        
        if ($items > 0) {
            return redirect()->back()->with('error', 'Cannot delete location with associated items');
        }

        if ($this->locationModel->delete($id)) {
            return redirect()->to('/inventory/locations')->with('success', 'Location deleted successfully');
        }

        return redirect()->back()->with('error', 'Failed to delete location');
    }
}
