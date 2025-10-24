<?php 
require_once __DIR__ . '/layout/header.php'; 
?>

<style>
    #baslik{
        background-image: url("/assets/images/home_page_background.png");
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center;
    }
</style>

<div class="p-5 mb-5 bg-light" id="baslik">
    <div class="container-fluid py-5 container mt-4">
        <h1 class="display-5 fw-bold text-white">Size en ugun otobüs biletini bulun</h1>
        <p class="col-md-8 fs-4 text-white">Türkiye’nin her yerine kolayca bilet bul, yolculuğuna hemen başla!</p>
    </div>
        
    <div class="container mt-4">
        <div class="card my-4">
            <div class="card-body">
                <form action="/index.php?page=search-trips" method="GET">
                    <input type="hidden" name="page" value="search-trips">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <i class="fa-solid fa-location-dot"></i>
                            <label for="departure" class="form-label">Kalkış Noktası</label>
                            <input type="text" class="form-control" id="departure" name="departure" placeholder="Örn: İstanbul">
                        </div>
                        <div class="col-md-4">
                            <i class="fa-solid fa-location-dot"></i>
                            <label for="arrival" class="form-label">Varış Noktası</label>
                            <input type="text" class="form-control" id="arrival" name="arrival" placeholder="Örn: Ankara">
                        </div>
                        <div class="col-md-2">
                            <i class="fa-solid fa-calendar"></i>
                            <label for="date" class="form-label">Tarih</label>
                            <input type="date" class="form-control" id="date" name="date">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fa-solid fa-magnifying-glass me-1"></i>
                                Sefer Bul
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="container mt-4">
    <div class="pt-5 pb-5">
            <div class="row g-4 text-center">

                <div class="col-md-4">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body">
                        <i class="fa-solid fa-wallet fa-3x text-primary mb-3"></i>
                        <h5 class="card-title fw-semibold">En Uygun Fiyat</h5>
                        <p class="card-text text-muted">Türkiye’nin her yerine en uygun bilet seçeneklerini bulabilirsiniz.</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body">
                        <i class="fa-solid fa-shield-halved fa-3x text-success mb-3"></i>
                        <h5 class="card-title fw-semibold">Güvenli Ödeme</h5>
                        <p class="card-text text-muted">Biletinizi güvenle satın alın, bilgileriniz koruma altında.</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body">
                        <i class="fa-solid fa-clock fa-3x text-warning mb-3"></i>
                        <h5 class="card-title fw-semibold">7/24 Destek</h5>
                        <p class="card-text text-muted">Her saat, her gün sorularınıza cevap bulabilirsiniz.</p>
                        </div>
                    </div>
                </div>

            </div>
    </div>

    <div class="container my-5">
        <h2 class="mb-4 fw-bold">Sıkça Sorulan Sorular (SSS)</h2>
        <div class="accordion shadow-sm" id="faqAccordion">

            <div class="accordion-item">
                <h2 class="accordion-header" id="faqHeading1">
                    <button class="accordion-button fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse1" aria-expanded="true" aria-controls="faqCollapse1">
                        Nasıl bilet satın alabilirim?
                    </button>
                </h2>
                <div id="faqCollapse1" class="accordion-collapse collapse show" aria-labelledby="faqHeading1" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Ana sayfadaki arama formunu kullanarak gitmek istediğiniz güzergah ve tarihi seçin. Arama sonuçlarından size uygun seferi seçtikten sonra "Koltuk Seç" butonuna tıklayın. Açılan sayfada koltuklarınızı seçin, varsa indirim kuponunuzu girin ve "Satın Almayı Onayla" butonuna tıklayın. Ödeme, hesabınızdaki mevcut bakiyeden düşülerek tamamlanacaktır. Satın alma işlemi için sisteme giriş yapmış olmanız gerekmektedir.
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header" id="faqHeading2">
                    <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse2" aria-expanded="false" aria-controls="faqCollapse2">
                        Bilet almak için üye olmam gerekiyor mu?
                    </button>
                </h2>
                <div id="faqCollapse2" class="accordion-collapse collapse" aria-labelledby="faqHeading2" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Evet, bilet satın alabilmek için sisteme kayıt olmanız ve giriş yapmanız gerekmektedir. Ancak seferleri aramak ve detaylarını görmek için üye olmanıza gerek yoktur.
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header" id="faqHeading3">
                    <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse3" aria-expanded="false" aria-controls="faqCollapse3">
                        Ödemeyi nasıl yapacağım? Kredi kartı geçerli mi?
                    </button>
                </h2>
                <div id="faqCollapse3" class="accordion-collapse collapse" aria-labelledby="faqHeading3" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Ödemeler, sistemdeki sanal bakiyeniz üzerinden yapılır. "Hesabım" sayfasından kredi kartı bilgileri girerek hesabınıza bakiye yükleyebilirsiniz.
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header" id="faqHeading4">
                    <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse4" aria-expanded="false" aria-controls="faqCollapse4">
                        Satın aldığım biletleri nerede görebilirim?
                    </button>
                </h2>
                <div id="faqCollapse4" class="accordion-collapse collapse" aria-labelledby="faqHeading4" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Giriş yaptıktan sonra menüdeki "Biletlerim" linkine tıklayarak satın aldığınız tüm biletleri listeleyebilirsiniz. Bu sayfadan biletlerinizi iptal edebilir veya PDF olarak indirebilirsiniz.
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header" id="faqHeading5">
                    <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse5" aria-expanded="false" aria-controls="faqCollapse5">
                        Biletimi nasıl iptal edebilirim ve ücret iadesi alabilir miyim?
                    </button>
                </h2>
                <div id="faqCollapse5" class="accordion-collapse collapse" aria-labelledby="faqHeading5" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        "Biletlerim" sayfasında, durumu "AKTIF" olan biletlerinizin yanında "İptal Et" butonu bulunur. Seferin kalkış saatine 1 saatten fazla süre varsa bu butona tıklayarak biletinizi iptal edebilirsiniz. İptal işlemi başarılı olduğunda, bilet için ödediğiniz tutar otomatik olarak hesabınızdaki bakiyeye geri yüklenir.
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header" id="faqHeading7">
                    <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse7" aria-expanded="false" aria-controls="faqCollapse7">
                        İndirim kuponlarını nasıl kullanabilirim?
                    </button>
                </h2>
                <div id="faqCollapse7" class="accordion-collapse collapse" aria-labelledby="faqHeading7" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Bilet satın alma ekranında, koltuk seçimi bölümünün altında "İndirim Kuponu" alanı bulunmaktadır. Geçerli bir kupon kodunuz varsa bu alana girerek "Satın Almayı Onayla" butonuna bastığınızda, indirim otomatik olarak toplam fiyata yansıtılacaktır. Kuponlar tek kullanımlık olabilir veya belirli bir firmaya özel olabilir.
                    </div>
                </div>
            </div>
    </div>
</div>

<?php require_once __DIR__ . '/layout/footer.php'; ?>