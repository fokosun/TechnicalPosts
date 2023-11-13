<?php

namespace Practice\Http\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\TransferStats;

class Controller
{
    protected string $url;
    protected bool $http_errors;

    public function __construct(string $url, bool $http_errors = true)
    {
        //todo sanitize the given url
        $this->url = $url;
        $this->http_errors = $http_errors;
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function makeGetRequest()
    {
        $guzzle = new Client;

        if (!$this->http_errors) {
            $response = $guzzle->get($this->url, [
                'on_stats' => function (TransferStats $stats) {
                    var_dump("Page Load Time in seconds: " . $stats->getTransferTime());
                },
                'http_errors' => false
            ]);
        } else {
            $response = $guzzle->get($this->url, [
                'on_stats' => function (TransferStats $stats) {
                    var_dump($stats->getTransferTime());
                }
            ]);

        }

        $html = $response->getBody()->getContents();
        return $this->formatResponse($html);
    }

    private function formatResponse(string $response): mixed
    {
        $decoded = json_decode($response, true);

        if ($decoded != null) {
            return json_decode($response, true);
        } else {
            $domDoc = new \DOMDocument();
            $domDoc->loadHTML($response, LIBXML_NOERROR);

            return $domDoc->getElementsByTagName('title')[0]->textContent;
        }
    }
}
