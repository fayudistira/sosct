<?php

namespace Modules\Inventory\Controllers;

use App\Controllers\BaseController;
use Modules\Inventory\Models\ItemModel;
use Modules\Inventory\Models\CategoryModel;
use Modules\Inventory\Models\LocationModel;
use Modules\Inventory\Models\MovementModel;
use Modules\Inventory\Models\AlertModel;

class ReportController extends BaseController
{
    protected $itemModel;
    protected $categoryModel;
    protected $locationModel;
    protected $movementModel;
    protected $alertModel;

    public function __construct()
    {
        $this->itemModel = new ItemModel();
        $this->categoryModel = new CategoryModel();
        $this->locationModel = new LocationModel();
        $this->movementModel = new MovementModel();
        $this->alertModel = new AlertModel();
    }

    /**
     * Inventory summary report
     */
    public function summary()
    {
        // Get all active items
        $items = $this->itemModel->where('status', 'active')->findAll();
        
        // Get categories
        $categories = $this->categoryModel->findAll();
        $categoryMap = [];
        foreach ($categories as $cat) {
            $categoryMap[$cat['id']] = $cat['name'];
        }
        
        // Get locations
        $locations = $this->locationModel->findAll();
        $locationMap = [];
        foreach ($locations as $loc) {
            $locationMap[$loc['id']] = $loc['name'];
        }

        // Calculate totals
        $totalItems = count($items);
        $totalStock = array_sum(array_column($items, 'current_stock'));
        $totalValue = 0;
        foreach ($items as $item) {
            $totalValue += (float) $item['current_stock'] * (float) $item['purchase_price'];
        }

        // Low stock items
        $lowStock = array_filter($items, fn($i) => $i['current_stock'] <= $i['minimum_stock']);
        
        // Out of stock items
        $outOfStock = array_filter($items, fn($i) => $i['current_stock'] == 0);

        // By category
        $byCategory = [];
        foreach ($items as $item) {
            $catId = $item['category_id'] ?? 'uncategorized';
            $catName = $categoryMap[$catId] ?? 'Uncategorized';
            if (!isset($byCategory[$catName])) {
                $byCategory[$catName] = ['count' => 0, 'stock' => 0, 'value' => 0];
            }
            $byCategory[$catName]['count']++;
            $byCategory[$catName]['stock'] += $item['current_stock'];
            $byCategory[$catName]['value'] += $item['current_stock'] * (float) $item['purchase_price'];
        }

        // Alert counts
        $alertCounts = $this->alertModel->getCounts();

        $data = [
            'items' => $items,
            'categories' => $categories,
            'locations' => $locations,
            'totalItems' => $totalItems,
            'totalStock' => $totalStock,
            'totalValue' => $totalValue,
            'lowStockCount' => count($lowStock),
            'outOfStockCount' => count($outOfStock),
            'byCategory' => $byCategory,
            'alertCounts' => $alertCounts
        ];

        return view('Modules\Inventory\Views\reports\summary', $data);
    }

    /**
     * Stock valuation report
     */
    public function valuation()
    {
        $categoryId = $this->request->getGet('category');
        $locationId = $this->request->getGet('location');

        $builder = $this->itemModel->builder();
        $builder->where('status', 'active');
        
        if ($categoryId) {
            $builder->where('category_id', $categoryId);
        }
        
        if ($locationId) {
            $builder->where('location_id', $locationId);
        }

        $items = $this->itemModel->findAll();

        // Calculate valuations
        $totalPurchaseValue = 0;
        $totalSellingValue = 0;
        $totalStock = 0;

        foreach ($items as $item) {
            $purchaseValue = (float) $item['current_stock'] * (float) $item['purchase_price'];
            $sellingValue = (float) $item['current_stock'] * (float) $item['selling_price'];
            
            $totalPurchaseValue += $purchaseValue;
            $totalSellingValue += $sellingValue;
            $totalStock += $item['current_stock'];
        }

        // By category
        $categories = $this->categoryModel->findAll();
        $categoryMap = [];
        foreach ($categories as $cat) {
            $categoryMap[$cat['id']] = $cat['name'];
        }

        $byCategory = [];
        foreach ($items as $item) {
            $catId = $item['category_id'] ?? 'uncategorized';
            $catName = $categoryMap[$catId] ?? 'Uncategorized';
            if (!isset($byCategory[$catName])) {
                $byCategory[$catName] = ['items' => 0, 'stock' => 0, 'purchase_value' => 0, 'selling_value' => 0];
            }
            $byCategory[$catName]['items']++;
            $byCategory[$catName]['stock'] += $item['current_stock'];
            $byCategory[$catName]['purchase_value'] += $item['current_stock'] * (float) $item['purchase_price'];
            $byCategory[$catName]['selling_value'] += $item['current_stock'] * (float) $item['selling_price'];
        }

        $data = [
            'items' => $items,
            'categories' => $categories,
            'locations' => $this->locationModel->findAll(),
            'totalPurchaseValue' => $totalPurchaseValue,
            'totalSellingValue' => $totalSellingValue,
            'totalStock' => $totalStock,
            'potentialProfit' => $totalSellingValue - $totalPurchaseValue,
            'byCategory' => $byCategory,
            'selectedCategory' => $categoryId,
            'selectedLocation' => $locationId
        ];

        return view('Modules\Inventory\Views\reports\valuation', $data);
    }

    /**
     * Movement report
     */
    public function movement()
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

        // Calculate summary by type
        $summary = [];
        $totalIn = 0;
        $totalOut = 0;
        
        $inTypes = ['purchase', 'return', 'initial'];
        $outTypes = ['sale', 'damage', 'expired', 'adjustment'];
        
        foreach ($this->movementModel->getTypes() as $typeKey => $typeName) {
            $typeMovements = array_filter($movements, fn($m) => $m['movement_type'] === $typeKey);
            $totalQty = array_sum(array_column($typeMovements, 'quantity'));
            
            $summary[$typeKey] = [
                'name' => $typeName,
                'count' => count($typeMovements),
                'quantity' => $totalQty
            ];
            
            if (in_array($typeKey, $inTypes)) {
                $totalIn += $totalQty;
            } elseif (in_array($typeKey, $outTypes)) {
                $totalOut += abs($totalQty);
            }
        }

        $data = [
            'movements' => $movements,
            'items' => $items,
            'summary' => $summary,
            'types' => $this->movementModel->getTypes(),
            'totalIn' => $totalIn,
            'totalOut' => $totalOut,
            'netMovement' => $totalIn - $totalOut,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'selectedType' => $type
        ];

        return view('Modules\Inventory\Views\reports\movement', $data);
    }
}
