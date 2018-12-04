<?php

namespace Instagram\API\Framework;

use Curl\Curl;
use Instagram\Util\CustomJsonMapper;
use JsonMapper;

abstract class Request {

    const GET = 0;
    const POST = 1;

    /**
     * Used for Mapping response Json to Class instances.
     * @var JsonMapper
     */
    public $mapper;

    /**
     * Proxy used for Requests
     * @var string
     */
    private $proxy;

    /**
     * Proxy Credentials used for Requests
     * @var string
     */
    private $proxyCredentials;

    /**
     * Proxy used for Requests
     * @var string
     */
    private $verifyPeer = true;

    /**
     * @var array HTTP Headers to send in Request
     */
    private $headers = array();

    /**
     * @var array HTTP Cookies to send in Request
     */
    private $cookies = array();

    /**
     * @var array Parameters to send in Request
     */
    private $params = array();

    /**
     * Whether this API call needs signing of the POST data.
     *
     * On by default since most calls require it.
     *
     * @var bool
     */
    protected $_signedPost;

    /**
     * Whether this API endpoint responds with multiple JSON objects.
     *
     * Off by default.
     *
     * @var bool
     */
    protected $_isMultiResponse;

    /**
     * An array of POST params.
     *
     * @var array
     */
    protected $_posts;
    /**
     * @return string Request Method
     */
    public abstract function getMethod();

    /**
     * @return string Request Url
     */
    public abstract function getUrl();

    public function __construct(){
        $this->mapper = new CustomJsonMapper();
    }
    /**
     * Set signed request data flag.
     *
     * @param bool $signedPost
     *
     * @return self
     */
    public function setSignedPost(
        $signedPost = true)
    {
        $this->_signedPost = $signedPost;

        return $this;
    }

    /**
     * Set the "this API endpoint responds with multiple JSON objects" flag.
     *
     * @param bool $flag
     *
     * @return self
     */
    public function setIsMultiResponse(
        $flag = false)
    {
        $this->_isMultiResponse = $flag;

        return $this;
    }

    /**
     * Add POST param to request, overwriting any previous value.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return self
     */
    public function addPost(
        $key,
        $value)
    {
        if ($value === true) {
            $value = 'true';
        } elseif ($value === false) {
            $value = 'false';
        }
        $this->_posts[$key] = $value;

        return $this;
    }
    /**
     * Set Proxy to be used for Requests
     * @param $proxy string
     */
    public function setProxy($proxy){
        $this->proxy = $proxy;
    }

    /**
     * Set Proxy Credentials to be used for Requests
     * @param $credentials string
     */
    public function setProxyCredentials($credentials){
        $this->proxyCredentials = $credentials;
    }

    /**
     * Enable/Disable SSL Verification of Peer
     * @param $verifyPeer boolean
     */
    public function setVerifyPeer($verifyPeer){
        $this->verifyPeer = $verifyPeer;
    }

    /**
     *
     * Add Header to the Request
     *
     * @param $key string Header Key
     * @param $value string Header Value
     */
    public function addHeader($key, $value){
        $this->headers[$key] = $value;
    }

    /**
     *
     * Add Cookie to the Request
     *
     * @param $key string Cookie Key
     * @param $value string Cookie Value
     */
    public function addCookie($key, $value){
        $this->cookies[$key] = $value;
    }

    /**
     *
     * Add Parameter to the Request
     *
     * @param $key string Parameter Key
     * @param $value string Parameter Value
     */
    public function addParam($key, $value){
        $this->params[$key] = $value;
    }

    /**
     *
     * Add File to the Request
     *
     * @param $key string File Key
     * @param $file RequestFile
     */
    public function addFile($key, $file){
        $this->params[$key] = new \CURLFile($file->getPath(), $file->getMime(), $file->getName());
    }

    /**
     * @return array Request Headers
     */
    public function getHeaders(){
        return $this->headers;
    }

    /**
     * @return array Request Cookies
     */
    public function getCookies(){
        return $this->cookies;
    }

    /**
     * @return array Request Parameters
     */
    public function getParams(){
        return $this->params;
    }

    public function clearHeaders(){
        return $this->headers = array();
    }

    public function clearCookies(){
        return $this->cookies = array();
    }

    public function clearParams(){
        return $this->params = array();
    }

    /**
     *
     * Execute the Request
     *
     * @return Response The Response
     * @throws \Exception
     */
    public function execute(){

        $data = null;
        $curl = new Curl();

        $curl->setOpt(CURLOPT_SSL_VERIFYPEER, $this->verifyPeer);

        if($this->proxy != null){

            $curl->setOpt(CURLOPT_PROXY, $this->proxy);

            if($this->proxyCredentials != null){
                $curl->setOpt(CURLOPT_PROXYUSERPWD, $this->proxyCredentials);
            }

        }

        foreach($this->getHeaders() as $key => $value){
            $curl->setHeader($key, $value);
        }

        foreach($this->getCookies() as $key => $value){
            $curl->setCookie($key, $value);
        }

        //print_R($this->getCookies());print_R($this->getHeaders());

        $error_format = "Instagram Request failed: [%s] [%s] %s";

        switch($this->getMethod()){

            case self::GET: {

                $data = $curl->get($this->getUrl(), $this->getParams());

                if($curl->curlError){
                    throw new InstagramException(sprintf($error_format, "GET", $this->getUrl(), $curl->errorMessage));
                }

                break;

            }

            case self::POST: {

                $data = $curl->post($this->getUrl(), $this->getParams());

                if($curl->curlError){
                    throw new InstagramException(sprintf($error_format, "POST", $this->getUrl(), $curl->errorMessage));
                }

                break;

            }

            default: {
                throw new InstagramException(sprintf($error_format, "UNKNOWN", $this->getUrl(), "Unsupported Request Method"));
            }

        }

        return new Response($curl, $data);

    }

}
