<?php

namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;

class FileController extends BaseController
{
    /**
     * Serve uploaded files from writable/uploads directory
     */
    public function serve(...$segments): ResponseInterface
    {
        // Reconstruct the file path
        $filePath = implode('/', $segments);
        $fullPath = WRITEPATH . 'uploads/' . $filePath;
        
        // Log for debugging (only in development)
        if (ENVIRONMENT === 'development') {
            log_message('debug', 'FileController::serve - Requested path: ' . $filePath);
            log_message('debug', 'FileController::serve - Full path: ' . $fullPath);
        }
        
        // Security check - prevent directory traversal
        $realPath = realpath($fullPath);
        $uploadsPath = realpath(WRITEPATH . 'uploads/');
        
        if (!$realPath || strpos($realPath, $uploadsPath) !== 0) {
            log_message('error', 'FileController::serve - Security check failed for: ' . $filePath);
            return $this->response->setStatusCode(404, 'File not found');
        }
        
        // Check if file exists
        if (!file_exists($fullPath) || !is_file($fullPath)) {
            log_message('error', 'FileController::serve - File not found: ' . $fullPath);
            return $this->response->setStatusCode(404, 'File not found');
        }
        
        // Check if file is readable
        if (!is_readable($fullPath)) {
            log_message('error', 'FileController::serve - File not readable: ' . $fullPath);
            return $this->response->setStatusCode(403, 'Access denied');
        }
        
        // Get mime type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $fullPath);
        finfo_close($finfo);
        
        // Set headers and return file
        return $this->response
            ->setHeader('Content-Type', $mimeType)
            ->setHeader('Content-Length', (string) filesize($fullPath))
            ->setHeader('Cache-Control', 'public, max-age=31536000') // Cache for 1 year
            ->setBody(file_get_contents($fullPath));
    }
}
