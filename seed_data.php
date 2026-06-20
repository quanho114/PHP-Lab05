<?php
 
require __DIR__ . '/app/Core/Database.php';
require __DIR__ . '/app/Core/helpers.php';
 
$config = require __DIR__ . '/config/database.php';
 
try {
    echo "Connecting to database... ";
    $db = (new Database($config))->getConnection();
    echo "Connected.\n";
 
    // 1. Generate patients
    echo "Seeding patients... ";
    $firstNames = ['Nguyen', 'Tran', 'Le', 'Pham', 'Hoang', 'Phan', 'Vu', 'Vo', 'Dang', 'Bui'];
    $middleNames = ['Van', 'Thi', 'Minh', 'Duc', 'Hoang', 'An', 'Hai', 'Xuan', 'Ngoc', 'Quang'];
    $lastNames = ['Anh', 'Binh', 'Chi', 'Dung', 'Em', 'Giang', 'Huong', 'Khanh', 'Linh', 'Minh', 'Nam', 'Oanh', 'Phuc', 'Quynh', 'Son', 'Trang', 'Tuan', 'Vy', 'Yen'];
    $genders = ['male', 'female', 'other'];
    $statuses = ['pending', 'confirmed', 'completed', 'cancelled'];
 
    $patientCount = 150;
    $patientIds = [];
 
    // Prepare inserts
    $patientStmt = $db->prepare("INSERT IGNORE INTO patients (name, email, phone, gender, note, created_at) VALUES (:name, :email, :phone, :gender, :note, :created_at)");
    
    for ($i = 0; $i < $patientCount; $i++) {
        $name = $firstNames[array_rand($firstNames)] . ' ' . $middleNames[array_rand($middleNames)] . ' ' . $lastNames[array_rand($lastNames)];
        $email = strtolower(str_replace(' ', '', $name)) . $i . '@example.com';
        $phone = '090' . str_pad($i, 7, '0', STR_PAD_LEFT);
        $gender = $genders[array_rand($genders)];
        $note = "Seeded automatically for pagination testing. Patient reference #$i";
        // Distribute created_at over the last 30 days
        $daysAgo = rand(0, 30);
        $createdAt = date('Y-m-d H:i:s', strtotime("-$daysAgo days"));
 
        $patientStmt->execute([
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'gender' => $gender,
            'note' => $note,
            'created_at' => $createdAt
        ]);
        
        $patientIds[] = $db->lastInsertId();
    }
    echo "Successfully seeded $patientCount patients.\n";
 
    // 2. Generate appointments
    echo "Seeding appointments... ";
    $apptStmt = $db->prepare("INSERT IGNORE INTO appointments (appointment_code, patient_name, patient_email, appointment_date, status, note, created_at) VALUES (:code, :name, :email, :date, :status, :note, :created_at)");
 
    for ($i = 0; $i < $patientCount; $i++) {
        $code = 'APT-AUTO-' . str_pad($i, 4, '0', STR_PAD_LEFT);
        
        // Random patient info
        $name = $firstNames[array_rand($firstNames)] . ' ' . $middleNames[array_rand($middleNames)] . ' ' . $lastNames[array_rand($lastNames)];
        $email = strtolower(str_replace(' ', '', $name)) . $i . '@example.com';
        
        $daysOffset = rand(-10, 20);
        $apptDate = date('Y-m-d H:i:s', strtotime("$daysOffset days " . rand(8, 17) . ":00:00"));
        $status = $statuses[array_rand($statuses)];
        $note = "Auto-generated appointment scheduling reference #$i";
        $createdAt = date('Y-m-d H:i:s', strtotime("-15 days"));
 
        $apptStmt->execute([
            'code' => $code,
            'name' => $name,
            'email' => $email,
            'date' => $apptDate,
            'status' => $status,
            'note' => $note,
            'created_at' => $createdAt
        ]);
    }
    echo "Successfully seeded $patientCount appointments.\n";
    echo "All data seeding completed successfully!\n";
 
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
