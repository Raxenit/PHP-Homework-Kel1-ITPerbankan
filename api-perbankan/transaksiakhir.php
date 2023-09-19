<?php
$servername = "localhost";
$username = "root"; 
$password = ""; 
$database = "db_transfer"; 
$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Koneksi ke database gagal: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['account_number'])) {
        $account_number = $_GET['account_number'];

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

$conn->close();
?>
