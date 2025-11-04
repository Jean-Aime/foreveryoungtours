-- Setup Login Users for All Dashboards
-- Run this SQL in phpMyAdmin or MySQL command line

-- First, check if users exist and delete test users if they exist
DELETE FROM users WHERE email IN (
    'admin@foreveryoung.com',
    'mca@foreveryoung.com', 
    'advisor@foreveryoung.com',
    'client@foreveryoung.com'
);

-- Insert Admin User
INSERT INTO users (
    email, 
    password, 
    role, 
    first_name, 
    last_name, 
    phone, 
    status, 
    email_verified,
    created_at
) VALUES (
    'admin@foreveryoung.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password: admin123
    'admin',
    'Super',
    'Admin',
    '+1234567890',
    'active',
    1,
    NOW()
);

-- Insert MCA User
INSERT INTO users (
    email, 
    password, 
    role, 
    first_name, 
    last_name, 
    phone, 
    status, 
    email_verified,
    created_at
) VALUES (
    'mca@foreveryoung.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password: mca123
    'mca',
    'MCA',
    'User',
    '+1234567891',
    'active',
    1,
    NOW()
);

-- Insert Advisor User
INSERT INTO users (
    email, 
    password, 
    role, 
    first_name, 
    last_name, 
    phone, 
    status, 
    email_verified,
    created_at
) VALUES (
    'advisor@foreveryoung.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password: advisor123
    'advisor',
    'Advisor',
    'User',
    '+1234567892',
    'active',
    1,
    NOW()
);

-- Insert Client User
INSERT INTO users (
    email, 
    password, 
    role, 
    first_name, 
    last_name, 
    phone, 
    status, 
    email_verified,
    created_at
) VALUES (
    'client@foreveryoung.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password: client123
    'user',
    'Client',
    'User',
    '+1234567893',
    'active',
    1,
    NOW()
);

-- Verify users were created
SELECT id, email, role, first_name, last_name, status FROM users 
WHERE email IN (
    'admin@foreveryoung.com',
    'mca@foreveryoung.com',
    'advisor@foreveryoung.com',
    'client@foreveryoung.com'
);
