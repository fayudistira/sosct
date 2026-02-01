<?php

namespace Modules\Dashboard\Controllers;

use App\Controllers\BaseController;

class DashboardController extends BaseController
{
    /**
     * Dashboard home page
     * 
     * @return string
     */
    public function index(): string
    {
        $user = auth()->user();
        
        // Get menu items from all modules
        $menuItems = $this->loadModuleMenus();
        
        // Get admission statistics if user has permission
        $admissionStats = null;
        $programStats = null;
        
        if ($user->can('admission.view') || $user->can('admission.manage')) {
            $admissionModel = new \Modules\Admission\Models\AdmissionModel();
            $admissionStats = $admissionModel->getStatusCounts();
        }
        
        if ($user->can('program.view') || $user->can('program.manage')) {
            $programModel = new \Modules\Program\Models\ProgramModel();
            $programStats = $programModel->getProgramsByCategory();
        }
        
        return view('Modules\Dashboard\Views\index', [
            'title' => 'Dashboard',
            'user' => $user,
            'menuItems' => $menuItems,
            'admissionStats' => $admissionStats,
            'programStats' => $programStats
        ]);
    }
}
