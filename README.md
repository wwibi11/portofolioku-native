```markdown


# 🎨 Portofolio Wisnu Wibisono

**Website portofolio pribadi + blog dengan PHP native & Bootstrap 5**

[![PHP](https://img.shields.io/badge/PHP-8.1-777BB4?style=flat-square&logo=php&logoColor=white)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=flat-square&logo=mysql&logoColor=white)](https://mysql.com)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=flat-square&logo=bootstrap&logoColor=white)](https://getbootstrap.com)
[![License](https://img.shields.io/badge/License-MIT-green?style=flat-square)](LICENSE)

[🌐 Live Demo](https://wisnuwb.my.id) · [📝 Blog](https://blog.wisnuwb.my.id) · [🐛 Report Bug](https://github.com/wwibi11/portofolio/issues)

</div>

---

## 📖 Tentang Project

Website portofolio pribadi yang dibangun dengan **PHP native** (tanpa framework) untuk menunjukkan kemampuan web development. Dilengkapi dengan **admin panel** untuk mengelola konten secara dinamis.

Project ini melayani **2 domain** dari 1 codebase:
- **`wisnuwb.my.id`** — Portfolio utama
- **`blog.wisnuwb.my.id`** — Blog (subdomain)

---

## ✨ Fitur

### 🌐 Halaman Publik
- **Home** — Hero section dengan foto profil & portfolio terbaru
- **Tentang Saya** — Profil, skill, sosial media
- **Portfolio** — Grid proyek 2 kolom dengan detail
- **Pendidikan** — Riwayat pendidikan
- **Pengalaman** — Riwayat pekerjaan
- **Blog** — List artikel dengan filter kategori
- **Kontak** — Form kontak (tersimpan ke DB)

### 🔐 Admin Panel
- **Dashboard** — Statistik & overview
- **Profil** — Edit data diri, foto, CV, sosial media
- **Skill** — CRUD keahlian dengan level & kategori
- **Pendidikan** — CRUD riwayat pendidikan
- **Pengalaman** — CRUD riwayat pekerjaan
- **Proyek** — CRUD portfolio + upload thumbnail
- **Blog** — CRUD artikel + upload cover + kategori + tags
- **Kategori** — CRUD kategori blog
- **Pesan** — Inbox dari form kontak
- **Users** — Manajemen user admin
- **Pengaturan** — Toggle maintenance mode

### 🎯 Fitur Khusus
- 🔧 **Maintenance Mode** — Blokir pengunjung, admin tetap akses
- 📱 **Responsive** — Mobile & desktop friendly
- 🎨 **Modern UI** — Warna brand ungu (`#7c3aed`) konsisten
- 🚀 **Subdomain Support** — 1 codebase, 2 domain
- 🔐 **Auto-detect Environment** — Localhost vs hosting
- 📤 **Upload System** — Foto, CV, thumbnail, cover

---

## 🛠️ Tech Stack

| Layer | Teknologi |
|---|---|
| **Backend** | PHP 8.1 (native, tanpa framework) |
| **Database** | MySQL / MariaDB (PDO) |
| **Frontend** | Bootstrap 5, Bootstrap Icons |
| **Font** | Plus Jakarta Sans (Google Fonts) |
| **Admin Template** | Ruang Admin (SB Admin 2 fork) |
| **Library** | jQuery 3.7, Font Awesome 6 |

---
```



```markdown


## 📁 Struktur Folder

portofolio/
├── 📁 assets/                  # Aset statis
│   ├── css/                    # Stylesheet
│   ├── js/                     # JavaScript
│   ├── img/                    # Gambar (logo, icon)
│   ├── font/                   # Custom fonts
│   └── uploads/                # Upload dari admin (JANGAN COMMIT)
│
├── 📁 auth/                    # Autentikasi
│   ├── middleware.php          # Cek login & role
│   ├── login.php               # Halaman login
│   ├── proses_login.php        # Handler login
│   └── logout.php              # Proses logout
│
├── 📁 blog/                    # Bridge blog subdomain
│   ├── index.php               # Redirect ke blog page
│   └── .htaccess               # Rewrite rules
│
├── 📁 config/                  # Konfigurasi
│   ├── config.php              # Auto-detect environment
│   ├── database.php            # PDO wrapper
│   └── helper.php              # Helper functions
│
├── 📁 database/                # SQL files
│   └── portofolio.sql          # Struktur + seed data
│
├── 📁 modules/                 # Modul admin (CRUD)
│   ├── dashboard/
│   ├── profil/
│   ├── skill/
│   ├── pendidikan/
│   ├── pengalaman/
│   ├── proyek/
│   ├── blog/
│   ├── kategori/
│   ├── pesan/
│   ├── users/
│   └── pengaturan/
│
├── 📁 vendor/                  # Library (JANGAN COMMIT)
│   ├── bootstrap/
│   ├── jquery/
│   ├── fontawesome-free/
│   └── jquery-easing/
│
├── 📁 views/                   # View templates
│   ├── admin/                  # Layout admin
│   │   ├── header.php
│   │   ├── sidebar.php
│   │   ├── topbar.php
│   │   └── footer.php
│   ├── layout/                 # Layout publik
│   │   ├── header.php
│   │   └── footer.php
│   ├── public/                 # Halaman publik
│   │   ├── home.php
│   │   ├── about.php
│   │   ├── projects.php
│   │   ├── pendidikan.php
│   │   ├── pengalaman.php
│   │   ├── blog.php
│   │   ├── blog_detail.php
│   │   └── contact.php
│   └── errors/                 # Error pages
│       ├── 404.php
│       ├── 403.php
│       └── 500.php
│
├── 📄 index.php                # Front controller
├── 📄 maintenance.php          # Halaman maintenance
├── 📄 .htaccess                # Apache config
├── 📄 .gitignore
└── 📄 README.md
```

---
```



## 🚀 Instalasi

### 📋 Prasyarat
- PHP >= 8.0
- MySQL / MariaDB
- Apache dengan `mod_rewrite` aktif
- Composer (opsional)

### 1️⃣ Clone Repository

```bash
git clone https://github.com/wwibi11/portofolio.git
cd portofolio
```

### 2️⃣ Import Database

Buka phpMyAdmin → buat database baru → import:

```bash
mysql -u root -p portofolio < database/portofolio.sql
```

Atau import via phpMyAdmin UI: pilih database → tab **Import** → pilih `database/portofolio.sql`.

### 3️⃣ Konfigurasi Database

Edit `config/config.php`:

```php
// Localhost
define('DB_HOST', 'localhost');
define('DB_NAME', 'portofolio');
define('DB_USER', 'root');
define('DB_PASS', '');

// Hosting — ganti sesuai cPanel Anda
define('DB_HOST', 'localhost');
define('DB_NAME', 'nqrwisnu_portofolio');
define('DB_USER', 'nqrwisnu_xxxx');
define('DB_PASS', 'xxxx');
```

### 4️⃣ Install Vendor (Library)

Copy folder `vendor/` dari backup, atau install manual:
- [jQuery 3.7](https://code.jquery.com/jquery-3.7.1.min.js)
- [Bootstrap 4.6](https://getbootstrap.com/docs/4.6/)
- [Font Awesome 6](https://fontawesome.com/)
- [jQuery Easing](https://github.com/gdsmith/jquery.easing)
- [Ruang Admin](https://github.com/StartBootstrap/startbootstrap-sb-admin-2)

Atau pakai CDN (edit `views/admin/header.php`).

### 5️⃣ Jalankan

Akses di browser:
```
http://localhost/portofolio
```

Admin panel:
```
http://localhost/portofolio/adm
```

### 6️⃣ Login Admin

- **URL:** `/auth/login.php`
- **Username:** `admin`
- **Password:** (reset via SQL kalau lupa)

**Reset password:**
```sql
UPDATE users 
SET password = '$2y$10$...hash_bcrypt...' 
WHERE username = 'admin';
```

Generate hash di PHP:
```php
<?php echo password_hash('admin123', PASSWORD_DEFAULT);
```

---

## 🌐 Deploy ke Hosting

### 1️⃣ Upload File

Upload semua file **kecuali**:
- `vendor/` (install manual di hosting)
- `assets/uploads/*` (kosong)
- `BACKUP_*/`

Via **cPanel File Manager** atau **FTP** (FileZilla).

### 2️⃣ Buat Database

cPanel → **MySQL Databases** → buat database + user.

### 3️⃣ Import SQL

phpMyAdmin cPanel → import `database/portofolio.sql`.

### 4️⃣ Update Config

Edit `config/config.php` di hosting:
- `DB_NAME`, `DB_USER`, `DB_PASS` → sesuai cPanel

### 5️⃣ Set Permission

Folder `assets/uploads/` → **755** (atau 777 kalau perlu).

### 6️⃣ Setup Subdomain

cPanel → **Subdomains** → buat `blog` → arahkan ke `public_html/blog/`.

---

## 🎯 Fitur Maintenance Mode

Toggle di **Admin → Pengaturan → Mode Maintenance**.

- **Aktif:** Pengunjung redirect ke `maintenance.php`, admin tetap bisa akses
- **Nonaktif:** Website normal

Setting disimpan di **tabel `settings`** (bukan file config).

---

## 🔐 Keamanan

- ✅ Password di-hash dengan `password_hash()` (bcrypt)
- ✅ PDO prepared statements (anti SQL injection)
- ✅ HTML escape dengan `htmlspecialchars()`
- ✅ Session cookie dengan `httponly` & `samesite`
- ✅ Auto-detect HTTPS
- ✅ Login required untuk admin area
- ⚠️ **Jangan commit** `config.local.php` kalau ada kredensial

---

## 🤝 Kontribusi

Pull request sangat diterima! Untuk perubahan besar, buka **issue** dulu untuk diskusi.

1. Fork repository
2. Buat branch baru (`git checkout -b fitur/awesome-feature`)
3. Commit perubahan (`git commit -m 'Add: awesome feature'`)
4. Push ke branch (`git push origin fitur/awesome-feature`)
5. Buka **Pull Request**

---

## 📄 Lisensi

Project ini dilisensikan di bawah **MIT License** — bebas digunakan untuk keperluan pribadi maupun komersial.

---

## 👤 Author

**Wisnu Wibisono**

- 🌐 Website: [wisnuwb.my.id](https://wisnuwb.my.id)
- 📝 Blog: [blog.wisnuwb.my.id](https://blog.wisnuwb.my.id)
- 🐙 GitHub: [@wwibi11](https://github.com/wwibi11)
- 💼 LinkedIn: [linkedin.com/in/wisnuwibisono](https://linkedin.com/in/wisnuwibisono)
- 📧 Email: [wiisnuwb@gmail.com](mailto:wiisnuwb@gmail.com)

---

<div align="center">

**⭐ Kalau project ini bermanfaat, jangan lupa kasih star! ⭐**

Made with ❤️ in Kebumen, Indonesia

</div>
```

---

