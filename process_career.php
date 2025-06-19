<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['email'])) {
    echo json_encode(["status" => "error", "message" => "User not logged in"]);
    exit();
}

// Database connection
$db = new mysqli('localhost', 'root', '', 'career_guidance');
if ($db->connect_error) {
    echo json_encode(["status" => "error", "message" => "Database connection failed"]);
    exit();
}

// Retrieve form data
$education = $_POST['education_level'] ?? '';
$field_of_study = $_POST['field_of_study'] ?? '';
$skills = $_POST['skills'] ?? '';
$interest = $_POST['interest'] ?? '';

if (empty($education) || empty($field_of_study) || empty($skills) || empty($interest)) {
    echo json_encode(["status" => "error", "message" => "All fields are required"]);
    exit();
}

// Process input for flexible matching
$education = strtolower($education);
$field_of_study = strtolower($field_of_study);
$skills_array = array_map('trim', explode(',', strtolower($skills)));
$interest_array = array_map('trim', explode(',', strtolower($interest)));

$query = "SELECT DISTINCT career_suggestions FROM careers WHERE LOWER(education_level) = ? OR LOWER(field_of_study) = ?";

$params = ["ss", $education, $field_of_study];

foreach (array_merge($skills_array, $interest_array) as $param) {
    $query .= " OR LOWER(skills_required) LIKE ? OR LOWER(career_suggestions) LIKE ?";
    $params[0] .= "ss";
    $params[] = "%$param%";
    $params[] = "%$param%";
}

$stmt = $db->prepare($query);
$stmt->bind_param(...$params);
$stmt->execute();
$result = $stmt->get_result();

$careers = [];
while ($row = $result->fetch_assoc()) {
    $careers[] = $row['career_suggestions'];
}

echo json_encode(["status" => empty($careers) ? "error" : "success", "careers" => $careers]);

$stmt->close();
$db->close();
?>
