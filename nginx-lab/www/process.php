<?php
session_start();

$fullname = trim($_POST['fullname'] ?? '');
$email = trim($_POST['email'] ?? '');
$duration = $_POST['duration'] ?? '';
$magazine = $_POST['magazine'] ?? '';
$digital = $_POST['digital'] ?? 'Нет';
$payment = $_POST['payment'] ?? '';

$errors = [];

if (empty($fullname)) {
    $errors[] = "Имя не может быть пустым";
} elseif (strlen($fullname) < 2) {
    $errors[] = "Имя должно содержать минимум 2 символа";
}

if (empty($email)) {
    $errors[] = "Email не может быть пустым";
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Некорректный формат email (пример: name@domain.com)";
}

if (empty($duration)) {
    $errors[] = "Выберите срок подписки";
}

if (empty($magazine)) {
    $errors[] = "Выберите журнал";
}

if (empty($payment)) {
    $errors[] = "Выберите способ оплаты";
}

if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    
    $_SESSION['old_input'] = [
        'fullname' => $fullname,
        'email' => $email,
        'duration' => $duration,
        'magazine' => $magazine,
        'digital' => $digital,
        'payment' => $payment
    ];
    
    header("Location: index.php");
    exit();
}

$fullname_safe = htmlspecialchars($fullname);
$email_safe = htmlspecialchars($email);
$duration_safe = htmlspecialchars($duration);
$magazine_safe = htmlspecialchars($magazine);
$digital_safe = htmlspecialchars($digital);
$payment_safe = htmlspecialchars($payment);

$_SESSION['user_data'] = [
    'fullname' => $fullname_safe,
    'email' => $email_safe,
    'duration' => $duration_safe,
    'magazine' => $magazine_safe,
    'digital' => $digital_safe,
    'payment' => $payment_safe
];

setcookie("last_fullname", $fullname_safe, time() + 86400 * 30, "/");
setcookie("last_email", $email_safe, time() + 86400 * 30, "/");
setcookie("last_magazine", $magazine_safe, time() + 86400 * 30, "/");
setcookie("last_duration", $duration_safe, time() + 86400 * 30, "/");
setcookie("last_payment", $payment_safe, time() + 86400 * 30, "/");
setcookie("last_digital", $digital_safe, time() + 86400 * 30, "/");

$line = $fullname_safe . ";" . $email_safe . ";" . $magazine_safe . ";" . $duration_safe . ";" . $digital_safe . ";" . $payment_safe . "\n";
file_put_contents("data.txt", $line, FILE_APPEND);

header("Location: index.php");
exit();
?>