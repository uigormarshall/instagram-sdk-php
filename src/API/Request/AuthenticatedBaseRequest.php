<?php

namespace Instagram\API\Request;

use Instagram\Instagram;

abstract class AuthenticatedBaseRequest extends BaseRequest {

    public function __construct($instagram){

        parent::__construct($instagram);

        foreach($instagram->getCookies() as $key => $value){
            $this->addCookie($key, $value);
        }

    }
}