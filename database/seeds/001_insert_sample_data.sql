-- Insert sample users
INSERT INTO users (name, email, password, role, status) VALUES
('Admin User', 'admin@hando.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'active'),
('John Doe', 'john@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', 'active'),
('Jane Smith', 'jane@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', 'active'),
('Bob Johnson', 'bob@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'moderator', 'active');

-- Insert sample customers
INSERT INTO customers (name, email, phone, company, status, source) VALUES
('Alice Brown', 'alice@company.com', '+1-555-0101', 'Tech Corp', 'qualified', 'website'),
('Charlie Wilson', 'charlie@startup.io', '+1-555-0102', 'Startup Inc', 'contacted', 'referral'),
('Diana Prince', 'diana@enterprise.com', '+1-555-0103', 'Enterprise Ltd', 'proposal', 'cold_call'),
('Eve Adams', 'eve@smallbiz.com', '+1-555-0104', 'Small Biz', 'negotiation', 'social_media');

-- Insert sample transactions
INSERT INTO transactions (customer_id, amount, status, payment_method, description) VALUES
(1, 1500.00, 'completed', 'credit_card', 'Software License'),
(2, 2500.00, 'completed', 'bank_transfer', 'Consulting Services'),
(3, 5000.00, 'pending', 'invoice', 'Enterprise Package'),
(4, 750.00, 'completed', 'paypal', 'Basic Plan');

-- Insert sample leads
INSERT INTO leads (name, email, phone, company, status, source, score) VALUES
('Frank Miller', 'frank@newcorp.com', '+1-555-0201', 'New Corp', 'new', 'website', 75),
('Grace Lee', 'grace@techstart.com', '+1-555-0202', 'TechStart', 'contacted', 'referral', 85),
('Henry Davis', 'henry@bigbiz.com', '+1-555-0203', 'BigBiz', 'qualified', 'cold_call', 90),
('Ivy Chen', 'ivy@innovate.com', '+1-555-0204', 'Innovate Co', 'converted', 'social_media', 95);

-- Insert sample tasks
INSERT INTO tasks (title, description, status, priority, assigned_to, due_date, created_by) VALUES
('Follow up with Alice Brown', 'Call to discuss pricing for enterprise package', 'pending', 'high', 2, DATE_ADD(CURDATE(), INTERVAL 2 DAY), 1),
('Prepare proposal for Charlie Wilson', 'Create detailed proposal for startup consulting', 'in_progress', 'medium', 3, DATE_ADD(CURDATE(), INTERVAL 5 DAY), 1),
('Review contract with Diana Prince', 'Review and finalize contract terms', 'pending', 'high', 2, DATE_ADD(CURDATE(), INTERVAL 1 DAY), 1),
('Update customer database', 'Clean up and update customer information', 'completed', 'low', 4, CURDATE(), 1);
