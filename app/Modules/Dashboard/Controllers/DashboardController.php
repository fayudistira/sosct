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
        
        // Get payment statistics
        $paymentStats = null;
        try {
            $paymentModel = new \Modules\Payment\Models\PaymentModel();
            $startDate = date('Y-01-01');
            $endDate = date('Y-m-d');
            $paymentStats = $paymentModel->getDashboardStatistics($startDate, $endDate);
            $paymentStats['revenue_by_method'] = $paymentModel->getRevenueByMethod();
        } catch (\Exception $e) {
            // Payment module might not be available
            $paymentStats = null;
        }
        
        return view('Modules\Dashboard\Views\index', [
            'title' => 'Dashboard',
            'user' => $user,
            'menuItems' => $menuItems,
            'admissionStats' => $admissionStats,
            'programStats' => $programStats,
            'paymentStats' => $paymentStats
        ]);
    }
}
