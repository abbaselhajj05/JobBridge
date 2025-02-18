<?php

// Function to update user profile
function updateUserProfile($pdo, $current_user, $new_email, $new_hashed_password, $new_profile_picture) {
    $stmt = $pdo->prepare("CALL UpdateUserProfile(:user_id, :new_email, :new_password, :profile_pic)");
    $stmt->execute([
        ':user_id' => $current_user['user_id'],
        ':new_email' => $new_email,
        ':new_password' => $new_hashed_password,
        ':profile_pic' => $new_profile_picture
    ]);
}

// Function to update employee profile
function updateEmployeeProfile($pdo, $current_user, $new_resume_url) {
    $stmt = $pdo->prepare("CALL GetEmployeeProfile(:user_id)");
    $stmt->execute([':user_id' => $current_user['user_id']]);
    $employee = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    $resume_url = empty($new_resume_url) ? $employee['resume_url'] : $new_resume_url;

    $stmt = $pdo->prepare("CALL UpdateEmployeeProfile(:user_id, :first_name, :last_name, :phone_number, :job_title, :bio, :resume)");
    $stmt->execute([
        ':user_id' => $current_user['user_id'],
        ':first_name' => $_POST['first_name'],
        ':last_name' => $_POST['last_name'],
        ':phone_number' => $_POST['phone_number'],
        ':job_title' => $_POST['job_title'],
        ':bio' => $_POST['bio'],
        ':resume' => $resume_url
    ]);
}

// Function to update company profile
function updateCompanyProfile($pdo, $current_user) {
    $stmt = $pdo->prepare("CALL UpdateCompanyProfile(:user_id, :company_name, :website_url, :contact_person, :phone_number, :industry, :description, :address)");
    $stmt->execute([
        ':user_id' => $current_user['user_id'],
        ':company_name' => $_POST['company_name'],
        ':website_url' => $_POST['website_url'],
        ':contact_person' => $_POST['contact_person'],
        ':phone_number' => $_POST['phone_number'],
        ':industry' => $_POST['industry'],
        ':description' => $_POST['description'],
        ':address' => $_POST['address']
    ]);
}

?>