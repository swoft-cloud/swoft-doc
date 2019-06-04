<?php

namespace Swoft\DocBuild;

use GuzzleHttp\Client;
use Swoft\Stdlib\Helper\ObjectHelper;

/**
 * class MicrosoftTranslator
 * @see https://github.com/MicrosoftTranslator/Text-Translation-API-V3-PHP/blob/master/Translate.php
 */
class MicrosoftTranslator
{
    public const BASE_URL = 'https://api.cognitive.microsofttranslator.com';
    public const PREFIX = '/translate?api-version=3.0';

    public $key = '';

    /**
     * @see https://docs.microsoft.com/zh-cn/azure/cognitive-services/translator/reference/v3-0-translate
     * @var [type]
     */
    private $params = [
        'from'      => 'zh-CN', // 'zh-Hans'
        'to'        => 'en',
        'textType'  => 'plain', // plain(默认) html
        // 'category' => '',
    ];

    /**
     * @var Client
     */
    private $client;

    public function __construct(array $config = [])
    {
        ObjectHelper::init($this, $config);

        if (!$this->client) {
            $this->client = new Client([
                // 'handler' => $stack,
                'base_uri' => self::BASE_URL,
                // 'auth' => 'google_auth' // authorize all requests
            ]);
        }
    }

    public function translate(string $text, array $params = []): array
    {
        $data = [
            ['Text' => $text],
        ];

        $rets = $this->translateBatch($data, $params);

        if (count($rets) > 0) {
            return $rets[0];
        }

        return [];
    }

    public function translateBatch(array $data, array $params = []): array
    {
        $headers = [
            'Ocp-Apim-Subscription-Key' => $this->key,
            'Content-Type'              => 'application/json',
            // 'Content-Type'           => 'text/plain',
            'X-ClientTraceId'           => $this->genGuid(),
        ];

        $queryString = $this->buildQuery(array_merge($this->params, $params));

        $resp = $this->client->post(self::PREFIX . '&' . $queryString, [
            'headers' => $headers,
            'json'    => $data,
        ]);

        $result = $resp->getBody()->getContents();

        return (array)\json_decode($result, true);
    }

    public function genGuid()
    {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
            mt_rand( 0, 0xffff ),
            mt_rand( 0, 0x0fff ) | 0x4000,
            mt_rand( 0, 0x3fff ) | 0x8000,
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
        );
    }

    /**
     * @param array|string $queryData
     *
     * @return string
     */
    public function buildQuery($queryData): string
    {
        // is string
        if (is_string($queryData)) {
            return $queryData;
        }

        // array: k-v map
        return preg_replace('/%5B(?:[0-9]|[1-9][0-9]+)%5D=/', '=', http_build_query($queryData));
    }
}
