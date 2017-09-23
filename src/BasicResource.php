<?php
/**
 * Created by PhpStorm.
 * User: zhandev
 * Date: 9/21/17
 * Time: 7:44 PM
 */

namespace Zhandev312\ShopifyApi;

class BasicResource
{

    protected $domain;
    protected $token;
    protected $guzzleClient;
    protected $resourceName;

    public function __construct($domain = null, $token = null, $resourceName = null)
    {
        $this->domain = $domain;
        $this->token = $token;
        $this->resourceName = $this->from_camel_case($resourceName);
        $this->guzzleClient = new \GuzzleHttp\Client();
    }

    public function all($options = [])
    {
        $options = http_build_query($options);
        $all = $this->get("/admin/$this->resourceName.json?$options");

        return $all[$this->resourceName];
    }

    public function count($options = [])
    {
        $options = http_build_query($options);

        $all = $this->get("/admin/$this->resourceName/count.json?$options");

        return $all['count'];
    }

    public function single($id, $options = [])
    {
        $options = http_build_query($options);

        return $this->get("/admin/$this->resourceName/$id.json?$options");

    }

    public function create($postData)
    {
        return $this->post("/admin/$this->resourceName.json", $postData);
    }

    public function update($id, $postData)
    {
        return $this->put("/admin/$this->resourceName/$id.json", $postData);
    }

    public function get($resource)
    {

        $response = $this->guzzleClient->request('GET', "https://" . $this->domain . $resource, ['headers' => [
            'X-Shopify-Access-Token' => $this->token,
            'X-Frame-Options' => 'allow'
        ]]);

        return json_decode($response->getBody()->getContents(), true);
    }

    public function post($resource, $postData)
    {
        $response = $this->guzzleClient->request('POST', "https://" . $this->domain . $resource, [
            'headers' => [
                'X-Shopify-Access-Token' => $this->token,
                'X-Frame-Options' => 'allow'
            ],
            'form_params' => $postData
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    public function put($resource, $postData)
    {
        $response = $this->resourceName->request('PUT', "https://" . $this->domain . $resource, [
            'headers' => [
                'X-Shopify-Access-Token' => $this->token,
                'X-Frame-Options' => 'allow'
            ],
            'form_params' => $postData
        ]);
        return json_decode($response->getBody()->getContents(), true);
    }

    public function delete($id) {

        $response = $this->guzzleClient->request('DELETE', "https://" . $this->domain . "/admin/$this->resourceName/$id.json", ['headers' => [
            'X-Shopify-Access-Token' => $this->token,
            'X-Frame-Options' => 'allow'
        ]]);

        return $response->getStatusCode();

    }

    private function from_camel_case($input) {
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $input, $matches);
        $ret = $matches[0];
        foreach ($ret as &$match) {
            $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
        }
        return implode('_', $ret);
    }
}