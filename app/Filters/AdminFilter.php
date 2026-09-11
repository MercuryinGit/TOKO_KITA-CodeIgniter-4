<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AdminFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null): ?\CodeIgniter\HTTP\ResponseInterface
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to(base_url('/'))
                ->with('error', 'Akses admin hanya untuk administrator.');
        }

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null): void
    {
    }
}