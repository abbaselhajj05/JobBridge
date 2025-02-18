<?php
require '../config/config.php';
require '../includes/header.php';

if (isset($_SESSION['current_user'])):
    $current_user = $_SESSION['current_user'];

    // Initialize messages and file upload settings
    $error_message = '';
    $success_message = '';
    $max_size = 3000000; // 3MB limit
    $allowed_types = ['image/jpeg', 'image/png'];
    $profilePic = $current_user['profile_picture'] ?? DEFAULT_PROFILE_PICTURE;

    // Function to handle file upload
    function handleFileUpload($file, $allowed_types, $max_size, $destination_dir) {
        if ($file['error'] === UPLOAD_ERR_OK) {
            if (!in_array($file['type'], $allowed_types)) {
                return ['error' => 'Invalid file type.'];
            } elseif ($file['size'] > $max_size) {
                return ['error' => 'File size exceeds the allowed limit.'];
            } else {
                $destination = $destination_dir . basename($file['name']);
                if (!move_uploaded_file($file['tmp_name'], $destination)) {
                    return ['error' => 'File upload failed.'];
                }
                return ['success' => APP_URL . 'uploads/' . basename($file['name'])];
            }
        } elseif ($file['error'] !== UPLOAD_ERR_NO_FILE) {
            return ['error' => 'Error during file upload: ' . $file['error']];
        }
        return [];
    }

    // Process file uploads for profile picture and resume
    if (isset($_FILES['profile_picture_file'])) {
        $result = handleFileUpload($_FILES['profile_picture_file'], $allowed_types, $max_size, '../uploads/');
        if (isset($result['error'])) {
            $error_message = '<div class="alert alert-danger">' . $result['error'] . '</div>';
        } elseif (isset($result['success'])) {
            $profilePic = $result['success'];
            $success_message = '<div class="alert alert-success">File uploaded successfully!</div>';
        }
    }

    $new_resume_url = '';
    if (isset($_FILES['resume'])) {
        $allowed_resume_types = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        $result = handleFileUpload($_FILES['resume'], $allowed_resume_types, $max_size, '../uploads/');
        if (isset($result['error'])) {
            $error_message = '<div class="alert alert-danger">' . $result['error'] . '</div>';
        } elseif (isset($result['success'])) {
            $new_resume_url = $result['success'];
            $success_message = '<div class="alert alert-success">Resume uploaded successfully!</div>';
        }
    }

    require '../functions/update-profile-functions.php';

    if (isset($_POST['update_profile'])) {
        $new_email = $_POST['email'];
        $new_hashed_password = empty($_POST['password']) ? $current_user['password'] : password_hash($_POST['password'], PASSWORD_BCRYPT);
        $new_profile_picture = !empty($profilePic) ? $profilePic : ($current_user['profile_picture'] ?? DEFAULT_PROFILE_PICTURE);

        try {
            $pdo->beginTransaction();
            updateUserProfile($pdo, $current_user, $new_email, $new_hashed_password, $new_profile_picture);

            if ($current_user['user_type'] === 'employee') {
                updateEmployeeProfile($pdo, $current_user, $new_resume_url);
            } elseif ($current_user['user_type'] === 'company') {
                updateCompanyProfile($pdo, $current_user);
            }

            $pdo->commit();

            // Refresh current user data
            $stmt = $pdo->prepare("CALL GetUserDetailsById(:user_id)");
            $stmt->execute([':user_id' => $current_user['user_id']]);
            $_SESSION['current_user'] = $stmt->fetch(PDO::FETCH_ASSOC);
            $current_user = $_SESSION['current_user'];
            $stmt->closeCursor();

            $success_message = '<div class="alert alert-success">Profile updated successfully!</div>';
        } catch (PDOException $e) {
            $pdo->rollBack();
            $error_message = '<div class="alert alert-danger">Error: ' . $e->getMessage() . '</div>';
        }
    }

    // Fetch profile details based on user type
    if ($current_user['user_type'] === 'employee') {
        $stmt = $pdo->prepare("CALL GetEmployeeProfile(:user_id)");
        $stmt->execute([':user_id' => $current_user['user_id']]);
        $employee = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        $resume_url = APP_URL . '/uploads/' . ($employee['resume_url'] ?? 'default-resume.pdf');
    } elseif ($current_user['user_type'] === 'company') {
        $stmt = $pdo->prepare("CALL GetCompanyProfile(:user_id)");
        $stmt->execute([':user_id' => $current_user['user_id']]);
        $company = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
    }
?>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">
    <section class="section-hero overlay inner-page bg-image" id="home-section" style="background-image: url('../images/hero_1.jpg'); height: 400px; display: flex; align-items: center;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-7">
                    <h1 class="text-white font-weight-bold">Update Profile</h1>
                    <div class="custom-breadcrumbs">
                        <a href="<?php echo APP_URL; ?>">Home</a> <span class="mx-2 slash">/</span>
                        <span class="text-white"><strong>Update Profile</strong></span>
                    </div>
                </div>
                <div class="col-md-5 text-md-right text-center">
    <div class="profile-display" style="position: relative; display: inline-block;">
        <img src="<?php echo $profilePic; ?>" alt="Profile Picture" width="180" class="rounded-circle shadow" style="border: 4px solid white;">
        <label for="profile_picture_file" class="camera-icon" style="position: absolute; bottom: 10px; right: 10px; background: rgba(0,0,0,0.6); color: #fff; border-radius: 50%; padding: 8px; cursor: pointer; z-index: 10;">
            <i class="bi bi-camera" style="font-size: 1.2rem;"></i>
        </label>
        <input type="hidden" name="MAX_FILE_SIZE" value="3000000" />
        <input type="file" name="profile_picture_file" id="profile_picture_file" class="form-control-file" style="display: none;" accept="image/*">
    </div>
</div>
            </div>
        </div>
    </section>

    <section class="site-section" style="margin-top: -100px;">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mb-5 mb-lg-0">
                    <div class="container mt-3">
                        <?php
                        if (!empty($error_message)) {
                            echo $error_message;
                        } elseif (!empty($success_message)) {
                            echo $success_message;
                        }
                        ?>
                    </div>
                    <h4 class="mb-4">Common Details</h4>
                    <div class="form-group">
                        <label class="text-black" for="email">Email</label>
                        <input type="email" name="email" id="email" class="form-control" value="<?php echo $current_user['email']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label class="text-black" for="password">Password</label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Enter new password if you want to change it">
                    </div>

                    <?php if ($current_user['user_type'] === 'employee' && isset($employee)): ?>
                        <hr>
                        <h4 class="mb-4">Employee Details</h4>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label class="text-black" for="first_name">First Name</label>
                                <input type="text" name="first_name" id="first_name" class="form-control" value="<?php echo $employee['first_name']; ?>">
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="text-black" for="last_name">Last Name</label>
                                <input type="text" name="last_name" id="last_name" class="form-control" value="<?php echo $employee['last_name']; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="text-black" for="phone_number">Phone Number</label>
                            <input type="text" name="phone_number" id="phone_number" class="form-control" value="<?php echo $employee['phone_number']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label class="text-black" for="job_title">Job Title</label>
                            <input type="text" name="job_title" id="job_title" class="form-control" value="<?php echo $employee['job_title']; ?>">
                        </div>
                        <div class="form-group">
                            <label class="text-black" for="bio">Bio</label>
                            <textarea name="bio" id="bio" cols="30" rows="5" class="form-control"><?php echo $employee['bio']; ?></textarea>
                        </div>
                        <div class="form-group">
                            <label class="text-black" for="resume">Resume</label>
                            <input type="file" name="resume" id="resume" class="form-control-file">
                            <?php if (!empty($employee['resume_url'])): ?>
                                <p class="mt-2">Current Resume: <a href="<?php echo $resume_url; ?>" download>Download Resume</a></p>
                            <?php endif; ?>
                        </div>
                    <?php elseif ($current_user['user_type'] === 'company' && isset($company)): ?>
                        <hr>
                        <h4 class="mb-4">Company Details</h4>
                        <div class="form-group">
                            <label class="text-black" for="company_name">Company Name</label>
                            <input type="text" name="company_name" id="company_name" class="form-control" value="<?php echo $company['company_name']; ?>">
                        </div>
                        <div class="form-group">
                            <label class="text-black" for="website_url">Website URL</label>
                            <input type="url" name="website_url" id="website_url" class="form-control" value="<?php echo $company['website_url']; ?>">
                        </div>
                        <div class="form-group">
                            <label class="text-black" for="contact_person">Contact Person</label>
                            <input type="text" name="contact_person" id="contact_person" class="form-control" value="<?php echo $company['contact_person']; ?>">
                        </div>
                        <div class="form-group">
                            <label class="text-black" for="phone_number">Phone Number</label>
                            <input type="text" name="phone_number" id="phone_number" class="form-control" value="<?php echo $company['phone_number']; ?>">
                        </div>
                        <div class="form-group">
                            <label class="text-black" for="industry">Industry</label>
                            <input type="text" name="industry" id="industry" class="form-control" value="<?php echo $company['industry']; ?>">
                        </div>
                        <div class="form-group">
                            <label class="text-black" for="description">Description</label>
                            <textarea name="description" id="description" cols="30" rows="4" class="form-control"><?php echo $company['description']; ?></textarea>
                        </div>
                        <div class="form-group">
                            <label class="text-black" for="address">Address</label>
                            <textarea name="address" id="address" cols="30" rows="3" class="form-control"><?php echo $company['address']; ?></textarea>
                        </div>
                    <?php endif; ?>

                    <div class="form-group">
                        <input type="submit" name="update_profile" value="Update Profile" class="btn btn-primary btn-md text-white">
                    </div>
                </div>

                <?php if ($current_user['user_type'] === 'company' && isset($company)): ?>
                    <div class="col-lg-4">
                        <div class="p-4 mb-3 bg-white">
                            <h4 class="mb-3">Contact Info</h4>
                            <p class="mb-0 font-weight-bold">Address</p>
                            <p class="mb-4"><?php echo $company['address']; ?></p>
                            <p class="mb-0 font-weight-bold">Phone</p>
                            <p class="mb-4"><a href="#"><?php echo $company['phone_number']; ?></a></p>
                            <p class="mb-0 font-weight-bold">Email Address</p>
                            <p class="mb-0"><a href="#"><?php echo $current_user['email']; ?></a></p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

<?php else: ?>
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

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
<script>
document.getElementById('profile_picture_file').addEventListener('change', function(e) {
    const [file] = e.target.files;
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.querySelector('.profile-display img').src = e.target.result;
        }
        reader.readAsDataURL(file);
    }
});
</script>

<?php require '../includes/footer.php'; ?>