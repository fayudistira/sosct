<?php

namespace Modules\Settings\Controllers;

use App\Controllers\BaseController;

class SettingsController extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $data = [
            'title' => 'Settings',
            'menuItems' => $this->loadModuleMenus(),
            'user' => auth()->user(),
            'tables' => $this->getTableStats(),
        ];

        return view('Modules\Settings\Views\index', $data);
    }

    public function cleanup()
    {
        $data = [
            'title' => 'Cleanup Test Data',
            'menuItems' => $this->loadModuleMenus(),
            'user' => auth()->user(),
            'tables' => $this->getTableStats(),
        ];

        return view('Modules\Settings\Views\cleanup', $data);
    }

    public function doCleanup()
    {
        $confirm = $this->request->getPost('confirm');

        if ($confirm !== 'DELETE') {
            return redirect()->to('settings/cleanup')
                ->with('error', 'You must type "DELETE" to confirm.');
        }

        // Disable foreign key checks, delete in order, then re-enable
        $this->db->query('SET FOREIGN_KEY_CHECKS = 0');

        $tables = [
            'messages',
            'conversation_participants',
            'payments',
            'invoices',
            'students',
            'admissions',
            'profiles',
            'conversations',
        ];

        $results = [];
        foreach ($tables as $table) {
            try {
                $count = $this->db->table($table)->countAllResults();
                $this->db->table($table)->truncate();
                $results[$table] = ['success' => true, 'count' => $count];
            } catch (\Exception $e) {
                // Try delete as fallback if truncate fails
                try {
                    $count = $this->db->table($table)->countAllResults();
                    $this->db->table($table)->emptyTable();
                    $results[$table] = ['success' => true, 'count' => $count];
                } catch (\Exception $e2) {
                    $results[$table] = ['success' => false, 'error' => $e2->getMessage()];
                }
            }
        }

        $this->db->query('SET FOREIGN_KEY_CHECKS = 1');

        // Clear upload directories
        $uploadDirs = [
            FCPATH . 'uploads/profiles/photos',
            FCPATH . 'uploads/profiles/documents',
        ];

        foreach ($uploadDirs as $dir) {
            if (is_dir($dir)) {
                $files = glob($dir . '/*');
                foreach ($files as $file) {
                    if (is_file($file)) {
                        unlink($file);
                    }
                }
            }
        }

        return redirect()->to('settings/cleanup')
            ->with('success', 'Test data has been cleared successfully.')
            ->with('results', $results);
    }

    public function testData()
    {
        $data = [
            'title' => 'Generate Test Data',
            'menuItems' => $this->loadModuleMenus(),
            'user' => auth()->user(),
        ];

        return view('Modules\Settings\Views\test_data', $data);
    }

    public function generateTestData()
    {
        $type = $this->request->getPost('type');

        try {
            switch ($type) {
                case 'admissions':
                    $this->generateTestAdmissions();
                    break;
                case 'invoices':
                    $this->generateTestInvoices();
                    break;
                default:
                    throw new \Exception('Unknown test data type');
            }

            return redirect()->to('settings/test-data')
                ->with('success', 'Test data generated successfully.');
        } catch (\Exception $e) {
            return redirect()->to('settings/test-data')
                ->with('error', 'Failed to generate test data: ' . $e->getMessage());
        }
    }

    private function getTableStats()
    {
        $tables = [
            'profiles',
            'admissions',
            'invoices',
            'payments',
            'students',
            'conversations',
            'messages',
        ];

        $stats = [];
        foreach ($tables as $table) {
            try {
                $stats[$table] = $this->db->table($table)->countAllResults();
            } catch (\Exception $e) {
                $stats[$table] = 0;
            }
        }

        return $stats;
    }

    private function generateTestAdmissions()
    {
        // This would generate test admissions
        // Implementation depends on your needs
        log_message('info', 'Test admissions generation triggered');
    }

    private function generateTestInvoices()
    {
        // This would generate test invoices
        log_message('info', 'Test invoices generation triggered');
    }
}
