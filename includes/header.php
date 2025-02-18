<?php
// Start output buffering
ob_start();
session_start();
define('APP_URL', 'http://localhost/job_bridge/');

// Handle logout
if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
}
?>
<html lang="en">
<head>
    <title>JobBridge &mdash; Find Your Dream Job Today</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="<?php echo APP_URL; ?>/ftco-32x32.png">

    <!-- Google Fonts & CSS -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,500,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo APP_URL; ?>/css/custom-bs.css">
    <link rel="stylesheet" href="<?php echo APP_URL; ?>/css/jquery.fancybox.min.css">
    <link rel="stylesheet" href="<?php echo APP_URL; ?>/css/bootstrap-select.min.css">
    <link rel="stylesheet" href="<?php echo APP_URL; ?>/fonts/icomoon/style.css">
    <link rel="stylesheet" href="<?php echo APP_URL; ?>/css/owl.carousel.min.css">
    <link rel="stylesheet" href="<?php echo APP_URL; ?>/css/animate.min.css">
    <link rel="stylesheet" href="<?php echo APP_URL; ?>/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
    <link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

    <!-- jQuery, Popper.js, and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

    <link rel="stylesheet" href="<?php echo APP_URL; ?>/css/header-styles.css">
    
</head>
<body id="top">
    <div class="site-wrap">
        <!-- NAVBAR -->
        <header class="site-navbar">
            <div class="container">
                <div class="row align-items-center">
                    <div class="site-logo col-6">
                        <a href="<?php echo APP_URL; ?>">Job Bridge</a>
                    </div>
                    <nav class="mx-auto site-navigation">
                        <ul class="site-menu js-clone-nav d-none d-xl-block">
                            <li><a href="<?php echo APP_URL; ?>" class="nav-link active">Home</a></li>
                            <li><a href="<?php echo APP_URL; ?>about.php" class="nav-link">About</a></li>
                            <li><a href="<?php echo APP_URL; ?>contact.php" class="nav-link">Contact</a></li>
                            <?php if (isset($_SESSION['current_user'])): ?>
                                <li class="dropdown">
                                    <a href="#" class="nav-link dropdown-toggle" id="userDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <?php echo $_SESSION['current_user']['email']; ?>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="userDropdown">
                                        <a class="dropdown-item" href="<?php echo APP_URL; ?>users/public-profile.php">Public Profile</a>
                                        <a class="dropdown-item" href="<?php echo APP_URL; ?>users/update-profile.php">Update Profile</a>
                                        <div class="dropdown-divider"></div>
                                        <form action="<?php echo APP_URL; ?>" method="POST" style="display: inline;">
                                            <button type="submit" name="logout" class="dropdown-item text-danger logout-btn">Logout</button>
                                        </form>
                                    </div>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                    <div class="right-cta-menu text-right d-flex align-items-center col-6">
                        <div class="ml-auto">
                            <?php if (isset($_SESSION['current_user']) && $_SESSION['current_user']['user_type'] == 'company'): ?>
                                <a href="<?php echo APP_URL; ?>jobs/post-job.php" class="btn btn-outline-white d-none d-lg-inline-block">Post a Job</a>
                            <?php elseif (!isset($_SESSION['current_user'])): ?>
                                <a href="<?php echo APP_URL; ?>auth/login.php" class="btn btn-primary d-none d-lg-inline-block">Log In</a>
                                <a href="<?php echo APP_URL; ?>auth/register.php" class="btn btn-primary d-none d-lg-inline-block">Register</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </header>