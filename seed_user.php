<?php
include 'db.php';

/*
|--------------------------------------------------------------------------
| PASSWORD
|--------------------------------------------------------------------------
*/

$admin_pass = password_hash("admin123", PASSWORD_DEFAULT);
$emp_pass   = password_hash("employee123", PASSWORD_DEFAULT);
$cust_pass  = password_hash("customer123", PASSWORD_DEFAULT);

/*
|--------------------------------------------------------------------------
| INSERT ADMIN
|--------------------------------------------------------------------------
*/

mysqli_query($conn, "
INSERT INTO users
(first_name, last_name, contact_number, email, password, role)
VALUES
('System', 'Admin', '09111111111', 'admin@salon.com', '$admin_pass', 'admin')
");

/*
|--------------------------------------------------------------------------
| INSERT EMPLOYEE
|--------------------------------------------------------------------------
*/

mysqli_query($conn, "
INSERT INTO users
(first_name, last_name, contact_number, email, password, role)
VALUES
('Maria', 'Santos', '09222222222', 'employee@salon.com', '$emp_pass', 'employee')
");

$employee_user_id = mysqli_insert_id($conn);

/*
|--------------------------------------------------------------------------
| LINK EMPLOYEE PROFILE
|--------------------------------------------------------------------------
*/

mysqli_query($conn, "
INSERT INTO employees
(user_id, specialization)
VALUES
('$employee_user_id', 'Hair Styling')
");

/*
|--------------------------------------------------------------------------
| INSERT CUSTOMER
|--------------------------------------------------------------------------
*/

mysqli_query($conn, "
INSERT INTO users
(first_name, last_name, contact_number, email, password, role)
VALUES
('Juan', 'Dela Cruz', '09333333333', 'customer@salon.com', '$cust_pass', 'customer')
");

echo "Seed data created successfully!";
?>