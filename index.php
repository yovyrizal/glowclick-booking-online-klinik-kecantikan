<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>GlowClick — Beauty Clinic Booking</title>

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;1,300;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>

  <!-- Main CSS -->
  <link rel="stylesheet" href="assets/style/style.css" />
</head>
<body>

  <!-- ===== NAVBAR ===== -->
  <nav id="navbar">
    <a href="#" class="nav-logo">Glow<span>Click</span></a>
    <ul class="nav-links" id="navLinks">
      <li><a href="#beranda">Beranda</a></li>
      <li><a href="#tentang">Tentang</a></li>
      <li><a href="#layanan">Layanan</a></li>
      <li><a href="#testimoni">Testimoni</a></li>
      <li><a href="#kontak">Kontak</a></li>
    </ul>
    <div class="nav-actions" id="navActions">
      <a href="app/pages/login.php"    class="btn-outline">Login</a>
      <a href="app/pages/register.php" class="btn-solid">Sign Up</a>
    </div>
    <button class="hamburger" id="hamburger" onclick="toggleMenu()" aria-label="Menu">
      <span></span><span></span><span></span>
    </button>
  </nav>

  <!-- ===== HERO ===== -->
  <section class="hero" id="beranda">
    <div class="hero-content">
      <p class="hero-eyebrow">Beauty Clinic Booking</p>
      <h1 class="hero-title">
        Revitalize Your Skin,<br>
        <em>Elevate</em> Your Glow
      </h1>
      <p class="hero-desc">
        Rediscover your natural beauty with our signature clinical treatments.
        Experience expert care and seamless scheduling designed for your modern lifestyle.
      </p>
      <a href="app/pages/login.php" class="btn-cta">
        Schedule a Visit <i class="fa-solid fa-arrow-right"></i>
      </a>
    </div>
    <div class="hero-image">
      <img
        src="assets/img/hero-img.webp"
        alt="Beauty Treatment"
      />
      <div class="hero-image-overlay">
        <div class="ov-icon"><i class="fa-solid fa-star"></i></div>
        <div class="ov-text">
          <span>Rating Klien</span>
          <strong>4.9 / 5 — 1,200+ Review</strong>
        </div>
      </div>
    </div>
  </section>

  <!-- ===== BADGES STRIP ===== -->
  <div class="badges">
    <div class="badge-item reveal">
      <div class="badge-icon"><i class="fa-solid fa-gift"></i></div>
      <div class="badge-text">
        <strong>Free Consultation</strong>
        <span>Konsultasi pertama gratis</span>
      </div>
    </div>
    <div class="badge-item reveal reveal-delay-2">
      <div class="badge-icon"><i class="fa-solid fa-tag"></i></div>
      <div class="badge-text">
        <strong>Special Price</strong>
        <span>Diskon 20% untuk member baru</span>
      </div>
    </div>
    <div class="badge-item reveal reveal-delay-4">
      <div class="badge-icon"><i class="fa-solid fa-user-doctor"></i></div>
      <div class="badge-text">
        <strong>Certified Professionals</strong>
        <span>Tenaga ahli berpengalaman</span>
      </div>
    </div>
  </div>

  <!-- ===== ABOUT ===== -->
  <section class="about" id="tentang">
    <div class="about-images reveal">
      <div class="about-img-main">
        <img src="https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?w=700&q=80&fit=crop" alt="Clinic Interior" />
      </div>
      <div class="about-img-accent">
        <img src="https://images.unsplash.com/photo-1616394584738-fc6e612e71b9?w=500&q=80&fit=crop" alt="Treatment" />
      </div>
    </div>
    <div class="about-content">
      <p class="section-label reveal">Tentang Kami</p>
      <h2 class="section-title reveal reveal-delay-1">Your Treatments,<br><em>Your Space</em></h2>
      <div class="about-divider reveal reveal-delay-2"></div>
      <p class="section-sub reveal reveal-delay-2">
        GlowClick hadir sebagai solusi booking kecantikan yang mudah, cepat, dan terpercaya.
        Kami menghubungkan Anda dengan para profesional kecantikan terbaik — tanpa antri,
        tanpa ribet, langsung dari genggaman Anda.
      </p>
      <div class="about-stats reveal reveal-delay-3">
        <div class="stat-item">
          <div class="stat-num">5<span>K+</span></div>
          <div class="stat-label">Klien Puas</div>
        </div>
        <div class="stat-item">
          <div class="stat-num">7<span>+</span></div>
          <div class="stat-label">Layanan</div>
        </div>
        <div class="stat-item">
          <div class="stat-num">3<span>yr</span></div>
          <div class="stat-label">Pengalaman</div>
        </div>
      </div>
    </div>
  </section>

  <!-- ===== SERVICES ===== -->
  <section class="services" id="layanan">
    <div class="services-header">
      <p class="section-label reveal">Layanan Kami</p>
      <h2 class="section-title reveal reveal-delay-1">Temukan Treatment<br><em>Terbaik Untuk Anda</em></h2>
      <p class="section-sub reveal reveal-delay-2">Pilih dari berbagai perawatan klinis yang dirancang khusus untuk memenuhi kebutuhan kulit Anda.</p>
    </div>
    <div class="services-grid">
      <div class="service-card reveal">
        <img src="assets/img/layanan-botox.webp" alt="Botox" />
        <div class="service-card-overlay">
          <span class="service-tag">Perawatan Wajah</span>
          <span class="service-name">Botox</span>
          <span class="service-arrow">Lihat Detail <i class="fa-solid fa-arrow-right fa-xs"></i></span>
        </div>
      </div>
      <div class="service-card reveal reveal-delay-1">
        <img src="assets/img/layanan-skinbooster.webp" alt="Skin Booster" />
        <div class="service-card-overlay">
          <span class="service-tag">Hidrasi Kulit</span>
          <span class="service-name">Skin Booster</span>
          <span class="service-arrow">Lihat Detail <i class="fa-solid fa-arrow-right fa-xs"></i></span>
        </div>
      </div>
      <div class="service-card reveal reveal-delay-2">
        <img src="assets/img/layanan-filler.webp" alt="Filler" />
        <div class="service-card-overlay">
          <span class="service-tag">Estetika</span>
          <span class="service-name">Filler</span>
          <span class="service-arrow">Lihat Detail <i class="fa-solid fa-arrow-right fa-xs"></i></span>
        </div>
      </div>
      <div class="service-card reveal reveal-delay-3">
        <img src="assets/img/layanan-exilis.webp" alt="Exilis" />
        <div class="service-card-overlay">
          <span class="service-tag">Body Contouring</span>
          <span class="service-name">Exilis</span>
          <span class="service-arrow">Lihat Detail <i class="fa-solid fa-arrow-right fa-xs"></i></span>
        </div>
      </div>
      <div class="service-card reveal reveal-delay-1">
        <img src="assets/img/layanan-facial.webp" alt="Facial" />
        <div class="service-card-overlay">
          <span class="service-tag">Perawatan Dasar</span>
          <span class="service-name">Facial</span>
          <span class="service-arrow">Lihat Detail <i class="fa-solid fa-arrow-right fa-xs"></i></span>
        </div>
      </div>
      <div class="service-card reveal reveal-delay-2">
        <img src="assets/img/layanan-laser.webp" alt="Laser" />
        <div class="service-card-overlay">
          <span class="service-tag">Teknologi Terkini</span>
          <span class="service-name">Laser</span>
          <span class="service-arrow">Lihat Detail <i class="fa-solid fa-arrow-right fa-xs"></i></span>
        </div>
      </div>
      <div class="service-card reveal reveal-delay-3">
        <img src="assets/img/layanan-acneremoval.webp" alt="Acne Removal" />
        <div class="service-card-overlay">
          <span class="service-tag">Kulit Bermasalah</span>
          <span class="service-name">Acne Removal</span>
          <span class="service-arrow">Lihat Detail <i class="fa-solid fa-arrow-right fa-xs"></i></span>
        </div>
      </div>
    </div>
  </section>

  <!-- ===== TESTIMONIALS ===== -->
  <section class="testimonials" id="testimoni">
    <div class="testimonials-header">
      <p class="section-label reveal">Testimoni</p>
      <h2 class="section-title reveal reveal-delay-1">Apa Kata<br><em>Klien Kami</em></h2>
      <p class="section-sub reveal reveal-delay-2">Ribuan pelanggan telah mempercayakan perawatan kulit mereka kepada GlowClick.</p>
    </div>

    <!-- Grid Testimoni — diisi via JS (fetch dari fetch_testimoni.php) -->
    <div class="testi-grid" id="testiGrid">
      <div class="testi-loading">
        <i class="fa-solid fa-spinner"></i>
        Memuat testimoni...
      </div>
    </div>

    <!-- Form Tambah Testimoni -->
    <div class="testi-form-wrap reveal">
      <h3>Bagikan <em>Pengalaman Anda</em></h3>
      <p>Ceritakan hasil perawatan Anda kepada ribuan calon klien GlowClick lainnya.</p>

      <div class="form-alert" id="formAlert">
        <i class="fa-solid fa-circle-check"></i>
        <span id="formAlertMsg"></span>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label for="tNama">Nama Lengkap <span style="color:var(--gold-deep)">*</span></label>
          <input type="text" id="tNama" name="nama" placeholder="Contoh: Sari Dewi" maxlength="100" required />
        </div>
        <div class="form-group">
          <label for="tKota">Kota <span style="color:var(--gold-deep)">*</span></label>
          <input type="text" id="tKota" name="kota" placeholder="Contoh: Yogyakarta" maxlength="100" required />
        </div>
      </div>

      <div class="form-group">
        <label>Rating <span style="color:var(--gold-deep)">*</span></label>
        <div class="star-rating" id="starRating">
          <input type="radio" name="rating" id="star5" value="5" checked />
          <label for="star5" title="5 bintang"><i class="fa-solid fa-star"></i></label>
          <input type="radio" name="rating" id="star4" value="4" />
          <label for="star4" title="4 bintang"><i class="fa-solid fa-star"></i></label>
          <input type="radio" name="rating" id="star3" value="3" />
          <label for="star3" title="3 bintang"><i class="fa-solid fa-star"></i></label>
          <input type="radio" name="rating" id="star2" value="2" />
          <label for="star2" title="2 bintang"><i class="fa-solid fa-star"></i></label>
          <input type="radio" name="rating" id="star1" value="1" />
          <label for="star1" title="1 bintang"><i class="fa-solid fa-star"></i></label>
        </div>
      </div>

      <div class="form-group">
        <label for="tPesan">Testimoni <span style="color:var(--gold-deep)">*</span></label>
        <textarea id="tPesan" name="pesan" placeholder="Ceritakan pengalaman Anda menggunakan layanan GlowClick..." maxlength="1000" required></textarea>
        <small style="color:var(--gray-soft);font-size:0.72rem;margin-top:0.25rem;" id="charCount">0 / 1000 karakter</small>
      </div>

      <button class="btn-submit" id="btnSubmit" onclick="submitTestimoni()">
        <i class="fa-solid fa-paper-plane"></i>
        Kirim Testimoni
      </button>
    </div>
  </section>

  <!-- ===== CTA BANNER ===== -->
  <div class="cta-banner" id="kontak">
    <div class="cta-banner-text reveal">
      <p class="label">Mulai Sekarang</p>
      <h2>Siap untuk <em>Tampil Lebih Glowing?</em></h2>
    </div>
    <a href="app/pages/login.php" class="btn-cta-gold reveal reveal-delay-2">
      <i class="fa-solid fa-calendar-check"></i>
      Book Sekarang
    </a>
  </div>

  <!-- ===== FOOTER ===== -->
  <footer>
    <div class="footer-grid">
      <div class="footer-brand">
        <div class="footer-logo">Glow<span>Click</span></div>
        <p>Platform booking kecantikan online terpercaya. Temukan treatment terbaik dan jadwalkan kunjungan Anda dengan mudah kapan saja.</p>
        <div class="footer-socials">
          <a href="#" class="social-btn"><i class="fa-brands fa-instagram"></i></a>
          <a href="#" class="social-btn"><i class="fa-brands fa-tiktok"></i></a>
          <a href="#" class="social-btn"><i class="fa-brands fa-whatsapp"></i></a>
          <a href="#" class="social-btn"><i class="fa-brands fa-facebook-f"></i></a>
        </div>
      </div>
      <div class="footer-col">
        <h4>Navigasi</h4>
        <ul>
          <li><a href="#beranda">Beranda</a></li>
          <li><a href="#tentang">Tentang Kami</a></li>
          <li><a href="#layanan">Layanan</a></li>
          <li><a href="#testimoni">Testimoni</a></li>
        </ul>
      </div>
      <div class="footer-col">
        <h4>Layanan</h4>
        <ul>
          <li><a href="#">Botox</a></li>
          <li><a href="#">Skin Booster</a></li>
          <li><a href="#">Filler</a></li>
          <li><a href="#">Exilis</a></li>
          <li><a href="#">Facial</a></li>
          <li><a href="#">Laser</a></li>
          <li><a href="#">Acne Removal</a></li>
        </ul>
      </div>
      <div class="footer-col">
        <h4>Kontak</h4>
        <div class="footer-contact-item">
          <i class="fa-solid fa-location-dot"></i>
          <span>Jl. Kecantikan No. 10,<br>Yogyakarta, Indonesia</span>
        </div>
        <div class="footer-contact-item">
          <i class="fa-solid fa-phone"></i>
          <span>+62 812 3456 7890</span>
        </div>
        <div class="footer-contact-item">
          <i class="fa-solid fa-envelope"></i>
          <span>hello@glowclick.id</span>
        </div>
        <div class="footer-contact-item">
          <i class="fa-solid fa-clock"></i>
          <span>Senin – Sabtu, 09:00 – 20:00</span>
        </div>
      </div>
    </div>
    <div class="footer-bottom">
      <p>&copy; <?= date('Y') ?> GlowClick. All rights reserved.</p>
      <div class="footer-bottom-links">
        <a href="#">Kebijakan Privasi</a>
        <a href="#">Syarat & Ketentuan</a>
      </div>
    </div>
  </footer>

  <!-- ===== JAVASCRIPT ===== -->
  <script>
    // ── Hamburger menu ──────────────────────────────
    function toggleMenu() {
      document.getElementById('navLinks').classList.toggle('open');
      document.getElementById('navActions').classList.toggle('open');
    }

    // ── Navbar shadow on scroll ──────────────────────
    window.addEventListener('scroll', () => {
      document.getElementById('navbar').style.boxShadow =
        window.scrollY > 10 ? '0 2px 20px rgba(0,0,0,0.06)' : 'none';
    });

    // ── Scroll reveal ────────────────────────────────
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(e => {
        if (e.isIntersecting) { e.target.classList.add('visible'); observer.unobserve(e.target); }
      });
    }, { threshold: 0.12 });
    document.querySelectorAll('.reveal').forEach(r => observer.observe(r));

    // ── Char counter ─────────────────────────────────
    document.getElementById('tPesan').addEventListener('input', function () {
      document.getElementById('charCount').textContent = this.value.length + ' / 1000 karakter';
    });

    // ── Render bintang ───────────────────────────────
    function renderStars(rating) {
      let html = '';
      for (let i = 1; i <= 5; i++) {
        if (i <= rating) {
          html += '<i class="fa-solid fa-star"></i>';
        } else {
          html += '<i class="fa-regular fa-star"></i>';
        }
      }
      return html;
    }

    // ── Render satu card testimoni ───────────────────
    function renderCard(t) {
      return `
        <div class="testi-card">
          <div class="testi-stars">${renderStars(t.rating)}</div>
          <p class="testi-text">${escHtml(t.pesan)}</p>
          <div class="testi-author">
            <div class="testi-avatar">
              <img src="${t.foto_url}" alt="${escHtml(t.nama)}" onerror="this.src='https://ui-avatars.com/api/?name=${encodeURIComponent(t.nama)}&background=E8DBB3&color=000&size=100'" />
            </div>
            <div>
              <div class="testi-name">${escHtml(t.nama)}</div>
              <div class="testi-role">Klien · ${escHtml(t.kota)}</div>
            </div>
          </div>
        </div>`;
    }

    // ── Escape HTML (XSS protection) ─────────────────
    function escHtml(str) {
      return String(str)
        .replace(/&/g,'&amp;').replace(/</g,'&lt;')
        .replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    // ── Load testimoni dari server ───────────────────
    function loadTestimoni() {
      const grid = document.getElementById('testiGrid');
      grid.innerHTML = '<div class="testi-loading"><i class="fa-solid fa-spinner"></i>Memuat testimoni...</div>';

      fetch('app/auth/fetch_testimoni.php')
        .then(r => r.json())
        .then(res => {
          if (!res.success) throw new Error(res.message);
          if (res.data.length === 0) {
            grid.innerHTML = '<div class="testi-empty"><i class="fa-regular fa-comment-dots"></i>Belum ada testimoni. Jadilah yang pertama!</div>';
            return;
          }
          grid.innerHTML = res.data.map(renderCard).join('');
        })
        .catch(err => {
          grid.innerHTML = `<div class="testi-empty"><i class="fa-solid fa-triangle-exclamation"></i>Gagal memuat testimoni.<br><small>${err.message}</small></div>`;
        });
    }

    // ── Submit testimoni ─────────────────────────────
    function submitTestimoni() {
      const nama   = document.getElementById('tNama').value.trim();
      const kota   = document.getElementById('tKota').value.trim();
      const pesan  = document.getElementById('tPesan').value.trim();
      const rating = document.querySelector('input[name="rating"]:checked')?.value || 5;
      const btn    = document.getElementById('btnSubmit');
      const alert  = document.getElementById('formAlert');
      const alertMsg = document.getElementById('formAlertMsg');

      // Reset alert
      alert.className = 'form-alert';

      // Validasi client-side
      if (!nama || !kota || !pesan) {
        alertMsg.textContent = 'Semua field wajib diisi.';
        alert.className = 'form-alert error';
        return;
      }

      // Disable tombol & show loading
      btn.disabled = true;
      btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Mengirim...';

      const formData = new FormData();
      formData.append('nama',   nama);
      formData.append('kota',   kota);
      formData.append('pesan',  pesan);
      formData.append('rating', rating);

      fetch('app/auth/submit_testimoni.php', { method: 'POST', body: formData })
        .then(r => r.json())
        .then(res => {
          if (res.success) {
            // Tampilkan pesan sukses
            alertMsg.textContent = res.message;
            alert.className = 'form-alert success';
            alert.querySelector('i').className = 'fa-solid fa-circle-check';

            // Reset form
            document.getElementById('tNama').value  = '';
            document.getElementById('tKota').value  = '';
            document.getElementById('tPesan').value = '';
            document.getElementById('charCount').textContent = '0 / 1000 karakter';
            document.getElementById('star5').checked = true;

            // Reload grid testimoni
            loadTestimoni();
          } else {
            alertMsg.textContent = res.message || 'Gagal mengirim testimoni.';
            alert.className = 'form-alert error';
            alert.querySelector('i').className = 'fa-solid fa-circle-xmark';
          }
        })
        .catch(() => {
          alertMsg.textContent = 'Terjadi kesalahan jaringan. Coba lagi.';
          alert.className = 'form-alert error';
          alert.querySelector('i').className = 'fa-solid fa-circle-xmark';
        })
        .finally(() => {
          btn.disabled = false;
          btn.innerHTML = '<i class="fa-solid fa-paper-plane"></i> Kirim Testimoni';
        });
    }

    // ── Init ─────────────────────────────────────────
    loadTestimoni();
  </script>

</body>
</html>
