<?php

declare(strict_types=1);

/**
 * Tiny local endpoint for the decision document.
 *
 * Run from the project root:
 *   php -S 127.0.0.1:8099 -t docs
 *
 * The database (answers.db) is created next to this file on first use.
 */

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

$dbPath = __DIR__ . '/answers.db';
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

try {
    $pdo = new PDO('sqlite:' . $dbPath, null, null, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    $pdo->exec(
        'CREATE TABLE IF NOT EXISTS answers (
            question TEXT PRIMARY KEY,
            choice TEXT NOT NULL,
            label TEXT NOT NULL,
            updated_at TEXT NOT NULL
        )'
    );

    if ($method === 'GET') {
        $rows = $pdo->query(
            'SELECT question, choice, label, updated_at FROM answers ORDER BY question'
        )->fetchAll();

        echo json_encode(['ok' => true, 'db' => basename($dbPath), 'answers' => $rows]);
        exit;
    }

    if ($method !== 'POST') {
        http_response_code(405);
        echo json_encode(['ok' => false, 'error' => 'Use GET or POST.']);
        exit;
    }

    $raw = file_get_contents('php://input') ?: '';

    if (strlen($raw) > 65536) {
        http_response_code(413);
        echo json_encode(['ok' => false, 'error' => 'Payload too large.']);
        exit;
    }

    $body = json_decode($raw, true);
    $answers = is_array($body) ? ($body['answers'] ?? null) : null;

    if (!is_array($answers) || $answers === []) {
        http_response_code(422);
        echo json_encode(['ok' => false, 'error' => 'No answers received.']);
        exit;
    }

    $upsert = $pdo->prepare(
        'INSERT INTO answers (question, choice, label, updated_at)
         VALUES (?, ?, ?, ?)
         ON CONFLICT(question) DO UPDATE SET
            choice = excluded.choice,
            label = excluded.label,
            updated_at = excluded.updated_at'
    );

    $now = gmdate('c');
    $saved = 0;

    $pdo->beginTransaction();

    foreach ($answers as $question => $answer) {
        if (!is_string($question) || !preg_match('/^[A-E][1-7]$/', $question)) {
            continue;
        }

        if (!is_array($answer)) {
            continue;
        }

        $choice = (string) ($answer['choice'] ?? '');
        $label = trim((string) ($answer['label'] ?? ''));

        if (!preg_match('/^[A-C]$/', $choice) || $label === '') {
            continue;
        }

        $upsert->execute([$question, $choice, $label, $now]);
        $saved++;
    }

    $pdo->commit();

    echo json_encode(['ok' => true, 'db' => basename($dbPath), 'saved' => $saved]);
} catch (Throwable $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }

    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
}
