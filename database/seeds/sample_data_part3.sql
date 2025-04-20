-- Sample data for Kinglang Booking System - Part 3 of 4

-- Insert bookings 51-75
INSERT INTO `bookings` 
(`destination`, `pickup_point`, `date_of_tour`, `end_of_tour`, `number_of_days`, 
`number_of_buses`, `total_cost`, `balance`, `status`, `payment_status`, `user_id`, 
`is_rebooking`, `is_rebooked`, `booked_at`) 
VALUES
-- Booking 51
('Nueva Ecija', 'Manila', '2026-02-16', '2026-02-18', 3, 1, 52000.00, 52000.00, 'Pending', 'Unpaid', 1, 0, 0, '2025-05-15 08:20:00'),
-- Booking 52
('Tarlac', 'Quezon City', '2026-02-20', '2026-02-22', 3, 1, 54000.00, 0.00, 'Confirmed', 'Paid', 2, 0, 0, '2025-05-15 14:45:00'),
-- Booking 53
('Olongapo', 'Makati', '2026-02-24', '2026-02-26', 3, 1, 56000.00, 28000.00, 'Confirmed', 'Partially Paid', 3, 0, 0, '2025-05-16 09:10:00'),
-- Booking 54
('Ilocos Norte', 'Pasig', '2026-02-28', '2026-03-03', 5, 2, 160000.00, 160000.00, 'Processing', 'Unpaid', 4, 0, 0, '2025-05-16 15:35:00'),
-- Booking 55
('Ilocos Sur', 'Taguig', '2026-03-05', '2026-03-09', 5, 1, 95000.00, 0.00, 'Confirmed', 'Paid', 5, 0, 0, '2025-05-17 10:00:00'),
-- Booking 56
('Cagayan', 'Manila', '2026-03-11', '2026-03-15', 5, 1, 98000.00, 49000.00, 'Confirmed', 'Partially Paid', 1, 1, 0, '2025-05-17 14:25:00'),
-- Booking 57
('Isabela', 'Quezon City', '2026-03-17', '2026-03-20', 4, 1, 79000.00, 79000.00, 'Pending', 'Unpaid', 2, 0, 0, '2025-05-18 09:50:00'),
-- Booking 58
('Nueva Vizcaya', 'Makati', '2026-03-22', '2026-03-25', 4, 1, 82000.00, 0.00, 'Confirmed', 'Paid', 3, 0, 0, '2025-05-18 15:15:00'),
-- Booking 59
('Quirino', 'Pasig', '2026-03-27', '2026-03-30', 4, 2, 150000.00, 150000.00, 'Rejected', 'Unpaid', 4, 0, 0, '2025-05-19 10:40:00'),
-- Booking 60
('Apayao', 'Taguig', '2026-04-01', '2026-04-05', 5, 1, 92000.00, 46000.00, 'Confirmed', 'Partially Paid', 5, 0, 0, '2025-05-19 15:05:00'),
-- Booking 61
('Kalinga', 'Manila', '2026-04-07', '2026-04-10', 4, 1, 85000.00, 0.00, 'Confirmed', 'Paid', 1, 0, 0, '2025-05-20 08:30:00'),
-- Booking 62
('Abra', 'Quezon City', '2026-04-12', '2026-04-15', 4, 1, 87000.00, 87000.00, 'Canceled', 'Unpaid', 2, 0, 0, '2025-05-20 14:55:00'),
-- Booking 63
('Mountain Province', 'Makati', '2026-04-17', '2026-04-21', 5, 2, 155000.00, 77500.00, 'Confirmed', 'Partially Paid', 3, 0, 0, '2025-05-21 09:20:00'),
-- Booking 64
('Ifugao', 'Pasig', '2026-04-23', '2026-04-26', 4, 1, 88000.00, 0.00, 'Confirmed', 'Paid', 4, 0, 0, '2025-05-21 15:45:00'),
-- Booking 65
('Benguet', 'Taguig', '2026-04-28', '2026-05-01', 4, 1, 84000.00, 42000.00, 'Confirmed', 'Partially Paid', 5, 0, 0, '2025-05-22 10:10:00'),
-- Booking 66
('La Union', 'Manila', '2026-05-03', '2026-05-06', 4, 2, 145000.00, 145000.00, 'Processing', 'Unpaid', 1, 0, 0, '2025-05-22 14:35:00'),
-- Booking 67
('Pangasinan', 'Quezon City', '2026-05-08', '2026-05-11', 4, 1, 80000.00, 0.00, 'Confirmed', 'Paid', 2, 0, 0, '2025-05-23 09:00:00'),
-- Booking 68
('Zambales', 'Makati', '2026-05-13', '2026-05-16', 4, 1, 78000.00, 39000.00, 'Confirmed', 'Partially Paid', 3, 0, 0, '2025-05-23 15:25:00'),
-- Booking 69
('Bataan', 'Pasig', '2026-05-18', '2026-05-20', 3, 1, 55000.00, 55000.00, 'Pending', 'Unpaid', 4, 0, 0, '2025-05-24 10:50:00'),
-- Booking 70
('Pampanga', 'Taguig', '2026-05-22', '2026-05-24', 3, 1, 52000.00, 0.00, 'Confirmed', 'Paid', 5, 0, 0, '2025-05-24 15:15:00'),
-- Booking 71
('Tarlac', 'Manila', '2026-05-26', '2026-05-28', 3, 1, 54000.00, 27000.00, 'Confirmed', 'Partially Paid', 1, 0, 0, '2025-05-25 08:40:00'),
-- Booking 72
('Bulacan', 'Quezon City', '2026-05-30', '2026-06-01', 3, 2, 105000.00, 105000.00, 'Pending', 'Unpaid', 2, 0, 0, '2025-05-25 14:05:00'),
-- Booking 73
('Nueva Ecija', 'Makati', '2026-06-03', '2026-06-05', 3, 1, 58000.00, 0.00, 'Confirmed', 'Paid', 3, 1, 0, '2025-05-26 09:30:00'),
-- Booking 74
('Aurora', 'Pasig', '2026-06-07', '2026-06-10', 4, 1, 76000.00, 38000.00, 'Confirmed', 'Partially Paid', 4, 0, 0, '2025-05-26 15:55:00'),
-- Booking 75
('Quezon', 'Taguig', '2026-06-12', '2026-06-15', 4, 1, 80000.00, 0.00, 'Confirmed', 'Paid', 5, 0, 0, '2025-05-27 10:20:00');

-- Insert booking_buses for each booking
-- For bookings 51-75
INSERT INTO `booking_buses` (`booking_id`, `bus_id`)
VALUES
(51, 2),
(52, 3),
(53, 4),
(54, 1), (54, 2),
(55, 3),
(56, 4),
(57, 1),
(58, 2),
(59, 3), (59, 4),
(60, 1),
(61, 2),
(62, 3),
(63, 4), (63, 1),
(64, 2),
(65, 3),
(66, 4), (66, 1),
(67, 2),
(68, 3),
(69, 4),
(70, 1),
(71, 2),
(72, 3), (72, 4),
(73, 1),
(74, 2),
(75, 3);

-- Insert booking_stops for some bookings
INSERT INTO `booking_stops` (`stop_order`, `location`, `booking_id`)
VALUES
-- Booking 54 stops
(1, 'NLEX', 54),
(2, 'San Fernando', 54),
(3, 'Vigan', 54),
-- Booking 55 stops
(1, 'NLEX', 55),
(2, 'Tarlac', 55),
(3, 'Candon', 55),
-- Booking 63 stops
(1, 'NLEX', 63),
(2, 'Baguio', 63),
(3, 'Bontoc', 63),
-- Booking 66 stops
(1, 'NLEX', 66),
(2, 'TPLEX', 66),
(3, 'San Fernando', 66),
-- Booking 73 stops
(1, 'NLEX', 73),
(2, 'Cabanatuan', 73);

-- Insert payments for bookings marked as Paid or Partially Paid
INSERT INTO `payments` (`amount`, `payment_method`, `booking_id`, `user_id`, `is_canceled`, `proof_of_payment`, `status`)
VALUES
-- Full payments
(54000.00, 'Bank Transfer', 52, 2, 0, 'payment_proof_52.jpg', 'Confirmed'),
(95000.00, 'Online Payment', 55, 5, 0, 'payment_proof_55.jpg', 'Confirmed'),
(82000.00, 'Bank Transfer', 58, 3, 0, 'payment_proof_58.jpg', 'Confirmed'),
(85000.00, 'Online Payment', 61, 1, 0, 'payment_proof_61.jpg', 'Confirmed'),
(88000.00, 'Bank Transfer', 64, 4, 0, 'payment_proof_64.jpg', 'Confirmed'),
(80000.00, 'Cash', 67, 2, 0, NULL, 'Confirmed'),
(52000.00, 'Bank Transfer', 70, 5, 0, 'payment_proof_70.jpg', 'Confirmed'),
(58000.00, 'Online Payment', 73, 3, 0, 'payment_proof_73.jpg', 'Confirmed'),
(80000.00, 'Bank Transfer', 75, 5, 0, 'payment_proof_75.jpg', 'Confirmed'),
-- Partial payments
(28000.00, 'Bank Transfer', 53, 3, 0, 'payment_proof_53.jpg', 'Confirmed'),
(49000.00, 'Online Payment', 56, 1, 0, 'payment_proof_56.jpg', 'Confirmed'),
(46000.00, 'Cash', 60, 5, 0, NULL, 'Confirmed'),
(77500.00, 'Bank Transfer', 63, 3, 0, 'payment_proof_63.jpg', 'Confirmed'),
(42000.00, 'Online Payment', 65, 5, 0, 'payment_proof_65.jpg', 'Confirmed'),
(39000.00, 'Bank Transfer', 68, 3, 0, 'payment_proof_68.jpg', 'Confirmed'),
(27000.00, 'Online Payment', 71, 1, 0, 'payment_proof_71.jpg', 'Confirmed'),
(38000.00, 'Bank Transfer', 74, 4, 0, 'payment_proof_74.jpg', 'Confirmed');

-- Insert trip_distances for each booking
INSERT INTO `trip_distances` (`origin`, `destination`, `distance`, `booking_id`)
VALUES
('Manila', 'Nueva Ecija', 120.00, 51),
('Quezon City', 'Tarlac', 125.00, 52),
('Makati', 'Olongapo', 130.00, 53),
('Pasig', 'Ilocos Norte', 485.00, 54),
('Taguig', 'Ilocos Sur', 415.00, 55),
('Manila', 'Cagayan', 430.00, 56),
('Quezon City', 'Isabela', 375.00, 57),
('Makati', 'Nueva Vizcaya', 305.00, 58),
('Pasig', 'Quirino', 340.00, 59),
('Taguig', 'Apayao', 455.00, 60),
('Manila', 'Kalinga', 410.00, 61),
('Quezon City', 'Abra', 375.00, 62),
('Makati', 'Mountain Province', 395.00, 63),
('Pasig', 'Ifugao', 380.00, 64),
('Taguig', 'Benguet', 255.00, 65),
('Manila', 'La Union', 270.00, 66),
('Quezon City', 'Pangasinan', 215.00, 67),
('Makati', 'Zambales', 155.00, 68),
('Pasig', 'Bataan', 145.00, 69),
('Taguig', 'Pampanga', 95.00, 70),
('Manila', 'Tarlac', 130.00, 71),
('Quezon City', 'Bulacan', 40.00, 72),
('Makati', 'Nueva Ecija', 125.00, 73),
('Pasig', 'Aurora', 195.00, 74),
('Taguig', 'Quezon', 180.00, 75);

-- Insert rejected_trips for rejected bookings
INSERT INTO `rejected_trips` (`reason`, `type`, `booking_id`, `user_id`)
VALUES
('Insufficient buses available for requested dates', 'Booking', 59, 4);

-- Insert canceled_trips for canceled bookings
INSERT INTO `canceled_trips` (`reason`, `booking_id`, `user_id`, `amount_refunded`, `canceled_by`)
VALUES
('Client changed travel plans', 62, 2, 0.00, 'Client'); 