<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class LanguageFilters implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response. If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return RequestInterface|ResponseInterface|string|void
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        $uri = service('uri');
        $lang = $uri->getSegment(1);

        // Jika tidak ada segment bahasa
        if (!$lang) {
            $lang = $session->get('lang', 'id'); // Default ke 'id'
            $currentPath = ltrim(uri_string(), '/');
            return redirect()->to(base_url($lang . '/' . $currentPath));
        }

        // Jika bahasa valid
        if (in_array($lang, ['id', 'en'])) {
            $session->set('lang', $lang);
            service('request')->setLocale($lang);
        } else {
            // Jika bahasa tidak valid, redirect ke default
            return redirect()->to(base_url('id' . uri_string()));
        }
    }
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}
