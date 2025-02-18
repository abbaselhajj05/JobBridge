<?php require 'config/config.php'; ?>
<?php require 'includes/header.php'; ?>


<style>
  .container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 15px;
  }

  /* HERO SECTION */
  .section-hero {
    position: relative;
    height: 80vh;
    background-size: cover;
    background-position: center;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .section-hero::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
  }

  .section-hero .container {
    position: relative;
    z-index: 1;
    text-align: center;
  }

  .section-hero h1 {
    font-size: 3rem;
    color: #fff;
    margin-bottom: 1rem;
  }

  .section-hero p {
    font-size: 1.2rem;
    color: #fff;
    margin-bottom: 2rem;
  }

  /* SEARCH FORM */
  .search-jobs-form input,
  .search-jobs-form select {
    border-radius: 6px;
    border: 1px solid #ccc;
    padding: 0.75rem;
    width: 100%;
  }

  .search-jobs-form .btn-search {
    background: #4299e1;
    border: none;
    border-radius: 6px;
    padding: 0.75rem;
    color: #fff;
    font-weight: 600;
  }

  .search-jobs-form .btn-search:hover {
    background: #3182ce;
  }

  /* APPLICATIONS SECTION */
  .applications-section {
    padding: 2rem 0;
    background-color: #fff;
  }

  .applications-section h2 {
    text-align: center;
    margin-bottom: 2rem;
    font-size: 2rem;
    color: #333;
  }

  .application-cards-grid {
    display: flex;
    gap: 1.5rem;
    overflow-x: auto;
    padding: 1rem;
    -webkit-overflow-scrolling: touch;
  }

  .application-card {
    min-width: 280px;
    flex: 0 0 auto;
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    transition: transform 0.2s ease;
    overflow: hidden;
  }

  .application-card:hover {
    transform: translateY(-5px);
  }

  .card-inner {
    padding: 1rem;
  }

  .card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
  }

  .card-actions .btn {
    height: 40px;
    /* Adjust as needed */
    line-height: 40px;
    /* Match height */
    padding: 0 15px;
    /* Adjust horizontal padding */
    display: flex;
    align-items: center;
    justify-content: center;
  }


  .job-info .job-title {
    font-size: 1.2rem;
    color: #1a202c;
    margin: 0;
  }

  .company-name {
    font-size: 0.9rem;
    color: #718096;
    margin: 0.2rem 0 0;
  }

  .status-badge {
    padding: 0.3rem 0.6rem;
    border-radius: 12px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
    color: #fff;
  }

  .status-badge.accepted {
    background-color: #38a169;
  }

  .status-badge.rejected {
    background-color: #e53e3e;
  }

  .status-badge.pending {
    background-color: #f6ad55;
  }

  .card-actions {
    display: flex;
    justify-content: space-between;
    margin-top: 1rem;
  }

  .btn {
    padding: 0.5rem 1rem;
    border-radius: 6px;
    font-size: 0.9rem;
    transition: background 0.2s ease;
  }

  .btn.btn-outline {
    border: 1px solid #4299e1;
    color: #4299e1;
    background: transparent;
  }

  .btn.btn-outline:hover {
    background: #4299e1;
    color: #fff;
  }

  .btn:not(.btn-outline) {
    background: #4299e1;
    color: #fff;
    border: none;
  }

  .btn:not(.btn-outline):hover {
    background: #3182ce;
  }

  /* JOB LISTINGS SECTION */
  .job-list-container {
    padding: 2rem 0;
  }

  .job-list-container .section-header {
    text-align: center;
    margin-bottom: 2rem;
  }

  .job-list-container .job-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 1rem;
    display: flex;
    flex-direction: column;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
  }

  .job-list-container .job-card:hover {
    border-color: #cbd5e0;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
  }

  .job-card-header {
    display: flex;
    align-items: center;
    gap: 1rem;
  }

  .company-logo {
    width: 50px;
    height: 50px;
    border-radius: 8px;
    object-fit: cover;
  }

  .job-header-info {
    flex: 1;
  }

  .job-title {
    font-size: 1.25rem;
    color: #1a202c;
    margin: 0;
  }

  .job-meta {
    display: flex;
    gap: 0.5rem;
    font-size: 0.9rem;
    color: #718096;
  }

  .job-meta .company-name::after {
    content: "•";
    margin: 0 0.5rem;
    color: #cbd5e0;
  }

  .job-card-body {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin: 1rem 0;
    flex-wrap: wrap;
    gap: 0.5rem;
  }

  .job-tags {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
  }

  .job-tag {
    background: #f7fafc;
    border: 1px solid #e2e8f0;
    padding: 0.3rem 0.7rem;
    border-radius: 4px;
    font-size: 0.85rem;
    color: #4a5568;
  }

  .job-salary {
    font-size: 0.9rem;
  }

  .salary-tag {
    color: #38a169;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.3rem;
  }

  .job-card-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-top: 1px solid #e2e8f0;
    padding-top: 1rem;
    flex-wrap: wrap;
    gap: 0.5rem;
  }

  .job-dates {
    display: flex;
    gap: 1rem;
    font-size: 0.85rem;
    color: #718096;
  }

  .date-item {
    display: flex;
    align-items: center;
    gap: 0.25rem;
  }

  .job-view-btn {
    background: #4299e1;
    color: #fff;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 6px;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    gap: 0.25rem;
    transition: background 0.2s ease;
    cursor: pointer;
  }

  .job-view-btn:hover {
    background: #3182ce;
  }

  /* Responsive Adjustments */
  @media (max-width: 768px) {

    .job-card-header,
    .job-card-body,
    .job-card-footer {
      flex-direction: column;
      align-items: flex-start;
    }

    .job-card-footer {
      gap: 1rem;
      align-items: stretch;
    }

    .job-dates {
      flex-direction: column;
      gap: 0.5rem;
    }
  }
</style>

<?php
global $pdo, $jobs;
if ((isset($_SESSION['current_user']) && $_SESSION['current_user']['user_type'] == 'employee') || !isset($_SESSION['current_user'])):
  $visitor = array('user_type' => 'visitor');
  $current_user = $_SESSION['current_user'] ?? $visitor;
  $stmt = $pdo->prepare("CALL GetAllJobs()");
  $stmt->execute();
  $jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);
  $stmt->closeCursor();
elseif ($_SESSION['current_user']['user_type'] == 'company'):
  $current_user = $_SESSION['current_user'];
  require 'functions/public-profile-functions.php';
  $jobs = getCompanyJobs($pdo, $current_user['user_id']);
endif;
?>
<!-- HERO SECTION -->
<section class="home-section section-hero overlay bg-image" style="background-image: url('images/hero_1.jpg');" id="home-section">
  <div class="container">
    <div class="row align-items-center justify-content-center">
      <div class="col-md-12">
      <?php if ($current_user['user_type'] == 'company'): ?>
          <h1>Attract Top Talent for Your Team</h1>
          <p>Post your job openings and connect with skilled professionals.</p>
        <?php else: ?>
          <h1>Find Your Dream Job Today</h1>
          <p>Discover opportunities that match your skills and aspirations.</p>
        <?php endif; ?>
        <form method="post" class="search-jobs-form">
          <div class="row mb-4">
            <div class="col-lg-3 mb-3">
              <input type="text" class="form-control form-control-lg" placeholder="Job title or company">
            </div>
            <div class="col-lg-3 mb-3">
              <select class="selectpicker form-control form-control-lg" data-style="btn-white btn-lg" data-width="100%" title="Select Location">
                <option>Anywhere</option>
                <option>San Francisco</option>
                <option>New York</option>
                <option>Toronto</option>
              </select>
            </div>
            <div class="col-lg-3 mb-3">
              <select class="selectpicker form-control form-control-lg" data-style="btn-white btn-lg" data-width="100%" title="Select Job Type">
                <option>Part Time</option>
                <option>Full Time</option>
              </select>
            </div>
            <div class="col-lg-3 mb-3">
              <button type="submit" class="btn btn-primary btn-lg btn-block btn-search">
                Search Job
              </button>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 popular-keywords text-white">
              <h3>Trending Keywords:</h3>
              <ul class="keywords list-unstyled m-0 p-0">
                <li class="d-inline-block mr-3"><a href="#" class="text-white">UI Designer</a></li>
                <li class="d-inline-block mr-3"><a href="#" class="text-white">Python</a></li>
                <li class="d-inline-block mr-3"><a href="#" class="text-white">Developer</a></li>
              </ul>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>

<?php
if ($current_user['user_type'] == 'employee') {
  $stmt = $pdo->prepare("CALL GetAppliedJobs(:employee_id)");
  $stmt->execute(['employee_id' => $current_user['user_id']]);
  $app_jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);
  $stmt->closeCursor();
?>

  <!-- APPLICATIONS SECTION -->
  <div class="applications-section">
    <div class="container">
    <div class="section-header text-center mb-5">
      <h2>Your Applications</h2>
      <p>Explore the latest opportunities tailored just for you.</p>
    </div>
      <div class="application-cards-grid">
        <?php if (empty($app_jobs)): ?>
          <center><p class="no-applications-message" style="text-align: center; display: block;">You have not applied to any jobs yet. Start exploring job listings to apply!</p></center>
        <?php else: ?>
          <?php foreach ($app_jobs as $app_job): ?>
            <div class="application-card">
              <div class="card-inner">
                <div class="card-header">
                  <div class="job-info">
                    <h3 class="job-title"><?php echo htmlspecialchars($app_job['job_title']); ?></h3>
                    <p class="company-name"><?php echo htmlspecialchars($app_job['company_name']); ?></p>
                  </div>
                  <div class="status-badge <?php echo ($app_job['status'] == 'accepted') ? 'accepted' : (($app_job['status'] == 'rejected') ? 'rejected' : 'pending'); ?>">
                    <?php echo ucfirst(htmlspecialchars($app_job['status'])); ?>
                  </div>
                </div>
                <div class="card-actions">
                  <a href="<?php echo APP_URL . 'users/public-profile.php?user_id=' . htmlspecialchars($app_job['company_id']); ?>" class="btn btn-outline">
                    View Company
                  </a>
                  <form method="post" action="<?php echo 'jobs/view-job.php'; ?>">
                    <button type="submit" name="<?php echo 'view[' . $app_job['job_id'] . ']'; ?>" class="btn">
                      View Job
                    </button>
                  </form>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>
  </div>
<?php } ?>

<!-- JOB LISTINGS SECTION -->
<div class="job-list-container">
  <div class="container">
    <div class="section-header text-center mb-5">
      <?php if ($current_user['user_type'] == 'company'): ?>
        <h2>Your Dashboard</h2>
        <p>Welcome! Here's a quick look at your job postings and applicants.</p>
      <?php else: ?>
        <h2>Available Job Listings</h2>
        <p>Explore the latest opportunities tailored just for you.</p>
      <?php endif; ?>
    </div>
    <div class="row">
      <?php if (empty($jobs)): ?>
        <div class="col-12 text-center">
          <?php if ($current_user['user_type'] == 'company'): ?>
            <p>You haven't posted any jobs yet. Start creating openings to attract talent!</p>
          <?php else: ?>
            <p>Currently, there are no job listings available. Check back later!</p>
          <?php endif; ?>
        </div>
      <?php else: ?>
        <?php foreach ($jobs as $job): ?>
          <div class="col-md-6 mb-4">
            <article class="job-card">
              <!-- Card Header: Company & Job Title -->
              <header class="job-card-header">
                <?php if ($current_user['user_type'] == 'employee' || $current_user['user_type'] == 'visitor'): ?>
                  <a href="<?php echo APP_URL . 'users/public-profile.php?user_id=' . htmlspecialchars($job['company_id']); ?>">
                    <img src="<?php echo htmlspecialchars($job['company_profile_picture']); ?>" class="company-logo" alt="Company Logo">
                  </a>
                <?php else: ?>
                  <img src="<?php echo htmlspecialchars($job['company_profile_picture']); ?>" class="company-logo" alt="Company Logo">
                <?php endif; ?>
                <div class="job-header-info">
                  <h3 class="job-title"><?php echo htmlspecialchars($job['job_title']); ?></h3>
                  <div class="job-meta">
                    <?php if ($current_user['user_type'] == 'employee' || $current_user['user_type'] == 'visitor'): ?>
                      <a href="<?php echo APP_URL . 'users/public-profile.php?user_id=' . htmlspecialchars($job['company_id']); ?>" class="company-name">
                        <?php echo htmlspecialchars($job['company_name']); ?>
                      </a>
                    <?php else: ?>
                      <span class="company-name"><?php echo htmlspecialchars($job['company_name']); ?></span>
                    <?php endif; ?>
                    <span class="job-location"><i class="bi bi-geo-alt"></i> <?php echo htmlspecialchars($job['location']); ?></span>
                  </div>
                </div>
              </header>
              <!-- Card Body: Tags & Salary -->
              <div class="job-card-body">
                <div class="job-tags">
                  <span class="job-tag"><?php echo htmlspecialchars($job['job_type']); ?></span>
                  <span class="job-tag"><?php echo htmlspecialchars($job['experience_level']); ?></span>
                </div>
                <div class="job-salary">
                  <span class="salary-tag"><i class="bi bi-cash"></i> <?php echo htmlspecialchars($job['salary_range']); ?></span>
                </div>
                <?php if ($current_user['user_type'] == 'company'): ?>
                  <div class="job-status">
                    <span class="status-tag"><i class="bi bi-info-circle"></i> <?php echo htmlspecialchars($job['job_status']); ?></span>
                  </div>
                <?php endif; ?>
              </div>
              <!-- Card Footer: Dates & Action Button -->
              <footer class="job-card-footer">
                <div class="job-dates">
                  <div class="date-item">
                    <i class="bi bi-calendar-plus"></i>
                    <span><?php echo date('d M', strtotime($job['posted_date'])); ?></span>
                  </div>
                </div>
                <?php if ($current_user['user_type'] == 'employee' || $current_user['user_type'] == 'visitor'): ?>
                  <form method="post" action="<?php echo 'jobs/view-job.php'; ?>">
                    <button type="submit" name="<?php echo 'view[' . $job['job_id'] . ']'; ?>" class="job-view-btn">
                      View Job Details<i class="bi bi-arrow-right-short"></i>
                    </button>
                  </form>
                <?php elseif ($current_user['user_type'] == 'company'): ?>
                  <form method="post" action="<?php echo 'jobs/view-applicants.php'; ?>">
                    <button type="submit" name="<?php echo 'view_applicants[' . $job['job_id'] . ']'; ?>" class="job-view-btn">
                      <?php echo ($job['job_status'] == 'open') ? 'View Applicants' : 'View Offer Results'; ?><i class="bi bi-arrow-right-short"></i>
                    </button>
                  </form>
                <?php endif; ?>
              </footer>
            </article>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
</div>
<?php require 'includes/footer.php'; ?>