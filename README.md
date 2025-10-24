# BiletFix - Otobüs Bileti Satış Platformu

Bu proje, PHP, SQLite ve Docker kullanılarak geliştirilmiş dinamik, çok kullanıcılı bir otobüs bileti satış platformudur. Proje, farklı kullanıcı rolleri (Yolcu, Firma Admin, Sistem Admini) için özel yetkilendirme ve işlevsellikler içerir.

---

## ✨ Temel Özellikler

### 1. Genel & Yolcu (User) Özellikleri
- **Sefer Arama:** Kalkış, varış ve tarihe göre sefer arama.
- **Filtreleme & Sıralama:** Arama sonuçlarını fiyata, zamana ve firmaya göre filtreleme ve sıralama.
- **Kullanıcı Sistemi:** Güvenli kayıt olma, giriş yapma ve oturum yönetimi.
- **Bakiye Sistemi:** Kullanıcıların hesaplarına bakiye yüklemesi ve bilet alımlarını bu bakiye üzerinden yapması.
- **Bilet Satın Alma:** Görsel 2+2 otobüs planı üzerinden çoklu koltuk seçimi.
- **Kupon Kullanımı:** Satın alma sırasında indirim kuponu uygulama.
- **Biletlerim Sayfası:** Satın alınan tüm biletleri listeleme.
- **Bilet İptali:** Sefer saatine 1 saat kalana kadar bilet iptal etme ve ücretin bakiyeye anında iadesi.
- **PDF Bilet:** Satın alınan her bilet için dinamik olarak PDF oluşturma ve indirme.
- **SSS Sayfası:** Kullanıcılar için sıkça sorulan sorular bölümü.

### 2. Firma Admin Paneli
- **Yetkilendirme:** Firma Adminleri, sadece kendi firmalarına ait verileri (seferler, kuponlar) görebilir ve yönetebilir.
- **Sefer Yönetimi:** Kendi firması için yeni seferler oluşturabilir, mevcutları düzenleyebilir ve silebilir.
- **Kupon Yönetimi:** Sadece kendi firmasında geçerli olacak indirim kuponları oluşturabilir, düzenleyebilir ve silebilir.

### 3. Admin Paneli (Sistem Yöneticisi)
- **Firma Yönetimi:** Sisteme yeni otobüs firmaları ekleyebilir, düzenleyebilir ve silebilir.
- **Firma Admini Yönetimi:** Yeni firma_admin rolünde kullanıcılar oluşturabilir, bu kullanıcıları belirli firmalara atayabilir, düzenleyebilir ve silebilir.
- **Genel Kupon Yönetimi:** Tüm firmalarda geçerli olan genel indirim kuponları oluşturabilir, düzenleyebilir ve silebilir.

---

## 🔧 Kullanılan Teknolojiler
- **Backend:** PHP 8.2
- **Veritabanı:** SQLite
- **Frontend:** HTML5, CSS3, Bootstrap 5
- **PDF Oluşturma:** FPDF Kütüphanesi
- **Paketleme & Sunucu:** Docker & Docker Compose

---

## 🚀 Kurulum ve Başlatma

Bu proje, Docker ile paketlendiği için kurulumu son derece basittir. Tek gereksinim, sisteminizde Docker Desktop'ın kurulu olmasıdır.

1. **Projeyi Klonlayın:**
```bash
git clone https://github.com/senin-kullanici-adin/bilet-satin-alma.git
cd bilet-satin-alma
```

2. **Docker Container'larını Başlatın:**
```bash
docker-compose up -d --build
```

3. **Siteye Erişin:**  
Kurulum tamamlandı! Artık `http://localhost:8080/` adresinden siteye erişebilirsiniz.

---

## 👥 Demo Hesap Bilgileri

Sistemi test etmek için aşağıdaki demo hesaplarını kullanabilirsiniz.

| Rol            | E-posta                       | Şifre         |
|----------------|-------------------------------|---------------|
| Sistem Admini  | admin@biletfix.com            | !Admin123.     |
| Firma Admini   | anayoladmin1@anayol.com       | !Anayol123.    |
| Firma Admini   | gitgitadmin@gitgit.com        | !GitGit123.   |
| Firma Admini   | kaptiradmin@kaptir.com        | !Kaptır123.   |
| Firma Admini   | konnektadmin@konnekt.com      | !Konnekt123.   |
| Yolcu (User)   | (Site üzerinden kayıt olabilirsiniz) | - |
