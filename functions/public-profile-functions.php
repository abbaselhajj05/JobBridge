<?php

function getEmployeeProfile($pdo, $employee_id) {
    $stmt = $pdo->prepare("CALL GetEmployeeProfile(:employee_id)");
    $stmt->execute(['employee_id' => $employee_id]);
    $employee_profile = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt->closeCursor(); // Close the cursor to free up the connection
    return $employee_profile;
}

function getCompanyProfile($pdo, $user_id) {
    $stmt = $pdo->prepare("CALL GetCompanyProfile(:user_id)");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $company = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    return $company;
}

function getCompanyJobs($pdo, $company_id) {
    $stmt = $pdo->prepare("CALL GetCompanyJobs(:company_id)");
    $stmt->bindParam(':company_id', $company_id);
    $stmt->execute();
    $jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    return $jobs;
}

function getJobDetails($pdo, $job_id) {
    $stmt = $pdo->prepare("CALL GetJobById(:job_id)");
    $stmt->bindParam(':job_id', $job_id);
    $stmt->execute();
    $job = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    return $job;
}

?>