INSERT INTO roles (name) VALUES
  ('Admin'),
  ('Amir'),
  ('Bakim'),
  ('Depo'),
  ('Operator');

INSERT INTO permissions (code, description) VALUES
  ('auth.login', 'Oturum açma'),
  ('dashboard.view', 'Dashboard görüntüleme'),
  ('machine.view', 'Makine listeleme'),
  ('machine.create', 'Makine oluşturma'),
  ('machine.edit', 'Makine düzenleme'),
  ('machine.delete', 'Makine silme'),
  ('audit.view', 'Audit kayıtlarını görüntüleme');

INSERT INTO role_permissions (role_id, permission_id)
SELECT r.id, p.id
FROM roles r
JOIN permissions p
WHERE r.name = 'Admin';

INSERT INTO role_permissions (role_id, permission_id)
SELECT r.id, p.id
FROM roles r
JOIN permissions p
WHERE p.code = 'auth.login';

INSERT INTO users (name, email, password_hash, role_id, is_active, created_at, updated_at)
VALUES (
  'Admin',
  'admin@local',
  '$2y$12$xo4elFY99/.YVcuNDDv9tejtPWMLCjRCGzyg2KJ9q5O9UiRKjo4f.',
  (SELECT id FROM roles WHERE name = 'Admin' LIMIT 1),
  1,
  NOW(),
  NOW()
);
