<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'generator.php';

$emailBody = "This is a sample email body.";
$keywords = "sample, test, email";
$targetAudience = "nepali";
$api_key = "chatGPT-3.5-API-key";
$subject = generateEmailSubject($emailBody, $keywords, $targetAudience, $api_key);

echo "Generated subject: " . $subject;