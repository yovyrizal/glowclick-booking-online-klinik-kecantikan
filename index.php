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
  <link rel="stylesheet" href="./assets/style/style.css" />
</head>
<body>

  <!-- ===== NAVBAR ===== -->
  <nav id="navbar">
    <a href="#" class="nav-logo">Glow<span>Click</span></a>
    <ul class="nav-links" id="navLinks">
      <li><a href="#beranda">Beranda</a></li>
      <li><a href="#tentang">Tentang</a></li>
      <li><a href="#layanan">Layanan</a></li>
      <li><a href="#kontak">Kontak</a></li>
    </ul>
    <div class="nav-actions" id="navActions">
      <a href="login.php"    class="btn-outline">Login</a>
      <a href="register.php" class="btn-solid">Sign Up</a>
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
      <a href="login.php" class="btn-cta">
        Schedule a Visit <i class="fa-solid fa-arrow-right"></i>
      </a>
    </div>
    <div class="hero-image">
      <img
        src="https://images.unsplash.com/photo-1519125323398-675f0ddb6308?w=800&q=80&fit=crop&crop=face"
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
        <img src="https://images.unsplash.com/photo-1579734671973-f1e9c2d80a74?w=500&q=80&fit=crop" alt="Botox" />
        <div class="service-card-overlay">
          <span class="service-tag">Perawatan Wajah</span>
          <span class="service-name">Botox</span>
          <span class="service-arrow">Lihat Detail <i class="fa-solid fa-arrow-right fa-xs"></i></span>
        </div>
      </div>
      <div class="service-card reveal reveal-delay-1">
        <img src="https://images.unsplash.com/photo-1610992015732-2449b76344bc?w=500&q=80&fit=crop" alt="Skin Booster" />
        <div class="service-card-overlay">
          <span class="service-tag">Hidrasi Kulit</span>
          <span class="service-name">Skin Booster</span>
          <span class="service-arrow">Lihat Detail <i class="fa-solid fa-arrow-right fa-xs"></i></span>
        </div>
      </div>
      <div class="service-card reveal reveal-delay-2">
        <img src="https://images.unsplash.com/photo-1512290923902-8a9f81dc236c?w=500&q=80&fit=crop" alt="Filler" />
        <div class="service-card-overlay">
          <span class="service-tag">Estetika</span>
          <span class="service-name">Filler</span>
          <span class="service-arrow">Lihat Detail <i class="fa-solid fa-arrow-right fa-xs"></i></span>
        </div>
      </div>
      <div class="service-card reveal reveal-delay-3">
        <img src="https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?w=500&q=80&fit=crop" alt="Exilis" />
        <div class="service-card-overlay">
          <span class="service-tag">Body Contouring</span>
          <span class="service-name">Exilis</span>
          <span class="service-arrow">Lihat Detail <i class="fa-solid fa-arrow-right fa-xs"></i></span>
        </div>
      </div>
      <div class="service-card reveal reveal-delay-1">
        <img src="https://images.unsplash.com/photo-1616394584738-fc6e612e71b9?w=500&q=80&fit=crop" alt="Facial" />
        <div class="service-card-overlay">
          <span class="service-tag">Perawatan Dasar</span>
          <span class="service-name">Facial</span>
          <span class="service-arrow">Lihat Detail <i class="fa-solid fa-arrow-right fa-xs"></i></span>
        </div>
      </div>
      <div class="service-card reveal reveal-delay-2">
        <img src="https://images.unsplash.com/photo-1582750433449-648ed127bb54?w=500&q=80&fit=crop" alt="Laser" />
        <div class="service-card-overlay">
          <span class="service-tag">Teknologi Terkini</span>
          <span class="service-name">Laser</span>
          <span class="service-arrow">Lihat Detail <i class="fa-solid fa-arrow-right fa-xs"></i></span>
        </div>
      </div>
      <div class="service-card reveal reveal-delay-3">
        <img src="https://images.unsplash.com/photo-1598300042247-d088f8ab3a91?w=500&q=80&fit=crop" alt="Acne Removal" />
        <div class="service-card-overlay">
          <span class="service-tag">Kulit Bermasalah</span>
          <span class="service-name">Acne Removal</span>
          <span class="service-arrow">Lihat Detail <i class="fa-solid fa-arrow-right fa-xs"></i></span>
        </div>
      </div>
    </div>
  </section>

  <!-- ===== CTA BANNER ===== -->
  <div class="cta-banner" id="kontak">
    <div class="cta-banner-text reveal">
      <p class="label">Mulai Sekarang</p>
      <h2>Siap untuk <em>Tampil Lebih Glowing?</em></h2>
    </div>
    <a href="login.php" class="btn-cta-gold reveal reveal-delay-2">
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

  </script>

</body>
</html>
