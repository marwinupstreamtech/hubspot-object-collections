<?php

namespace HubSpot\ObjectCollection;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use HubSpot\ObjectCollection\Exceptions\APIExceptions\UnauthorizedException;
use HubSpot\ObjectCollection\Exceptions\ConfigurationException;

class HubSpotObjectCollection
{
    protected $hubspotApi = "https://api.hubapi.com/crm/v3/objects";
    protected $apiToken;
    protected $client;
    protected $rawResponse;
    protected $path;

    /**
     * Constructor - sets apiToken
     * @param String token
     *
     * @throws HubSpot\ObjectCollection\Exceptions\ConfigurationException if valid token is not provided
     */
    public function __construct(String $token = null, ClientInterface $client = null)
    {
        $token = $token ? $token : ( function_exists('config') ? config('hubspot.api_token') : null );

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
     * @return HubSpot\ObjectCollection $this
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

    /**
     * Gets a collection of objects from the HubSpot API
     *
     * @param String objects[companies, contacts, deals, feedback_submissions, line_items, products, quotes, discounts, fees, taxes, tickets, goals]
     * @param Array properties
     * @param Int limit
     * @param Bool $archive
     */
    public function getCollection(String $objectType, Array $properties = ['hs_object_id', 'name'], Int $limit = 100, Bool $archive = false)
    {
        $objectType = (!$objectType) ? throw new ConfigurationException("No API token provided") : strtolower($objectType);
        $has_more = true;
        $after = null;
        $collected_objects = [];

        while ($has_more) {
            $path = "{$objectType}?limit={$limit}&archived={$archive}&properties={$this->arrangeProperties($properties)}";
            if (!is_null($after)) {
                $path .= ($after) ? "&after={$after}" : "";
            }

            $response = $this->sendHubSpotRequest($this->createHubSpotRequest("GET", $path));
            $objects = $response['results'];

            if (isset($response['paging'])) {
                $after = $response['paging']['next']['after'];
            } else {
                $has_more = false;
            }

            $collected_objects = array_merge($collected_objects, array_column($objects, 'properties'));
        }

        return collect(array_values(array_map("unserialize", array_unique(array_map("serialize", $collected_objects)))));
    }

    protected function createHubSpotRequest(String $method = 'GET', String $path = "", Array $body = [])
    {
        return new Request($method, "{$this->hubspotApi}/{$path}", [
                'accept' => 'application/json',
                'content-type' => 'application/json',
                'authorization' => "Bearer {$this->apiToken}"
            ],
            json_encode(['json' => $body]),
        );
    }

    protected function sendHubSpotRequest(Request $request)
    {
        try {
            $response = $this->client->send($request);
            $input = json_decode($response->getBody()->getContents(), true);
            $this->rawResponse = $input;

            return $this->rawResponse;
        } catch (ClientException $ce) {
            $response = $ce->getResponse();
            if ($response->getStatusCode() === 403) {
                throw new UnauthorizedException("Unauthorised action", ['path' => $this->path, 'response' => $this->rawResponse], 403, $ce);
            }
        } catch (RequestException $re) {
            throw $re;
        }
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
