<?php
require_once __DIR__ . '/vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

function generateEmailSubject(string $emailBody, string $keywords, string $targetAudience, string $api_key) {
    if (empty($emailBody) || empty($keywords) || empty($api_key)) {
        return "Error: Parameter not satisfied";
    }
    $client = new Client(['base_uri' => 'https://api.openai.com']);

    try {
        $response = $client->request('POST', '/v1/chat/completions', [
            'headers' => [
                'Authorization' => "Bearer $api_key",
                'Content-Type' => 'application/json'
            ],
            'json' => [
                'model' => 'gpt-3.5-turbo-0125',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are an AI with extensive knowledge in marketing and human psychology. ' .
                            'Your goal is to generate an engaging email subject ' .
                            'that would outperform those created by top marketers.'
                    ],
                    [
                        'role' => 'user',
                        'content' => "email body: '{$emailBody}', keywords: '{$keywords}', " .
                        "target audience: '{$targetAudience}'"
                    ],
                    [
                        'role' => 'assistant',
                        'content' => 'Think step-by-step brainstorm, debate pros and cons of different subject'.
                        'and Generate a concise, engaging subject for this email targeting the given audience.'.
                        'if target audience is not provided, try to predict audience from email body and keywords'.
                        'or assume a general audience.'.
                        'The subject should be catchy and relevant to the email content.'
                    ]
                ]
            ]
        ]);

        $body = $response->getBody();

        $arr = json_decode($body, true);

        if (isset($arr['choices'][0]['message']['content'])) {
            return $arr['choices'][0]['message']['content'];
        } else {
            return "Error: Unexpected API response";
        }
    } catch (RequestException $e) {
        $response = $e->getResponse();
        if ($response) {
            return "Error: " . $response->getBody();
        }

        return "Error: " . $e->getMessage();
    }
}
