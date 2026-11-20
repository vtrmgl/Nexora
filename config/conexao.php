<?php 
    /**
     * Configuração e conexão com o banco de dados Nexora via PDO.
     */

    $host     = 'localhost';
    $dbname   = 'nexora';
    $username = 'root';
    $password = '';

    try {
        $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
        
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        $pdo = new PDO($dsn, $username, $password, $options);
    } catch (PDOException $e) {
        error_log('Erro de conexão com o banco: ' . $e->getMessage());
        die('Não foi possível conectar ao sistema. Tente novamente mais tarde.');
    }