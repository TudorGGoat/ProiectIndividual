<?php
// Adresa de expeditor și parola
$sender_email = "admin@radiatoare-utilaje.ro";
$password = "1234";

// Adresa destinatarului
$recipient_email = <mb_convert_variables class="emailSender"></mb_convert_variables>;

// Subiect și conținutul mesajului
$subject = $subjectSender;
$body = "Acesta este conținutul e-mailului trimis prin PHP.";

// Configurarea antetelor e-mailului
$headers = "From: $sender_email\r\n";
$headers .= "Reply-To: $sender_email\r\n";
$headers .= "Content-type: text/html\r\n";

// Trimiterea e-mailului
$mail_success = mail($recipient_email, $subject, $body, $headers);

