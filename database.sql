-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 02, 2025 at 09:37 AM
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
-- Database: `refined_work`
--

-- --------------------------------------------------------

--
-- Table structure for table `academic_results`
--

CREATE TABLE `academic_results` (
  `result_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `class_subject_id` int(11) NOT NULL,
  `term_id` int(11) NOT NULL,
  `continuous_assessment` decimal(5,2) DEFAULT NULL CHECK (`continuous_assessment` between 0 and 100),
  `final_exam` decimal(5,2) DEFAULT NULL CHECK (`final_exam` between 0 and 100),
  `total_mark` decimal(5,2) GENERATED ALWAYS AS (`continuous_assessment` + `final_exam`) VIRTUAL,
  `grade` char(2) GENERATED ALWAYS AS (case when `continuous_assessment` + `final_exam` >= 75 then 'A' when `continuous_assessment` + `final_exam` >= 65 then 'B' when `continuous_assessment` + `final_exam` >= 50 then 'C' when `continuous_assessment` + `final_exam` >= 35 then 'D' else 'E' end) VIRTUAL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `academic_years`
--

CREATE TABLE `academic_years` (
  `academic_year_id` int(11) NOT NULL,
  `name` varchar(9) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `academic_years`
--

INSERT INTO `academic_years` (`academic_year_id`, `name`, `start_date`, `end_date`) VALUES
(1, '2024-2025', '2024-01-10', '2025-12-10'),
(2, '2023-2024', '2023-01-10', '2023-12-10'),
(3, '2025-2026', '2025-01-12', '2026-12-10');

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE `classes` (
  `class_id` int(11) NOT NULL,
  `class_name` varchar(20) NOT NULL,
  `academic_year_id` int(11) NOT NULL,
  `stream` enum('General','Science','Arts','Commercial') DEFAULT 'General',
  `class_teacher_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `classes`
--

INSERT INTO `classes` (`class_id`, `class_name`, `academic_year_id`, `stream`, `class_teacher_id`) VALUES
(1, 'Form 1A', 1, 'General', 1),
(2, 'Form 2Science', 1, 'Science', 2),
(3, 'Form 3Arts', 1, 'Arts', 4);

-- --------------------------------------------------------

--
-- Table structure for table `class_subjects`
--

CREATE TABLE `class_subjects` (
  `class_subject_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `hours_per_week` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fee_management`
--

CREATE TABLE `fee_management` (
  `fee_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `academic_year_id` int(11) NOT NULL,
  `tuition` decimal(10,2) NOT NULL,
  `examination_fee` decimal(10,2) NOT NULL,
  `sports_levy` decimal(10,2) NOT NULL,
  `total_amount` decimal(10,2) GENERATED ALWAYS AS (`tuition` + `examination_fee` + `sports_levy`) VIRTUAL,
  `amount_paid` decimal(10,2) DEFAULT 0.00,
  `balance` decimal(10,2) GENERATED ALWAYS AS (`total_amount` - `amount_paid`) VIRTUAL,
  `payment_deadline` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `guardians`
--

CREATE TABLE `guardians` (
  `guardian_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `relationship` enum('Parent','Sibling','Relative','Other') NOT NULL,
  `contact_number` varchar(20) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `is_primary` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `guardians`
--

INSERT INTO `guardians` (`guardian_id`, `student_id`, `full_name`, `relationship`, `contact_number`, `email`, `address`, `is_primary`) VALUES
(1, 1, 'Farai Moyo', 'Parent', '+263773123456', 'fmoyo@example.com', '123 Chikwaka Village', 1),
(2, 1, 'Sekai Moyo', 'Parent', '+263772654321', NULL, '123 Chikwaka Village', 0),
(3, 2, 'Tendai Chiweshe', 'Parent', '+263713987654', 'tchiweshe@example.com', '456 Murewa Road', 1),
(4, 3, 'Blessing Marufu', 'Relative', '+263774456123', NULL, '789 Mutoko Lane', 1);

-- --------------------------------------------------------

--
-- Table structure for table `prevoius_schools`
--

CREATE TABLE `prevoius_schools` (
  `previous_school_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `school_name` varchar(150) NOT NULL,
  `level_completed` enum('Primary','Secondary','O-Level','A-Level','Other') NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `reason_for_leaving` text DEFAULT NULL,
  `performance_summary` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `staff_id` int(11) NOT NULL,
  `national_id` varchar(20) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `position` enum('Teacher','Administrator','Support Staff') NOT NULL,
  `qualification_level` enum('Diploma','Degree','Masters','PhD') NOT NULL,
  `employment_date` date NOT NULL,
  `subject_specialization` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`staff_id`, `national_id`, `first_name`, `last_name`, `gender`, `position`, `qualification_level`, `employment_date`, `subject_specialization`) VALUES
(1, '13425ed', 'Rufaro', 'Sitotombe', 'Male', 'Administrator', 'Degree', '2025-04-15', 'N/A'),
(2, 'ZW-63-654321A', 'Chengetai', 'Maphosa', 'Female', 'Teacher', 'Degree', '2018-03-01', 'Mathematics'),
(3, 'ZW-63-765432B', 'Tatenda', 'Nyoni', 'Male', 'Teacher', 'Masters', '2020-02-15', 'English Language'),
(4, 'ZW-63-876543C', 'Munashe', 'Chideme', 'Female', 'Administrator', 'Diploma', '2015-06-01', NULL),
(5, 'ZW-63-987654D', 'Tawanda', 'Hove', 'Male', 'Teacher', 'Degree', '2019-04-01', 'Shona');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `student_id` int(11) NOT NULL,
  `zimsec_candidate_number` varchar(20) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `date_of_birth` date NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `national_id` varchar(20) DEFAULT NULL,
  `birth_cert_number` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `enrollment_date` date NOT NULL,
  `current_class_id` int(11) DEFAULT NULL,
  `status` enum('Active','Graduated','Transferred','Dropped Out') DEFAULT 'Active',
  `email` varchar(100) DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`student_id`, `zimsec_candidate_number`, `first_name`, `last_name`, `date_of_birth`, `gender`, `national_id`, `birth_cert_number`, `address`, `enrollment_date`, `current_class_id`, `status`, `email`, `phone_number`) VALUES
(1, 'ZIM2024001', 'Tadiwa', 'Moyo', '2008-03-15', 'Male', 'ZW-63-084753F', 'BC0001', '123 Chikwaka Village, Mash East', '2023-01-10', 1, 'Active', NULL, NULL),
(2, 'ZIM2024002', 'Rufaro', 'Chiweshe', '2007-11-22', 'Female', 'ZW-63-094752G', 'BC0002', '456 Murewa Road, Mash East', '2023-01-10', 2, 'Active', NULL, NULL),
(3, 'ZIM2024003', 'Tanaka', 'Marufu', '2009-05-30', 'Male', 'ZW-63-104753H', 'BC0003', '789 Mutoko Lane, Mash East', '2023-01-10', 1, 'Active', NULL, NULL),
(4, 'ZIM2024004', 'Nyasha', 'Mabika', '2008-12-05', 'Female', 'ZW-63-114754J', 'BC0004', '321 Goromonzi Street, Mash East', '2023-01-10', 3, 'Active', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `student_attendance`
--

CREATE TABLE `student_attendance` (
  `attendance_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `status` enum('Present','Absent','Late','Excused') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_behavior`
--

CREATE TABLE `student_behavior` (
  `behavior_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `behavior_type` enum('Positive','Negative') NOT NULL,
  `description` text DEFAULT NULL,
  `action_taken` text DEFAULT NULL,
  `recorded_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `subject_id` int(11) NOT NULL,
  `zimsec_code` varchar(10) NOT NULL,
  `subject_name` varchar(100) NOT NULL,
  `level` enum('O-Level','A-Level') NOT NULL,
  `is_core` tinyint(1) DEFAULT 0,
  `recommended_books` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`subject_id`, `zimsec_code`, `subject_name`, `level`, `is_core`, `recommended_books`) VALUES
(1, '7004', 'Mathematics', 'O-Level', 1, NULL),
(2, '6002', 'English Language', 'O-Level', 1, NULL),
(3, '5003', 'Shona', 'O-Level', 1, NULL),
(4, '4022', 'History', 'O-Level', 0, NULL),
(5, '7015', 'Chemistry', 'A-Level', 1, NULL),
(6, '6018', 'Biology', 'O-Level', 0, NULL),
(34, '7021', 'Physics', 'A-Level', 1, NULL),
(35, '4033', 'Geography', 'O-Level', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `terms`
--

CREATE TABLE `terms` (
  `term_id` int(11) NOT NULL,
  `academic_year_id` int(11) NOT NULL,
  `term_number` tinyint(4) NOT NULL CHECK (`term_number` between 1 and 3),
  `start_date` date NOT NULL,
  `end_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `terms`
--

INSERT INTO `terms` (`term_id`, `academic_year_id`, `term_number`, `start_date`, `end_date`) VALUES
(1, 1, 1, '2024-01-10', '2024-04-10'),
(2, 2, 1, '2023-01-10', '2023-04-10'),
(3, 2, 2, '2023-05-10', '2023-08-10'),
(4, 2, 3, '2023-09-10', '2023-11-10'),
(5, 1, 1, '2023-01-10', '2023-04-10'),
(6, 1, 2, '2023-05-10', '2023-08-10'),
(7, 1, 3, '2023-09-10', '2023-11-10'),
(8, 2, 1, '2024-01-10', '2024-04-10'),
(9, 1, 1, '2023-01-10', '2023-04-10'),
(10, 1, 2, '2023-05-10', '2023-08-10'),
(11, 1, 3, '2023-09-10', '2023-11-10'),
(12, 2, 1, '2024-01-10', '2024-04-10'),
(13, 1, 1, '2023-01-10', '2023-04-10'),
(14, 1, 2, '2023-05-10', '2023-08-10'),
(15, 1, 3, '2023-09-10', '2023-11-10'),
(16, 2, 1, '2024-01-10', '2024-04-10'),
(17, 1, 1, '2023-01-10', '2023-04-10'),
(18, 1, 2, '2023-05-10', '2023-08-10'),
(19, 1, 3, '2023-09-10', '2023-11-10'),
(20, 2, 1, '2024-01-10', '2024-04-10'),
(21, 1, 1, '2023-01-10', '2023-04-10'),
(22, 1, 2, '2023-05-10', '2023-08-10'),
(23, 1, 3, '2023-09-10', '2023-11-10'),
(24, 2, 1, '2024-01-10', '2024-04-10'),
(25, 1, 1, '2023-01-10', '2023-04-10'),
(26, 1, 2, '2023-05-10', '2023-08-10'),
(27, 1, 3, '2023-09-10', '2023-11-10'),
(28, 2, 1, '2024-01-10', '2024-04-10');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `user_role` enum('Admin','Teacher','Accountant','Parent') NOT NULL,
  `associated_id` int(11) NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `account_status` enum('Active','Disabled') DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password_hash`, `user_role`, `associated_id`, `last_login`, `account_status`) VALUES
(2, 'rufaro', 'rufarodon', 'Admin', 1, NULL, 'Active'),
(3, 'don', 'don123', 'Teacher', 2, NULL, 'Active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `academic_results`
--
ALTER TABLE `academic_results`
  ADD PRIMARY KEY (`result_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `class_subject_id` (`class_subject_id`),
  ADD KEY `term_id` (`term_id`);

--
-- Indexes for table `academic_years`
--
ALTER TABLE `academic_years`
  ADD PRIMARY KEY (`academic_year_id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`class_id`),
  ADD KEY `academic_year_id` (`academic_year_id`),
  ADD KEY `class_teacher_id` (`class_teacher_id`);

--
-- Indexes for table `class_subjects`
--
ALTER TABLE `class_subjects`
  ADD PRIMARY KEY (`class_subject_id`),
  ADD KEY `class_id` (`class_id`),
  ADD KEY `subject_id` (`subject_id`),
  ADD KEY `teacher_id` (`teacher_id`);

--
-- Indexes for table `fee_management`
--
ALTER TABLE `fee_management`
  ADD PRIMARY KEY (`fee_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `academic_year_id` (`academic_year_id`),
  ADD KEY `idx_fee_balance` (`balance`);

--
-- Indexes for table `guardians`
--
ALTER TABLE `guardians`
  ADD PRIMARY KEY (`guardian_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `prevoius_schools`
--
ALTER TABLE `prevoius_schools`
  ADD PRIMARY KEY (`previous_school_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`staff_id`),
  ADD UNIQUE KEY `national_id` (`national_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`student_id`),
  ADD UNIQUE KEY `zimsec_candidate_number` (`zimsec_candidate_number`),
  ADD UNIQUE KEY `birth_cert_number` (`birth_cert_number`),
  ADD UNIQUE KEY `national_id` (`national_id`),
  ADD KEY `idx_student_name` (`last_name`,`first_name`);

--
-- Indexes for table `student_attendance`
--
ALTER TABLE `student_attendance`
  ADD PRIMARY KEY (`attendance_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `class_id` (`class_id`);

--
-- Indexes for table `student_behavior`
--
ALTER TABLE `student_behavior`
  ADD PRIMARY KEY (`behavior_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `recorded_by` (`recorded_by`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`subject_id`),
  ADD UNIQUE KEY `zimsec_code` (`zimsec_code`),
  ADD KEY `idx_subject_code` (`zimsec_code`);

--
-- Indexes for table `terms`
--
ALTER TABLE `terms`
  ADD PRIMARY KEY (`term_id`),
  ADD KEY `academic_year_id` (`academic_year_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `academic_results`
--
ALTER TABLE `academic_results`
  MODIFY `result_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `academic_years`
--
ALTER TABLE `academic_years`
  MODIFY `academic_year_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `classes`
--
ALTER TABLE `classes`
  MODIFY `class_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `class_subjects`
--
ALTER TABLE `class_subjects`
  MODIFY `class_subject_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `fee_management`
--
ALTER TABLE `fee_management`
  MODIFY `fee_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `guardians`
--
ALTER TABLE `guardians`
  MODIFY `guardian_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `prevoius_schools`
--
ALTER TABLE `prevoius_schools`
  MODIFY `previous_school_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `staff_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `student_attendance`
--
ALTER TABLE `student_attendance`
  MODIFY `attendance_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_behavior`
--
ALTER TABLE `student_behavior`
  MODIFY `behavior_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `subject_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `terms`
--
ALTER TABLE `terms`
  MODIFY `term_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `academic_results`
--
ALTER TABLE `academic_results`
  ADD CONSTRAINT `academic_results_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`),
  ADD CONSTRAINT `academic_results_ibfk_2` FOREIGN KEY (`class_subject_id`) REFERENCES `class_subjects` (`class_subject_id`),
  ADD CONSTRAINT `academic_results_ibfk_3` FOREIGN KEY (`term_id`) REFERENCES `terms` (`term_id`);

--
-- Constraints for table `classes`
--
ALTER TABLE `classes`
  ADD CONSTRAINT `classes_ibfk_1` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`academic_year_id`),
  ADD CONSTRAINT `classes_ibfk_2` FOREIGN KEY (`class_teacher_id`) REFERENCES `staff` (`staff_id`);

--
-- Constraints for table `class_subjects`
--
ALTER TABLE `class_subjects`
  ADD CONSTRAINT `class_subjects_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `classes` (`class_id`),
  ADD CONSTRAINT `class_subjects_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`subject_id`),
  ADD CONSTRAINT `class_subjects_ibfk_3` FOREIGN KEY (`teacher_id`) REFERENCES `staff` (`staff_id`);

--
-- Constraints for table `fee_management`
--
ALTER TABLE `fee_management`
  ADD CONSTRAINT `fee_management_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`),
  ADD CONSTRAINT `fee_management_ibfk_2` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`academic_year_id`);

--
-- Constraints for table `guardians`
--
ALTER TABLE `guardians`
  ADD CONSTRAINT `guardians_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE;

--
-- Constraints for table `prevoius_schools`
--
ALTER TABLE `prevoius_schools`
  ADD CONSTRAINT `prevoius_schools_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE;

--
-- Constraints for table `student_attendance`
--
ALTER TABLE `student_attendance`
  ADD CONSTRAINT `student_attendance_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`),
  ADD CONSTRAINT `student_attendance_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `classes` (`class_id`);

--
-- Constraints for table `student_behavior`
--
ALTER TABLE `student_behavior`
  ADD CONSTRAINT `student_behavior_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`),
  ADD CONSTRAINT `student_behavior_ibfk_2` FOREIGN KEY (`recorded_by`) REFERENCES `staff` (`staff_id`);

--
-- Constraints for table `terms`
--
ALTER TABLE `terms`
  ADD CONSTRAINT `terms_ibfk_1` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`academic_year_id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`associated_id`) REFERENCES `staff` (`staff_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
