<?php

class Conexion {
    private $pdo;

    public function __construct() {
        $config = require __DIR__ . '/../config/database.php';

        $dsn = "mysql:host={$config['host']};dbname={$config['db']};charset={$config['charset']}";

        $opciones = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $config['user'], $config['pass'], $opciones);
        } catch (PDOException $e) {
            error_log('Error de conexión: ' . $e->getMessage());
            die('Error al conectar a la Base de Datos: ' . $e->getMessage());
        }
    }

    public function getConexion() {
        return $this->pdo;
    }
}

