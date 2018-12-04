<?php

namespace Instagram\API\Request;

use Instagram\Instagram;
use Instagram\API\Response\BusinessStaticsResponse;
class BusinessStaticsRequest extends AuthenticatedBaseRequest {

    public function __construct($instagram, $day = null){
        $pk = $instagram->getLoggedInUser()->getPk();
        $variables = json_encode([
            'IgInsightsGridMediaImage_SIZE' => 240,
            'timezone'                      => 'Pacific/Auckland',
            'query_params'                  => json_encode([
                'access_token'  => '',
                'id'            => $pk,
            ]),
        ]);

        parent::__construct($instagram);
        $this->setSignedPost(false);
        $this->setIsMultiResponse(true);
        $this->addParam("locale", 'pt-Br');
        $this->addParam("vc_policy", 'insights_policy');
        $this->addParam("surface", 'account');
        $this->addParam("access_token", 'undefined');
        $this->addParam("fb_api_caller_class", 'RelayModern');
        $this->addParam("variables", $variables);
        $this->addParam('doc_id', '2300767026607614');
    }

    public function getMethod(){
        return self::POST;
    }

    public function getEndpoint(){
        return "/v1/ads/graphql/";
    }
    public function getResponseObject(){
        return new BusinessStaticsResponse($this);
    }
    public function execute(){
        return parent::execute();
    }

}