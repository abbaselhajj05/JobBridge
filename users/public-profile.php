<?php require '../config/config.php'; ?>
<?php require '../includes/header.php'; ?>

<!-- Custom CSS for Profile Layout -->
<link rel="stylesheet" href="<?php echo APP_URL; ?>/css/public-profile.css">
<link rel="stylesheet" href="<?php echo APP_URL; ?>/css/job-postings.css">

<!-- HERO SECTION -->
<section class="section-hero overlay inner-page bg-image" style="background-image: url('../images/hero_1.jpg');" id="home-section">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <h1 class="text-white font-weight-bold">Profile</h1>
                <div class="custom-breadcrumbs">
                    <a href="<?php echo APP_URL; ?>">Home</a> <span class="mx-2 slash">/</span>
                    <span class="text-white"><strong>Profile</strong></span>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
require '../functions/public-profile-functions.php';

$current_user = null;

// Fetch user details based on GET parameter or session
if (isset($_GET['user_id']) || isset($_SESSION['current_user'])):
    if (isset($_GET['user_id'])) {
        $stmt = $pdo->prepare("CALL GetUserDetailsById(:user_id)");
        $stmt->execute(['user_id' => $_GET['user_id']]);
        $current_user = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
    } else {
        $current_user = $_SESSION['current_user'];
    }

    // Check user type and retrieve corresponding profile
    if ($current_user['user_type'] == 'employee') {
        $employee = getEmployeeProfile($pdo, $current_user['user_id']);
        $resume_url = !empty($employee['resume_url']) ? $employee['resume_url'] : null;
    } elseif ($current_user['user_type'] == 'company') {
        $company = getCompanyProfile($pdo, $current_user['user_id']);
    }

?>
    <!-- PROFILE SECTION -->
    <section class="profile-section">
        <div class="container-fluid">
            <?php if ($current_user['user_type'] == 'employee' && $employee): ?>
                <div class="row">
                    <div class="col-md-4">
                        <div class="profile-info text-center">
                            <img src="<?php echo !empty($current_user['profile_picture']) ? $current_user['profile_picture'] : 'default-profile.png'; ?>" alt="Profile Image" class="profile-image">
                            <h2 class="mt-4 mb-3"><?php echo !empty($employee['first_name']) && !empty($employee['last_name']) ? $employee['first_name'] . ' ' . $employee['last_name'] : 'Name not available'; ?></h2>
                            <p class="text-primary"><?php echo !empty($employee['job_title']) ? $employee['job_title'] : 'Job title not available.'; ?></p>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="content-info">
                            <h4>About Me</h4>
                            <p style="font-size: 1.1rem; color: #555;"><?php echo !empty($employee['bio']) ? nl2br($employee['bio']) : 'No bio available.'; ?></p>
                            <div class="mt-4">
                                <a href="<?php echo $resume_url; ?>" class="btn btn-lg btn-primary" download>
                                    <i class="fas fa-download mr-2"></i>Download Resume
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php elseif ($current_user['user_type'] == 'company' && $company): ?>
                <div class="row">
                    <div class="col-md-4">
                        <div class="profile-info text-center">
                            <img src="<?php echo !empty($current_user['profile_picture']) ? $current_user['profile_picture'] : 'default-company-logo.png'; ?>" alt="Company Logo" class="profile-image">
                            <h2 class="mt-4 mb-3"><?php echo !empty($company['company_name']) ? $company['company_name'] : 'Company name not available.'; ?></h2>
                            <p>
                                <strong>Website:</strong>
                                <a href="<?php echo !empty($company['website_url']) ? $company['website_url'] : '#'; ?>" target="_blank"><?php echo !empty($company['website_url']) ? $company['website_url'] : 'URL not available.'; ?></a>
                            </p>
                            <p><strong>Contact:</strong> <?php echo !empty($company['contact_person']) ? $company['contact_person'] : 'Contact person not available.'; ?></p>
                            <p><strong>Phone:</strong> <?php echo !empty($company['phone_number']) ? $company['phone_number'] : 'Phone number not available.'; ?></p>
                            <p><strong>Industry:</strong> <?php echo !empty($company['industry']) ? $company['industry'] : 'Industry not available.'; ?></p>
                            <p><strong>Address:</strong> <?php echo !empty($company['address']) ? $company['address'] : 'Address not available.'; ?></p>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="content-info">
                            <h4>About Us</h4>
                            <p style="font-size: 1.1rem; color: #555;"><?php echo !empty($company['description']) ? nl2br($company['description']) : 'No description available.'; ?></p>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="alert alert-warning text-center">Profile not found or not available.</div>
            <?php endif; ?>
        </div>
    </section>

    <?php require '../includes/footer.php'; ?>

<?php else: ?>
    <!-- Access Denied -->
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="alert alert-danger mt-5 text-center" role="alert">
                    Access Denied. Please <a href="<?php echo APP_URL . '/auth/login.php'; ?>" class="alert-link">log in</a> to view this page.
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Optional: Include FontAwesome for icons -->
<script src="https://kit.fontawesome.com/a076d05399.js"></script>