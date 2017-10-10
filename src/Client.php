<?php
/**
 * Created by PhpStorm.
 * User: zhandev
 * Date: 9/21/17
 * Time: 5:25 PM
 */

namespace Zhandev312\ShopifyApi;

class Client
{
    protected $domain;

    protected $token;

    public function __construct($domain = null, $token = null)
    {
        $this->domain = $domain;
        $this->token = $token;
    }

    /**
     * Generate resource
     * @param $name Resource Name
     * @return object BasicResource || Resource
     */
    public function __get($name)
    {
        $fullClassName = __NAMESPACE__ . '\\' . ucfirst($name);

        if(class_exists($fullClassName)) {
            $resource = new $fullClassName($this->domain, $this->token, $name);
        }else {
            $resource = new BasicResource($this->domain, $this->token, $name);
        }

        return $resource;
    }

    public static function auth($domain, $code, $apiKey, $secretKey)
    {
        $client = new \GuzzleHttp\Client();

        $response = $client->request(
            'POST',
            "https://" .
            $domain .
            "/admin/oauth/access_token",
            ['query' => [
                'client_id' => $apiKey,
                'client_secret' => $secretKey,
                'code' => $code
            ]]);

        $accessToken = json_decode($response->getBody(),true)['access_token'];


        return new static($domain, $accessToken);
    }

    public function getToken() {

    	return $this->token;

    }

}