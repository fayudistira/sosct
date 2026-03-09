<?php

namespace Modules\Inventory\Controllers;

use App\Controllers\BaseController;

class InventoryController extends BaseController
{
    /**
     * Main inventory dashboard
     */
    public function index()
    {
        return view('Modules\Inventory\Views\index');
    }
}
