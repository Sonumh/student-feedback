<?php
// ============================================
// submit.php — Handle feedback form POST
// ============================================
header('Content-Type: application/json');
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
    exit;
}

// Sanitize inputs
$event_id     = isset($_POST['event_id'])     ? (int) $_POST['event_id']              : 0;
$student_name = isset($_POST['student_name']) ? trim(strip_tags($_POST['student_name'])) : '';
$student_id   = isset($_POST['student_id'])   ? trim(strip_tags($_POST['student_id']))   : '';
$rating       = isset($_POST['rating'])       ? (int) $_POST['rating']                : 0;

// Validate
if ($event_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Please select a valid event.']); exit;
}
if (empty($student_name) || strlen($student_name) > 255) {
    echo json_encode(['success' => false, 'message' => 'Please enter a valid student name.']); exit;
}
if (empty($student_id) || strlen($student_id) > 100) {
    echo json_encode(['success' => false, 'message' => 'Please enter a valid Student ID.']); exit;
}
if ($rating < 1 || $rating > 5) {
    echo json_encode(['success' => false, 'message' => 'Please select a rating between 1 and 5.']); exit;
}

try {
    $db = getDB();

    // Verify event exists and is active
    $stmt = $db->prepare("SELECT id FROM events WHERE id = ? AND is_active = 1");
    $stmt->execute([$event_id]);
    if (!$stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Selected event is not available.']); exit;
    }

    // Insert feedback
    $ins = $db->prepare("INSERT INTO feedback (event_id, student_name, student_id, rating) VALUES (?, ?, ?, ?)");
    $ins->execute([$event_id, $student_name, $student_id, $rating]);

    echo json_encode(['success' => true, 'message' => 'Feedback submitted successfully!']);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error. Please try again later.']);
}
?>
