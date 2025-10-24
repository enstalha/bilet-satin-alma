<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="container mt-4">
    <h2 class="mb-4 fw-bold">Biletlerim</h2>

    <?php if (isset($_GET['purchase']) && $_GET['purchase'] === 'success'): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Biletiniz başarıyla satın alındı!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php elseif (isset($_GET['cancellation']) && $_GET['cancellation'] === 'success'): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Biletiniz başarıyla iptal edildi ve ücret iadesi yapıldı.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php elseif (isset($_GET['cancellation']) && $_GET['cancellation'] === 'failed_time'): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            Sefere 1 saatten az kaldığı için bilet iptal edilemez.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php elseif (isset($_GET['cancellation']) && $_GET['cancellation'] === 'failed_db'): ?>
         <div class="alert alert-danger alert-dismissible fade show" role="alert">
            Bilet iptal edilirken bir veritabanı hatası oluştu.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>


    <?php if (empty($tickets)): ?>
        <div class="alert alert-info text-center">Henüz satın alınmış bir biletiniz bulunmamaktadır.</div>
    <?php else: ?>
        <div class="row g-3">
            <?php foreach ($tickets as $ticket): ?>
                <?php
                    $departure_time = new DateTime($ticket['departure_time']);
                    $now = new DateTime(); 
                    $is_past_trip = $departure_time < $now;
                ?>
                <div class="col-12">
                    <div class="card shadow-sm border-light h-100 <?php echo $is_past_trip ? 'bg-light opacity-75' : '';  ?>">
                        <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-center p-3">

                            <div class="mb-2 mb-md-0 text-center text-md-start" style="min-width: 200px;">
                                <strong class="text-secondary d-block"><?php echo htmlspecialchars($ticket['company_name']); ?></strong>
                                <span class="text-muted">
                                    <?php echo htmlspecialchars($ticket['departure_city']); ?> ➔ <?php echo htmlspecialchars($ticket['destination_city']); ?>
                                </span>
                            </div>

                            <div class="mb-2 mb-md-0 text-center text-muted">
                                <i class="far fa-calendar-alt me-1"></i> <?php echo date('d.m.Y', strtotime($ticket['departure_time'])); ?><br>
                                <i class="far fa-clock me-1"></i> <?php echo date('H:i', strtotime($ticket['departure_time'])); ?>
                            </div>

                            <div class="mb-2 mb-md-0 text-center">
                                Koltuk No:<br>
                                <span class="badge bg-primary rounded-pill fs-6"><?php echo htmlspecialchars($ticket['seat_number']); ?></span>
                            </div>

                             <div class="mb-3 mb-md-0 text-center">
                                Durum:<br>
                                <span class="badge <?php echo $ticket['status'] === 'active' ? ($is_past_trip ? 'bg-warning text-dark' : 'bg-success') : 'bg-secondary'; ?>">
                                     <?php
                                        if($ticket['status'] === 'active' && $is_past_trip){
                                            echo 'SEFER TAMAMLANDI';
                                        }elseif($ticket['status'] === 'active'){
                                            echo 'AKTIF';
                                        }else{
                                            echo 'IPTAL EDILDI';
                                        }
                                    ?>
                                </span>
                            </div>

                            <div class="text-center d-flex flex-column flex-sm-row gap-2 align-items-center">
                                <?php
                                if ($ticket['status'] === 'active' && !$is_past_trip):
                                ?>
                                    <a href="/index.php?page=cancel-ticket&ticket_id=<?php echo $ticket['ticket_id']; ?>"
                                       class="btn btn-danger btn-sm"
                                       onclick="return confirm('Bu bileti iptal etmek istediğinizden emin misiniz? Ücreti hesabınıza iade edilecek.')">
                                       <i class="fas fa-times me-1"></i> İptal Et
                                    </a>
                                <?php
                                elseif ($ticket['status'] === 'active' && $is_past_trip):
                                ?>
                                    <a class="btn btn-secondary btn-sm">
                                        Süresi geçti
                                    </a>
                                <?php   
                                endif;
                                ?>
                                <a href="/generate_pdf.php?ticket_id=<?php echo $ticket['ticket_id']; ?>"
                                   class="btn btn-info btn-sm text-white" target="_blank">
                                   <i class="fas fa-file-pdf me-1"></i> PDF İndir
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>