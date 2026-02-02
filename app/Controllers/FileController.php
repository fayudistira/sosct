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
        
        // Security check - prevent directory traversal
        $realPath = realpath($fullPath);
        $uploadsPath = realpath(WRITEPATH . 'uploads/');
        
        if (!$realPath || strpos($realPath, $uploadsPath) !== 0) {
            return $this->response->setStatusCode(404, 'File not found');
        }
        
        // Check if file exists
        if (!file_exists($fullPath) || !is_file($fullPath)) {
            return $this->response->setStatusCode(404, 'File not found');
        }
        
        // Get mime type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $fullPath);
        finfo_close($finfo);
        
        // Set headers and return file
        return $this->response
            ->setHeader('Content-Type', $mimeType)
            ->setHeader('Content-Length', (string) filesize($fullPath))
            ->setBody(file_get_contents($fullPath));
    }
}
