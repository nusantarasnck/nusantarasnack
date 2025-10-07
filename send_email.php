<?php
// Konfigurasi email tujuan
$to = "jamespangestu.de@gmail.com";
$subject = "Pesanan Baru dari TemanRusiaKamu";

// Cek apakah ada file upload
if (!isset($_FILES['payment'])) {
    echo "Tidak ada file bukti pembayaran yang diunggah!";
    exit;
}

// Ambil file upload
$file_tmp = $_FILES['payment']['tmp_name'];
$file_name = $_FILES['payment']['name'];
$file_size = $_FILES['payment']['size'];
$file_type = $_FILES['payment']['type'];
$file_error = $_FILES['payment']['error'];

if ($file_error !== UPLOAD_ERR_OK) {
    echo "Terjadi kesalahan saat mengunggah file!";
    exit;
}

// Ambil data tambahan dari form
$items = isset($_POST['items']) ? $_POST['items'] : 'Tidak ada detail barang.';
$total = isset($_POST['total']) ? $_POST['total'] : '0';

// Buat pesan email
$boundary = md5(time());
$headers = "From: no-reply@temanrusiakamu.com\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: multipart/mixed; boundary=\"".$boundary."\"\r\n";

$message = "--".$boundary."\r\n";
$message .= "Content-Type: text/plain; charset=UTF-8\r\n";
$message .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
$message .= "Pesanan Baru:\n\n";
$message .= "Detail Barang:\n$items\n\n";
$message .= "Total Harga: Rp $total\n\n";
$message .= "Bukti Pembayaran terlampir.\r\n";

// Lampirkan file
$file_content = chunk_split(base64_encode(file_get_contents($file_tmp)));
$message .= "--".$boundary."\r\n";
$message .= "Content-Type: $file_type; name=\"$file_name\"\r\n";
$message .= "Content-Disposition: attachment; filename=\"$file_name\"\r\n";
$message .= "Content-Transfer-Encoding: base64\r\n\r\n";
$message .= $file_content . "\r\n";
$message .= "--".$boundary."--";

// Kirim email
if(mail($to, $subject, $message, $headers)){
    echo "Pesanan berhasil dikirim! Terima kasih.";
}else{
    echo "Gagal mengirim email. Silakan coba lagi.";
}
?>