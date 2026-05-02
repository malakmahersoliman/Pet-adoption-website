<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Signup successful — The stray Station</title>
    <link rel="shortcut icon" type="x-icon" href="https://cdn-icons-png.flaticon.com/256/2960/2960221.png">
    <link href="../css/styles.css" rel="stylesheet" />
</head>
<body class="site-sticky-footer bg-light">
    <nav class="navbar navbar-light bg-white border-bottom shadow-sm">
        <div class="container py-2">
            <a class="navbar-brand fw-semibold" href="../index.html">The stray Station</a>
            <a class="nav-link ms-auto py-1" href="../login.html">Sign in</a>
        </div>
    </nav>

    <main class="flex-grow-1 d-flex flex-column justify-content-center bg-primary text-light py-5 px-3">
        <div class="container col-lg-8 col-xl-6">
            <div class="bg-white text-dark rounded-3 shadow p-4 p-md-5 text-center">
                <h1 class="h3 mb-3">Signup successful</h1>
                <p class="mb-4">
                    Thank you for registering<?php
                    $name = isset($_GET['name']) ? (string) $_GET['name'] : '';
                    echo $name !== '' ? ', ' . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') : '';
                    ?>! Your account is stored in the MySQL <code>adopters</code> table.
                </p>
                <p class="small text-muted mb-4">We've recorded the following:</p>
                <ul class="list-unstyled small mb-4">
                    <li class="mb-1"><strong>Email:</strong> <?php echo htmlspecialchars((string) ($_GET['email'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></li>
                    <li><strong>Phone:</strong> <?php echo htmlspecialchars((string) ($_GET['phone'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></li>
                </ul>
                <div class="d-grid gap-2 d-sm-flex justify-content-center">
                    <a class="btn btn-primary" href="../pets.html">Browse pets</a>
                    <a class="btn btn-outline-secondary" href="../index.html">Home</a>
                </div>
            </div>
        </div>
    </main>

    <footer class="py-3 mt-auto bg-white border-top text-center small text-muted">
        &copy; <?php echo date('Y'); ?> The stray Station
    </footer>
</body>
</html>
