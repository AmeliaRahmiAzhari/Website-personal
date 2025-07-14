<?php 
include 'db.php';

// Hapus pesan yang lebih dari 1 jam
$conn->query("DELETE FROM chat WHERE timestamp < NOW() - INTERVAL 1 HOUR");
$result = $conn->query("SELECT * FROM chat ORDER BY timestamp ASC");

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $username = htmlspecialchars($row['username']);
        $message = htmlspecialchars($row['message']);
        $role = $row['role'];

        if ($role == 'admin') {
            // Tampilan balasan dari admin
            echo '
                <div class="text-end mb-2">
                    <div class="d-inline-block p-2 rounded bg-warning text-dark">
                        <strong>' . $username . ':</strong> ' . $message . '
                    </div>
                </div>
            ';
        } else {
            // Tampilan dari user
            echo '
                <div class="text-start mb-2">
                    <div class="d-inline-block p-2 rounded bg-light text-dark border">
                        <strong>' . $username . ':</strong> ' . $message . '
                    </div>
                </div>
            ';
        }
    }
} else {
    echo '<div class="text-center text-muted">Belum ada pesan.</div>';
}
?>
