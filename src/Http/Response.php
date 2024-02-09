<?php

class Response {

    /**
     *
     * HubSpot API Response
     *
     * @property array|string|null $content - API response body
     * @property int|null @statusCode - API response code
     *                                  Note: Some error response from the HubSpot API return a 200 status code
     *
     *
     * @method getContent
     */

    protected $content;
    protected $statusCode;

    public function __construct(int $statusCode, ?string $content) {
        $this->statusCode = $statusCode;
        $this->content = $content;
    }


    public function getContent() {
        return \json_decode($this->content, true);
    }

    public function getStatusCode(): int {
        return $this->statusCode;
    }

    public function __toString(): string {
        return '[Response] HTTP ' . $this->getStatusCode() . ' ' . $this->content;
    }
}
