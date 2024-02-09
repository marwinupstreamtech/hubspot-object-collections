<?php

namespace HubSpot\ObjectCollection;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Request;
use HubSpot\ObjectCollection\Exceptions\ConfigurationException;

class HubSpotObjectCollection
{
    protected $hubspotApi = "https://api.hubapi.com/crm/v3/objects";
    protected $apiToken;
    protected $client;

    /**
     * Constructor - sets apiToken
     * @param String token
     *
     * @throws HubSpot\ObjectCollection\Exceptions\ConfigurationException if valid token is not provided
     */
    public function __construct(string $token = null, ClientInterface $client = null)
    {
        $token = $token ? $token : (function_exists('config') ? config('hubspot.api_token') : null);

        if (!$token) {
            throw new ConfigurationException("No API token provided");
        }

        $this->apiToken = $token;
        $this->client = $client ?: new Client();
    }

    /**
     * Sets the users API Token
     * @param String token
     *
     * @return JackTaylorGroup\MondaySDK $this
     */
    public function setAPIToken(string $token)
    {
        $this->apiToken = $token;
        return $this;
    }

    /**
     * Gets the users API Token
     *
     * @return string|null
     */
    public function getAPIToken()
    {
        return $this->apiToken;
    }

    public function getProducts(Array $properties = ['hs_object_id', 'name'], Int $limit = 100)
    {
        $has_more = true;
        $after = null;
        $collected_products = [];

        while ($has_more) {
            $query = "products?limit={$limit}&properties={$this->arrangeProperties($properties)}";
            if (!is_null($after)) {
                $query .= ($after) ? "&after={$after}" : "";
            }

            $response = $this->sendHubSpotRequest($this->createHubSpotRequest("GET", $query));
            $products = $response['results'];

            if (isset($response['paging'])) {
                $after = $response['paging']['next']['after'];
            } else {
                $has_more = false;
            }

            $collected_products = array_merge($collected_products, array_column($products, 'properties'));
        }

        return $collected_products;
    }

    protected function createHubSpotRequest(String $method = 'GET', String $query = null, Array $body = [])
    {
        return new Request($method, "{$this->hubspotApi}/{$query}", [
                'accept' => 'application/json',
                'content-type' => 'application/json',
                'authorization' => "Bearer {$this->apiToken}"
            ],
            json_encode(['json' => $body]),
        );
    }

    protected function sendHubSpotRequest(Request $request)
    {
        $response = $this->client->send($request);
        $input = json_decode($response->getBody()->getContents(), true);

        return $input;
    }

    private function arrangeProperties($properties)
    {
        return implode('%2C', array_map(
            function ($v, $k) {
                return "{$v}";
            },
            $properties,
            array_keys($properties)
        ));
    }
}
