<?php
include 'includes/db.php';
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendEmailNotification($email, $siteUrl, $newStatus) {
    $mail = new PHPMailer(true);
    try {
        // Configuración del servidor de correo
        $mail->isSMTP();
        $mail->Host = 'smtp.example.com';  // Cambia esto al servidor SMTP que uses
        $mail->SMTPAuth = true;
        $mail->Username = 'tu_correo@example.com';  // Cambia esto a tu correo
        $mail->Password = 'tu_contraseña';  // Cambia esto a tu contraseña
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Destinatarios
        $mail->setFrom('tu_correo@example.com', 'Web Monitor');
        $mail->addAddress($email);

        // Contenido del correo
        $mail->isHTML(true);
        $mail->Subject = 'Site Status Change Notification';
        $mail->Body    = "The status of <strong>$siteUrl</strong> has changed to <strong>$newStatus</strong>.";
        $mail->AltBody = "The status of $siteUrl has changed to $newStatus.";

        $mail->send();
        echo 'Message has been sent';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

function checkSiteStatus($url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return $httpCode;
}

$sql = "SELECT sites.*, users.username, users.email FROM sites JOIN users ON sites.user_id = users.id WHERE sites.status = 'active'";
$result = $conn->query($sql);

while ($site = $result->fetch_assoc()) {
    $statusCode = checkSiteStatus($site['url']);
    $newStatus = ($statusCode == 200) ? 'active' : 'inactive';

    if ($newStatus != $site['status']) {
        // Update status in the database
        $updateSql = "UPDATE sites SET status = ?, last_checked = NOW() WHERE id = ?";
        $stmt = $conn->prepare($updateSql);
        $stmt->bind_param("si", $newStatus, $site['id']);
        $stmt->execute();

        // Send email notification
        sendEmailNotification($site['email'], $site['url'], $newStatus);
    }
}

echo "Site statuses updated.";
?>
