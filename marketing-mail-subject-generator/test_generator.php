<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'generator.php';

$emailBody = "This is a sample email body.";
$keywords = "sample, test, email";
$api_key = "your api key";

// Generate target audience suggestion
$result = generateTargetAudienceSuggestion($emailBody, $keywords, $api_key);
if (is_array($result) && isset($result['response'])) {
    $targetAudienceSuggestion = $result['response'];
    $conversation_history = $result['conversation_history'];
    echo "Target Audience Suggestion: " . $targetAudienceSuggestion . "\n";

    // inputs one of the suggestions or inputs their own target audience
    $userSelectedTargetAudience = "nepali";

    // Generate email subject
    $subject = generateEmailSubject($emailBody, $keywords, $userSelectedTargetAudience, $api_key, $conversation_history);
    echo "Generated subject: " . $subject . "\n";
} else {
    echo "Error: " . $result . "\n";
}
