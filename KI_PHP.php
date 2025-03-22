<?php
header("Content-Type: application/json");

// OpenAI API-Schlüssel einfügen
$apiKey = "DEIN_OPENAI_API_KEY";

// Eingehende Nachricht abrufen
$data = json_decode(file_get_contents("php://input"), true);
$userMessage = $data["message"] ?? "";

// OpenAI API Anfrage
$apiUrl = "https://api.openai.com/v1/chat/completions";
$postData = json_encode([
    "model" => "gpt-3.5-turbo",
    "messages" => [
        ["role" => "system", "content" => "Du bist ein hilfreicher Chatbot."],
        ["role" => "user", "content" => $userMessage]
    ]
]);

$ch = curl_init($apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $apiKey",
    "Content-Type: application/json"
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

$response = curl_exec($ch);
curl_close($ch);

$responseData = json_decode($response, true);
$botReply = $responseData["choices"][0]["message"]["content"] ?? "Es gab ein Problem mit der Antwort.";

echo json_encode(["reply" => $botReply]);
?>
