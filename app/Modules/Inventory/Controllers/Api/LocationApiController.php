<?php

namespace Modules\Inventory\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use Modules\Inventory\Models\LocationModel;

class LocationApiController extends ResourceController
{
    protected $locationModel;

    public function __construct()
    {
        $this->locationModel = new LocationModel();
    }

    /**
     * Get all locations
     * GET /api/inventory/locations
     */
    public function index()
    {
        $locations = $this->locationModel->orderBy('name', 'ASC')->findAll();

        return $this->respond([
            'success' => true,
            'data' => $locations
        ]);
    }

    /**
     * Get single location
     * GET /api/inventory/locations/{id}
     */
    public function show($id = null)
    {
        $location = $this->locationModel->find($id);

        if (!$location) {
            return $this->respond([
                'success' => false,
                'message' => 'Location not found'
            ], 404);
        }

        return $this->respond([
            'success' => true,
            'data' => $location
        ]);
    }

    /**
     * Create new location
     * POST /api/inventory/locations
     */
    public function create()
    {
        $data = $this->request->getPost();

        // Generate ID if not provided
        if (empty($data['id'])) {
            $data['id'] = uuid_v4();
        }

        // Handle is_default
        if (isset($data['is_default']) && $data['is_default']) {
            $this->locationModel->where('is_default', true)->set(['is_default' => false])->update();
        }

        if ($this->locationModel->insert($data)) {
            return $this->respond([
                'success' => true,
                'message' => 'Location created successfully',
                'data' => $this->locationModel->find($data['id'])
            ], 201);
        }

        return $this->respond([
            'success' => false,
            'message' => 'Failed to create location',
            'errors' => $this->locationModel->errors()
        ], 400);
    }

    /**
     * Update location
     * PUT /api/inventory/locations/{id}
     */
    public function update($id = null)
    {
        $location = $this->locationModel->find($id);

        if (!$location) {
            return $this->respond([
                'success' => false,
                'message' => 'Location not found'
            ], 404);
        }

        $data = $this->request->getRawInput();

        // Handle is_default
        if (isset($data['is_default']) && $data['is_default']) {
            $this->locationModel->where('is_default', true)->where('id !=', $id)->set(['is_default' => false])->update();
        }

        if ($this->locationModel->update($id, $data)) {
            return $this->respond([
                'success' => true,
                'message' => 'Location updated successfully',
                'data' => $this->locationModel->find($id)
            ]);
        }

        return $this->respond([
            'success' => false,
            'message' => 'Failed to update location',
            'errors' => $this->locationModel->errors()
        ], 400);
    }

    /**
     * Delete location
     * DELETE /api/inventory/locations/{id}
     */
    public function delete($id = null)
    {
        $location = $this->locationModel->find($id);

        if (!$location) {
            return $this->respond([
                'success' => false,
                'message' => 'Location not found'
            ], 404);
        }

        // Check for items
        $itemModel = new \Modules\Inventory\Models\ItemModel();
        $items = $itemModel->where('location_id', $id)->countAllResults();
        if ($items > 0) {
            return $this->respond([
                'success' => false,
                'message' => 'Cannot delete location with associated items'
            ], 400);
        }

        if ($this->locationModel->delete($id)) {
            return $this->respond([
                'success' => true,
                'message' => 'Location deleted successfully'
            ]);
        }

        return $this->respond([
            'success' => false,
            'message' => 'Failed to delete location'
        ], 400);
    }
}
