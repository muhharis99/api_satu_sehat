<?php

namespace App\Controllers;

use Config\SatuSehatCatalog;
use Throwable;

class ApiCatalog extends BaseController
{
    public function index()
    {
        $catalog = new SatuSehatCatalog();
        return $this->response->setJSON([
            'ok' => true,
            'source' => 'SATUSEHAT PUBLIC Postman',
            'resources' => $catalog->resources,
            'collections' => $catalog->collections,
            'request_groups' => $catalog->requestGroups,
            'documentation' => 'https://www.postman.com/satusehat/satusehat-public/overview',
        ]);
    }

    public function requestTemplates()
    {
        $catalog = new SatuSehatCatalog();
        return $this->response->setJSON([
            'ok' => true,
            'request_groups' => $catalog->requestGroups,
        ]);
    }
}
