<?php
try {
    $host = '127.0.0.1';
    $db   = 'RezerVist';
    $user = 'root';
    $pass = '';
    $charset = 'utf8mb4';

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    $pdo = new PDO("mysql:host=$host;charset=$charset", $user, $pass, $options);
    $pdo->exec("DROP DATABASE IF EXISTS RezerVist");
    $pdo->exec("CREATE DATABASE RezerVist");
    $pdo->exec("USE RezerVist");
    
    echo "Veritabanı sıfırlandı. İçeri aktarma başlatılıyor...\n";

    $sql = file_get_contents('rezervist.sql');
    $pdo->exec($sql);
    
    echo "Veritabanı başarıyla içe aktarıldı!\n";

} catch (\PDOException $e) {
     echo "HATA: " . $e->getMessage() . "\n";
     exit(1);
}
