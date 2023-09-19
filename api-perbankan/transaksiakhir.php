<?php
// Konfigurasi koneksi database
$servername = "localhost";
$username = "root"; // Ganti dengan username MySQL Anda
$password = ""; // Ganti dengan password MySQL Anda
$database = "db_transfer"; // Ganti dengan nama database Anda

// Membuat koneksi ke database
$conn = new mysqli($servername, $username, $password, $database);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi ke database gagal: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['account_number'])) {
        $account_number = $_GET['account_number'];

        // Mengambil transaksi terakhir untuk akun tertentu
        $sql = "SELECT * FROM transactions WHERE from_account_number = '$account_number' OR to_account_number = '$account_number' ORDER BY transaction_date DESC LIMIT 10";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $transactions = [];
            while ($row = $result->fetch_assoc()) {
                $transactions[] = $row;
            }
            echo json_encode(['transactions' => $transactions]);
        } else {
            echo json_encode(['message' => 'Tidak ada transaksi ditemukan untuk akun ini']);
        }
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Parameter account_number tidak ditemukan']);
    }
}

// Menutup koneksi ke database
$conn->close();
?>
