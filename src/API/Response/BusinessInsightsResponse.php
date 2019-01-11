<?php

namespace Instagram\API\Response;

class BusinessInsightsResponse extends BaseResponse {
    public $insights;
    public function __construct($params){
        $response = $params->response->getData();
        $this->insights = $response;
    }
    
    public function getLightes()
    {
        return $this->insights;
    }

}