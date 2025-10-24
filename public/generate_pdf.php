<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once '../app/core/Database.php';
require_once '../app/core/helpers.php';
require_once '../app/models/User.php';
require_once '../app/models/Company.php'; 
require_once '../app/models/Trip.php';
require_once '../app/models/Ticket.php';
require_once '../app/lib/fpdf.php';

if (!isset($_SESSION['user_id']) && $_SESSION['user_role'] !== 'user' && $ticket['user_id'] === $_SESSION['user_id']) {
    view_error(403, 'Erişim Engellendi', 'Bu bileti görüntüleyebilmek için lütfen sisteme giriş yapın.');
}

$ticket_id = $_GET['ticket_id'] ?? 0;
$user_id = $_SESSION['user_id'];

$ticketModel = new Ticket();
$ticket = $ticketModel->findById($ticket_id); 

if (!$ticket || (string)$ticket['user_id'] != (string)$user_id) {
    view_error(404, 'Bilet Bulunamadı', 'Aradığınız bilet mevcut değil veya bu bileti görüntüleme yetkiniz olmayabilir.');
}


class PDF extends FPDF {
    function tr($text) {
        return iconv('UTF-8', 'ISO-8859-9//TRANSLIT', $text);
    }
    function Header() {
        $this->SetFont('Arial','B',14);
        $this->Cell(0,10, $this->tr('BiletFix E-Bilet'),0,1,'C');
        $this->Ln(5);
    }
    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial','I',8);
        $this->SetTextColor(128);
        $this->Cell(0,10, $this->tr('BiletFix iyi yolculuklar diler.'),0,0,'C');
    }
}

$pdf = new PDF('P', 'mm', 'A5'); 
$pdf->AddPage();
$pdf->SetMargins(15, 15, 15); 
$pdf->SetAutoPageBreak(true, 15);


$pdf->SetFont('Arial','B',10);
$pdf->Cell(20, 6, '     Firma:', 0, 0);
$pdf->SetFont('Arial','',10);
$pdf->Cell(70, 6, $pdf->tr($ticket['company_name']), 0, 0);

$pdf->SetFont('Arial','',9);
$pdf->SetTextColor(108, 117, 125);
$pdf->Cell(0, 6,  date('d.m.Y H:i', strtotime($ticket['created_at'] ?? 'now')), 0, 1, 'R');
$pdf->SetTextColor(0,0,0);

$pdf->SetFont('Arial','B',10);
$pdf->Cell(20, 6, 'Durum:', 0, 0);
$pdf->SetFont('Arial','',10);
$status_text = strtoupper($ticket['status']);
$status_color = [0, 0, 0];
if ($ticket['status'] === 'active') {
    $status_text = 'AKTIF (ODENDI)';
    $status_color = [0, 100, 0];
} elseif ($ticket['status'] === 'cancelled') {
    $status_text = 'IPTAL EDILDI';
    $status_color = [200, 0, 0];
}
$pdf->SetTextColor($status_color[0], $status_color[1], $status_color[2]);
$pdf->Cell(0, 6, $pdf->tr($status_text), 0, 1);
$pdf->SetTextColor(0,0,0);

$pdf->Ln(5); 

$pdf->Line($pdf->GetX(), $pdf->GetY(), $pdf->GetX() + $pdf->GetPageWidth() - 30, $pdf->GetY());
$pdf->Ln(5);

$pdf->SetFont('Arial','B',10);
$pdf->SetTextColor(100);
$pdf->Cell(30, 6, $pdf->tr('Güzergah:'), 0, 0);
$pdf->SetFont('Arial','',12);
$pdf->SetTextColor(0);
$pdf->Cell(0, 6, $pdf->tr($ticket['departure_city']) . ' -> ' . $pdf->tr($ticket['destination_city']), 0, 1);
$pdf->Ln(3);

$pdf->SetFont('Arial','B',10);
$pdf->SetTextColor(100);
$pdf->Cell(30, 6, 'Koltuk:', 0, 0);
$pdf->SetFont('Arial','',12);
$pdf->SetTextColor(0);
$pdf->Cell(0, 6,  $ticket['seat_number'], 0, 1);
$pdf->Ln(5);

$pdf->SetFont('Arial','B',10);
$pdf->SetTextColor(100);
$pdf->Cell(30, 6, $pdf->tr('Kalkis:'), 0, 0);
$pdf->SetFont('Arial','',12);
$pdf->SetTextColor(0);
$pdf->Cell(0, 6, date('d.m.Y H:i', strtotime($ticket['departure_time'])), 0, 1);
$pdf->Ln(3);

$pdf->SetFont('Arial','B',10);
$pdf->SetTextColor(100);
$pdf->Cell(30, 6, $pdf->tr('Varis:'), 0, 0);
$pdf->SetFont('Arial','',12);
$pdf->SetTextColor(0);
$pdf->Cell(0, 6, date('d.m.Y H:i', strtotime($ticket['arrival_time'])), 0, 1);
$pdf->Ln(3);

$pdf->SetFont('Arial','B',10);
$pdf->SetTextColor(100);
$pdf->Cell(30, 6, $pdf->tr('Yolcu:'), 0, 0);
$pdf->SetFont('Arial','',12);
$pdf->SetTextColor(0);
$pdf->Cell(0, 6, $pdf->tr($_SESSION['full_name']), 0, 1);

$pdf->Ln(8); 

$pdf->Line($pdf->GetX(), $pdf->GetY(), $pdf->GetX() + $pdf->GetPageWidth() - 30, $pdf->GetY());
$pdf->Ln(5);

$pdf->SetFont('Arial','B',10);
$pdf->SetTextColor(100);
$pdf->Cell(30, 6, $pdf->tr('Ödenen:'), 0, 1);
$pdf->SetFont('Arial','B',14);
$pdf->SetTextColor(0);
$pdf->Cell(0, 8, number_format($ticket['total_price'], 2, ',', '.') . ' TL', 0, 1);

if ($ticket['status'] === 'cancelled') {
    $pdf->Ln(10);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->SetTextColor(220, 53, 69); 
    $pdf->Cell(0, 8, $pdf->tr("!!! BU BİLET İPTAL EDİLMİŞTİR VE GEÇERSİZDİR !!!"), 1, 1, 'C');
}

$pdf_filename = 'BiletFix-' . $ticket['ticket_id'] . '.pdf';
$pdf->Output('D', $pdf_filename); 
exit;
?>