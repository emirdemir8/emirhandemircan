<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(['success' => false, 'message' => 'Only POST requests are allowed.'], 405);
}

$name = clean_string($_POST['name'] ?? '');
$email = clean_string($_POST['email'] ?? '');
$message = clean_string($_POST['message'] ?? '');

$errors = [];

if (strlen($name) < 2) {
    $errors[] = 'Name must be at least 2 characters.';
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'A valid email address is required.';
}

if (strlen($message) < 10) {
    $errors[] = 'Message must be at least 10 characters.';
}

if ($errors !== []) {
    json_response(['success' => false, 'message' => $errors[0]], 422);
}

try {
    $statement = db()->prepare(
        'INSERT INTO contacts (name, email, message, ip_address, user_agent)
         VALUES (:name, :email, :message, :ip_address, :user_agent)'
    );
    $statement->execute([
        ':name' => $name,
        ':email' => $email,
        ':message' => $message,
        ':ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
        ':user_agent' => substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255),
    ]);

    json_response([
        'success' => true,
        'message' => 'Thank you. Your message was saved successfully.',
    ]);
} catch (Throwable $exception) {
    json_response([
        'success' => false,
        'message' => 'Message could not be saved. Please check the MySQL connection.',
    ], 503);
}
