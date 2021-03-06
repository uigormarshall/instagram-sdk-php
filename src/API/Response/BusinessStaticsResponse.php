<?php

namespace Instagram\API\Response;

use Instagram\API\Request\BusinessStaticsRequest;

class BusinessStaticsResponse extends BaseResponse {
    public $lights;
    public function __construct($params){
        $response = $params;
        $response = $params->response->getData();
        $this->lights = $response;
    }

    public function getStatistics()
    {
        return $this->lights;
    }

}