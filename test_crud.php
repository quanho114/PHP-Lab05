<?php
 
// Setup error handling
set_error_handler(function($severity, $message, $file, $line) {
    throw new ErrorException($message, 0, $severity, $file, $line);
});
 
// Define helper view function mock since we run in CLI
function view($name, $data = []) {
    // Mock view function
    echo "Rendering view: $name\n";
    foreach ($data as $k => $v) {
        if (is_scalar($v)) {
            echo "  $k => $v\n";
        } else {
            echo "  $k => [" . count($v) . " items]\n";
        }
    }
}
 
// Require files
require_once __DIR__ . '/app/Core/helpers.php';
require_once __DIR__ . '/app/Core/Database.php';
require_once __DIR__ . '/app/Core/DuplicateRecordException.php';
require_once __DIR__ . '/app/Repositories/PatientRepository.php';
require_once __DIR__ . '/app/Repositories/AppointmentRepository.php';
require_once __DIR__ . '/app/Controllers/HomeController.php';
require_once __DIR__ . '/app/Controllers/HealthController.php';
require_once __DIR__ . '/app/Controllers/PatientController.php';
require_once __DIR__ . '/app/Controllers/AppointmentController.php';
 
// Load database config
$config = require __DIR__ . '/config/database.php';
 
echo "=== CLINIC APP INTEGRATION TESTS START ===\n\n";
 
try {
    // 1. Test Database connection
    echo "1. Connecting to database... ";
    $db = (new Database($config))->getConnection();
    echo "OK.\n";
 
    // 2. Test PatientRepository pagination and sorting
    echo "2. Testing PatientRepository count & pagination... ";
    $patientRepo = new PatientRepository($db);
    $totalPatients = $patientRepo->countAll();
    $paginatedPatients = $patientRepo->getPaginated('', '', 5, 0, 'created_at', 'desc');
    assert($totalPatients >= 15, "Expected at least 15 seeded patients.");
    assert(count($paginatedPatients) === 5, "Expected 5 patients in paginated result.");
    echo "OK.\n";
 
    // 3. Test PatientRepository creation and unique key constraint
    echo "3. Testing PatientRepository unique email validation... ";
    $testEmail = 'test_unique_' . time() . '@example.com';
    $patientData = [
        'name' => 'Integration Test Patient',
        'email' => $testEmail,
        'phone' => '0901234567',
        'gender' => 'male',
        'note' => 'CLI Test Patient Note'
    ];
 
    $success = $patientRepo->create($patientData);
    assert($success === true, "Failed to create new unique patient.");
 
    // Try to create duplicate
    $duplicateCaught = false;
    try {
        $patientRepo->create($patientData);
    } catch (DuplicateRecordException $e) {
        $duplicateCaught = true;
    }
    assert($duplicateCaught === true, "Duplicate email registration did not throw DuplicateRecordException.");
    echo "OK.\n";
 
    // 4. Test PatientRepository find, update, and delete
    echo "4. Testing PatientRepository find, update & delete... ";
    $q = $db->query("SELECT id FROM patients WHERE email = " . $db->quote($testEmail));
    $patientId = $q->fetch()['id'] ?? null;
    assert($patientId !== null, "Could not find created patient id.");
 
    $patient = $patientRepo->findById($patientId);
    assert($patient['name'] === 'Integration Test Patient', "Patient name mismatch.");
 
    $patientData['name'] = 'Integration Test Patient Updated';
    $patientRepo->update($patientId, $patientData);
    $updatedPatient = $patientRepo->findById($patientId);
    assert($updatedPatient['name'] === 'Integration Test Patient Updated', "Patient update failed.");
 
    $patientRepo->delete($patientId);
    $deletedPatient = $patientRepo->findById($patientId);
    assert($deletedPatient === null, "Patient deletion failed.");
    echo "OK.\n";
 
    // 5. Test AppointmentRepository pagination and sorting
    echo "5. Testing AppointmentRepository count & pagination... ";
    $apptRepo = new AppointmentRepository($db);
    $totalAppts = $apptRepo->countAll();
    $paginatedAppts = $apptRepo->getPaginated('', '', 5, 0, 'appointment_date', 'desc');
    assert($totalAppts >= 15, "Expected at least 15 seeded appointments.");
    assert(count($paginatedAppts) === 5, "Expected 5 appointments in paginated result.");
    echo "OK.\n";
 
    // 6. Test AppointmentRepository creation and unique key constraint
    echo "6. Testing AppointmentRepository unique appointment_code validation... ";
    $testCode = 'APT-TEST-' . time();
    $apptData = [
        'appointment_code' => $testCode,
        'patient_name' => 'Integration Test Patient',
        'patient_email' => 'patient@example.com',
        'appointment_date' => date('Y-m-d H:i:s', strtotime('+2 days')),
        'status' => 'pending',
        'note' => 'Test appointment note'
    ];
 
    $success = $apptRepo->create($apptData);
    assert($success === true, "Failed to create new unique appointment.");
 
    // Try to create duplicate
    $duplicateCaught = false;
    try {
        $apptRepo->create($apptData);
    } catch (DuplicateRecordException $e) {
        $duplicateCaught = true;
    }
    assert($duplicateCaught === true, "Duplicate appointment_code registration did not throw DuplicateRecordException.");
    echo "OK.\n";
 
    // 7. Test AppointmentRepository find, update, and delete
    echo "7. Testing AppointmentRepository find, update & delete... ";
    $q = $db->query("SELECT id FROM appointments WHERE appointment_code = " . $db->quote($testCode));
    $apptId = $q->fetch()['id'] ?? null;
    assert($apptId !== null, "Could not find created appointment id.");
 
    $appt = $apptRepo->findById($apptId);
    assert($appt['patient_name'] === 'Integration Test Patient', "Patient name mismatch.");
 
    $apptData['patient_name'] = 'Integration Test Patient Updated';
    $apptRepo->update($apptId, $apptData);
    $updatedAppt = $apptRepo->findById($apptId);
    assert($updatedAppt['patient_name'] === 'Integration Test Patient Updated', "Appointment update failed.");
 
    $apptRepo->delete($apptId);
    $deletedAppt = $apptRepo->findById($apptId);
    assert($deletedAppt === null, "Appointment deletion failed.");
    echo "OK.\n";
 
    echo "\n=== ALL CLINIC INTEGRATION TESTS PASSED SUCCESSFULLY! ===\n";
    exit(0);
 
} catch (Throwable $e) {
    echo "\n!!! TEST FAILURE !!!\n";
    echo "Message: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}
