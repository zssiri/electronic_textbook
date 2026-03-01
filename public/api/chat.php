<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");

$input = json_decode(file_get_contents("php://input"), true);

$userMessage = trim($input["message"] ?? "");
$topic = trim($input["topic"] ?? "");

if (!$userMessage) {
    echo json_encode(["text" => "Пустой запрос. PHP не получил сообщение."]);
    exit;
}

$systemPrompt = "
Ты — узкоспециализированный ИИ-ассистент цифрового учебника по Основам предпринимательской деятельности.

ТВОЯ РОЛЬ:
Ты помогаешь студентам осваивать материалы учебника. Твои знания ограничены темами: бизнес-планирование, маркетинг (B2B, B2C, B2G), виды мышления предпринимателя, операционная деятельность, финансы, инвестиции и стратегия развития бизнеса.

КОНТЕКСТ ТЕКУЩЕГО УРОКА:
Тема, которую сейчас изучает студент: «$topic».

ПРАВИЛА ОТВЕТОВ:
1. Если вопрос касается предпринимательства или текущей темы («$topic»), отвечай кратко, профессионально и понятно (не более 3-4 предложений).
2. Используй терминологию из учебника (например: пассионарность, модель PAEI, бизнес-культура).
3. Если вопрос НЕ касается предпринимательства (например, политика, кулинария, игры, программирование или общие темы), ты ОБЯЗАН ответить строго по шаблону:
   «Я — специализированный помощник по курсу «Основы предпринимательской деятельности». Я могу обсуждать только темы бизнеса и материалы вашего учебника. Пожалуйста, задайте вопрос по текущей теме.»

ВОПРОС СТУДЕНТА:
$userMessage
";

$apiKey = "AIzaSyB9OmEZ4aRTvEWoG2x0OvBgbPQjmvoAJ4M"; 
$url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=" . $apiKey;

$data = [
    "contents" => [["parts" => [["text" => $systemPrompt]]]]
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$result = json_decode($response, true);

if ($httpCode === 200 && isset($result['candidates'][0]['content']['parts'][0]['text'])) {
    echo json_encode(["text" => $result['candidates'][0]['content']['parts'][0]['text']]);
} else {
    $error = $result['error']['message'] ?? "Неизвестная ошибка API";
    echo json_encode(["text" => "Ошибка Google: " . $error]);
}