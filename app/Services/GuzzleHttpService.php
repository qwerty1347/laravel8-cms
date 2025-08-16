<?php

namespace App\Services;

use Exception;
use App\Constants\HttpCodeConstant;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class GuzzleHttpService
{
    protected Client $client;

    public function __construct() {
        $this->client = new Client();
    }

    /**
     * POST 요청하는 메소드
     *
     * @param   string  $url      엔드포인트 URL
     * @param   array   $headers
     * @param   array   $params
     *
     * @return  mixed   (array|throw Exception)
     */
    public function postRequest(string $url, array $headers=[], array $params=[])
    {
        $options = ['headers' => $headers];

        if (!empty($params)) {
            if (isset($headers['Content-Type']) && $headers['Content-Type'] === 'application/json') {
                $options['json'] = $params;
            } else {
                $options['form_params'] = $params;
            }
        }

        try {
            $response = $this->client->request('POST', $url, $options);
            return successResponse(json_decode($response->getBody()->getContents(), true) ?? []);

        } catch (RequestException $e) {
            $statusCode = $e->getResponse() ? $e->getResponse()->getStatusCode() : HttpCodeConstant::INTERVAL_SERVER_ERROR;
            throw new Exception($e->getMessage(), $statusCode);

        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpCodeConstant::UNKNOWN_ERROR);
        }
    }

    /**
     * GET 요청하는 메소드
     *
     * @param   string  $url          엔드포인트 URL
     * @param   array   $headers
     * @param   array   $queryParams
     *
     * @return  mixed   (array|throw Exception)
     */
    public function getRequest(string $url, array $headers=[], array $queryParams=[])
    {
        $options = ['headers' => $headers];

        if (!empty($queryParams)) {
            $options['query'] = $queryParams;
        }

        try {
            $response = $this->client->request('GET', $url, $options);
            return successResponse(json_decode($response->getBody()->getContents(), true) ?? []);

        } catch (RequestException $e) {
            $statusCode = $e->getResponse() ? $e->getResponse()->getStatusCode() : HttpCodeConstant::INTERVAL_SERVER_ERROR;
            throw new Exception($e->getMessage(), $statusCode);

        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpCodeConstant::UNKNOWN_ERROR);
        }
    }

}
