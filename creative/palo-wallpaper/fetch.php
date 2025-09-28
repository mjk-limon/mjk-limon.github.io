<?php

header('Content-Type: application/json');

$host = 'localhost';
$db   = 'dhakaso3_jahidlimon';
$user = 'dhakaso3_jahid';
$pass = '4A(kptrDLc)m';
$charset = 'utf8mb4';

// Create connection
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

$bnMonths = ['জানুয়ারি', 'ফেব্রুয়ারি', 'মার্চ', 'এপ্রিল', 'মে', 'জুন', 'জুলাই', 'আগস্ট', 'সেপ্টেম্বর', 'অক্টোবর', 'নভেম্বর', 'ডিসেম্বর'];

function en2bn($number)
{
    $bn = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
    $en = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
    return str_replace($en, $bn, $number);
}

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    $pdo->exec("SET NAMES utf8mb4");

    // Prepare SQL
    $sql = "SELECT * FROM `palo_wallpapers`";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    echo json_encode(array_map(function ($item) use ($bnMonths) {
        $captured = new DateTime($item->captured_at);
        $capturedAtBn = sprintf('%s %s, %s', en2bn($captured->format('d')), $bnMonths[$captured->format('m') - 1], en2bn($captured->format('Y')));

        return [
            'uuid' => 'palo_wp_' . $item->id,
            'url' => $item->image,
            'title' => $item->title,
            'captured_by' => $item->author_name,
            'captured_at' => $capturedAtBn,
            'location' => $item->location,
            'more_info_link' => $item->target,
        ];
    }, $stmt->fetchAll(PDO::FETCH_OBJ)));
} catch (\PDOException $e) {
    die('Connection failed: ' . $e->getMessage());
} catch (\Exception $e) {
    die($e->getMessage());
}
