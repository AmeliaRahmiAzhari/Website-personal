<?php
session_start();
include 'header.php';

// Redirect jika user belum login
if (!isset($_SESSION['user_id'])) {
    header("Location: login_user.php");
    exit;
}
?>

<!-- Hero Section -->
<section class="hero text-center text-white">
  <div class="container">
    <!-- Pesan selamat datang -->
    <?php if (isset($_SESSION['user_nama'])): ?>
      <p class="text-end text-white me-3">👋 Halo, <strong><?= htmlspecialchars($_SESSION['user_nama']); ?></strong></p>
    <?php endif; ?>

    <h1 class="display-4 fw-bold">Selamat Datang di Surga Kuliner Nusantara</h1>
    <p class="lead">Jelajahi kekayaan rasa dari Sabang hingga Merauke</p>
    <a href="#unggulan" class="btn btn-warning btn-lg mt-3">Lihat Makanan Unggulan</a>
  </div>
</section>

<!-- Kategori Makanan -->
<section class="py-5" id="kategori">
  <div class="container text-center">
    <h2 class="mb-4">Kategori Makanan</h2>
    <div class="row g-4">
      <?php
        $kategori = [
          ["🍛", "Makanan Tradisional", "Nasi Padang, Gudeg, Nasi Liwet", "tradisional"],
          ["🍢", "Jajanan", "Siomay, Batagor, Lumpia", "jajanan"],
          ["🍰", "Kue Tradisional", "Klepon, Lapis Legit, Kue Putu", "kue"],
          ["🥤", "Minuman Khas", "Cendol, Dawet, Wedang Jahe", "minuman"],
          ["🎁", "Souvenir", "Keripik, Dodol, Oleh-oleh daerah", "souvenir"]
        ];
        foreach ($kategori as $k) {
          echo '
          <div class="col-md-3">
            <a href="?kategori='.$k[3].'#unggulan" class="text-decoration-none text-dark">
              <div class="p-4 border rounded bg-light h-100">
                <div class="category-icon mb-2 fs-1">'.$k[0].'</div>
                <h5>'.$k[1].'</h5>
                <p>'.$k[2].'</p>
              </div>
            </a>
          </div>';
        }
      ?>
    </div>
  </div>
</section>

<!-- Produk Unggulan -->
<section class="py-5 bg-light" id="unggulan">
  <div class="container">
    <h2 class="text-center mb-4">Makanan Unggulan</h2>
    <div class="row g-4">
      <?php
        $filter_kategori = $_GET['kategori'] ?? 'all';
        $produk = [
          ["id" => 1, "nama" => "Rendang", "kategori" => "tradisional", "deskripsi" => "Rendang Padang, makanan terenak di dunia versi CNN!", "gambar" => "Rendang.jpeg", "harga" => 50000],
          ["id" => 2, "nama" => "Sate Madura", "kategori" => "tradisional", "deskripsi" => "Daging empuk dengan bumbu kacang khas Madura yang menggoda.", "gambar" => "sate.jpeg", "harga" => 30000],
          ["id" => 3, "nama" => "Gudeg Jogja", "kategori" => "tradisional", "deskripsi" => "Manis, gurih, dan penuh nostalgia dari Kota Gudeg.", "gambar" => "gudeg.jpeg", "harga" => 25000],
          ["id" => 4, "nama" => "ES Cendol", "kategori" => "minuman", "deskripsi" => "Manis, segar...", "gambar" => "es cendol.jpg", "harga" => 20000],
          ["id" => 5, "nama" => "ES Bongkol", "kategori" => "minuman", "deskripsi" => "Segar, manis...", "gambar" => "es bongko.jpg", "harga" => 15000],
          ["id" => 6, "nama" => "Teh Telur", "kategori" => "minuman", "deskripsi" => "Unik dan kaya...", "gambar" => "teh talua.jpg", "harga" => 30000],
          ["id" => 7, "nama" => "Biji Salak", "kategori" => "jajanan", "deskripsi" => "Manis legit...", "gambar" => "biji salak.jpg", "harga" => 35000],
          ["id" => 8, "nama" => "Kue Putu", "kategori" => "kue", "deskripsi" => "Manis dan harum...", "gambar" => "kue putu.jpg", "harga" => 12000],
          ["id" => 9, "nama" => "Lupis", "kategori" => "kue", "deskripsi" => "Manis gurih...", "gambar" => "lupis.jpg", "harga" => 10000],
          ["id" => 10, "nama" => "Keripik Balado", "kategori" => "souvenir", "deskripsi" => "Gurih dan pedas...", "gambar" => "keripik.jpeg", "harga" => 25000],
          ["id" => 11, "nama" => "Dodol Garut", "kategori" => "souvenir", "deskripsi" => "Manis dan legit...", "gambar" => "dodol.jpg", "harga" => 30000]
        ];
        foreach ($produk as $p) {
          if ($filter_kategori !== 'all' && $p["kategori"] !== $filter_kategori) continue;

          echo '
            <div class="col-md-4">
              <div class="card h-100">
                <div class="overflow-hidden" style="height: 200px;">
                  <img src="gambar/'.$p["gambar"].'" class="card-img-top img-fluid" alt="'.$p["nama"].'" style="object-fit: cover; height: 100%; width: 100%;">
                </div>
                <div class="card-body">
                  <h5 class="card-title">'.$p["nama"].'</h5>
                  <p class="card-text">'.$p["deskripsi"].'</p>
                  <p class="text-danger fw-bold">Rp '.number_format($p["harga"],0,",",".").'</p>
                  <form method="post" action="keranjang.php">
                    <input type="hidden" name="id" value="'.$p["id"].'">
                    <input type="hidden" name="nama" value="'.$p["nama"].'">
                    <input type="hidden" name="harga" value="'.$p["harga"].'">
                    <input type="hidden" name="jumlah" value="1">
                    <button type="submit" class="btn btn-warning">Tambah ke Keranjang</button>
                  </form>
                </div>
              </div>
            </div>';
        }

      ?>
    </div>
  </div>
</section>

<!-- Tentang Kami -->
<section class="py-5" id="tentang">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-md-6 mb-4 mb-md-0">
        <img src="gambar/3.jpeg" alt="Tentang Kami" class="img-fluid rounded">
      </div>
      <div class="col-md-6">
        <h2>Tentang Kami</h2>
        <p>Kami adalah platform yang didedikasikan untuk memperkenalkan dan mempromosikan kuliner tradisional Indonesia ke seluruh penjuru dunia. Dengan semangat cinta budaya dan cita rasa autentik, kami menghadirkan informasi, rekomendasi, dan penjualan makanan khas Nusantara secara online.</p>
      </div>
    </div>
  </div>
</section>

<!-- Live Chat -->
<section class="py-5 bg-warning-subtle" id="livechat">
  <div class="container">
    <h2 class="text-center mb-4">💬 Live Chat Pengunjung</h2>
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div id="chat-box" style="background:white; padding:15px; height:300px; overflow-y:auto; border:1px solid #ccc; margin-bottom: 15px;"></div>

        <form id="chat-form" class="d-flex flex-column flex-md-row gap-2">
          <input type="text" name="username" id="username" class="form-control" placeholder="Nama Anda" required>
          <input type="text" name="message" id="message" class="form-control" placeholder="Pesan Anda" required>
          <button type="submit" class="btn btn-dark">Kirim</button>
        </form>
      </div>
    </div>
  </div>
</section>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  function loadChat() {
    $.get('fetch_chat.php', function(data) {
      $('#chat-box').html(data);
      $('#chat-box').scrollTop($('#chat-box')[0].scrollHeight);
    });
  }

  $('#chat-form').on('submit', function(e) {
    e.preventDefault();
    $.post('chat.php', {
      username: $('#username').val(),
      message: $('#message').val()
    }, function() {
      $('#message').val('');
      loadChat();
    });
  });

  setInterval(loadChat, 3000);
  loadChat();
</script>

<!-- Testimoni -->
<section class="py-5 bg-light">
  <div class="container text-center">
    <h2 class="mb-4">Apa Kata Mereka?</h2>
    <div class="row g-4">
      <?php
        $testimoni = [
          ["“Saya rindu kampung halaman, tapi makanan di sini mengobati rasa itu!”", "Rina, Jakarta"],
          ["“Website ini punya koleksi makanan tradisional terlengkap!”", "Budi, Surabaya"],
          ["“Rendang dan gudegnya enak banget! Seperti masakan ibu.”", "Maya, Bandung"]
        ];
        foreach ($testimoni as $t) {
          echo '
          <div class="col-md-4">
            <div class="p-4 border rounded bg-white testimonial">
              <p>'.$t[0].'</p>
              <p class="fw-bold mb-0">— '.$t[1].'</p>
            </div>
          </div>';
        }
      ?>
    </div>
  </div>
</section>

<?php include 'footer.php'; ?>
