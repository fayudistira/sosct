<?php

namespace Modules\Dashboard\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;

/**
 * Dashboard API Controller
 * Provides statistics and dashboard data for the frontend
 */
class DashboardApiController extends ResourceController
{
    protected $format = 'json';

    /**
     * Get overall dashboard statistics
     * GET /api/dashboard/stats
     */
    public function stats()
    {
        $db = \Config\Database::connect();
        
        $stats = [];
        
        // Admissions stats
        $admissionStats = $db->table('admissions')
            ->select('status, COUNT(*) as count')
            ->groupBy('status')
            ->get()
            ->getResultArray();
        
        $stats['admissions'] = [
            'pending' => 0,
            'approved' => 0,
            'rejected' => 0,
            'total' => 0
        ];
        
        foreach ($admissionStats as $row) {
            $stats['admissions'][$row['status']] = (int) $row['count'];
            $stats['admissions']['total'] += (int) $row['count'];
        }
        
        // Students stats
        $studentStats = $db->table('students')
            ->select('status, COUNT(*) as count')
            ->where('deleted_at IS NULL')
            ->groupBy('status')
            ->get()
            ->getResultArray();
        
        $stats['students'] = [
            'active' => 0,
            'inactive' => 0,
            'graduated' => 0,
            'total' => 0
        ];
        
        foreach ($studentStats as $row) {
            $stats['students'][$row['status']] = (int) $row['count'];
            $stats['students']['total'] += (int) $row['count'];
        }
        
        // Users stats
        $stats['users'] = [
            'total' => $db->table('users')->countAll(),
            'active' => $db->table('users')->where('active', 1)->countAllResults()
        ];
        
        // Payment stats (this month)
        $monthStart = date('Y-m-01');
        $monthEnd = date('Y-m-t');
        
        $paymentStats = $db->table('payments')
            ->select('status, SUM(amount) as total')
            ->where('payment_date >=', $monthStart)
            ->where('payment_date <=', $monthEnd)
            ->groupBy('status')
            ->get()
            ->getResultArray();
        
        $stats['payments'] = [
            'this_month' => [
                'paid' => 0,
                'pending' => 0,
                'failed' => 0
            ]
        ];
        
        foreach ($paymentStats as $row) {
            if ($row['status'] === 'paid') {
                $stats['payments']['this_month']['paid'] = (float) ($row['total'] ?? 0);
            } elseif ($row['status'] === 'pending') {
                $stats['payments']['this_month']['pending'] = (float) ($row['total'] ?? 0);
            } elseif ($row['status'] === 'failed') {
                $stats['payments']['this_month']['failed'] = (float) ($row['total'] ?? 0);
            }
        }
        
        // Invoice stats
        $invoiceStats = $db->table('invoices')
            ->select('status, COUNT(*) as count, SUM(total_amount) as total')
            ->groupBy('status')
            ->get()
            ->getResultArray();
        
        $stats['invoices'] = [
            'unpaid' => 0,
            'paid' => 0,
            'overdue' => 0
        ];
        
        $today = date('Y-m-d');
        foreach ($invoiceStats as $row) {
            if ($row['status'] === 'unpaid' || $row['status'] === 'outstanding') {
                // Check if overdue
                $overdue = $db->table('invoices')
                    ->where('status', 'unpaid')
                    ->where('due_date <', $today)
                    ->countAllResults();
                $stats['invoices']['overdue'] = $overdue;
                $stats['invoices']['unpaid'] = (int) $row['count'];
            } elseif ($row['status'] === 'paid') {
                $stats['invoices']['paid'] = (int) $row['count'];
            }
        }
        
        return $this->respond([
            'status' => 'success',
            'data' => $stats,
            'generated_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Get recent admissions
     * GET /api/dashboard/recent-admissions
     */
    public function recentAdmissions()
    {
        $limit = $this->request->getGet('limit') ?? 10;
        
        $db = \Config\Database::connect();
        
        $admissions = $db->table('admissions a')
            ->select('a.*, p.full_name, p.email, prog.title as program_title')
            ->join('profiles p', 'p.id = a.profile_id', 'left')
            ->join('programs prog', 'prog.id = a.program_id', 'left')
            ->orderBy('a.created_at', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();
        
        return $this->respond([
            'status' => 'success',
            'data' => $admissions
        ]);
    }

    /**
     * Get recent payments
     * GET /api/dashboard/recent-payments
     */
    public function recentPayments()
    {
        $limit = $this->request->getGet('limit') ?? 10;
        
        $db = \Config\Database::connect();
        
        $payments = $db->table('payments p')
            ->select('p.*, a.full_name')
            ->join('admissions a', 'a.registration_number = p.registration_number', 'left')
            ->orderBy('p.payment_date', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();
        
        return $this->respond([
            'status' => 'success',
            'data' => $payments
        ]);
    }

    /**
     * Get overdue invoices
     * GET /api/dashboard/overdue-invoices
     */
    public function overdueInvoices()
    {
        $limit = $this->request->getGet('limit') ?? 10;
        
        $db = \Config\Database::connect();
        $today = date('Y-m-d');
        
        $invoices = $db->table('invoices')
            ->select('invoices.*, profiles.full_name')
            ->join('profiles', 'profiles.id = invoices.profile_id', 'left')
            ->where('invoices.status', 'unpaid')
            ->where('invoices.due_date <', $today)
            ->orderBy('invoices.due_date', 'ASC')
            ->limit($limit)
            ->get()
            ->getResultArray();
        
        return $this->respond([
            'status' => 'success',
            'data' => $invoices
        ]);
    }

    /**
     * Get revenue chart data
     * GET /api/dashboard/revenue-chart
     */
    public function revenueChart()
    {
        $year = $this->request->getGet('year') ?? date('Y');
        
        $db = \Config\Database::connect();
        
        // Get monthly revenue
        $monthlyRevenue = [];
        
        for ($month = 1; $month <= 12; $month++) {
            $monthStart = sprintf('%s-%02d-01', $year, $month);
            $monthEnd = sprintf('%s-%02d-t', $year, $month);
            
            $total = $db->table('payments')
                ->where('status', 'paid')
                ->where('payment_date >=', $monthStart)
                ->where('payment_date <=', $monthEnd)
                ->selectSum('amount')
                ->get()
                ->getRow();
            
            $monthlyRevenue[] = [
                'month' => $month,
                'month_name' => date('F', mktime(0, 0, 0, $month, 1)),
                'revenue' => (float) ($total->amount ?? 0)
            ];
        }
        
        return $this->respond([
            'status' => 'success',
            'data' => [
                'year' => $year,
                'monthly' => $monthlyRevenue
            ]
        ]);
    }

    /**
     * Get admissions chart data
     * GET /api/dashboard/admissions-chart
     */
    public function admissionsChart()
    {
        $year = $this->request->getGet('year') ?? date('Y');
        
        $db = \Config\Database::connect();
        
        // Get monthly admissions
        $monthlyAdmissions = [];
        
        for ($month = 1; $month <= 12; $month++) {
            $monthStart = sprintf('%s-%02d-01', $year, $month);
            $monthEnd = sprintf('%s-%02d-t', $year, $month);
            
            $total = $db->table('admissions')
                ->where('created_at >=', $monthStart)
                ->where('created_at <=', $monthEnd . ' 23:59:59')
                ->countAllResults();
            
            $approved = $db->table('admissions')
                ->where('status', 'approved')
                ->where('created_at >=', $monthStart)
                ->where('created_at <=', $monthEnd . ' 23:59:59')
                ->countAllResults();
            
            $monthlyAdmissions[] = [
                'month' => $month,
                'month_name' => date('F', mktime(0, 0, 0, $month, 1)),
                'total' => $total,
                'approved' => $approved
            ];
        }
        
        return $this->respond([
            'status' => 'success',
            'data' => [
                'year' => $year,
                'monthly' => $monthlyAdmissions
            ]
        ]);
    }

    /**
     * Get quick overview (lightweight)
     * GET /api/dashboard/overview
     */
    public function overview()
    {
        $db = \Config\Database::connect();
        
        // Quick counts
        $overview = [
            'admissions_pending' => $db->table('admissions')->where('status', 'pending')->countAllResults(),
            'students_active' => $db->table('students')->where('status', 'active')->where('deleted_at IS NULL', null, false)->countAllResults(),
            'invoices_unpaid' => $db->table('invoices')->whereIn('status', ['unpaid', 'outstanding'])->countAllResults(),
            'invoices_overdue' => $db->table('invoices')->where('status', 'unpaid')->where('due_date <', date('Y-m-d'))->countAllResults(),
            'payments_today' => $db->table('payments')->where('payment_date', date('Y-m-d'))->countAllResults()
        ];
        
        return $this->respond([
            'status' => 'success',
            'data' => $overview
        ]);
    }
}
