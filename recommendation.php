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

// Retrieve user inputs
$education = $_POST['education_level'] ?? '';
$field_of_study = $_POST['field_of_study'] ?? '';
$skills = $_POST['skills'] ?? '';
$interest = $_POST['interest'] ?? '';

if (empty($education) || empty($field_of_study) || empty($skills) || empty($interest)) {
    echo json_encode(["status" => "error", "message" => "All fields are required"]);
    exit();
}

// Convert input values into arrays for flexible searching
$skills_array = array_filter(array_map('trim', explode(',', $skills)));
$interest_array = array_filter(array_map('trim', explode(',', $interest)));

// Construct the query dynamically based on inputs
$query = "SELECT DISTINCT career_suggestions FROM careers WHERE education_level LIKE ? OR field_of_study LIKE ?";
$params = ["%$education%", "%$field_of_study%"];

if (!empty($skills_array)) {
    foreach ($skills_array as $skill) {
        $query .= " OR skills_required LIKE ?";
        $params[] = "%$skill%";
    }
}
if (!empty($interest_array)) {
    foreach ($interest_array as $interest) {
        $query .= " OR career_suggestions LIKE ?";
        $params[] = "%$interest%";
    }
}

$stmt = $db->prepare($query);
$stmt->bind_param(str_repeat("s", count($params)), ...$params);
$stmt->execute();
$result = $stmt->get_result();

$careers = [];
while ($row = $result->fetch_assoc()) {
    $careers[] = $row['career_suggestions'];
}

if (!empty($careers)) {
    echo json_encode(["status" => "success", "careers" => $careers]);
} else {
    echo json_encode(["status" => "error", "message" => "No career suggestions found"]);
}

$stmt->close();
$db->close();
?>

