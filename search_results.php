<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <script async src="https://cse.google.com/cse.js?cx=b36ee7af8bff64d24"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background: #f4f4f9;
            margin: 20px;
        }

        .search-container {
            margin: 20px auto;
            width: 100%;
            max-width: 600px;
            background: #fff;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        input[type="text"] {
            flex: 1;
            padding: 12px;
            border: 2px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            outline: none;
        }

        button {
            padding: 12px 20px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background: #0056b3;
        }

        .results-container {
            width: 80%;
            margin: 30px auto;
            text-align: left;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            font-size: 16px;
            color: #007bff;
            font-weight: bold;
            transition: 0.3s;
        }

        .back-link:hover {
            text-decoration: underline;
            color: #0056b3;
        }
    </style>
</head>
<body>

    <div class="search-container">
        <form action="search_results.php" method="GET">
            <input type="text" name="q" value="<?php echo htmlspecialchars($_GET['q'] ?? ''); ?>" required>
            <button type="submit">🔍 Search</button>
        </form>
    </div>

    <div class="results-container">
        <gcse:search></gcse:search>
    </div>

    <a href="dashboard.php" class="back-link">🔙 Back to Dashboard</a>

</body>
</html>

