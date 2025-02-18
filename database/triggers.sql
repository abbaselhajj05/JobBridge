CREATE TRIGGER prevent_duplicate_application BEFORE INSERT ON applications
 FOR EACH ROW BEGIN
    IF EXISTS (
        SELECT 1 FROM applications
        WHERE job_id = NEW.job_id AND employee_id = NEW.employee_id
    ) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'You have already applied for this job';
    END IF;
END

CREATE TRIGGER prevent_duplicate_email BEFORE INSERT ON users
 FOR EACH ROW BEGIN
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