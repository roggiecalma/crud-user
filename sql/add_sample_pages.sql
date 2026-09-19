-- Registers the 10 sample pages and grants them to the Administrator role.
-- Safe to run more than once. Run this against an existing database (schema.sql already includes these rows).

INSERT INTO pages (page_key, label, sort_order) VALUES
    ('reports', 'Reports', 5),
    ('announcements', 'Announcements', 6),
    ('tasks', 'Tasks', 7),
    ('attendance', 'Attendance', 8),
    ('leave_requests', 'Leave Requests', 9),
    ('inventory', 'Inventory', 10),
    ('customers', 'Customers', 11),
    ('invoices', 'Invoices', 12),
    ('audit_log', 'Audit Log', 13),
    ('settings', 'Settings', 14)
ON DUPLICATE KEY UPDATE label = VALUES(label), sort_order = VALUES(sort_order);

INSERT IGNORE INTO role_pages (role_id, page_id)
    SELECT r.id, p.id FROM roles r CROSS JOIN pages p WHERE r.name = 'Administrator';
