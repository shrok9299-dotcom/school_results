<?php
$conn = mysqli_connect("localhost", "root", "", "school_db");

if (!$conn) {
    die("Database connection failed!");
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["student_name"] ?? "");
    $score = $_POST["student_score"] ?? "";

    if (
        $name !== "" &&
        $score !== "" &&
        is_numeric($score) &&
        $score >= 0 &&
        $score <= 100
    ) {
        $score = (int) $score;

        $stmt = mysqli_prepare(
            $conn,
            "INSERT INTO students (name, score) VALUES (?, ?)"
        );

        mysqli_stmt_bind_param($stmt, "si", $name, $score);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        header("Location: index.php");
        exit;
    } else {
        $error = "Enter a name and a score between 0 and 100.";
    }
}

if (
    isset($_GET["delete_id"]) &&
    ctype_digit($_GET["delete_id"])
) {
    $id = (int) $_GET["delete_id"];

    $stmt = mysqli_prepare(
        $conn,
        "DELETE FROM students WHERE id = ?"
    );

    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin Dashboard</title>

    <style>
        body {
            background: #0b0b10;
            color: #f5eaf0;
            font-family: Arial, sans-serif;
        }

        .page-title {
            color: #f9a8d4;
            font-weight: 800;
            margin-bottom: 25px;
        }

        .custom-card {
            background: #15131a;
            border: 1px solid #3d1b30;
            border-radius: 18px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.25);
        }

        .section-title {
            color: #f472b6;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .form-label {
            color: #eadde5;
        }

        .form-control {
            background: #0d0c11;
            border: 1px solid #593047;
            color: #ffffff;
            border-radius: 10px;
            padding: 12px;
        }

        .form-control:focus {
            background: #0d0c11;
            color: #ffffff;
            border-color: #ec4899;
            box-shadow: 0 0 0 0.2rem rgba(236, 72, 153, 0.15);
        }

        .form-control::placeholder {
            color: #9b8c98;
        }

        .btn-pink {
            background: #9d174d;
            color: #ffffff;
            border: 1px solid #be185d;
            border-radius: 10px;
            padding: 10px 20px;
            font-weight: bold;
            transition: 0.3s;
        }

        .btn-pink:hover {
            background: #be185d;
            color: #ffffff;
            transform: translateY(-1px);
        }

        .table-responsive {
            border-radius: 12px;
        }

        .custom-table {
            --bs-table-bg: #17141c;
            --bs-table-color: #f5eaf0;
            --bs-table-striped-bg: #201923;
            --bs-table-striped-color: #ffffff;
            --bs-table-hover-bg: #30202d;
            --bs-table-hover-color: #ffffff;
            --bs-table-border-color: #3b2938;
            margin-bottom: 0;
        }

        .custom-table thead th {
            background: #3b1029;
            color: #fbcfe8;
            padding: 15px;
            white-space: nowrap;
        }

        .custom-table td {
            padding: 14px;
            vertical-align: middle;
        }

        .status-pass {
            background: #14532d;
            color: #bbf7d0;
        }

        .status-fail {
            background: #7f1d1d;
            color: #fecaca;
        }

        .delete-btn {
            color: #fda4af;
            border: 1px solid #9f1239;
            border-radius: 8px;
        }

        .delete-btn:hover {
            background: #9f1239;
            color: white;
        }

        .error-message {
            color: #fecdd3;
            background: #3b101c;
            border: 1px solid #881337;
            border-radius: 10px;
            padding: 12px;
            margin-top: 15px;
        }
    </style>
</head>

<body>

<?php include 'nav.php'; ?>

<div class="container pb-5">

    <h2 class="page-title">Admin Dashboard</h2>

    <div class="custom-card">
        <h4 class="section-title">+ Add New Student</h4>

        <form method="POST" action="index.php">

            <div class="mb-3">
                <label class="form-label">Student Name</label>
                <input
                    type="text"
                    name="student_name"
                    class="form-control"
                    placeholder="Enter student name"
                    required
                >
            </div>

            <div class="mb-3">
                <label class="form-label">Score (0-100)</label>
                <input
                    type="number"
                    name="student_score"
                    class="form-control"
                    placeholder="Enter student score"
                    min="0"
                    max="100"
                    step="1"
                    required
                >
            </div>

            <button type="submit" class="btn btn-pink">
                Save Student
            </button>

        </form>

        <?php if ($error !== "") { ?>
            <div class="error-message">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php } ?>
    </div>

    <div class="custom-card">
        <h4 class="section-title">All Student Records</h4>

        <div class="table-responsive">
            <table class="table table-striped table-hover custom-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Score</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                <?php
                $result = mysqli_query(
                    $conn,
                    "SELECT * FROM students ORDER BY id DESC"
                );

                while ($row = mysqli_fetch_assoc($result)) {
                    $isPass = $row["score"] >= 50;
                ?>
                    <tr>
                        <td><?= (int) $row["id"] ?></td>

                        <td>
                            <?= htmlspecialchars($row["name"]) ?>
                        </td>

                        <td><?= (int) $row["score"] ?></td>

                        <td>
                            <span class="badge <?= $isPass ? 'status-pass' : 'status-fail' ?>">
                                <?= $isPass ? 'Pass' : 'Fail' ?>
                            </span>
                        </td>

                        <td>
                            <a
                                href="index.php?delete_id=<?= (int) $row['id'] ?>"
                                class="btn btn-sm delete-btn"
                                onclick="return confirm('Are you sure you want to delete this student?')"
                            >
                                Delete
                            </a>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

</body>
</html>





