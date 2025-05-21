# Bağış Takip Uygulaması

Bu proje, kullanıcıların bağış yapabildiği ve yöneticinin tüm kullanıcıları ve bağışları görüntüleyip düzenleyebildiği bir bağış takip sistemidir. Uygulama **PHP (frontend)** ve **Python Flask (backend)** teknolojileriyle geliştirilmiştir.

## 🔧 Kullanılan Teknolojiler

- **Frontend:** PHP (Vanilla)
- **Backend:** Python (Flask)
- **Veritabanı:** PostgreSQL
- **Kimlik Doğrulama:** JWT (JSON Web Token)
- **İstek Yönetimi:** PHP'de cURL kullanımı
- **Sunucu:** XAMPP (Apache)
- **Veritabanı Arayüzü:** pgAdmin

---

##  Kurulum

### 1. Backend (Flask)

```bash
cd backend-flask
pip install -r requirements.txt
python app.py
```

### 2. Frontend (PHP)

1. `frontend-php` klasörünü `htdocs` içine kopyalayın (örnek: `C:\xampp\htdocs\frontend-php`).
2. XAMPP üzerinden Apache sunucusunu başlatın.
3. Tarayıcınızdan şu adrese gidin:  
   `http://localhost/frontend-php/index.php`

---

##  Veritabanı Şeması

### Kullanıcılar (users)

| Alan Adı        | Veri Türü  |
|------------------|------------|
| id               | integer    |
| username         | string     |
| email            | string     |
| password_hash    | string     |
| is_admin         | boolean    |

### Bağışlar (donations)

| Alan Adı        | Veri Türü  |
|------------------|------------|
| id               | integer    |
| amount           | float      |
| description      | string     |
| user_id          | integer (FK → users.id) |
| created_at       | datetime   |

---

##  Notlar

- JWT token, giriş sonrası PHP oturumunda saklanır ve tüm API isteklerinde `Authorization: Bearer <token>` şeklinde kullanılır.
- Admin paneli, kullanıcı düzenleme ve bağış filtreleme işlemlerini destekler.
- Projede `session_start()` yapılarıyla oturum yönetimi yapılmaktadır.

---

##  Geliştirici

**Ezgi Albardak**  
Proje GitHub Linki: [(https://github.com/EzgiAlbardak/bag-s-uygulamasi))
