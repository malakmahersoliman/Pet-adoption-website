<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Signup Success</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Merriweather+Sans:400,700" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Merriweather:400,300,300italic,400italic,700,700italic" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/SimpleLightbox/2.1.0/simpleLightbox.min.css" rel="stylesheet" />
    <link rel="shortcut icon" type="x-icon" href="https://cdn-icons-png.flaticon.com/256/2960/2960221.png">
    <link href="/styles.css" rel="stylesheet" />
</head>
<body>
    <div class="container px-5 px-lg-5 bg-primary">
        <div class="row gx-4 gx-lg-5 justify-content-center">
            <div class="col-lg-8 col-xl-6 text-center">
                <h2 class="mt-0">Signup Successful!</h2>
                <hr class="divider" />
                <p>Thank you for signing up, <?php echo htmlspecialchars($_GET['name']); ?>!</p>
                <p>We've received the following details:</p>
                <ul>
                    <li>Email: <?php echo htmlspecialchars($_GET['email']); ?></li>
                    <li>Phone: <?php echo htmlspecialchars($_GET['phone']); ?></li>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>

