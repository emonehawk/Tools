<?php
require_once __DIR__ . '/vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

// function to generate target audience suggestion
function generateTargetAudienceSuggestion(string $emailBody, string $keywords, string $api_key) {
    if (empty($emailBody) || empty($keywords) || empty($api_key)) {
        return "Error: Parameter not satisfied";
    }

    // Define the conversation prompt
    $prompt = [
        [
            'role' => 'system',
            'content' => 'You are an AI with extensive knowledge in marketing and human psychology. ' .
                'Your goal is to generate a target audience suggestion max 3' .
                'that would outperform those created by top marketers.'
        ],
        [
            'role' => 'user',
            'content' => "email body: '{$emailBody}', keywords: '{$keywords}'"
        ],
        [
            'role' => 'assistant',
            'content' => 'Think step-by-step brainstorm, debate pros and cons of different target audience'.
            'and Generate three concise, engaging target audience suggestions for this email.'.
            'The target audience should be relevant to the email content and keywords.'.
            'Please format your response as follows: "1. First suggestion 2. Second suggestion 3. Third suggestion"'
        ]
    ];

    $content = makeApiRequest($api_key, $prompt);
    $formattedContent = formatGeneratedText($content);
    return ['response' => $formattedContent, 'conversation_history' => $prompt];
}

// function to generate email subject
function generateEmailSubject(string $emailBody, string $keywords, string $targetAudience, string $api_key, array $conversation_history) {
    if (empty($emailBody) || empty($keywords) || empty($api_key)) {
        return "Error: Parameter not satisfied";
    }

    $prompt = [];

    // Add the conversation history to the prompt so that the AI can understand the context and generate a relevant response
    $prompt = $conversation_history;

    // Add the new prompt for generating the email subject
    $prompt[] = [
        'role' => 'system',
        'content' => 'You are an AI with extensive knowledge in marketing and human psychology. ' .
            'Your goal is to generate an engaging email subject ' .
            'that would outperform those created by top marketers.'
    ];

    $prompt[] =
    [
        'role' => 'user',
        'content' => "email body: '{$emailBody}', keywords: '{$keywords}', " .
        "target audience: '{$targetAudience}'"
    ];

    $prompt[] =
    [
        'role' => 'assistant',
        'content' => 'Based on the provided email body, keywords, and target audience, ' .
        'please brainstorm different subjects, debate their pros and cons, and choose the best one. ' .
        'Generate a single, concise, engaging subject for this email targeting the given audience.' .
        'Please format your response as follows: "Your subject here"'
    ];

    return makeApiRequest($api_key, $prompt);
}

// function to format the generated text
function formatGeneratedText(string $content) {
    $sentences = explode('.', $content);
    $formattedContent = "";
    foreach ($sentences as $sentence) {
        if (trim($sentence) !== '') {
            $formattedContent .= trim($sentence) . ". ";
        }
    }
    return $formattedContent;
}

// function to make an API request to OpenAI
function makeApiRequest(string $api_key, array $prompt) {
    try {
        $client = new Client(['base_uri' => 'https://api.openai.com']);
        $response = $client->request('POST', '/v1/chat/completions', [
            'headers' => [
                'Authorization' => "Bearer $api_key",
                'Content-Type' => 'application/json'
            ],
            'json' => [
                'model' => 'gpt-3.5-turbo-0125',
                'messages' => $prompt,
                'temperature' => 0.5,
                'max_tokens' => 60,
                'seed' => rand(0, 10000)
            ]
        ]);

        $body = $response->getBody();
        $arr = json_decode($body, true);
        if (isset($arr['choices'][0]['message']['content'])) {
            return $arr['choices'][0]['message']['content'];
        } else {
            return "Error: Unexpected API response";
        }
    }
    catch (RequestException $e)
    {
        $response = $e->getResponse();
        if ($response) {
            return "Error: " . $response->getBody();
        }

        return "Error: " . $e->getMessage();
    }
}
