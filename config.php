<?php
// Database configuration for the sample exam project.
// Fill these values with your local credentials before running the demo.
const DB_HOST = '127.0.0.1';
const DB_NAME = 'exam_library';
const DB_USER = 'root';
const DB_PASS = 'password';

/**
 * Returns a PDO connection to the configured MySQL database.
 */
function getPDO(): PDO
{
    static $pdo = null;
    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $dsn = sprintf('mysql:host=%s;dbname=%s;charset=utf8mb4', DB_HOST, DB_NAME);
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    return $pdo;
}
