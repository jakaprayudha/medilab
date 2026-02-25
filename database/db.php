<?php
// ===========================================
// Load ENV variabel dari file .env
// ===========================================
$envPath = __DIR__ . '/../.env';
if (!file_exists($envPath)) {
   die('.env file not found');
}

// baca isi .env
$envContent = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
foreach ($envContent as $line) {
   // abaikan baris komentar
   if (strpos(trim($line), '#') === 0) {
      continue;
   }

   list($key, $value) = explode('=', $line, 2);
   $key = trim($key);
   $value = trim($value);

   $_ENV[$key] = $value;
}

// ===========================================
// Ambil credential database dari .env
// ===========================================
$host = $_ENV['DB_HOST'] ?? 'localhost';
$port = $_ENV['DB_PORT'] ?? '3306';
$name = $_ENV['DB_NAME'] ?? '';
$user = $_ENV['DB_USER'] ?? '';
$pass = $_ENV['DB_PASS'] ?? '';

// ===========================================
// Koneksi mysqli
// ===========================================
$conn = mysqli_connect($host, $user, $pass, $name, $port);

// cek koneksi
if (mysqli_connect_errno()) {
   die("Database connection failed: " . mysqli_connect_error());
}

// opsional: set charset
mysqli_set_charset($conn, "utf8mb4");

// echo "Database connected"; // untuk test