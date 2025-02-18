-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 17, 2025 at 06:10 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `jobportal`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `AcceptApplication` (IN `p_application_id` INT)   BEGIN
	UPDATE applications a
    SET a.status = 'accepted'
    WHERE a.application_id = p_application_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ApplyForJob` (IN `p_job_id` INT, IN `p_employee_id` INT)   BEGIN
    -- Insert a new application record into the applications table
    INSERT INTO applications (job_id, employee_id, application_date, status)
    VALUES (p_job_id, p_employee_id, CURDATE(), 'Pending');
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetAllJobs` ()   BEGIN
  SELECT 
    u.email AS 'company_email', 
    u.profile_picture AS 'company_profile_picture', 
    cp.company_name,
    cp.contact_person,
    cp.phone_number,
    cp.website_url,
    cp.industry,
    cp.description,
    cp.address,
    j.job_id,
    j.company_id,
    j.job_title,
    j.job_description,
    j.job_type,
    j.salary_range,
    j.location,
    j.experience_level,
    j.posted_date
  FROM jobs j
  JOIN companyprofiles cp ON j.company_id = cp.company_id
  JOIN users u ON cp.company_id = u.user_id
  WHERE j.job_status LIKE 'open'
  ORDER BY j.posted_date DESC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetApplicationId` (IN `p_job_id` INT, IN `p_employee_id` INT)   BEGIN
	SELECT a.application_id AS 'application_id'
    FROM applications a
    WHERE a.job_id = p_job_id
    AND a.employee_id = p_employee_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetAppliedJobs` (IN `p_employee_id` INT)   BEGIN
    SELECT 
        a.application_id,
        a.application_date,
        a.status,
        u.profile_picture AS 'company_profile_picture',
        j.job_id,
        j.company_id,
        j.job_title,
        j.job_description,
        cp.company_id,
        cp.company_name,
        cp.contact_person,
        cp.phone_number,
        cp.website_url,
        cp.industry,
        cp.address
    FROM applications a
    JOIN jobs j ON j.job_id = a.job_id
    JOIN companyprofiles cp ON cp.company_id = j.company_id
    JOIN employeeprofiles e ON a.employee_id = e.employee_id
    JOIN users u ON u.user_id = e.employee_id
    WHERE a.employee_id = p_employee_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetCompanyJobs` (IN `p_company_id` INT)   BEGIN
    SELECT 
        j.job_id,
        j.job_title,
        j.job_description,
        j.job_type,
        j.job_status,
        j.salary_range,
        j.location,
        j.experience_level,
        j.posted_date,
        cp.company_name,
        u.profile_picture AS 'company_profile_picture',
        cp.contact_person,
        cp.phone_number,
        cp.website_url
    FROM 
        jobs j
    JOIN 
        companyprofiles cp ON j.company_id = cp.company_id
    JOIN
        users u ON cp.company_id = u.user_id
    WHERE 
        j.company_id = p_company_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetCompanyProfile` (IN `user_id` INT)   BEGIN
    SELECT c.*
    FROM companyprofiles c
    JOIN users u ON c.company_id = u.user_id
    WHERE u.user_id = user_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetEmployeeProfile` (IN `user_id` INT)   BEGIN
    SELECT e.*, u.profile_picture AS 'employee_profile_picture'
    FROM employeeprofiles e
    JOIN users u ON e.employee_id = u.user_id
    WHERE u.user_id = user_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetJobApplicants` (IN `p_job_id` INT)   BEGIN
    SELECT
    	a.application_id AS 'application_id',
        a.job_id AS 'job_id',
        a.employee_id AS 'applicant_id',
        a.status AS 'status'
    FROM 
        applications a
    WHERE 
        a.job_id = p_job_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetJobById` (IN `p_job_id` INT)   BEGIN
  SELECT 
    u.email AS 'company_email', 
    u.profile_picture AS 'company_profile_picture', 
    cp.company_name,
    cp.contact_person,
    cp.phone_number AS 'company_phone_number',
    cp.website_url,
    cp.industry,
    cp.description AS 'company_description',
    cp.address AS 'company_address',
    j.job_id,
    j.company_id,
    j.job_title,
    j.job_description,
    j.job_type,
    j.salary_range,
    j.location AS 'job_location',
    j.experience_level,
    j.posted_date
  FROM jobs j
  JOIN companyprofiles cp ON j.company_id = cp.company_id
  JOIN users u ON cp.company_id = u.user_id
  AND j.job_id = p_job_id
  ORDER BY j.posted_date DESC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetUserDetailsByEmail` (IN `p_user_email` VARCHAR(255))   BEGIN
    -- Fetch user details based on the provided email
    SELECT *
    FROM users
    WHERE email = p_user_email;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetUserDetailsById` (`p_user_id` INT)   BEGIN
	SELECT *
    FROM users
    WHERE user_id = p_user_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `MarkJobAsFilled` (IN `p_job_id` INT)   BEGIN
    UPDATE jobs
    SET job_status = 'filled'
    WHERE job_id = p_job_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `PostJob` (IN `p_company_id` INT, IN `p_job_title` VARCHAR(255), IN `p_job_description` TEXT, IN `p_job_type` VARCHAR(50), IN `p_salary_range` VARCHAR(50), IN `p_location` VARCHAR(255), IN `p_experience_level` VARCHAR(50))   BEGIN
    INSERT INTO jobs (
        company_id,
        job_title,
        job_description,
        job_type,
        salary_range,
        location,
        experience_level
    ) VALUES (
        p_company_id,
        p_job_title,
        p_job_description,
        p_job_type,
        p_salary_range,
        p_location,
        p_experience_level
    );
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `RegisterCompany` (IN `p_user_id` INT, IN `p_company_name` VARCHAR(255), IN `p_contact_person` VARCHAR(100), IN `p_phone_number` VARCHAR(20))   BEGIN
    -- Insert company-specific details into the CompanyProfiles table
    INSERT INTO CompanyProfiles (company_id, company_name, contact_person, phone_number) 
    VALUES (p_user_id, p_company_name, p_contact_person, p_phone_number);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `RegisterEmployee` (IN `p_user_id` INT, IN `p_first_name` VARCHAR(100), IN `p_last_name` VARCHAR(100), IN `p_phone_number` VARCHAR(20))   BEGIN
    -- Insert employee-specific details into the EmployeeProfiles table
    INSERT INTO EmployeeProfiles (employee_id, first_name, last_name, phone_number) 
    VALUES (p_user_id, p_first_name, p_last_name, p_phone_number);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `RegisterUser` (IN `p_email` VARCHAR(255), IN `p_hashed_password` VARCHAR(255), IN `p_user_type` VARCHAR(255), IN `p_profile_picture` VARCHAR(255), OUT `o_new_user_id` INT)   BEGIN
    -- Insert into Users table
    INSERT INTO Users (email, password, user_type, profile_picture)
    VALUES (p_email, p_hashed_password, p_user_type, p_profile_picture);

    -- Set the output parameter to the last inserted ID
    SET o_new_user_id = LAST_INSERT_ID();
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `RejectApplication` (IN `p_application_id` INT)   BEGIN
    UPDATE applications a
    SET a.status = 'rejected';
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `RejectPendingApplicationsByJob` (IN `p_job_id` INT)   BEGIN
    DECLARE done INT DEFAULT FALSE;
    DECLARE v_application_id INT;

    -- Declare the cursor for applications with the specified job_id
    DECLARE application_cursor CURSOR FOR 
        SELECT application_id 
        FROM applications 
        WHERE job_id = p_job_id AND status = 'pending';

    -- Declare a handler to set 'done' when there are no more rows
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

    -- Open the cursor
    OPEN application_cursor;

    -- Loop through the cursor
    read_loop: LOOP
        FETCH application_cursor INTO v_application_id;

        IF done THEN
            LEAVE read_loop;
        END IF;

        -- Update the status to 'rejected'
        UPDATE applications 
        SET status = 'rejected' 
        WHERE application_id = v_application_id;
    END LOOP;

    -- Close the cursor
    CLOSE application_cursor;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateCompanyProfile` (IN `p_user_id` INT, IN `p_company_name` VARCHAR(100), IN `p_website_url` VARCHAR(100), IN `p_contact_person` VARCHAR(100), IN `p_phone_number` VARCHAR(20), IN `p_industry` VARCHAR(100), IN `p_description` TEXT, IN `p_address` VARCHAR(200))   BEGIN
    
    -- Update Company table
    UPDATE CompanyProfiles
    SET company_name = p_company_name, 
        website_url = p_website_url,
        contact_person = p_contact_person, 
        phone_number = p_phone_number,
        industry = p_industry, 
        description = p_description, 
        address = p_address
    WHERE company_id = p_user_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateEmployeeProfile` (IN `p_user_id` INT, IN `p_first_name` VARCHAR(255), IN `p_last_name` VARCHAR(255), IN `p_phone_number` VARCHAR(255), IN `p_job_title` VARCHAR(255), IN `p_bio` VARCHAR(255), IN `p_resume_url` VARCHAR(255))   BEGIN
    
    UPDATE employeeprofiles  
    SET first_name = p_first_name,
    	last_name = p_last_name,
        phone_number = p_phone_number,
        job_title = p_job_title,
        bio = p_bio,
        resume_url = p_resume_url
    WHERE employee_id = p_user_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateUserProfile` (IN `p_user_id` INT, IN `p_email` VARCHAR(255), IN `p_password` VARCHAR(255), IN `p_profile_picture` VARCHAR(255))   BEGIN
    -- Update the user's email, password, and profile picture
    UPDATE users  -- Replace 'users' with your actual table name
    SET email = p_email,
        password = p_password,  -- Ensure you handle password hashing as needed
        profile_picture = p_profile_picture
    WHERE user_id = p_user_id;
END$$

--
-- Functions
--
CREATE DEFINER=`root`@`localhost` FUNCTION `CheckIfApplied` (`p_job_id` INT, `p_employee_id` INT) RETURNS TINYINT(1) DETERMINISTIC BEGIN
    DECLARE result TINYINT(1);

    -- Check if the application exists
    SELECT EXISTS(
        SELECT 1
        FROM applications a
        WHERE a.job_id = p_job_id
        AND a.employee_id = p_employee_id
    ) INTO result;

    RETURN result;
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `GetUserIdByEmail` (`p_email` VARCHAR(255)) RETURNS INT(11)  BEGIN
    DECLARE o_user_id INT;

    SELECT user_id INTO o_user_id
    FROM users
    WHERE email = p_email; 

    RETURN o_user_id;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

CREATE TABLE `applications` (
  `application_id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `application_date` date DEFAULT curdate(),
  `status` enum('pending','accepted','rejected') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `applications`
--

INSERT INTO `applications` (`application_id`, `job_id`, `employee_id`, `application_date`, `status`) VALUES
(47, 24, 46, '2025-02-17', 'accepted'),
(48, 25, 47, '2025-02-17', 'accepted'),
(49, 25, 49, '2025-02-17', 'rejected');

--
-- Triggers `applications`
--
DELIMITER $$
CREATE TRIGGER `prevent_duplicate_application` BEFORE INSERT ON `applications` FOR EACH ROW BEGIN
    IF EXISTS (
        SELECT 1 FROM applications
        WHERE job_id = NEW.job_id AND employee_id = NEW.employee_id
    ) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'You have already applied for this job';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `companyprofiles`
--

CREATE TABLE `companyprofiles` (
  `company_id` int(11) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `contact_person` varchar(255) DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `website_url` varchar(255) DEFAULT NULL,
  `industry` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `companyprofiles`
--

INSERT INTO `companyprofiles` (`company_id`, `company_name`, `contact_person`, `phone_number`, `website_url`, `industry`, `description`, `address`) VALUES
(45, 'Style&Shop', 'Ali Haj Youssef', '81646230', NULL, NULL, NULL, NULL),
(48, 'Futuristic', 'Ella Semaan', '+961 1 234 567', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `employeeprofiles`
--

CREATE TABLE `employeeprofiles` (
  `employee_id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `job_title` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `resume_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employeeprofiles`
--

INSERT INTO `employeeprofiles` (`employee_id`, `first_name`, `last_name`, `phone_number`, `job_title`, `bio`, `resume_url`) VALUES
(46, 'عباس', 'الحاج يوسف', '123', NULL, NULL, NULL),
(47, 'Emily', 'Thomson', '+1 (234) 567 8901', NULL, NULL, NULL),
(49, 'Sophie', 'Parks', '+1 123 321 4567', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `job_id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `job_title` varchar(255) NOT NULL,
  `job_description` text NOT NULL,
  `job_type` enum('Full Time','Part Time') NOT NULL,
  `salary_range` varchar(100) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `experience_level` varchar(100) DEFAULT NULL,
  `posted_date` date DEFAULT current_timestamp(),
  `job_status` enum('open','filled') DEFAULT 'open'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`job_id`, `company_id`, `job_title`, `job_description`, `job_type`, `salary_range`, `location`, `experience_level`, `posted_date`, `job_status`) VALUES
(24, 45, 'Stylist', 'Building a clothing style by matching the suitable jacket to the jeans and shirt', 'Full Time', '$50k - $70k', 'Lailaki', '6-9 years', '2025-02-17', 'filled'),
(25, 48, 'Nutritionist', 'A smart nutritionist is needed!', 'Part Time', '$100k - $150k', 'Betroun', '1-3 years', '2025-02-17', 'filled');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_type` enum('employee','company') NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `email`, `password`, `user_type`, `profile_picture`, `created_at`, `updated_at`) VALUES
(45, 'style&shop-lb@gmail.com', '$2y$10$r6/L1quRjESgrj2hVCCyUuXh9F4NJn9/mwgK5FGF1aN7OOsHDlOdG', 'company', 'http://localhost/job_board%20modified/uploads/default_profile_pic.png', '2025-02-17 09:00:31', '2025-02-17 09:00:31'),
(46, 'abbas@gmail.com', '$2y$10$EFVjHPbjpdcVCWerlK9Xue6xWYij7oj7QWkLfym06lUfzE8EN6U1a', 'employee', 'http://localhost/job_board%20modified/uploads/default_profile_pic.png', '2025-02-17 09:08:43', '2025-02-17 09:08:43'),
(47, 'emily@hotmail.com', '$2y$10$6JLUikiZ7NgBwDrs35EM.OXZ5MYqxjDElawf18UzPEBELGLHiIXly', 'employee', 'http://localhost/job_board%20modified/uploads/default_profile_pic.png', '2025-02-17 12:12:32', '2025-02-17 12:12:32'),
(48, 'futuristic@hotmail.com', '$2y$10$XFab97o85bkLLtw4aXHYxuDKSpD5PhUVSdPoPFsir4eRCZ2GoWNse', 'company', 'http://localhost/job_board%20modified/uploads/default_profile_pic.png', '2025-02-17 12:13:51', '2025-02-17 12:13:51'),
(49, 'sophie@hotmail.com', '$2y$10$inBskURrQoZQLTBSc75Sr.dkcEmkqcpW20wxRt.ujbPoxNJimQ9aC', 'employee', 'http://localhost/job_board%20modified/uploads/default_profile_pic.png', '2025-02-17 12:16:13', '2025-02-17 12:16:13');

--
-- Triggers `users`
--
DELIMITER $$
CREATE TRIGGER `prevent_duplicate_email` BEFORE INSERT ON `users` FOR EACH ROW BEGIN
    DECLARE email_count INT;

    -- Count how many times the email appears in the Users table
    SELECT COUNT(*) INTO email_count 
    FROM Users 
    WHERE email = NEW.email;

    -- If email already exists, issue a signal
    IF email_count > 0 THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'This email is already registered!';
    END IF;
END
$$
DELIMITER ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`application_id`),
  ADD KEY `fk_application_job` (`job_id`),
  ADD KEY `fk_application_employee` (`employee_id`);

--
-- Indexes for table `companyprofiles`
--
ALTER TABLE `companyprofiles`
  ADD PRIMARY KEY (`company_id`);

--
-- Indexes for table `employeeprofiles`
--
ALTER TABLE `employeeprofiles`
  ADD PRIMARY KEY (`employee_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`job_id`),
  ADD KEY `fk_job_company` (`company_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `applications`
--
ALTER TABLE `applications`
  MODIFY `application_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `job_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `applications`
--
ALTER TABLE `applications`
  ADD CONSTRAINT `fk_application_employee` FOREIGN KEY (`employee_id`) REFERENCES `employeeprofiles` (`employee_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_application_job` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`job_id`) ON DELETE CASCADE;

--
-- Constraints for table `companyprofiles`
--
ALTER TABLE `companyprofiles`
  ADD CONSTRAINT `fk_company_user` FOREIGN KEY (`company_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `employeeprofiles`
--
ALTER TABLE `employeeprofiles`
  ADD CONSTRAINT `fk_employee_user` FOREIGN KEY (`employee_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `jobs`
--
ALTER TABLE `jobs`
  ADD CONSTRAINT `fk_job_company` FOREIGN KEY (`company_id`) REFERENCES `companyprofiles` (`company_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
