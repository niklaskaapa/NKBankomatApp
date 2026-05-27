<?php

function getDB(): PDO {
    static $pdo = null;         // static så att PDO‑anslutningen bara skapas en gång. tar mindre resurser.

    if ($pdo === null) {
        $pdo = new PDO("sqlite:" . __DIR__ . "/../database.sqlite");

        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $pdo->exec("PRAGMA foreign_keys = ON;");

        
    }

    return $pdo;
}
?>