DELIMITER $$
CREATE DEFINER=root@localhost PROCEDURE AcceptApplication(IN p_application_id INT)
BEGIN
	UPDATE applications a
    SET a.status = 'accepted'
    WHERE a.application_id = p_application_id;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=root@localhost PROCEDURE ApplyForJob(
    IN p_job_id INT,
    IN p_employee_id INT
)
BEGIN
    -- Insert a new application record into the applications table
    INSERT INTO applications (job_id, employee_id, application_date, status)
    VALUES (p_job_id, p_employee_id, CURDATE(), 'Pending');
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=root@localhost FUNCTION CheckIfApplied(p_job_id INT, p_employee_id INT) RETURNS tinyint(1)
    DETERMINISTIC
BEGIN
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
DELIMITER ;

DELIMITER $$
CREATE DEFINER=root@localhost PROCEDURE GetAllJobs()
BEGIN
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
DELIMITER ;

DELIMITER $$
CREATE DEFINER=root@localhost PROCEDURE GetApplicationId(IN p_job_id INT, IN p_employee_id INT)
BEGIN
	SELECT a.application_id AS 'application_id'
    FROM applications a
    WHERE a.job_id = p_job_id
    AND a.employee_id = p_employee_id;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=root@localhost PROCEDURE GetAppliedJobs(IN p_employee_id INT)
BEGIN
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
DELIMITER ;

DELIMITER $$
CREATE DEFINER=root@localhost PROCEDURE GetCompanyJobs(IN p_company_id INT)
BEGIN
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
DELIMITER ;

DELIMITER $$
CREATE DEFINER=root@localhost PROCEDURE GetCompanyProfile(IN user_id INT)
BEGIN
    SELECT c.*
    FROM companyprofiles c
    JOIN users u ON c.company_id = u.user_id
    WHERE u.user_id = user_id;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=root@localhost PROCEDURE GetEmployeeProfile(IN user_id INT)
BEGIN
    SELECT e.*, u.profile_picture AS 'employee_profile_picture'
    FROM employeeprofiles e
    JOIN users u ON e.employee_id = u.user_id
    WHERE u.user_id = user_id;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=root@localhost PROCEDURE GetJobApplicants(IN p_job_id INT)
BEGIN
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
DELIMITER ;

DELIMITER $$
CREATE DEFINER=root@localhost PROCEDURE GetJobById(IN p_job_id INT)
BEGIN
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
DELIMITER ;

DELIMITER $$
CREATE DEFINER=root@localhost PROCEDURE GetUserDetailsByEmail(IN p_user_email VARCHAR(255))
BEGIN
    -- Fetch user details based on the provided email
    SELECT *
    FROM users
    WHERE email = p_user_email;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=root@localhost PROCEDURE GetUserDetailsById(p_user_id INT)
BEGIN
	SELECT *
    FROM users
    WHERE user_id = p_user_id;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=root@localhost FUNCTION GetUserIdByEmail(p_email VARCHAR(255)) RETURNS int(11)
BEGIN
    DECLARE o_user_id INT;

    SELECT user_id INTO o_user_id
    FROM users
    WHERE email = p_email; 

    RETURN o_user_id;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=root@localhost PROCEDURE MarkJobAsFilled(IN p_job_id INT)
BEGIN
    UPDATE jobs
    SET job_status = 'filled'
    WHERE job_id = p_job_id;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=root@localhost PROCEDURE PostJob(
    IN p_company_id INT,
    IN p_job_title VARCHAR(255),
    IN p_job_description TEXT,
    IN p_job_type VARCHAR(50),
    IN p_salary_range VARCHAR(50),
    IN p_location VARCHAR(255),
    IN p_experience_level VARCHAR(50)
)
BEGIN
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
DELIMITER ;

DELIMITER $$
CREATE DEFINER=root@localhost PROCEDURE RegisterCompany(
    IN p_user_id INT,
    IN p_company_name VARCHAR(255),
    IN p_contact_person VARCHAR(100),
    IN p_phone_number VARCHAR(20)
)
BEGIN
    -- Insert company-specific details into the CompanyProfiles table
    INSERT INTO CompanyProfiles (company_id, company_name, contact_person, phone_number) 
    VALUES (p_user_id, p_company_name, p_contact_person, p_phone_number);
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=root@localhost PROCEDURE RegisterEmployee(
    IN p_user_id INT,
    IN p_first_name VARCHAR(100),
    IN p_last_name VARCHAR(100),
    IN p_phone_number VARCHAR(20)
)
BEGIN
    -- Insert employee-specific details into the EmployeeProfiles table
    INSERT INTO EmployeeProfiles (employee_id, first_name, last_name, phone_number) 
    VALUES (p_user_id, p_first_name, p_last_name, p_phone_number);
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=root@localhost PROCEDURE RegisterUser(IN p_email VARCHAR(255), IN p_hashed_password VARCHAR(255), IN p_user_type VARCHAR(255), IN p_profile_picture VARCHAR(255), OUT o_new_user_id INT)
BEGIN
    -- Insert into Users table
    INSERT INTO Users (email, password, user_type, profile_picture)
    VALUES (p_email, p_hashed_password, p_user_type, p_profile_picture);

    -- Set the output parameter to the last inserted ID
    SET o_new_user_id = LAST_INSERT_ID();
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=root@localhost PROCEDURE RejectApplication(IN p_application_id INT)
BEGIN
    UPDATE applications a
    SET a.status = 'rejected';
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=root@localhost PROCEDURE RejectPendingApplicationsByJob(IN p_job_id INT)
BEGIN
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
DELIMITER ;

DELIMITER $$
CREATE DEFINER=root@localhost PROCEDURE UpdateCompanyProfile(IN p_user_id INT, IN p_company_name VARCHAR(100), IN p_website_url VARCHAR(100), IN p_contact_person VARCHAR(100), IN p_phone_number VARCHAR(20), IN p_industry VARCHAR(100), IN p_description TEXT, IN p_address VARCHAR(200))
BEGIN
    
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
DELIMITER ;

DELIMITER $$
CREATE DEFINER=root@localhost PROCEDURE UpdateEmployeeProfile(IN p_user_id INT, IN p_first_name VARCHAR(255), IN p_last_name VARCHAR(255), IN p_phone_number VARCHAR(255), IN p_job_title VARCHAR(255), IN p_bio VARCHAR(255), IN p_resume_url VARCHAR(255))
BEGIN
    
    UPDATE employeeprofiles  
    SET first_name = p_first_name,
    	last_name = p_last_name,
        phone_number = p_phone_number,
        job_title = p_job_title,
        bio = p_bio,
        resume_url = p_resume_url
    WHERE employee_id = p_user_id;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=root@localhost PROCEDURE UpdateUserProfile(
    IN p_user_id INT,
    IN p_email VARCHAR(255),
    IN p_password VARCHAR(255),
    IN p_profile_picture VARCHAR(255)
)
BEGIN
    -- Update the user's email, password, and profile picture
    UPDATE users  -- Replace 'users' with your actual table name
    SET email = p_email,
        password = p_password,  -- Ensure you handle password hashing as needed
        profile_picture = p_profile_picture
    WHERE user_id = p_user_id;
END$$
DELIMITER ;