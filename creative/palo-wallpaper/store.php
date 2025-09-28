<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');

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

// Map Bengali months to English
$bnMonths = [
    'জানুয়ারি' => '01',
    'ফেব্রুয়ারি' => '02',
    'মার্চ' => '03',
    'এপ্রিল' => '04',
    'মে' => '05',
    'জুন' => '06',
    'জুলাই' => '07',
    'আগস্ট' => '08',
    'সেপ্টেম্বর' => '09',
    'অক্টোবর' => '10',
    'নভেম্বর' => '11',
    'ডিসেম্বর' => '12'
];

// Convert Bengali digits to English
function bn2en($number)
{
    $bn = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
    $en = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
    return str_replace($bn, $en, $number);
}

try {
    $pdo = new PDO($dsn, $user, $pass, $options);

    $posts = json_decode(file_get_contents("php://input"), true) ?? [];

    foreach ($posts as $post) {
        // Get posted data
        $img    = $post['img'] ?? '';
        $title  = $post['title'] ?? '';
        $author = $post['author'] ?? '';
        $target = $post['target'] ?? '';

        // Process title
        $titleParts = explode('।', $title);
        $titleParts = array_map('trim', $titleParts);

        $locationParts = [];
        $capturedAt = null;

        if (count($titleParts) > 1) {
            // Process location
            $capturedAtPart = array_pop($titleParts);

            $locationParts = explode(',', $capturedAtPart);
            $locationParts = array_map('trim', $locationParts);

            if (count($locationParts) > 1) {
                // Extract and convert captured_at (e.g., '২৭ জুন') to PHP datetime with year 2025
                $capturedAtRaw = array_pop($locationParts);

                preg_match('/([০-৯]+)\s+([^\s]+)/u', trim($capturedAtRaw), $matches);
                if (count($matches) === 3) {
                    $day = bn2en($matches[1]);
                    $monthBn = $matches[2];
                    $monthEn = $bnMonths[$monthBn] ?? null;

                    if ($monthEn) {
                        $capturedAt = date('Y-m-d', strtotime("2025-$monthEn-$day"));
                    }
                }
            }
        }

        $titleProcessed = implode('। ', $titleParts);
        $locationProcessed = implode(', ', $locationParts);

        // Process author
        $authorProcessed = trim(str_replace('ছবি: ', '', $author));

        // Process target image
        $imgProcessed = strtok($img, '?');

        // Prepare SQL
        $sql = "INSERT INTO `palo_wallpapers` (title, subtitle, location, captured_at, author_name, target, image)
        VALUES (:title, :subtitle, :location, :captured_at, :author_name, :target, :image)";
        $stmt = $pdo->prepare($sql);

        // Bind and execute
        $stmt->execute([
            ':title'       => $titleProcessed,
            ':subtitle'    => null,
            ':location'    => $locationProcessed,
            ':captured_at' => $capturedAt,
            ':author_name' => $authorProcessed,
            ':target'      => $target,
            ':image'       => $imgProcessed
        ]);
    }
} catch (\PDOException $e) {
    die('Connection failed: ' . $e->getMessage());
} catch (\Exception $e) {
    die($e->getMessage() . " " .
        "Img: " . $img . ", Title: " . $title . ", Author: " . $author . ", Target: " . $target);
}
