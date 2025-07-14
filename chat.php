<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = htmlspecialchars($_POST['username']);
    $message = htmlspecialchars($_POST['message']);
    $role = 'user';

    // Simpan pesan dari user
    $stmt = $conn->prepare("INSERT INTO chat (username, message, role) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $message, $role);
    $stmt->execute();

    // Logika balasan otomatis
    $reply = '';
    $admin_name = "Admin";
    $admin_role = "admin";

    // Daftar respon umum
    $responses = [
        'halo' => "Halo juga, $username! Ada yang bisa kami bantu?",
        'hai' => "Hai $username! Senang bisa membantu.",
        'minuman' => "Kami rekomendasikan: Es Cendol, Teh Talua, Es Bongko.",
        'makanan' => "Coba Rendang, Sate Madura, atau Gudeg Jogja.",
        'rekomendasi' => "Rekomendasi kami: Nasi Liwet, Klepon, dan Keripik Balado.",
        'rendang' => "Rendang adalah makanan khas Padang yang mendunia!",
        'sate' => "Sate Madura dan Sate Padang favorit banyak orang!",
        'gudeg' => "Gudeg Jogja terkenal dengan rasa manisnya.",
        'keripik' => "Keripik Balado sangat cocok jadi oleh-oleh.",
        'dodol' => "Dodol Garut adalah camilan manis khas Jawa Barat.",
        'kue' => "Kue tradisional seperti Putu dan Klepon tersedia.",
        'teh' => "Teh Talua adalah teh dengan telur khas Minang.",
        'oleh-oleh' => "Coba Dodol Garut, Keripik Balado, dan Kue Lapis.",
        'asal' => "Kami berbasis di seluruh Nusantara!",
        'buka' => "Kami buka 24 jam online!",
        'pesan' => "Silakan tambahkan ke keranjang dan checkout.",
        'harga' => "Harga bervariasi, silakan cek di masing-masing produk.",
        'diskon' => "Saat ini belum ada diskon, tapi pantau terus ya!",
        'promo' => "Promo akan diumumkan di halaman utama.",
        'alamat' => "Kami hanya melayani online saat ini.",
        'bayar' => "Metode pembayaran: Transfer bank, e-wallet, dan COD.",
        'menu' => "Menu lengkap bisa kamu lihat di halaman utama.",
        'favorit' => "Rendang, Gudeg, dan Cendol adalah yang paling favorit.",
        'enak' => "Kami pastikan semua produk berkualitas dan lezat.",
        'buka jam berapa' => "Kami selalu online 24/7 😊",
        'paket' => "Kami juga sediakan paket oleh-oleh loh!",
        'nasi liwet' => "Nasi Liwet berasal dari Solo, rasanya gurih dan lezat.",
        'klepon' => "Klepon berisi gula merah dan dibalut parutan kelapa.",
        'lupis' => "Lupis adalah kue beras ketan dengan kelapa dan gula merah.",
        'batagor' => "Batagor berasal dari Bandung dan cocok sebagai camilan.",
        'siomay' => "Siomay cocok disantap dengan sambal kacang.",
        'lumpia' => "Lumpia khas Semarang berisi rebung dan ayam.",
        'wedang' => "Wedang Jahe cocok untuk menghangatkan tubuh.",
    ];

    // Cek dan cari balasan otomatis dari kosakata yang cocok
    foreach ($responses as $keyword => $res) {
        if (stripos($message, $keyword) !== false) {
            $reply = $res;
            break;
        }
    }

    // Jika belum ada balasan, generate default berdasarkan pola
    if (!$reply) {
        // Buat 100 kata kunci otomatis: "kata1", "kata2", ..., "kata100"
        for ($i = 1; $i <= 100; $i++) {
            if (stripos($message, "kata$i") !== false) {
                $reply = "Ini adalah respon otomatis ke-$i berdasarkan kata 'kata$i'.";
                break;
            }
        }
    }

    // Jika tetap tidak cocok, gunakan balasan default
    if (!$reply) {
        $reply = "Maaf, kami belum bisa memahami pesan Anda. Silakan tanyakan tentang makanan, minuman, atau rekomendasi kuliner.";
    }

    // Simpan balasan admin
    $stmt = $conn->prepare("INSERT INTO chat (username, message, role) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $admin_name, $reply, $admin_role);
    $stmt->execute();
}
?>
