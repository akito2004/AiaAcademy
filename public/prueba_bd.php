<?php
$config = require __DIR__ . '/../app/Config/config.php';

$host = $config['database']['host'];
$dbname = $config['database']['dbname'];
$user = $config['database']['user'];
$pass = $config['database']['password'];

echo "🔍 Servidor: $host<br>";
echo "📚 Base: $dbname<br>";
echo "👤 Usuario: $user<br><br>";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    echo "✅ <strong>¡CONECTADO!</strong> Base: $dbname";
} catch (PDOException $e) {
    echo "❌ ERROR: " . $e->getMessage();
}
