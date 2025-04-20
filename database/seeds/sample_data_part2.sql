-- Sample data for Kinglang Booking System - Part 2 of 4

-- Insert bookings 26-50
INSERT INTO `bookings` 
(`destination`, `pickup_point`, `date_of_tour`, `end_of_tour`, `number_of_days`, 
`number_of_buses`, `total_cost`, `balance`, `status`, `payment_status`, `user_id`, 
`is_rebooking`, `is_rebooked`, `booked_at`) 
VALUES
-- Booking 26
('Camarines Sur', 'Manila', '2025-10-01', '2025-10-04', 4, 1, 75000.00, 75000.00, 'Pending', 'Unpaid', 1, 0, 0, '2025-05-02 13:45:00'),
-- Booking 27
('Legazpi', 'Quezon City', '2025-10-05', '2025-10-08', 4, 1, 80000.00, 40000.00, 'Confirmed', 'Partially Paid', 2, 0, 0, '2025-05-03 09:30:00'),
-- Booking 28
('Donsol', 'Makati', '2025-10-10', '2025-10-13', 4, 1, 82000.00, 0.00, 'Confirmed', 'Paid', 3, 0, 0, '2025-05-03 15:20:00'),
-- Booking 29
('Catanduanes', 'Pasig', '2025-10-15', '2025-10-19', 5, 2, 145000.00, 145000.00, 'Processing', 'Unpaid', 4, 0, 0, '2025-05-04 10:15:00'),
-- Booking 30
('Marinduque', 'Taguig', '2025-10-22', '2025-10-25', 4, 1, 78000.00, 39000.00, 'Confirmed', 'Partially Paid', 5, 0, 0, '2025-05-04 14:40:00'),
-- Booking 31
('Mindoro', 'Manila', '2025-10-28', '2025-10-31', 4, 1, 76000.00, 0.00, 'Confirmed', 'Paid', 1, 0, 0, '2025-05-05 08:25:00'),
-- Booking 32
('Romblon', 'Quezon City', '2025-11-01', '2025-11-05', 5, 1, 85000.00, 85000.00, 'Rejected', 'Unpaid', 2, 0, 0, '2025-05-05 13:50:00'),
-- Booking 33
('Iloilo', 'Makati', '2025-11-08', '2025-11-12', 5, 2, 150000.00, 75000.00, 'Confirmed', 'Partially Paid', 3, 0, 0, '2025-05-06 09:10:00'),
-- Booking 34
('Bacolod', 'Pasig', '2025-11-15', '2025-11-18', 4, 1, 90000.00, 0.00, 'Confirmed', 'Paid', 4, 0, 0, '2025-05-06 15:35:00'),
-- Booking 35
('Guimaras', 'Taguig', '2025-11-20', '2025-11-23', 4, 1, 92000.00, 92000.00, 'Canceled', 'Unpaid', 5, 0, 0, '2025-05-07 10:00:00'),
-- Booking 36
('Sagada', 'Manila', '2025-11-25', '2025-11-28', 4, 2, 120000.00, 0.00, 'Confirmed', 'Paid', 1, 0, 0, '2025-05-07 14:45:00'),
-- Booking 37
('Vigan', 'Quezon City', '2025-11-30', '2025-12-03', 4, 1, 85000.00, 42500.00, 'Confirmed', 'Partially Paid', 2, 0, 0, '2025-05-08 09:20:00'),
-- Booking 38
('Pagudpud', 'Makati', '2025-12-05', '2025-12-08', 4, 1, 88000.00, 88000.00, 'Pending', 'Unpaid', 3, 0, 0, '2025-05-08 15:10:00'),
-- Booking 39
('Tuguegarao', 'Pasig', '2025-12-10', '2025-12-14', 5, 1, 95000.00, 0.00, 'Confirmed', 'Paid', 4, 0, 0, '2025-05-09 10:30:00'),
-- Booking 40
('Aparri', 'Taguig', '2025-12-16', '2025-12-19', 4, 1, 90000.00, 45000.00, 'Confirmed', 'Partially Paid', 5, 0, 0, '2025-05-09 14:15:00'),
-- Booking 41
('Anilao', 'Manila', '2025-12-22', '2025-12-25', 4, 1, 75000.00, 75000.00, 'Pending', 'Unpaid', 1, 1, 0, '2025-05-10 08:50:00'),
-- Booking 42
('Nasugbu', 'Quezon City', '2025-12-27', '2025-12-30', 4, 2, 145000.00, 0.00, 'Confirmed', 'Paid', 2, 0, 0, '2025-05-10 13:25:00'),
-- Booking 43
('Laiya', 'Makati', '2026-01-02', '2026-01-05', 4, 1, 80000.00, 40000.00, 'Confirmed', 'Partially Paid', 3, 0, 0, '2025-05-11 09:40:00'),
-- Booking 44
('Calaguas', 'Pasig', '2026-01-08', '2026-01-12', 5, 1, 92000.00, 92000.00, 'Processing', 'Unpaid', 4, 0, 0, '2025-05-11 15:05:00'),
-- Booking 45
('Caramoan', 'Taguig', '2026-01-15', '2026-01-19', 5, 2, 150000.00, 150000.00, 'Pending', 'Unpaid', 5, 0, 0, '2025-05-12 10:30:00'),
-- Booking 46
('Jomalig', 'Manila', '2026-01-22', '2026-01-26', 5, 1, 95000.00, 0.00, 'Confirmed', 'Paid', 1, 0, 0, '2025-05-12 14:55:00'),
-- Booking 47
('Quezon', 'Quezon City', '2026-01-28', '2026-01-31', 4, 1, 78000.00, 39000.00, 'Confirmed', 'Partially Paid', 2, 0, 0, '2025-05-13 09:20:00'),
-- Booking 48
('Aurora', 'Makati', '2026-02-03', '2026-02-06', 4, 2, 140000.00, 140000.00, 'Rejected', 'Unpaid', 3, 0, 0, '2025-05-13 15:40:00'),
-- Booking 49
('Bulacan', 'Pasig', '2026-02-08', '2026-02-10', 3, 1, 55000.00, 0.00, 'Confirmed', 'Paid', 4, 0, 0, '2025-05-14 10:05:00'),
-- Booking 50
('Pampanga', 'Taguig', '2026-02-12', '2026-02-14', 3, 1, 58000.00, 29000.00, 'Confirmed', 'Partially Paid', 5, 0, 0, '2025-05-14 14:30:00');

-- Insert booking_buses for each booking
-- For bookings 26-50
INSERT INTO `booking_buses` (`booking_id`, `bus_id`)
VALUES
(26, 3),
(27, 4),
(28, 1),
(29, 2), (29, 3),
(30, 4),
(31, 1),
(32, 2),
(33, 3), (33, 4),
(34, 1),
(35, 2),
(36, 3), (36, 4),
(37, 1),
(38, 2),
(39, 3),
(40, 4),
(41, 1),
(42, 2), (42, 3),
(43, 4),
(44, 1),
(45, 2), (45, 3),
(46, 4),
(47, 1),
(48, 2), (48, 3),
(49, 4),
(50, 1);

-- Insert booking_stops for some bookings
INSERT INTO `booking_stops` (`stop_order`, `location`, `booking_id`)
VALUES
-- Booking 27 stops
(1, 'SLEX', 27),
(2, 'Naga', 27),
-- Booking 31 stops
(1, 'Batangas Port', 31),
(2, 'Calapan', 31),
-- Booking 33 stops
(1, 'NAIA Terminal 2', 33),
(2, 'Iloilo City', 33),
-- Booking 36 stops
(1, 'NLEX', 36),
(2, 'Baguio', 36),
(3, 'Halsema Highway', 36),
-- Booking 39 stops
(1, 'NLEX', 39),
(2, 'Cagayan Valley Road', 39);

-- Insert payments for bookings marked as Paid or Partially Paid
INSERT INTO `payments` (`amount`, `payment_method`, `booking_id`, `user_id`, `is_canceled`, `proof_of_payment`, `status`)
VALUES
-- Full payments
(82000.00, 'Bank Transfer', 28, 3, 0, 'payment_proof_28.jpg', 'Confirmed'),
(76000.00, 'Online Payment', 31, 1, 0, 'payment_proof_31.jpg', 'Confirmed'),
(90000.00, 'Bank Transfer', 34, 4, 0, 'payment_proof_34.jpg', 'Confirmed'),
(120000.00, 'Cash', 36, 1, 0, NULL, 'Confirmed'),
(95000.00, 'Bank Transfer', 39, 4, 0, 'payment_proof_39.jpg', 'Confirmed'),
(145000.00, 'Online Payment', 42, 2, 0, 'payment_proof_42.jpg', 'Confirmed'),
(95000.00, 'Bank Transfer', 46, 1, 0, 'payment_proof_46.jpg', 'Confirmed'),
(55000.00, 'Online Payment', 49, 4, 0, 'payment_proof_49.jpg', 'Confirmed'),
-- Partial payments
(40000.00, 'Bank Transfer', 27, 2, 0, 'payment_proof_27.jpg', 'Confirmed'),
(39000.00, 'Online Payment', 30, 5, 0, 'payment_proof_30.jpg', 'Confirmed'),
(75000.00, 'Cash', 33, 3, 0, NULL, 'Confirmed'),
(42500.00, 'Bank Transfer', 37, 2, 0, 'payment_proof_37.jpg', 'Confirmed'),
(45000.00, 'Online Payment', 40, 5, 0, 'payment_proof_40.jpg', 'Confirmed'),
(40000.00, 'Bank Transfer', 43, 3, 0, 'payment_proof_43.jpg', 'Confirmed'),
(39000.00, 'Online Payment', 47, 2, 0, 'payment_proof_47.jpg', 'Confirmed'),
(29000.00, 'Bank Transfer', 50, 5, 0, 'payment_proof_50.jpg', 'Confirmed');

-- Insert trip_distances for each booking
INSERT INTO `trip_distances` (`origin`, `destination`, `distance`, `booking_id`)
VALUES
('Manila', 'Camarines Sur', 382.00, 26),
('Quezon City', 'Legazpi', 460.00, 27),
('Makati', 'Donsol', 492.00, 28),
('Pasig', 'Catanduanes', 520.00, 29),
('Taguig', 'Marinduque', 235.00, 30),
('Manila', 'Mindoro', 195.00, 31),
('Quezon City', 'Romblon', 342.00, 32),
('Makati', 'Iloilo', 465.00, 33),
('Pasig', 'Bacolod', 510.00, 34),
('Taguig', 'Guimaras', 475.00, 35),
('Manila', 'Sagada', 412.00, 36),
('Quezon City', 'Vigan', 380.00, 37),
('Makati', 'Pagudpud', 558.00, 38),
('Pasig', 'Tuguegarao', 481.00, 39),
('Taguig', 'Aparri', 590.00, 40),
('Manila', 'Anilao', 120.00, 41),
('Quezon City', 'Nasugbu', 115.00, 42),
('Makati', 'Laiya', 152.00, 43),
('Pasig', 'Calaguas', 385.00, 44),
('Taguig', 'Caramoan', 430.00, 45),
('Manila', 'Jomalig', 225.00, 46),
('Quezon City', 'Quezon', 176.00, 47),
('Makati', 'Aurora', 268.00, 48),
('Pasig', 'Bulacan', 45.00, 49),
('Taguig', 'Pampanga', 85.00, 50);

-- Insert rejected_trips for rejected bookings
INSERT INTO `rejected_trips` (`reason`, `type`, `booking_id`, `user_id`)
VALUES
('Date conflict with another fully booked tour', 'Booking', 32, 2),
('Requested destination not serviceable during specified dates', 'Booking', 48, 3);

-- Insert canceled_trips for canceled bookings
INSERT INTO `canceled_trips` (`reason`, `booking_id`, `user_id`, `amount_refunded`, `canceled_by`)
VALUES
('Weather conditions expected to be unfavorable', 35, 5, 0.00, 'Client'); 