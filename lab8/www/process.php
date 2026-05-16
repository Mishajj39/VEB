<?php
require 'db.php';
require 'Subscription.php';

$subscription = new Subscription($pdo);

$name = htmlspecialchars($_POST['name']);
$subscription_term = htmlspecialchars($_POST['subscription_term']);
$magazine = htmlspecialchars($_POST['magazine']);
$digital_version = isset($_POST['digital_version']) ? 1 : 0;
$payment_format = htmlspecialchars($_POST['payment_format']);

$subscription->add($name, $subscription_term, $magazine, $digital_version, $payment_format);

header("Location: index.php");
exit();
?>