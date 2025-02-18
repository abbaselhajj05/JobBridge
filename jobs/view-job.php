<?php require '../includes/header.php'; ?>
<?php require '../config/config.php'; ?>

<?php

global $pdo, $already_applied;

if (isset($_SESSION['current_user']))
  $current_user = $_SESSION['current_user'];

if (isset($_POST['view'])) {
  $job_id = key($_POST['view']);
  $stmt = $pdo->prepare("CALL GetJobById(:job_id)");
  $stmt->bindParam(':job_id', $job_id);
  $stmt->execute();
  $_SESSION['job'] = $stmt->fetch(PDO::FETCH_ASSOC);
}

if (isset($_POST['apply_for_job'])) {
  if (!isset($_SESSION['current_user'])) {
    header("location: " . APP_URL . 'auth/login.php');
    exit;
  }

  if (!$already_applied) {
    $stmt = $pdo->prepare("CALL ApplyForJob(:job_id, :employee_id)");
    $stmt->bindParam(':job_id', $_SESSION['job']['job_id']);
    $stmt->bindParam(':employee_id', $current_user['user_id']);
    $stmt->execute();
    echo "<script>alert('Application submitted successfully.');</script>";
  }
}

// Check if the user has already applied for the job
$stmt->closeCursor();
$stmt = $pdo->prepare("Select CheckIfApplied(:job_id, :employee_id) AS result");
$stmt->bindParam(':job_id', $_SESSION['job']['job_id']);
$stmt->bindParam(':employee_id', $current_user['user_id']);
$stmt->execute();
$already_applied = $stmt->fetch(PDO::FETCH_ASSOC)['result'];

if (isset($_SESSION['job']))
  $job = $_SESSION['job'];

?>

<!-- JOB DETAILS HERO SECTION -->
<section class="section-hero overlay inner-page bg-image" style="background-image: url('../images/hero_1.jpg');" id="home-section">
  <div class="container">
    <div class="row">
      <div class="col-md-7">
        <h1 class="text-white font-weight-bold"><?php echo htmlspecialchars($job['job_title']); ?></h1>
        <div class="custom-breadcrumbs">
          <a href="<?php echo APP_URL; ?>">Home</a> <span class="mx-2 slash">/</span>
          <a href="#">Job</a> <span class="mx-2 slash">/</span>
          <span class="text-white"><strong><?php echo htmlspecialchars($job['job_title']); ?></strong></span>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- MAIN JOB DETAILS SECTION -->
<section class="site-section py-5">
  <div class="container">
    <div class="row">
      <!-- Left Column: Job Details & Description -->
      <div class="col-lg-8">
        <!-- Job Header -->
        <div class="d-flex align-items-center mb-4">
          <div class="border p-2 d-inline-block mr-3 rounded">
            <img src="<?php echo htmlspecialchars($job['company_profile_picture'] ?? 'default-logo.png'); ?>" alt="Company Logo" style="width:80px; height:80px; object-fit:cover;">
          </div>
          <div>
            <h2><?php echo htmlspecialchars($job['job_title']); ?></h2>
            <div class="d-flex flex-wrap">
              <span class="mr-3 mb-2">
                <span class="icon-briefcase mr-2"></span>
                <?php echo htmlspecialchars($job['company_name']); ?>
              </span>
              <span class="mr-3 mb-2">
                <span class="icon-room mr-2"></span>
                <?php echo htmlspecialchars($job['job_location']); ?>
              </span>
              <span class="mr-3 mb-2">
                <span class="icon-clock-o mr-2"></span>
                <span class="text-primary"><?php echo htmlspecialchars($job['job_type']); ?></span>
              </span>
            </div>
          </div>
        </div>
        <!-- Job Description -->
        <div class="mb-5">
          <h3 class="h5 d-flex align-items-center mb-4 text-primary">
            <span class="icon-align-left mr-3"></span>Job Description
          </h3>
          <p><?php echo nl2br(htmlspecialchars($job['job_description'])); ?></p>
        </div>

        <!-- Apply Now Button -->
        <div class="mb-5">
          <form action="<?php echo APP_URL; ?>" method="post">
            <button type="submit" name="apply_for_job" class="btn btn-primary btn-block btn-lg" <?php echo $already_applied ? 'disabled' : ''; ?>>
              <span class="icon-paper-plane mr-2"></span><?php echo $already_applied ? 'Applied' : 'Apply Now'; ?>
            </button>
          </form>
        </div>
      </div>

      <!-- Right Column: Job Summary & Company Information -->
      <div class="col-lg-4">
        <!-- Job Summary Panel -->
        <div class="bg-light p-4 border rounded mb-4">
          <h3 class="text-primary h5 mb-3">Job Summary</h3>
          <ul class="list-unstyled mb-0">
            <li class="mb-2"><strong class="text-black">Posted Date:</strong> <?php echo htmlspecialchars($job['posted_date']); ?></li>
            <li class="mb-2"><strong class="text-black">Job Type:</strong> <?php echo htmlspecialchars($job['job_type']); ?></li>
            <li class="mb-2"><strong class="text-black">Salary Range:</strong> <?php echo htmlspecialchars($job['salary_range']); ?></li>
            <li class="mb-2"><strong class="text-black">Location:</strong> <?php echo htmlspecialchars($job['job_location']); ?></li>
            <li class="mb-2"><strong class="text-black">Experience Level:</strong> <?php echo htmlspecialchars($job['experience_level']); ?></li>
          </ul>
        </div>

        <!-- Company Information Panel -->
        <div class="bg-light p-4 border rounded">
          <h3 class="text-primary h5 mb-3">Company Information</h3>
          <ul class="list-unstyled mb-0">
            <li class="mb-2"><strong class="text-black">Email:</strong> <?php echo htmlspecialchars($job['company_email']); ?></li>
            <li class="mb-2"><strong class="text-black">Contact Person:</strong> <?php echo htmlspecialchars($job['contact_person']); ?></li>
            <li class="mb-2"><strong class="text-black">Phone:</strong> <?php echo htmlspecialchars($job['company_phone_number']); ?></li>
            <li class="mb-2"><strong class="text-black">Website:</strong> <?php echo htmlspecialchars($job['website_url']); ?></li>
            <li class="mb-2"><strong class="text-black">Industry:</strong> <?php echo htmlspecialchars($job['industry']); ?></li>
            <li class="mb-2"><strong class="text-black">Address:</strong> <?php echo htmlspecialchars($job['company_address']); ?></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</section>

<?php require '../includes/footer.php'; ?>