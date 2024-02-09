<?php

namespace HubSpot\ObjectCollection;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use HubSpot\ObjectCollection\Exceptions\ConfigurationException;

class HubSpotObjectCollection
{
    const API_URL = "https://api.monday.com/v2";
    protected $apiToken;
    protected $client;
    protected $autoRetryComplexityLimit;
    protected $maxRetryLimit;
    protected $logger;
    protected $logRequests;
    protected $apiVersion;

    /**
     * Constructor - sets apiToken
     * @param String token
     *
     * @throws JackTaylorGroup\MondaySDK\Exceptions\ConfigurationException if valid token is not provided
     */
    public function __construct(string $token = null, string $apiVersion = null, ClientInterface $client = null)
    {

        $token = $token ? $token : ( function_exists('config') ? config('hubspot.api_token') : null );

        if (!$token) {
            throw new ConfigurationException("No API token provided");
        }

        $this->apiToken = $token;
        $this->client = $client ?: new Client();
        $this->apiVersion = $apiVersion ? $apiVersion : ( function_exists('config') ? config('hubspot.api_version') : null );
    }

    /**
     * Sets the users API Token
     * @param String token
     *
     * @return JackTaylorGroup\MondaySDK $this
     */
    public function setAPIToken(string $token) {
        $this->apiToken = $token;
        return $this;
    }

    /**
     * Gets the users API Token
     *
     * @return string|null
     */
    public function getAPIToken() {
        return $this->apiToken;
    }
}
