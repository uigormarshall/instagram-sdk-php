<?php

namespace Instagram\API\Request;

use Instagram\Instagram;
use Instagram\API\Response\RecentResponse;

class RecentRequest extends AuthenticatedBaseRequest {

    public function __construct($instagram){

        parent::__construct($instagram);

    }
    public function getMethod(){
        return self::GET;
    }

    public function getEndpoint(){
        return "/v1/news/inbox/";
    }

    public function getResponseObject(){
        return new RecentResponse($this);
    }

    public function execute(){
        return parent::execute();
    }
}
