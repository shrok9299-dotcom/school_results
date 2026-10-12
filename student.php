<?php
$conn = mysqli_connect("localhost", "root", "", "school_db");

if (!$conn) {
    die("Database connection failed!");
}

$students = [];
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $searchName = trim($_POST["search_name"] ?? "");

    if ($searchName !== "") {
        $stmt = mysqli_prepare(
            $conn,
            "SELECT * FROM students WHERE name LIKE ?"
        );

        $searchValue = "%" . $searchName . "%";

        mysqli_stmt_bind_param($stmt, "s", $searchValue);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        while ($row = mysqli_fetch_assoc($result)) {
            $students[] = $row;
        }

        mysqli_stmt_close($stmt);

        if (count($students) === 0) {
            $error = "No student found. Please check your name.";
        }
    } else {
        $error = "Please enter a student name.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Student Check</title>

    <style>
        body {
            background: #0a090e;
            color: #f8edf4;
            font-family: Arial, sans-serif;
            min-height: 100vh;
        }

        .student-wrapper {
            max-width: 720px;
            margin: 35px auto;
        }

        .student-card {
            background: linear-gradient(145deg, #18121a, #100d13);
            border: 1px solid #57203d;
            border-radius: 22px;
            padding: 30px;
            margin-bottom: 25px;
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.35);
        }

        .student-heading {
            text-align: center;
            color: #f9a8d4;
            font-weight: 800;
            margin-bottom: 12px;
        }

        .student-subtitle {
            color: #b9a5b4;
            text-align: center;
            margin-bottom: 25px;
        }

        .form-label {
            color: #f3d9e7;
        }

        .form-control {
            background: #0b090e;
            color: #ffffff;
            border: 1px solid #60334d;
            border-radius: 12px;
            padding: 13px;
        }

        .form-control:focus {
            background: #0b090e;
            color: #ffffff;
            border-color: #ec4899;
            box-shadow: 0 0 0 0.2rem rgba(236, 72, 153, 0.15);
        }

        .form-control::placeholder {
            color: #938391;
        }

        .search-btn {
            width: 100%;
            background: linear-gradient(100deg, #831843, #be185d);
            color: white;
            border: none;
            padding: 13px;
            border-radius: 12px;
            font-weight: bold;
            transition: 0.3s;
        }

        .search-btn:hover {
            background: linear-gradient(100deg, #9d174d, #db2777);
            color: white;
            transform: translateY(-2px);
        }

        .result-card {
            background: linear-gradient(145deg, #1d121d, #100d13);
            border: 1px solid #9d174d;
            border-radius: 22px;
            padding: 30px;
            margin-bottom: 20px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(157, 23, 77, 0.12);
        }

        .result-label {
            color: #f472b6;
            letter-spacing: 2px;
            font-size: 13px;
            font-weight: bold;
        }

        .student-name {
            color: #ffffff;
            font-weight: 800;
            overflow-wrap: anywhere;
        }

        .score-number {
            color: #f9a8d4;
            font-size: 38px;
            font-weight: 800;
        }

        .score-total {
            color: #bba9b7;
            font-size: 18px;
        }

        .pass-alert {
            background: #123522;
            color: #bbf7d0;
            border: 1px solid #166534;
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 0;
        }

        .fail-alert {
            background: #3b111b;
            color: #fecdd3;
            border: 1px solid #9f1239;
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 0;
        }

        .error-message {
            background: #351522;
            color: #fecdd3;
            border: 1px solid #831843;
            padding: 14px;
            border-radius: 12px;
            text-align: center;
        }

        @media (max-width: 576px) {
            .student-wrapper {
                margin: 20px 12px;
            }

            .student-card,
            .result-card {
                padding: 22px 16px;
            }
        }
    </style>
</head>

<body>

<?php include 'nav.php'; ?>

<div class="container student-wrapper">

    <div class="student-card">

        <h2 class="student-heading">Find Your Result</h2>

        <p class="student-subtitle">
            Enter your name to check your academic result.
        </p>

        <form method="POST" action="student.php">

            <div class="mb-4">
                <label class="form-label fw-bold">
                    Enter Your Name
                </label>

                <input
                    type="text"
                    name="search_name"
                    class="form-control"
                    placeholder="e.g. Ahmed"
                    required
                >
            </div>

            <button type="submit" class="btn search-btn">
                Search My Result
            </button>

        </form>

    </div>

    <?php foreach ($students as $student) {
        $isPass = $student["score"] >= 50;
    ?>

        <div class="result-card">

            <p class="result-label">OFFICIAL RESULT</p>

            <hr style="border-color: #67304d;">

            <h2 class="student-name mb-4">
                <?= htmlspecialchars($student["name"]) ?>
            </h2>

            <p class="mb-1" style="color: #d8c4d2;">
                Your Score
            </p>

            <div class="mb-4">
                <span class="score-number">
                    <?= (int) $student["score"] ?>
                </span>

                <span class="score-total"> / 100</span>
            </div>

            <div class="<?= $isPass ? 'pass-alert' : 'fail-alert' ?>">
                Status:
                <strong>
                    <?= $isPass ? "Pass" : "Fail" ?>
                </strong>
            </div>

        </div>

    <?php } ?>

    <?php if ($error !== "") { ?>
        <div class="error-message mb-4">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php } ?>

</div>

</body>
</html>














