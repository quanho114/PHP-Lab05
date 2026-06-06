USE web_php_lab05_clinic;

INSERT INTO users (name, email, password_hash, role)
VALUES
('Admin User', 'admin@example.com', '$2y$10$examplehashadmin', 'admin'),
('Clinic Staff', 'staff@example.com', '$2y$10$examplehashstaff', 'staff');

INSERT INTO patients (name, email, phone, gender, note, created_at)
VALUES
('Anna Nguyen', 'anna@example.com', '0909000001', 'female', 'Tiền sử dị ứng penicillin', DATE_SUB(NOW(), INTERVAL 15 DAY)),
('Ben Tran', 'ben@example.com', '0909000002', 'male', 'Khám sức khỏe tổng quát', DATE_SUB(NOW(), INTERVAL 14 DAY)),
('Chris Le', 'chris@example.com', '0909000003', 'male', 'Khám răng định kỳ', DATE_SUB(NOW(), INTERVAL 13 DAY)),
('Duyen Pham', 'duyen@example.com', '0909000004', 'female', 'Theo dõi huyết áp', DATE_SUB(NOW(), INTERVAL 12 DAY)),
('Minh Ho', 'minh@example.com', '0909000005', 'male', 'Đau khớp gối', DATE_SUB(NOW(), INTERVAL 11 DAY)),
('Elsa Smith', 'elsa@example.com', '0909000006', 'female', 'Tư vấn dinh dưỡng', DATE_SUB(NOW(), INTERVAL 10 DAY)),
('Frankie Miller', 'frankie@example.com', '0909000007', 'male', 'Xét nghiệm máu', DATE_SUB(NOW(), INTERVAL 9 DAY)),
('Grace Hopper', 'grace@example.com', '0909000008', 'female', 'Kiểm tra thị lực', DATE_SUB(NOW(), INTERVAL 8 DAY)),
('Henry Cavill', 'henry@example.com', '0909000009', 'male', 'Đau dạ dày', DATE_SUB(NOW(), INTERVAL 7 DAY)),
('Ivy League', 'ivy@example.com', '0909000010', 'female', 'Tái khám tai mũi họng', DATE_SUB(NOW(), INTERVAL 6 DAY)),
('Jack Ryan', 'jack@example.com', '0909000011', 'male', 'Sốt siêu vi', DATE_SUB(NOW(), INTERVAL 5 DAY)),
('Karen Gillan', 'karen@example.com', '0909000012', 'female', 'Rối loạn giấc ngủ', DATE_SUB(NOW(), INTERVAL 4 DAY)),
('Liam Neeson', 'liam@example.com', '0909000013', 'male', 'Đau lưng mãn tính', DATE_SUB(NOW(), INTERVAL 3 DAY)),
('Monica Geller', 'monica@example.com', '0909000014', 'female', 'Khám phụ khoa định kỳ', DATE_SUB(NOW(), INTERVAL 2 DAY)),
('Ned Stark', 'ned@example.com', '0909000015', 'male', 'Chấn thương vai', DATE_SUB(NOW(), INTERVAL 1 DAY)),
('Oliver Queen', 'oliver@example.com', '0909000016', 'male', 'Kiểm tra tim mạch', NOW()),
('Penny Hofstadter', 'penny@example.com', '0909000017', 'female', 'Cảm cúm thông thường', NOW());

INSERT INTO appointments (appointment_code, patient_name, patient_email, appointment_date, status, note, created_at)
VALUES
('APT-2026-0001', 'Anna Nguyen', 'anna@example.com', DATE_ADD(NOW(), INTERVAL 1 DAY), 'pending', 'Tái khám sản khoa', DATE_SUB(NOW(), INTERVAL 10 DAY)),
('APT-2026-0002', 'Ben Tran', 'ben@example.com', DATE_ADD(NOW(), INTERVAL 2 DAY), 'confirmed', 'Nội soi dạ dày', DATE_SUB(NOW(), INTERVAL 9 DAY)),
('APT-2026-0003', 'Chris Le', 'chris@example.com', DATE_SUB(NOW(), INTERVAL 1 DAY), 'completed', 'Nhổ răng khôn', DATE_SUB(NOW(), INTERVAL 8 DAY)),
('APT-2026-0004', 'Frankie Miller', 'frankie@example.com', DATE_SUB(NOW(), INTERVAL 2 DAY), 'completed', 'Xét nghiệm định kỳ', DATE_SUB(NOW(), INTERVAL 7 DAY)),
('APT-2026-0005', 'Grace Hopper', 'grace@example.com', DATE_ADD(NOW(), INTERVAL 3 DAY), 'pending', 'Khám mắt', DATE_SUB(NOW(), INTERVAL 6 DAY)),
('APT-2026-0006', 'Jack Ryan', 'jack@example.com', DATE_SUB(NOW(), INTERVAL 3 DAY), 'completed', 'Kê đơn thuốc sốt', DATE_SUB(NOW(), INTERVAL 5 DAY)),
('APT-2026-0007', 'Karen Gillan', 'karen@example.com', DATE_ADD(NOW(), INTERVAL 4 DAY), 'pending', 'Khám tâm lý', DATE_SUB(NOW(), INTERVAL 4 DAY)),
('APT-2026-0008', 'Liam Neeson', 'liam@example.com', DATE_SUB(NOW(), INTERVAL 4 DAY), 'cancelled', 'Hủy hẹn do bận đột xuất', DATE_SUB(NOW(), INTERVAL 3 DAY)),
('APT-2026-0009', 'Monica Geller', 'monica@example.com', DATE_SUB(NOW(), INTERVAL 5 DAY), 'completed', 'Tư vấn sức khỏe sinh sản', DATE_SUB(NOW(), INTERVAL 2 DAY)),
('APT-2026-0010', 'Ned Stark', 'ned@example.com', DATE_ADD(NOW(), INTERVAL 5 DAY), 'confirmed', 'Chụp X-quang khớp vai', DATE_SUB(NOW(), INTERVAL 1 DAY)),
('APT-2026-0011', 'Oliver Queen', 'oliver@example.com', DATE_ADD(NOW(), INTERVAL 6 DAY), 'pending', 'Đo điện tâm đồ', NOW()),
('APT-2026-0012', 'Penny Hofstadter', 'penny@example.com', DATE_ADD(NOW(), INTERVAL 7 DAY), 'pending', 'Khám tai mũi họng', NOW()),
('APT-2026-0013', 'Elsa Smith', 'elsa@example.com', DATE_ADD(NOW(), INTERVAL 8 DAY), 'pending', 'Tư vấn giảm cân', NOW()),
('APT-2026-0014', 'Duyen Pham', 'duyen@example.com', DATE_ADD(NOW(), INTERVAL 9 DAY), 'confirmed', 'Đo huyết áp định kỳ', NOW()),
('APT-2026-0015', 'Minh Ho', 'minh@example.com', DATE_ADD(NOW(), INTERVAL 10 DAY), 'pending', 'Vật lý trị liệu khớp gối', NOW());
