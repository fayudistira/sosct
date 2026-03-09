<?php

namespace Modules\Inventory\Controllers;

use App\Controllers\BaseController;
use Modules\Inventory\Models\MovementModel;
use Modules\Inventory\Models\ItemModel;
use Modules\Inventory\Models\LocationModel;

class MovementController extends BaseController
{
    protected $movementModel;
    protected $itemModel;
    protected $locationModel;

    public function __construct()
    {
        $this->movementModel = new MovementModel();
        $this->itemModel = new ItemModel();
        $this->locationModel = new LocationModel();
    }

    /**
     * List all movements
     */
    public function index()
    {
        $page = (int) ($this->request->getGet('page') ?? 1);
        $perPage = 20;
        $itemId = $this->request->getGet('item');
        $type = $this->request->getGet('type');
        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');

        $builder = $this->movementModel->builder();
        
        if ($itemId) {
            $builder->where('item_id', $itemId);
        }
        
        if ($type) {
            $builder->where('movement_type', $type);
        }
        
        if ($startDate) {
            $builder->where('movement_date >=', $startDate);
        }
        
        if ($endDate) {
            $builder->where('movement_date <=', $endDate . ' 23:59:59');
        }

        $movements = $this->movementModel->orderBy('movement_date', 'DESC')
                                         ->paginate($perPage, 'default', $page);

        // Get item names
        $itemIds = array_unique(array_column($movements, 'item_id'));
        $items = [];
        if ($itemIds) {
            $itemModel = new ItemModel();
            foreach ($itemIds as $id) {
                $item = $itemModel->find($id);
                if ($item) {
                    $items[$id] = $item['name'];
                }
            }
        }

        // Get location names
        $locationIds = [];
        foreach ($movements as $m) {
            if (!empty($m['location_id'])) $locationIds[] = $m['location_id'];
            if (!empty($m['source_location_id'])) $locationIds[] = $m['source_location_id'];
            if (!empty($m['to_location_id'])) $locationIds[] = $m['to_location_id'];
        }
        $locationIds = array_unique($locationIds);
        $locations = [];
        if ($locationIds) {
            $locationModel = new LocationModel();
            foreach ($locationIds as $id) {
                $loc = $locationModel->find($id);
                if ($loc) {
                    $locations[$id] = $loc['name'];
                }
            }
        }

        $data = [
            'movements' => $movements,
            'pager' => $this->movementModel->pager,
            'items' => $items,
            'locations' => $locations,
            'types' => $this->movementModel->getTypes(),
            'allItems' => $this->itemModel->findAll(),
            'selectedItem' => $itemId,
            'selectedType' => $type,
            'startDate' => $startDate,
            'endDate' => $endDate
        ];

        return view('Modules\Inventory\Views\movements\index', $data);
    }

    /**
     * Show create form
     */
    public function create()
    {
        $data = [
            'items' => $this->itemModel->where('status', 'active')->findAll(),
            'locations' => $this->locationModel->findAll(),
            'types' => $this->movementModel->getTypes()
        ];

        return view('Modules\Inventory\Views\movements\create', $data);
    }

    /**
     * Store new movement
     */
    public function store()
    {
        $data = $this->request->getPost();
        
        // Generate ID if not provided
        if (empty($data['id'])) {
            $data['id'] = uuid_v4();
        }

        // Handle transfer between locations
        if ($data['movement_type'] === 'transfer') {
            return $this->handleTransfer($data);
        }

        // Get item current stock
        $item = $this->itemModel->find($data['item_id']);
        if (!$item) {
            return redirect()->back()->withInput()->with('error', 'Item not found');
        }

        $data['quantity_before'] = $item['current_stock'];
        
        // Determine if this is an "in" or "out" movement
        $inTypes = ['purchase', 'return', 'initial'];
        $outTypes = ['sale', 'distributed', 'damage', 'expired'];
        
        if (in_array($data['movement_type'], $inTypes)) {
            // Add stock for in-types
            $data['quantity_after'] = $item['current_stock'] + $data['quantity'];
        } elseif (in_array($data['movement_type'], $outTypes)) {
            // Subtract stock for out-types
            $data['quantity_after'] = $item['current_stock'] - $data['quantity'];
            // Ensure stock doesn't go negative
            if ($data['quantity_after'] < 0) {
                $data['quantity_after'] = 0;
            }
        } else {
            // For adjustment and transfer, use the value as-is (can be positive or negative)
            $data['quantity_after'] = $item['current_stock'] + $data['quantity'];
        }
        
        // Store the signed quantity for reference
        if (in_array($data['movement_type'], $outTypes)) {
            $data['quantity'] = -abs($data['quantity']); // Store as negative for out types
        }
        
        $data['movement_date'] = date('Y-m-d H:i:s');

        // Get current user
        if (auth()->loggedIn()) {
            $user = auth()->user();
            $data['performed_by'] = $user->username ?? $user->email ?? 'Unknown';
        }

        if ($this->movementModel->insert($data)) {
            // Update item stock
            $this->itemModel->update($data['item_id'], [
                'current_stock' => $data['quantity_after']
            ]);

            return redirect()->to('/inventory/movements')->with('success', 'Movement recorded successfully');
        }

        return redirect()->back()->withInput()->with('error', 'Failed to record movement: ' . implode(', ', $this->movementModel->errors()));
    }

    /**
     * Handle transfer between locations
     */
    private function handleTransfer($data)
    {
        $item = $this->itemModel->find($data['item_id']);
        if (!$item) {
            return redirect()->back()->withInput()->with('error', 'Item not found');
        }

        // Validate source and destination locations
        $sourceLocation = $data['source_location_id'] ?? '';
        $destLocation = $data['to_location_id'] ?? '';
        $quantity = (int) ($data['quantity'] ?? 0);

        if (empty($sourceLocation)) {
            return redirect()->back()->withInput()->with('error', 'Source location is required for transfer');
        }

        if (empty($destLocation)) {
            return redirect()->back()->withInput()->with('error', 'Destination location is required for transfer');
        }

        if ($sourceLocation === $destLocation) {
            return redirect()->back()->withInput()->with('error', 'Source and destination locations must be different');
        }

        if ($quantity <= 0) {
            return redirect()->back()->withInput()->with('error', 'Transfer quantity must be greater than 0');
        }

        // Get current user
        $performedBy = 'Unknown';
        if (auth()->loggedIn()) {
            $user = auth()->user();
            $performedBy = $user->username ?? $user->email ?? 'Unknown';
        }

        $movementDate = date('Y-m-d H:i:s');
        $currentStock = $item['current_stock'];

        // Check if we have enough stock to transfer
        if ($quantity > $currentStock) {
            return redirect()->back()->withInput()->with('error', 'Insufficient stock. Available: ' . $currentStock);
        }

        // Create transfer out movement (from source)
        $transferOutId = uuid_v4();
        $transferOutData = [
            'id' => $transferOutId,
            'item_id' => $data['item_id'],
            'movement_type' => 'transfer',
            'quantity' => -$quantity,
            'quantity_before' => $currentStock,
            'quantity_after' => $currentStock - $quantity,
            'location_id' => $sourceLocation,
            'to_location_id' => $destLocation,
            'source_location_id' => $sourceLocation,
            'reference_number' => $data['reference_number'] ?? '',
            'description' => 'Transfer OUT: ' . ($data['description'] ?? ''),
            'performed_by' => $performedBy,
            'movement_date' => $movementDate
        ];

        // Create transfer in movement (to destination)
        $transferInId = uuid_v4();
        $transferInData = [
            'id' => $transferInId,
            'item_id' => $data['item_id'],
            'movement_type' => 'transfer',
            'quantity' => $quantity,
            'quantity_before' => 0, // We don't track per-location stock yet
            'quantity_after' => $quantity,
            'location_id' => $destLocation,
            'to_location_id' => $destLocation,
            'source_location_id' => $sourceLocation,
            'reference_number' => $data['reference_number'] ?? '',
            'description' => 'Transfer IN: ' . ($data['description'] ?? ''),
            'performed_by' => $performedBy,
            'movement_date' => $movementDate
        ];

        // Insert both movements
        if ($this->movementModel->insert($transferOutData) && $this->movementModel->insert($transferInData)) {
            // Update total item stock (remains the same for transfer)
            return redirect()->to('/inventory/movements')->with('success', 'Transfer recorded successfully');
        }

        return redirect()->back()->withInput()->with('error', 'Failed to record transfer: ' . implode(', ', $this->movementModel->errors()));
    }

    /**
     * Get movements for specific item
     */
    public function itemMovements($itemId)
    {
        $item = $this->itemModel->find($itemId);
        
        if (!$item) {
            return redirect()->to('/inventory/items')->with('error', 'Item not found');
        }

        $movements = $this->movementModel->where('item_id', $itemId)
                                         ->orderBy('movement_date', 'DESC')
                                         ->findAll();

        $data = [
            'item' => $item,
            'movements' => $movements,
            'types' => $this->movementModel->getTypes()
        ];

        return view('Modules\Inventory\Views\movements\item', $data);
    }

    /**
     * Movement report
     */
    public function report()
    {
        $startDate = $this->request->getGet('start_date') ?? date('Y-m-01');
        $endDate = $this->request->getGet('end_date') ?? date('Y-m-t');
        $type = $this->request->getGet('type');

        $builder = $this->movementModel->builder();
        
        $builder->where('movement_date >=', $startDate);
        $builder->where('movement_date <=', $endDate . ' 23:59:59');
        
        if ($type) {
            $builder->where('movement_type', $type);
        }

        $movements = $this->movementModel->orderBy('movement_date', 'DESC')->findAll();

        // Get item names
        $itemIds = array_unique(array_column($movements, 'item_id'));
        $items = [];
        foreach ($itemIds as $id) {
            $item = $this->itemModel->find($id);
            if ($item) {
                $items[$id] = $item['name'];
            }
        }

        // Calculate summary
        $summary = [];
        foreach ($this->movementModel->getTypes() as $typeKey => $typeName) {
            $typeMovements = array_filter($movements, fn($m) => $m['movement_type'] === $typeKey);
            $totalQty = array_sum(array_column($typeMovements, 'quantity'));
            $summary[$typeKey] = [
                'name' => $typeName,
                'count' => count($typeMovements),
                'total_quantity' => $totalQty
            ];
        }

        $data = [
            'movements' => $movements,
            'items' => $items,
            'summary' => $summary,
            'types' => $this->movementModel->getTypes(),
            'startDate' => $startDate,
            'endDate' => $endDate,
            'selectedType' => $type
        ];

        return view('Modules\Inventory\Views\movements\report', $data);
    }
}
