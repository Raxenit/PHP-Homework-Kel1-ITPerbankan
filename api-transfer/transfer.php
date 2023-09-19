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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['from_account']) && isset($data['to_account']) && isset($data['amount'])) {
        $from_account = $data['from_account'];
        $to_account = $data['to_account'];
        $amount = $data['amount'];

        // Memeriksa saldo akun pengirim
        $sql = "SELECT balance FROM accounts WHERE account_number = '$from_account'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $current_balance = $row['balance'];

            if ($current_balance >= $amount && $amount > 0) {
                // Melakukan transfer
                $new_balance_from = $current_balance - $amount;
                $new_balance_to = $amount;

                // Menyimpan transaksi
                $sql = "INSERT INTO transactions (from_account_number, to_account_number, amount) VALUES ('$from_account', '$to_account', $amount)";
                $conn->query($sql);

                // Memperbarui saldo akun
                $sql = "UPDATE accounts SET balance = $new_balance_from WHERE account_number = '$from_account'";
                $conn->query($sql);

                $sql = "UPDATE accounts SET balance = balance + $new_balance_to WHERE account_number = '$to_account'";
                $conn->query($sql);

                echo json_encode(['message' => 'Transfer berhasil']);
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'Transfer gagal']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Akun pengirim tidak ditemukan']);
        }
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Data tidak lengkap']);
    }
}

// Menutup koneksi ke database
$conn->close();
?>