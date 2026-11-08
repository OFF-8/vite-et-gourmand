<?php
$pdo = new PDO(
    'mysql:host=localhost;dbname=vite_et_gourmand;charset=utf8mb4',
    'root',
    ''
);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);