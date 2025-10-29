<?php
require __DIR__ . '/../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function enviarCorreoRecuperacion($email, $enlace) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'nexoraclickmatic@gmail.com';
        $mail->Password   = 'neey jdpa vaig uilu'; // tu app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('nexoraclickmatic@gmail.com', 'ClickMatic');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Recuperación de contraseña';
        $mail->Body    = "
            <h2>Solicitud de recuperación de contraseña</h2>
            <p>Hola, has solicitado restablecer tu contraseña. Haz clic en el siguiente enlace:</p>
            <p><a href='$enlace' target='_blank'>$enlace</a></p>
            <p><strong>Este enlace expirará en 1 hora.</strong></p>
        ";
        $mail->AltBody = "Has solicitado restablecer tu contraseña. Copia y pega este enlace en tu navegador:\n$enlace";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Error al enviar el correo: {$mail->ErrorInfo}");
        return false;
    }
}
function enviarCorreoVerificacion($email, $nombre, $link_verificacion) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'nexoraclickmatic@gmail.com';
        $mail->Password   = 'neey jdpa vaig uilu'; // contraseña de aplicación
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('nexoraclickmatic@gmail.com', 'ClickMatic');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Confirma tu cuenta en ClickMatic';

        $mail->Body    = "
            <div style='font-family: Arial, sans-serif; text-align: center;'>
                <h2>Hola, $nombre 👋</h2>
                <p>Tu cuenta ha sido registrada por un empleado de Turnomático.</p>
                <p>Para activarla, confirma tu correo haciendo clic en el siguiente botón:</p>
                <a href='$link_verificacion' 
                   style='display:inline-block;background:#4CAF50;color:white;
                          padding:12px 20px;text-decoration:none;border-radius:6px;
                          font-weight:bold;margin-top:10px;'>Confirmar cuenta</a>
                <p style='margin-top:20px;'>Si no solicitaste esta cuenta, ignora este mensaje.</p>
            </div>
        ";
        $mail->AltBody = "Hola $nombre, confirma tu cuenta visitando este enlace: $link_verificacion";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Error al enviar correo de verificación: {$mail->ErrorInfo}");
        return false;
    }
}

?>
