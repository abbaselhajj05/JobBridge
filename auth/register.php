<?php
require '../config/config.php';
require '../includes/header.php';

$error_message = '';

function register_user()
{
    global $error_message, $pdo;

    if (isset($_POST['submit'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $re_password = $_POST['re_password'];
        $user_type = $_POST['user_type'];

        // Input validation
        if (empty($email) || empty($password) || empty($re_password) || empty($user_type)) {
            $error_message = '<div class="alert alert-danger">All fields are required!</div>';
            return;
        }

        if ($password !== $re_password) {
            $error_message = '<div class="alert alert-danger">Passwords do not match!</div>';
            return;
        }

        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $profile_picture = DEFAULT_PROFILE_PICTURE;

        try {
            $pdo->beginTransaction();

            // Insert into Users table
            $stmt = $pdo->prepare("CALL RegisterUser(:email, :hashed_password, :user_type, :profile_picture, @new_user_id)");

            // Bind parameters
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':hashed_password', $hashed_password);
            $stmt->bindParam(':user_type', $user_type);
            $stmt->bindParam(':profile_picture', $profile_picture);

            $stmt->execute();

            $stmt = $pdo->query("SELECT @new_user_id AS user_id");
            $user_id = $stmt->fetchColumn();

            if ($user_type == 'employee') {

                $first_name = $_POST['first_name'];
                $last_name = $_POST['last_name'];
                $phone_number = $_POST['phone_number'];

                // Insert into EmployeeProfiles
                $stmt = $pdo->prepare("CALL RegisterEmployee(:user_id, :first_name, :last_name, :phone_number)");
                $stmt->bindParam(':user_id', $user_id);
                $stmt->bindParam(':first_name', $first_name);
                $stmt->bindParam(':last_name', $last_name);
                $stmt->bindParam(':phone_number', $phone_number);

            } elseif ($user_type == 'company') {
                $company_name = $_POST['company_name'];
                $contact_person = $_POST['contact_person'];
                $phone_number = $_POST['phone_number'];

                // Insert into CompanyProfiles
                $stmt = $pdo->prepare("CALL RegisterCompany(:user_id, :company_name, :contact_person, :phone_number)");
                $stmt->bindParam(':user_id', $user_id);
                $stmt->bindParam(':company_name', $company_name);
                $stmt->bindParam(':contact_person', $contact_person);
                $stmt->bindParam(':phone_number', $phone_number);
            }
            
            $stmt->execute();
            $pdo->commit();

            header("Location: login.php");  
            exit();
        } catch (PDOException $e) {
            $pdo->rollBack();
            $error_message = match ($e->getCode()) {
                ERROR_EMAIL_EXISTS => '<div class="alert alert-danger bg-danger text-white">' . $e->errorInfo[2] . '</div>',
                default => '<div class="alert alert-danger bg-danger text-white">Registration failed. Please try again!</div>'
            };
        }
    }
}

register_user();

?>

<!-- HOME -->
<section class="section-hero overlay inner-page bg-image" style="background-image: url('../images/hero_1.jpg');" id="home-section">
    <div class="container">
        <div class="row">
            <div class="col-md-7">
                <h1 class="text-white font-weight-bold">Register</h1>
                <div class="custom-breadcrumbs">
                    <a href="<?php echo APP_URL; ?>">Home</a> <span class="mx-2 slash">/</span>
                    <span class="text-white"><strong>Register</strong></span>
                </div>
            </div>
        </div>
    </div>
</section>


<section class="site-section">
    <div class="container">
        <div class="row">
            <div class="col-md-12 mb-5">
                <?php if (!empty($error_message)) echo $error_message; ?>
                <form action="register.php" method="POST" class="p-4 border rounded">
                    <div class="row form-group">
                        <div class="col-md-12">
                            <label for="email">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-12">
                            <label for="user_type">User Type</label>
                            <select name="user_type" id="user_type" class="form-control" required >
                                <option value="">Select user type</option>
                                <option value="employee">Employee</option>
                                <option value="company">Company</option>
                            </select>
                        </div>
                    </div>
                        <div id="employee_fields" style="display: none;">
                            <div class="row form-group">
                                <div class="col-md-6">
                                    <label for="first_name">First Name</label>
                                    <input type="text" name="first_name" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label for="last_name">Last Name</label>
                                    <input type="text" name="last_name" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div id="company_fields" style="display: none;">
                            <div class="row form-group">
                                <div class="col-md-12">
                                    <label for="company_name">Company Name</label>
                                    <input type="text" name="company_name" class="form-control">
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-12">
                                    <label for="contact_person">Contact Person</label>
                                    <input type="text" name="contact_person" class="form-control">
                                </div>
                            </div>
                        </div>
                    <div class="row form-group">
                        <div class="col-md-12">
                            <label for="phone_number">Phone Number</label>
                            <input type="text" name="phone_number" class="form-control">
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-12">
                            <label for="password">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                    </div>
                    <script>
                        document.getElementById('user_type').addEventListener('change', function () {
                            var userType = this.value;
                            document.getElementById('employee_fields').style.display = userType === 'employee' ? 'block' : 'none';
                            document.getElementById('company_fields').style.display = userType === 'company' ? 'block' : 'none';
                        });
                    </script>
                    <div class="row form-group">
                        <div class="col-md-12">
                            <label for="re_password">Re-Type Password</label>
                            <input type="password" name="re_password" class="form-control" required>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-12">
                            <input type="submit" name="submit" value="Sign Up" class="btn btn-primary">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<?php require '../includes/footer.php'; ?>