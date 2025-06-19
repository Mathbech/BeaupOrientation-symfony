
-- ========================
-- Jeu de données de test
-- ========================

-- Écoles
INSERT INTO schools (id, name, city, zip_code, adress, country, created_at, updated_at, deleting) VALUES
(1, 'Lycée Test', 'Paris', '75001', '1 rue de Paris', 'France', '2025-06-11 07:26:34', '2025-06-11 07:26:34', NULL);

-- Utilisateurs (profs)
INSERT INTO user (id, schools_id, email, roles, password, username, created_at, updated_at, active) VALUES
(1, 1, 'admin@beauporientation.com', '["ROLE_ADMIN"]', 'test123#', 'Admin', '2025-06-11 07:26:34', '2025-06-11 07:26:34', 1),
(2, 1, 'prof1@ecole.com', '["ROLE_TEACHER"]', 'Test123#', 'prof1', '2025-06-11 07:26:34', '2025-06-11 07:26:34', 1);

-- Courses
INSERT INTO courses (id, user_id, name) VALUES
(1, 1, 'Course A'),
(2, 2, 'Course B');

-- Markers
INSERT INTO markers (id, teacher_id, latitude, longitude, city, address, zip_code, country, qr_code, deleting, point, courses_id, name, type) VALUES
(1, 2, '48.8566', '2.3522', 'Paris', 'Place de la République', '75010', 'France', 'marker1.png', NULL, ST_PointFromText('POINT(2.3522 48.8566)', 4326), 1, 'BALISE-1', 1),
(2, 2, '43.6045', '1.4442', 'Toulouse', 'Capitole', '31000', 'France', 'marker2.png', NULL, ST_PointFromText('POINT(1.4442 43.6045)', 4326), 1, 'BALISE-2', 2);

-- Runners
INSERT INTO runners (id, course_id, name, code, is_teacher, teacher_id_id) VALUES
(1, 1, 'Prof1', 'Course1-1', 1, 2),
(2, 1, 'Runner 1', 'Course1-2', 0, NULL),
(3, 2, 'Prof2', 'Course2-1', 1, 2),
(4, 2, 'Runner 2', 'Course2-2', 0, NULL);
