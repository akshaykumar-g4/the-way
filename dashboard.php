<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php"); // Redirect if not logged in
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Career Guidance Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        body {
            background: linear-gradient(135deg, #e0eafc, #cfdef3);
            display: flex;
            flex-direction: column;
            align-items: center;
            height: 100vh;
            padding-top: 50px;
        }
        .container {
            width: 80%;
            max-width: 1000px;
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
            margin-bottom: 20px;
        }
        h2 {
            text-align: center;
            color: #222;
            font-weight: 600;
        }
        form {
            margin-top: 20px;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 20px;
        }
        .form-group {
            flex: 1 1 calc(50% - 20px);
            min-width: 220px;
        }
        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 5px;
        }
        .form-group input, .form-group select {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 10px;
            font-size: 16px;
            transition: border 0.3s ease;
        }
        .form-group input:focus, .form-group select:focus {
            border-color: #007bff;
            outline: none;
        }
        .btn {
            width: 100%;
            max-width: 300px;
            margin: 0 auto;
            display: block;
            padding: 14px;
            background: linear-gradient(90deg, #007bff, #00d4ff);
            color: white;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-size: 18px;
            font-weight: 600;
            transition: all 0.3s ease-in-out;
        }
        .btn:hover {
            background: linear-gradient(90deg, #0056b3, #0095ff);
            transform: scale(1.05);
        }
        .career-results {
            margin-top: 20px;
            padding: 15px;
            border-radius: 10px;
            background: #f9f9f9;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .career-card {
            background: #e3f2fd;
            padding: 10px;
            margin: 10px 0;
            border-radius: 8px;
            text-align: center;
            font-weight: 600;
            color: #0056b3;
        }
        .search-container {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 80%; /* Adjust based on container width */
    max-width: 800px; /* Prevent excessive stretching */
    margin: 20px auto; /* Centering */
}

/* Target the entire Google Search Box */
.gsc-control-cse {
    background: white !important;
    border: none !important;
    padding: 8px !important;
    border-radius: 25px !important;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
    width: 100% !important; /* Match the container width */
}

/* Remove unwanted inner borders */
.gsc-input-box {
    border: 1.5px solid #007bff !important;
    border-radius: 25px !important;
    padding: 10px !important;
    box-shadow: none !important;
    width: 100% !important; /* Make input field full width */
}

/* Search input styling */
input.gsc-input {
    border: none !important;
   /* background: white !important;*/
    font-size: 16px !important;
    padding: 10px !important;
    border-radius: 25px !important;
    outline: none !important;
    width: 100% !important;
}

/* Fix the search button */
button.gsc-search-button {
    border-radius: 30% !important;
    background: linear-gradient(90deg, #007bff, #00d4ff) !important;
    color: white !important;
    padding: 20px 15px !important;
    border: none !important;
    transition: all 0.3s ease-in-out;
    cursor: pointer;
}

button.gsc-search-button:hover {
    background: linear-gradient(90deg, #0056b3, #0095ff) !important;
    transform: scale(1.05);
}

/* Fix the search result area */
.gsc-results-wrapper-overlay {
    border-radius: 15px !important;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1) !important;
    padding: 15px !important;
}

    </style>
</head>
<body>
    <!-- Google Custom Search Bar -->
    <div class="search-container">
        <script async src="https://cse.google.com/cse.js?cx=b36ee7af8bff64d24"></script>
        <div class="gcse-search"></div>
    </div>

    <div class="container">
        <h2>Welcome to CGS, <?php echo $_SESSION['email']; ?>!</h2>
        <form id="careerForm">
            <div class="form-group">
                <label>Education Level</label>
                <select name="education_level" id="education_level" required>
                    <option value="">Select</option>
                    <option>High School</option>
                    <option>Intermediate</option>
                    <option>Undergraduate</option>
                    <option>Postgraduate</option>
                    <option>Job Seeker</option>
                </select>
            </div>
            <div class="form-group">
                <label>Field of Study/Work</label>
                <input type="text" name="field_of_study" id="field_of_study" placeholder="e.g., CSE, Mechanical, BiPC" required>
            </div>
            <div class="form-group">
                <label>Skill Set</label>
                <input type="text" name="skills" id="skills" placeholder="e.g., Coding, Communication" required>
            </div>
            <div class="form-group">
                <label>Interested Fields to Work</label>
                <input type="text" name="interest" id="interest" placeholder="e.g., Data Scientist, Software Developer" required>
            </div>
            <button type="submit" class="btn">SUBMIT</button>
        </form>
        <div class="career-results"></div>
    </div>

    <script>
        $(document).ready(function() {
            $("#careerForm").submit(function(event) {
                event.preventDefault();

                $.ajax({
                    url: "process_career.php",
                    type: "POST",
                    data: $(this).serialize(),
                    dataType: "json",
                    success: function(response) {
                        let resultContainer = $(".career-results");
                        resultContainer.html(""); // Clear previous results

                        if (response.status === "success") {
                            let careersHTML = "<h2>Recommended Careers</h2>";
                            response.careers.forEach(function(career) {
                                careersHTML += `<div class='career-card'><h3>${career}</h3></div>`;
                            });
                            resultContainer.html(careersHTML);
                        } else {
                            resultContainer.html("<p style='color: red; text-align: center;'>No career suggestions found.</p>");
                        }
                    },
                    error: function() {
                        alert("An error occurred while processing the request.");
                    }
                });
            });
        });
    </script>
</body>
</html>
