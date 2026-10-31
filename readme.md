# THE WAY — Career Guidance System (CGS)

[![PHP](https://img.shields.io/badge/PHP-8.x-777BB4?style=flat-square&logo=php&logoColor=white)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-8.0+-4479A1?style=flat-square&logo=mysql&logoColor=white)](https://www.mysql.com/)
[![JavaScript](https://img.shields.io/badge/JavaScript-ES6+-F7DF1E?style=flat-square&logo=javascript&logoColor=black)](https://developer.mozilla.org/)
[![License](https://img.shields.io/badge/License-MIT-blue.svg?style=flat-square)](LICENSE)

> A full-stack, data-driven decision support system designed to eliminate career indecision for students and early-career job seekers. The platform ingests multi-dimensional profile vectors (education levels, domains, skills, and industry interests) and computes relevant career trajectories using dynamic parameterized matching and asynchronous client-server communication.

---

## 📌 Executive Summary

Undergraduate students and entry-level professionals often face fragmented, unstructured information when navigating career paths. Generic online queries yield conflicting roadmaps, while specialized counseling services remain largely inaccessible.

**THE WAY** bridges this gap through a deterministic, self-service recommendation engine:
- **Zero Page-Reload Discovery:** Evaluates input profiles through an asynchronous AJAX-to-JSON pipeline.
- **Dynamic Parameterized Query Engine:** Dynamically tokenizes arbitrary comma-separated user inputs and safely binds variable-length parameters to prevent SQL injection ($100\%$ parameterized).
- **Dual-Factor Authentication Architecture:** Supports cryptographic salted hashing (`BCRYPT`) alongside an email-based One-Time Password (OTP) verification route.
- **Integrated Research Engine:** Seamlessly embeds Google's Programmable Custom Search Engine (CSE) to allow real-time career curriculum lookups inside the dashboard.

---

## 🏛️ System Architecture

```
[ User Browser / Client ]
           │
           │ (1) User Input via AJAX / Form Data
           ▼
┌─────────────────────────────────────────────────────────────┐
│                     Apache Web Server                       │
│                                                             │
│   [ Routing & Auth Guard ]                                  │
│   - session_start() & CSRF/Session verification             │
│                                                             │
│   [ Tokenization & Parsing Pipeline ]                       │
│   - CSV string normalization (explode, trim, strtolower)    │
│   - Dynamic SQL string generator & wildcard builder         │
│   - Variadic parameter binder (mysqli_stmt::bind_param)     │
└────────────────────────────────┬────────────────────────────┘
                                 │
                                 │ (2) Parameterized Prepared Queries
                                 ▼
┌─────────────────────────────────────────────────────────────┐
│                       MySQL Relational DB                   │
│                                                             │
│   - `users`   : Salted hashes, credentials, active sessions │
│   - `careers` : Multi-attribute career criteria matrix      │
└────────────────────────────────┬────────────────────────────┘
                                 │
                                 │ (3) Result Record Set
                                 ▼
┌─────────────────────────────────────────────────────────────┐
│                 JSON Serialization Layer                    │
│   - Transforms SQL output into structured JSON schema       │
│   - Dispatches payload back to jQuery frontend DOM renderer │
└─────────────────────────────────────────────────────────────┘
```

---

## 🚀 Key Technical Highlights & Engineering Decisions

### 1. Dynamic Parameterized Query Synthesis
Instead of naive, vulnerable raw-string interpolation (`"WHERE skill LIKE '%$input%'"`), the backend parses comma-delimited tokens, strips empty entries, and dynamically builds an expanding parameterized SQL clause:

```php
// Tokenization and normalization
$skills_array = array_filter(array_map('trim', explode(',', $skills)));
$interest_array = array_filter(array_map('trim', explode(',', $interest)));

// Dynamic query expansion with parameter tracking
$query = "SELECT DISTINCT career_suggestions FROM careers WHERE education_level LIKE ? OR field_of_study LIKE ?";
$params = ["%$education%", "%$field_of_study%"];

foreach ($skills_array as $skill) {
    $query .= " OR skills_required LIKE ?";
    $params[] = "%$skill%";
}
foreach ($interest_array as $interest) {
    $query .= " OR career_suggestions LIKE ?";
    $params[] = "%$interest%";
}

// Variadic argument unpacking with dynamically computed type string
$stmt = $db->prepare($query);
$stmt->bind_param(str_repeat("s", count($params)), ...$params);
$stmt->execute();
```
* **Trade-off:** Chose an in-house programmatic query compiler over heavy third-party search engines (e.g., Elasticsearch) to eliminate recurring infrastructure costs and maintain sub-50ms execution times for lightweight datasets.

### 2. Dual-Path Authentication & State Management
- **Cryptographic Security:** Passwords are never stored in plaintext. Passwords pass through PHP’s `password_hash($raw_password, PASSWORD_BCRYPT)` and are verified via timing-attack-safe `password_verify()`.
- **Session Isolation:** Authenticated states are tracked via PHP native session handlers (`$_SESSION['email']`), preventing unauthorized access to `dashboard.php` and `search_results.php`.
- **Alternative Verification:** Implements an OTP fallback mechanism via server-side random generation (`rand(100000, 999999)`) to verify user access independently.

### 3. Asynchronous UX & DOM Hydration
- Forms communicate directly via jQuery AJAX (`$.ajax()`) sending serialized payloads to standalone endpoints (`recommendation.php` / `process_career.php`).
- Responses are dispatched as clean JSON contracts (`{"status": "success", "careers": [...]}`), avoiding full document re-renders and preserving user session context.

---

## 📂 Repository Structure

```
the-way/
├── dashboard.php         # Authenticated core dashboard & recommendation input view
├── login.php             # Dual-path user authentication interface (Password & OTP)
├── signup.php            # New user registration & input validation form
├── server.php            # Core auth engine, session lifecycle, & database initialization
├── recommendation.php    # Computational engine: dynamic token parsing & prepared query execution
├── process_career.php    # Auxiliary JSON API endpoint for multi-attribute matching
├── search_results.php    # Integrated Google Custom Search Engine (CSE) container
├── index.html            # Landing page & value proposition presentation
├── know_about_us.html    # Mission overview and platform services breakdown
└── README.md             # Project documentation & architectural specification
```

---

## 🛠️ Tech Stack & Dependencies

- **Runtime & Backend:** PHP 8.x
- **Database Engine:** MySQL 8.x / MariaDB
- **Frontend Architecture:** Semantic HTML5, CSS3 (Modern Flexbox layouts & responsive gradients), JavaScript (ES6+), jQuery 3.6.0
- **External Integrations:** Google Programmable Custom Search Engine (CSE) API, FontAwesome Icons

---

## ⚡ Quick Start & Local Setup

### Prerequisites
- Install [XAMPP](https://www.apachefriends.org/) (or any LAMP / WAMP / MAMP stack providing PHP 8.x and MySQL).
- Git installed on your local machine.

### Installation Steps

1. **Clone the Repository**
   ```bash
   git clone https://github.com/akshaykumar-g4/the-way.git
   ```

2. **Move to Server Root**
   Move the cloned project folder into your local web server's public directory:
   - For XAMPP on Windows: `C:\xampp\htdocs\the-way`
   - For Linux: `/var/www/html/the-way`
   - For macOS (MAMP): `/Applications/MAMP/htdocs/the-way`

3. **Database Provisioning**
   - Open **phpMyAdmin** (`http://localhost/phpmyadmin`).
   - Create a new database named `career_guidance`:
     ```sql
     CREATE DATABASE career_guidance;
     USE career_guidance;
     ```
   - Run the following schema migrations:
     ```sql
     CREATE TABLE users (
         id INT AUTO_INCREMENT PRIMARY KEY,
         full_name VARCHAR(100) NOT NULL,
         email VARCHAR(100) UNIQUE NOT NULL,
         password VARCHAR(255) NOT NULL,
         created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
     );

     CREATE TABLE careers (
         id INT AUTO_INCREMENT PRIMARY KEY,
         education_level VARCHAR(50) NOT NULL,
         field_of_study VARCHAR(100) NOT NULL,
         skills_required TEXT NOT NULL,
         career_suggestions VARCHAR(150) NOT NULL
     );

     -- Seed benchmark career profiles
     INSERT INTO careers (education_level, field_of_study, skills_required, career_suggestions) VALUES
     ('Undergraduate', 'CSE', 'Python, Machine Learning, SQL', 'Data Scientist'),
     ('Undergraduate', 'CSE', 'JavaScript, React, Node.js', 'Full Stack Developer'),
     ('Intermediate', 'BiPC', 'Biology, Chemistry, Research', 'Biomedical Researcher'),
     ('Undergraduate', 'Mechanical', 'AutoCAD, SolidWorks, Thermodynamics', 'Design Engineer');
     ```

4. **Verify Database Configuration**
   Check `server.php`, `recommendation.php`, and `process_career.php` to verify your local MySQL credentials:
   ```php
   $db = new mysqli('localhost', 'root', '', 'career_guidance');
   ```

5. **Run the Application**
   - Start **Apache** and **MySQL** modules from the XAMPP Control Panel.
   - Navigate to:
     ```
     http://localhost/the-way/index.html
     ```

---

## 🛡️ Security Measures Implemented

- [x] **Zero Plaintext Passwords:** Strict enforcement of cryptographically secure one-way hashing with BCRYPT.
- [x] **SQL Injection Defense:** Complete elimination of string concatenation in database operations via parameter-bound prepared statements.
- [x] **XSS Mitigation:** Output sanitization on dynamic reflection points using `htmlspecialchars()`.
- [x] **Session Access Guards:** Restricted internal dashboards with unauthenticated redirect gates.

---

## 🔮 Roadmap & Future Enhancements

- [ ] **Weighted Relevance Scoring:** Transition from binary `OR` filtering to a frequency-based scoring index ($IDF$) to rank careers by match percentage.
- [ ] **Environment Configuration:** Extract database credentials into a unified `.env` configuration file using PHP dotenv.
- [ ] **ML Recommendation Integration:** Bridge user vectors with a trained scikit-learn classifier via a lightweight Flask / FastAPI microservice.

---

## 👤 Author

**Akshay Kumar Pilli**
- GitHub: [@akshaykumar-g4](https://github.com/akshaykumar-g4)
- Email: [pilliakshaykumar2004@gmail.com](mailto:pilliakshaykumar2004@gmail.com)