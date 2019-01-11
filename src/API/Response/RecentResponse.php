<?php

namespace Instagram\API\Response;

use Instagram\API\Request\RecentRequest;

class RecentResponse extends BaseResponse {
    public $recents;

    public function __construct($params){
        $response = $params->response->getData();
        $this->recents = $response;
    }

    public function getRecents()
    {
        return $this->recents;
    }
}