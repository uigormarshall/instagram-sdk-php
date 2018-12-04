<?php

namespace Instagram\API\Request;

use Instagram\Instagram;
use Instagram\API\Response\BusinessInsightsResponse;
class BusinessInsightsRequest extends AuthenticatedBaseRequest {

    public function __construct($instagram, $day = null){
        parent::__construct($instagram);
        $this->addParam("show_promotions_in_landing_page", 'true');
        $this->addParam('first', $day);
    }

    public function getMethod(){
        return self::GET;
    }

    public function getEndpoint(){
        return "/v1/insights/account_organic_insights/";
    }
    public function getResponseObject(){
        return new BusinessInsightsResponse($this);
    }
    public function execute(){
        return parent::execute();
    }

}