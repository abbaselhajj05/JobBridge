<?php
// Include header and config files
require '../includes/header.php';
require '../config/config.php';

// Check if the user is logged in and is a company user
if (!isset($_SESSION['current_user']) || $_SESSION['current_user']['user_type'] !== 'company') {
    header("Location: ../auth/login.php");
    exit;
}

// Fetch current user data
$current_user = $_SESSION['current_user'];

// Fetch company profile
$stmt = $pdo->prepare("CALL GetCompanyProfile(:user_id)");
$stmt->bindParam(':user_id', $current_user['user_id']);
$stmt->execute();
$company = $stmt->fetch(PDO::FETCH_ASSOC);
$stmt->closeCursor();

// Function to post a job
function post_job()
{
    global $pdo, $current_user;

    // Gather job data from the form
    $job_data = [
        'company_id'        => $current_user['user_id'],
        'job_title'         => $_POST['job_title'],
        'job_description'   => $_POST['job_description'],
        'job_type'          => $_POST['job_type'],
        'salary_range'      => $_POST['salary_range'],
        'location'          => $_POST['location'],
        'experience_level'  => $_POST['experience_level']
    ];

    // Prepare and execute the job posting statement
    $stmt = $pdo->prepare("CALL PostJob(:company_id, :job_title, :job_description, :job_type, :salary_range, :location, :experience_level)");

    // Bind parameters in the correct order
    foreach ($job_data as $key => &$value) {
        $stmt->bindParam(":$key", $value);
    }

    try {
        $stmt->execute();
        $stmt->closeCursor();
        return '<div class="alert alert-success">Job posted successfully!</div>';
    } catch (PDOException $e) {
        return '<div class="alert alert-danger">Error posting job: ' . htmlspecialchars($e->getMessage()) . '</div>';
    }
}

// Handle form submission
$message = '';
if (isset($_POST['submit'])) {
    $message = post_job();
}
?>

<!-- HERO SECTION -->
<section class="section-hero overlay inner-page bg-image" style="background-image: url('../images/hero_1.jpg');" id="home-section">
    <div class="container">
        <div class="row">
            <div class="col-md-7">
                <h1 class="text-white font-weight-bold">Post A Job</h1>
                <div class="custom-breadcrumbs">
                    <a href="<?php echo APP_URL; ?>">Home</a> <span class="mx-2 slash">/</span>
                    <span class="text-white"><strong>Post a Job</strong></span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- JOB POST FORM SECTION -->
<section class="site-section" style="margin-top: -50px;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <!-- Display error/success messages if available -->
                <?php echo $message; ?>
                <form class="job-post-form p-5 border rounded" action="" method="post">
                    <h2 class="text-black mb-4">Job Details</h2>

                    <!-- Job Title and Location -->
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="job_title">Job Title</label>
                            <input type="text" name="job_title" class="form-control" id="job_title" placeholder="e.g. Product Designer" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="location">Location</label>
                            <input type="text" name="location" class="form-control" id="location" placeholder="e.g. New York" required>
                        </div>
                    </div>

                    <!-- Job Description -->
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="job_description">Job Description</label>
                            <textarea name="job_description" id="job_description" cols="30" rows="5" class="form-control" placeholder="Write Job Description..." required></textarea>
                        </div>
                    </div>

                    <!-- Job Type, Salary Range, and Experience Level -->
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="job_type">Job Type</label>
                            <select name="job_type" class="form-control" id="job_type" required>
                                <option>Part Time</option>
                                <option>Full Time</option>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="salary_range">Salary Range</label>
                            <select name="salary_range" class="form-control" id="salary_range" required>
                                <option>$50k - $70k</option>
                                <option>$70k - $100k</option>
                                <option>$100k - $150k</option>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="experience_level">Experience Level</label>
                            <select name="experience_level" class="form-control" id="experience_level" required>
                                <option>1-3 years</option>
                                <option>3-6 years</option>
                                <option>6-9 years</option>
                            </select>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="row">
                        <div class="col-12 text-center mt-4">
                            <input type="submit" name="submit" class="btn btn-primary btn-lg" value="Post Job">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
<?php require '../includes/footer.php'; ?>