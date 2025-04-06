-- 1. Academic Year Management
CREATE TABLE academic_years (
    academic_year_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(9) NOT NULL UNIQUE, -- Format: YYYY-YYYY
    start_date DATE NOT NULL,
    end_date DATE NOT NULL
);

-- 2. Term Structure
CREATE TABLE terms (
    term_id INT AUTO_INCREMENT PRIMARY KEY,
    academic_year_id INT NOT NULL,
    term_number TINYINT NOT NULL CHECK (term_number BETWEEN 1 AND 3),
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    FOREIGN KEY (academic_year_id) REFERENCES academic_years(academic_year_id)
);

-- 3. Student Information
CREATE TABLE students (
    student_id INT AUTO_INCREMENT PRIMARY KEY,
    zimsec_candidate_number VARCHAR(20) UNIQUE NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    date_of_birth DATE NOT NULL,
    gender ENUM('Male','Female','Other') NOT NULL,
    national_id VARCHAR(20) UNIQUE,
    birth_cert_number VARCHAR(20) NOT NULL UNIQUE,
    address TEXT NOT NULL,
    enrollment_date DATE NOT NULL,
    current_class_id INT,
    status ENUM('Active','Graduated','Transferred','Dropped Out') DEFAULT 'Active'
);

-- 4. Guardian Information
CREATE TABLE guardians (
    guardian_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    relationship ENUM('Parent','Sibling','Relative','Other') NOT NULL,
    contact_number VARCHAR(20) NOT NULL,
    email VARCHAR(100),
    address TEXT,
    is_primary BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE
);

-- 5. Staff Management
CREATE TABLE staff (
    staff_id INT AUTO_INCREMENT PRIMARY KEY,
    national_id VARCHAR(20) UNIQUE NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    gender ENUM('Male','Female','Other') NOT NULL,
    position ENUM('Teacher','Administrator','Support Staff') NOT NULL,
    qualification_level ENUM('Diploma','Degree','Masters','PhD') NOT NULL,
    employment_date DATE NOT NULL,
    subject_specialization VARCHAR(100)
);

-- 6. ZIMSEC Subjects
CREATE TABLE subjects (
    subject_id INT AUTO_INCREMENT PRIMARY KEY,
    zimsec_code VARCHAR(10) UNIQUE NOT NULL,
    subject_name VARCHAR(100) NOT NULL,
    level ENUM('O-Level','A-Level') NOT NULL,
    is_core BOOLEAN DEFAULT FALSE,
    recommended_books TEXT
);

-- 7. Class Structure
CREATE TABLE classes (
    class_id INT AUTO_INCREMENT PRIMARY KEY,
    class_name VARCHAR(20) NOT NULL, -- Format: Form 1A, Form 4Science
    academic_year_id INT NOT NULL,
    stream ENUM('General','Science','Arts','Commercial') DEFAULT 'General',
    class_teacher_id INT,
    FOREIGN KEY (academic_year_id) REFERENCES academic_years(academic_year_id),
    FOREIGN KEY (class_teacher_id) REFERENCES staff(staff_id)
);

-- 8. Class Subject Allocation
CREATE TABLE class_subjects (
    class_subject_id INT AUTO_INCREMENT PRIMARY KEY,
    class_id INT NOT NULL,
    subject_id INT NOT NULL,
    teacher_id INT NOT NULL,
    hours_per_week INT NOT NULL,
    FOREIGN KEY (class_id) REFERENCES classes(class_id),
    FOREIGN KEY (subject_id) REFERENCES subjects(subject_id),
    FOREIGN KEY (teacher_id) REFERENCES staff(staff_id)
);

-- 9. Academic Results
CREATE TABLE academic_results (
    result_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    class_subject_id INT NOT NULL,
    term_id INT NOT NULL,
    continuous_assessment DECIMAL(5,2) CHECK (continuous_assessment BETWEEN 0 AND 100),
    final_exam DECIMAL(5,2) CHECK (final_exam BETWEEN 0 AND 100),
    total_mark DECIMAL(5,2) GENERATED ALWAYS AS (continuous_assessment + final_exam),
    grade CHAR(2) GENERATED ALWAYS AS (
        CASE
            WHEN (continuous_assessment + final_exam) >= 75 THEN 'A'
            WHEN (continuous_assessment + final_exam) >= 65 THEN 'B'
            WHEN (continuous_assessment + final_exam) >= 50 THEN 'C'
            WHEN (continuous_assessment + final_exam) >= 35 THEN 'D'
            ELSE 'E'
        END
    ),
    FOREIGN KEY (student_id) REFERENCES students(student_id),
    FOREIGN KEY (class_subject_id) REFERENCES class_subjects(class_subject_id),
    FOREIGN KEY (term_id) REFERENCES terms(term_id)
);

-- 10. Fee Management
CREATE TABLE fee_management (
    fee_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    academic_year_id INT NOT NULL,
    tuition DECIMAL(10,2) NOT NULL,
    examination_fee DECIMAL(10,2) NOT NULL,
    sports_levy DECIMAL(10,2) NOT NULL,
    total_amount DECIMAL(10,2) GENERATED ALWAYS AS (tuition + examination_fee + sports_levy),
    amount_paid DECIMAL(10,2) DEFAULT 0.00,
    balance DECIMAL(10,2) GENERATED ALWAYS AS (total_amount - amount_paid),
    payment_deadline DATE NOT NULL,
    FOREIGN KEY (student_id) REFERENCES students(student_id),
    FOREIGN KEY (academic_year_id) REFERENCES academic_years(academic_year_id)
);

-- 11. User Authentication
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    user_role ENUM('Admin','Teacher','Accountant','Parent') NOT NULL,
    associated_id INT NOT NULL, -- Links to student_id or staff_id
    last_login DATETIME,
    account_status ENUM('Active','Disabled') DEFAULT 'Active'
);

-- Indexes for Optimization
CREATE INDEX idx_student_name ON students(last_name, first_name);
CREATE INDEX idx_subject_code ON subjects(zimsec_code);
CREATE INDEX idx_fee_balance ON fee_management(balance);

-- Sample Data Insertion
INSERT INTO academic_years (name, start_date, end_date) 
VALUES ('2024-2025', '2024-01-10', '2025-12-10');

INSERT INTO terms (academic_year_id, term_number, start_date, end_date)
VALUES (1, 1, '2024-01-10', '2024-04-10');

INSERT INTO subjects (zimsec_code, subject_name, level, is_core)
VALUES ('7004', 'Mathematics', 'O-Level', TRUE),
       ('6002', 'English Language', 'O-Level', TRUE);