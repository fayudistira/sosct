<?php

namespace Modules\Frontend\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use Modules\Admission\Models\AdmissionModel;

class FrontendApiController extends ResourceController
{
    protected $format = 'json';

    /**
     * Get recent admissions for popup notification
     * Returns last 5 admissions with program and applicant info
     * 
     * GET /frontend/api/recent-admissions
     * 
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function recentAdmissions()
    {
        $admissionModel = new AdmissionModel();
        $db = \Config\Database::connect();

        // Get last 5 admissions with program and profile info
        $admissions = $db->table('admissions a')
            ->select('a.registration_number, a.application_date, a.status, p.full_name, pr.title as program_title, pr.category')
            ->join('profiles p', 'p.id = a.profile_id')
            ->join('programs pr', 'pr.id = a.program_id')
            ->orderBy('a.created_at', 'DESC')
            ->limit(5)
            ->get()
            ->getResultArray();

        // Format the data for display
        $formattedAdmissions = array_map(function ($admission) {
            // Mask the name for privacy (show first name only)
            $nameParts = explode(' ', $admission['full_name']);
            $maskedName = $nameParts[0];
            if (count($nameParts) > 1) {
                $maskedName .= ' ' . substr($nameParts[1], 0, 1) . '.';
            }

            // Calculate time ago
            $timeAgo = $this->timeAgo($admission['application_date']);

            return [
                'name' => $maskedName,
                'program' => $admission['program_title'],
                'category' => $admission['category'],
                'time_ago' => $timeAgo,
                'registration_number' => $admission['registration_number'],
            ];
        }, $admissions);

        return $this->respond([
            'success' => true,
            'admissions' => $formattedAdmissions
        ]);
    }

    /**
     * Convert date to time ago format
     * 
     * @param string $date
     * @return string
     */
    protected function timeAgo($date): string
    {
        $timestamp = strtotime($date);
        $now = time();
        $diff = $now - $timestamp;

        if ($diff < 60) {
            return 'Baru saja';
        } elseif ($diff < 3600) {
            $minutes = floor($diff / 60);
            return $minutes . ' menit yang lalu';
        } elseif ($diff < 86400) {
            $hours = floor($diff / 3600);
            return $hours . ' jam yang lalu';
        } elseif ($diff < 604800) {
            $days = floor($diff / 86400);
            return $days . ' hari yang lalu';
        } else {
            return date('d M Y', $timestamp);
        }
    }
}
