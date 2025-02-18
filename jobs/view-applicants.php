<?php require '../includes/header.php'; ?>
<?php require '../config/config.php'; ?>
<?php require '../functions/public-profile-functions.php'; ?>

<link rel="stylesheet" href="<?php echo APP_URL; ?>/css/view-applicants-styles.css">

<?php
global $pdo, $job_id;

function getJobApplicants($pdo, $job_id) {
	$stmt = $pdo->prepare("CALL GetJobApplicants(:job_id)");
	$stmt->execute(['job_id' => $job_id]);
	$job_applicants = $stmt->fetchAll(PDO::FETCH_ASSOC);

	$stmt->closeCursor(); // Close the cursor to free up the connection
	return $job_applicants;
}

if (isset($_POST['view_applicants'])) {
	$job_id = key($_POST['view_applicants']);
	$_SESSION['job_id'] = $job_id;
	$_SESSION['job_applicants'] = getJobApplicants($pdo, $job_id);
}

if (isset($_POST['accept'])) {
	$pdo->beginTransaction();
	try {
		$employee_id = key($_POST['accept']);
		$application_id = $_POST['application_id'][0];
		$stmt = $pdo->prepare("CALL AcceptApplication(:application_id)");
		$stmt->execute(['application_id' => $application_id]);
		$stmt->closeCursor();

		$stmt = $pdo->prepare("CALL RejectPendingApplicationsByJob(:job_id)");
		$stmt->execute(['job_id' => $_SESSION['job_id']]);
		$stmt->closeCursor();

		$stmt = $pdo->prepare("CALL MarkJobAsFilled(:job_id)");
		$stmt->execute(['job_id' => $_SESSION['job_id']]);
		$stmt->closeCursor();

		$pdo->commit();
		$_SESSION['job_applicants'] = getJobApplicants($pdo, $_SESSION['job_id']);
	} catch (Exception $e) {
		$pdo->rollBack();
		throw $e;
	}
}

$job_applicants = $_SESSION['job_applicants'] ?? [];
?>

<!-- HERO SECTION -->
<section class="section-hero overlay inner-page bg-image" style="background-image: url('../images/hero_1.jpg');" id="home-section">
	<div class="container">
		<div class="row">
			<div class="col-md-7">
				<h1 class="text-white font-weight-bold">Job Applicants</h1>
				<div class="custom-breadcrumbs">
					<a href="<?php echo APP_URL; ?>">Home</a> <span class="mx-2 slash">/</span>
					<span class="text-white"><strong>Job Applicants</strong></span>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- APPLICANTS SECTION -->
<section class="site-section" style="margin-top: -50px;">
	<div class="container">
		<header class="section-header">
			<h2 class="section-title">List of Applicants</h2>
			<p class="section-description">Below is the list of applicants who have applied for the job.</p>
		</header>
		<div class="applicants-grid">
			<?php if (empty($job_applicants)): ?>
				<p class="no-applicants-message">There are no applicants for this job yet.</p>
			<?php else: ?>
				<?php foreach ($job_applicants as $applicant): ?>
					<?php
					$applicant_id = $applicant['applicant_id'];
					// Always make sure to close the previous cursor before executing a new statement
					$stmt = $pdo->prepare("CALL GetEmployeeProfile(:employee_id)");
					$stmt->execute(['employee_id' => $applicant_id]); // Use $applicant_id here
					$employee = $stmt->fetch(PDO::FETCH_ASSOC);
					$stmt->closeCursor(); // Close the cursor to free up the connection
					?>
					
					<article class="applicant-card <?php echo ($applicant['status'] === 'accepted') ? 'accepted' : ''; ?>">

						<!-- Top Section: Profile Picture with Overlay Icons -->
						<div class="applicant-card-image">
							<img src="<?php echo $employee['employee_profile_picture']; ?>" alt="Profile Picture">
							<div class="card-overlay">
								<a href="<?php echo $employee['resume_url']; ?>" class="overlay-btn download-btn" title="Download Resume" download>
									<i class="bi bi-download" style="font-size: 1rem;"></i>
								</a>
								<a href="<?php echo APP_URL . 'users/public-profile.php?user_id=' . $employee['employee_id']; ?>" class="overlay-btn view-btn" title="View Profile">
									<i class="bi bi-person" style="font-size: 1rem;"></i>
								</a>
							</div>
						</div>
						<!-- Middle Section: Name & Job Title -->
						<div class="applicant-card-info">
							<h3 class="applicant-name"><?php echo $employee['first_name'] . ' ' . $employee['last_name']; ?></h3>
							<p class="applicant-job-title">
								<?php echo !empty($employee['job_title']) ? $employee['job_title'] : 'No job title available'; ?>
							</p>
						</div>
						<!-- Footer: Accept Button -->
						<div class="applicant-card-footer">
							<div class="applicant-btn-group">
								<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" style="display: flex; width: 100%;">
									<input type="hidden" name="application_id[]" value="<?php echo $applicant['application_id']; ?>" />
									<button
										type="submit"
										name="accept[<?php echo $applicant['applicant_id']; ?>]"
										class="applicant-btn accept-btn <?php echo ($applicant['status'] === 'pending') ? '' : 'disabled-btn'; ?>"
										<?php echo ($applicant['status'] === 'pending') ? '' : 'disabled'; ?>>
										<i class="bi bi-check-circle"></i> Accept
									</button>
								</form>
							</div>
						</div>
					</article>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
	</div>
</section>

<script>
  // Find all disabled buttons with the class 'accept-btn'
  const disabledButtons = document.querySelectorAll('button.applicant-btn.accept-btn[disabled]');

  // Apply styles to each disabled button
disabledButtons.forEach(button => {
	button.style.backgroundColor = '#e2e8f0';
	button.style.color = '#718096';
	button.style.cursor = 'not-allowed';
	button.style.pointerEvents = 'none';
	button.style.opacity = '0.8';
	button.style.boxShadow = 'none'
});
</script>

<!-- Add this script at the end of your PHP file before </body> -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Find all applicant cards
    const cards = document.querySelectorAll('.applicant-card');
    
    cards.forEach(card => {
        // Check if this is an accepted applicant (from PHP-generated class)
        if (card.classList.contains('accepted')) {
            // Create status badge
            const statusBadge = document.createElement('div');
            statusBadge.innerHTML = `
                <div style="
                    position: absolute;
                    top: 10px;
                    left: 10px;
                    background: #38a169;
                    color: white;
                    padding: 6px 12px;
                    border-radius: 20px;
                    font-size: 0.8rem;
                    font-weight: 600;
                    box-shadow: 0 2px 6px rgba(0,0,0,0.15);
                    display: flex;
                    align-items: center;
                    gap: 6px;
                    z-index: 3; /* Higher than profile picture */
                ">
                    <svg style="width:14px;height:14px;fill:white;" viewBox="0 0 16 16">
                        <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
                    </svg>
                    Hired
                </div>
            `;

            // Add badge to card
            card.style.position = 'relative';
            card.style.border = '2px solid #38a169';
            card.style.background = '#f8fff9';
            card.insertAdjacentElement('afterbegin', statusBadge.firstElementChild);

            // Add subtle animation
            statusBadge.firstElementChild.animate([
                { transform: 'scale(1)' },
                { transform: 'scale(1.05)' },
                { transform: 'scale(1)' }
            ], {
                duration: 2000,
                iterations: Infinity
            });
        }
    });
});
</script>

<?php require '../includes/footer.php'; ?>