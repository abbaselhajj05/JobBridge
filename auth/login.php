<?php
// Start output buffering
ob_start();
require '../config/config.php'; ?>
<?php require '../includes/header.php'; ?>

<?php

$error_message = '';

function loginUser()
{
    global $error_message, $pdo; // Include $pdo in the global scope

    if (!isset($_POST['submit']))
        return;

    if (empty($_POST['email']) || empty($_POST['password'])) {
        $error_message = '<div class="alert alert-danger bg-danger text-white">Some inputs are empty!</div>';
        return;
    }

    $email = $_POST['email'];
    $password = $_POST['password'];

    try {

        $stmt = $pdo->prepare("CALL GetUserDetailsByEmail(:email)");

        $stmt->bindParam(":email", $email);

        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$user || !password_verify($password, $user['password']))
            throw new PDOException("Invalid email or password!");

        $_SESSION['current_user'] = $user;

        header("location: " . APP_URL . "");
    } catch (PDOEXception $e) {
        $error_message = '<div class="alert alert-danger bg-danger text-white">' . $e->getMessage() . '</div>';
    }
}

loginUser();

?>

<!-- HERO SECTION -->
<section class="section-hero overlay inner-page bg-image" style="background-image: url('../images/hero_1.jpg');" id="home-section">
    <div class="container">
        <div class="row">
            <div class="col-md-7">
                <h1 class="text-white font-weight-bold">Log In</h1>
                <div class="custom-breadcrumbs">
                    <a href="<?php echo APP_URL; ?>">Home</a> <span class="mx-2 slash">/</span>
                    <span class="text-white"><strong>Log In</strong></span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- LOGIN FORM -->
<section class="site-section" style="margin-top: -50px;">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?php if (!empty($error_message)) echo $error_message; ?>
                <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" class="p-4 border rounded">
                    <div class="row form-group">
                        <div class="col-md-12 mb-3 mb-md-0">
                            <label class="text-black" for="email">Email</label>
                            <input type="email" name="email" id="email" class="form-control" placeholder="Email address" required>
                        </div>
                    </div>
                    <div class="row form-group mb-4">
                        <div class="col-md-12 mb-3 mb-md-0">
                            <label class="text-black" for="password">Password</label>
                            <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-12">
                            <input type="submit" name="submit" value="Log In" class="btn px-4 btn-primary text-white">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<?php require '../includes/footer.php'; ?>