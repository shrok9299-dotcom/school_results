<!-- nav.php -->
<link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
    rel="stylesheet"
>

<link
    href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap"
    rel="stylesheet"
>

<style>
    .navbar {
        background: linear-gradient(100deg, #08080b, #25101d) !important;
        border-bottom: 2px solid #9d174d;
        padding: 18px 0;
        font-family: 'Cairo', sans-serif;
    }

    .navbar-brand {
        color: #f9a8d4 !important;
        font-size: 23px;
        letter-spacing: 0.5px;
    }

    .navbar-brand:hover {
        color: #ffffff !important;
    }

    .navbar .nav-link {
        color: #e5d5df !important;
        margin-left: 12px;
        padding: 9px 15px !important;
        border-radius: 10px;
        transition: 0.3s;
    }

    .navbar .nav-link:hover {
        background: #831843;
        color: #ffffff !important;
    }

    .navbar .container {
        flex-wrap: wrap;
        gap: 10px;
    }

    .navbar-nav {
        flex-direction: row;
        flex-wrap: wrap;
    }
</style>

<nav class="navbar navbar-dark mb-4 shadow">
    <div class="container">

        <a class="navbar-brand fw-bold" href="index.php">
            WE Student Portal
        </a>

        <div class="navbar-nav">
            <a class="nav-link" href="student.php">
                Student Check
            </a>

            <a class="nav-link" href="index.php">
                Dashboard (Admin)
            </a>
        </div>

    </div>
</nav>