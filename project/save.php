<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $frequency = $_POST['frequency'] ?? 'weekly';
    
    $errors = [];
    
    if (empty($name)) {
        $errors[] = 'Имя обязательно для заполнения';
    } elseif (strlen($name) < 2) {
        $errors[] = 'Имя должно содержать минимум 2 символа';
    }
    
    if (empty($email)) {
        $errors[] = 'Email обязателен для заполнения';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Введите корректный email адрес';
    }
    
    if (!in_array($frequency, ['daily', 'weekly', 'monthly'])) {
        $errors[] = 'Неверная частота рассылки';
    }
    
    if (empty($errors)) {
        setcookie("last_submission", date('Y-m-d H:i:s'), time() + 3600, "/");
        
        $_SESSION['subscription'] = [
            'name' => $name,
            'email' => $email,
            'frequency' => $frequency,
            'date' => date('Y-m-d H:i:s')
        ];
        
        $_SESSION['subscription_success'] = "Спасибо, {$name}! Вы успешно подписались на {$frequency} рассылку новостей космонавтики.";
        
    } else {
        $_SESSION['subscription_error'] = implode(', ', $errors);
    }
    
    header('Location: index.php');
    exit;
} else {
    header('Location: index.php');
    exit;
}
?>