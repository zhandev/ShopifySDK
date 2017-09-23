<?php
/**
 * Created by PhpStorm.
 * User: zhandev
 * Date: 9/21/17
 * Time: 7:48 PM
 */

namespace Zhandev312\ShopifyApi;

class Products extends BasicResource
{

    public function __construct($domain = null, $token = null, $resourceName = null)
    {
        parent::__construct($domain, $token, $resourceName);
    }
}