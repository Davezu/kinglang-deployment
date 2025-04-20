-- Sample data for Kinglang Booking System - Part 4 of 4

-- Insert bookings 76-100
INSERT INTO `bookings` 
(`destination`, `pickup_point`, `date_of_tour`, `end_of_tour`, `number_of_days`, 
`number_of_buses`, `total_cost`, `balance`, `status`, `payment_status`, `user_id`, 
`is_rebooking`, `is_rebooked`, `booked_at`) 
VALUES
-- Booking 76
('Rizal', 'Manila', '2026-06-18', '2026-06-20', 3, 1, 45000.00, 45000.00, 'Pending', 'Unpaid', 1, 0, 0, '2025-05-27 14:45:00'),
-- Booking 77
('Laguna', 'Quezon City', '2026-06-22', '2026-06-24', 3, 1, 48000.00, 24000.00, 'Confirmed', 'Partially Paid', 2, 0, 0, '2025-05-28 09:10:00'),
-- Booking 78
('Cavite', 'Makati', '2026-06-26', '2026-06-27', 2, 1, 35000.00, 0.00, 'Confirmed', 'Paid', 3, 0, 0, '2025-05-28 15:35:00'),
-- Booking 79
('Batangas', 'Pasig', '2026-06-29', '2026-07-01', 3, 2, 95000.00, 95000.00, 'Processing', 'Unpaid', 4, 0, 0, '2025-05-29 10:00:00'),
-- Booking 80
('Marinduque', 'Taguig', '2026-07-03', '2026-07-06', 4, 1, 85000.00, 0.00, 'Confirmed', 'Paid', 5, 0, 0, '2025-05-29 14:25:00'),
-- Booking 81
('Romblon', 'Manila', '2026-07-08', '2026-07-12', 5, 1, 95000.00, 47500.00, 'Confirmed', 'Partially Paid', 1, 0, 0, '2025-05-30 08:50:00'),
-- Booking 82
('Palawan', 'Quezon City', '2026-07-15', '2026-07-20', 6, 2, 180000.00, 180000.00, 'Pending', 'Unpaid', 2, 0, 0, '2025-05-30 15:15:00'),
-- Booking 83
('Mindoro', 'Makati', '2026-07-22', '2026-07-25', 4, 1, 78000.00, 0.00, 'Confirmed', 'Paid', 3, 0, 0, '2025-05-31 09:40:00'),
-- Booking 84
('Albay', 'Pasig', '2026-07-27', '2026-07-30', 4, 1, 82000.00, 41000.00, 'Confirmed', 'Partially Paid', 4, 0, 0, '2025-05-31 15:05:00'),
-- Booking 85
('Camarines Norte', 'Taguig', '2026-08-01', '2026-08-04', 4, 1, 84000.00, 84000.00, 'Rejected', 'Unpaid', 5, 0, 0, '2025-06-01 10:30:00'),
-- Booking 86
('Camarines Sur', 'Manila', '2026-08-06', '2026-08-09', 4, 2, 145000.00, 0.00, 'Confirmed', 'Paid', 1, 0, 0, '2025-06-01 14:55:00'),
-- Booking 87
('Sorsogon', 'Quezon City', '2026-08-11', '2026-08-15', 5, 1, 92000.00, 46000.00, 'Confirmed', 'Partially Paid', 2, 0, 0, '2025-06-02 09:20:00'),
-- Booking 88
('Masbate', 'Makati', '2026-08-17', '2026-08-21', 5, 1, 94000.00, 94000.00, 'Canceled', 'Unpaid', 3, 0, 0, '2025-06-02 15:45:00'),
-- Booking 89
('Catanduanes', 'Pasig', '2026-08-23', '2026-08-27', 5, 1, 96000.00, 0.00, 'Confirmed', 'Paid', 4, 0, 0, '2025-06-03 10:10:00'),
-- Booking 90
('Samar', 'Taguig', '2026-08-29', '2026-09-02', 5, 2, 175000.00, 87500.00, 'Confirmed', 'Partially Paid', 5, 0, 0, '2025-06-03 14:35:00'),
-- Booking 91
('Leyte', 'Manila', '2026-09-04', '2026-09-08', 5, 1, 98000.00, 98000.00, 'Processing', 'Unpaid', 1, 1, 0, '2025-06-04 09:00:00'),
-- Booking 92
('Cebu', 'Quezon City', '2026-09-10', '2026-09-14', 5, 2, 180000.00, 0.00, 'Confirmed', 'Paid', 2, 0, 0, '2025-06-04 15:25:00'),
-- Booking 93
('Bohol', 'Makati', '2026-09-16', '2026-09-20', 5, 1, 95000.00, 47500.00, 'Confirmed', 'Partially Paid', 3, 0, 0, '2025-06-05 09:50:00'),
-- Booking 94
('Negros Oriental', 'Pasig', '2026-09-22', '2026-09-27', 6, 1, 105000.00, 105000.00, 'Pending', 'Unpaid', 4, 0, 0, '2025-06-05 15:15:00'),
-- Booking 95
('Negros Occidental', 'Taguig', '2026-09-29', '2026-10-03', 5, 1, 98000.00, 0.00, 'Confirmed', 'Paid', 5, 0, 0, '2025-06-06 10:40:00'),
-- Booking 96
('Panay', 'Manila', '2026-10-05', '2026-10-09', 5, 1, 96000.00, 48000.00, 'Confirmed', 'Partially Paid', 1, 0, 0, '2025-06-06 14:05:00'),
-- Booking 97
('Siquijor', 'Quezon City', '2026-10-11', '2026-10-16', 6, 2, 185000.00, 185000.00, 'Rejected', 'Unpaid', 2, 0, 0, '2025-06-07 09:30:00'),
-- Booking 98
('Mindanao', 'Makati', '2026-10-18', '2026-10-23', 6, 1, 110000.00, 0.00, 'Confirmed', 'Paid', 3, 0, 0, '2025-06-07 15:55:00'),
-- Booking 99
('Davao', 'Pasig', '2026-10-25', '2026-10-30', 6, 1, 115000.00, 57500.00, 'Confirmed', 'Partially Paid', 4, 0, 0, '2025-06-08 10:20:00'),
-- Booking 100
('Cagayan de Oro', 'Taguig', '2026-11-01', '2026-11-06', 6, 2, 190000.00, 0.00, 'Confirmed', 'Paid', 5, 0, 0, '2025-06-08 14:45:00');

-- Insert booking_buses for each booking
-- For bookings 76-100
INSERT INTO `booking_buses` (`booking_id`, `bus_id`)
VALUES
(76, 4),
(77, 1),
(78, 2),
(79, 3), (79, 4),
(80, 1),
(81, 2),
(82, 3), (82, 4),
(83, 1),
(84, 2),
(85, 3),
(86, 4), (86, 1),
(87, 2),
(88, 3),
(89, 4),
(90, 1), (90, 2),
(91, 3),
(92, 4), (92, 1),
(93, 2),
(94, 3),
(95, 4),
(96, 1),
(97, 2), (97, 3),
(98, 4),
(99, 1),
(100, 2), (100, 3);

-- Insert booking_stops for some bookings
INSERT INTO `booking_stops` (`stop_order`, `location`, `booking_id`)
VALUES
-- Booking 79 stops
(1, 'SLEX', 79),
(2, 'Lipa', 79),
-- Booking 82 stops
(1, 'NAIA Terminal 3', 82),
(2, 'Puerto Princesa', 82),
-- Booking 86 stops
(1, 'SLEX', 86),
(2, 'Legazpi', 86),
-- Booking 90 stops
(1, 'NAIA Terminal 2', 90),
(2, 'Tacloban', 90),
-- Booking 92 stops
(1, 'NAIA Terminal 3', 92),
(2, 'Cebu City', 92),
-- Booking 100 stops
(1, 'NAIA Terminal 1', 100),
(2, 'Cagayan de Oro City', 100);

-- Insert payments for bookings marked as Paid or Partially Paid
INSERT INTO `payments` (`amount`, `payment_method`, `booking_id`, `user_id`, `is_canceled`, `proof_of_payment`, `status`)
VALUES
-- Full payments
(35000.00, 'Bank Transfer', 78, 3, 0, 'payment_proof_78.jpg', 'Confirmed'),
(85000.00, 'Online Payment', 80, 5, 0, 'payment_proof_80.jpg', 'Confirmed'),
(78000.00, 'Bank Transfer', 83, 3, 0, 'payment_proof_83.jpg', 'Confirmed'),
(145000.00, 'Cash', 86, 1, 0, NULL, 'Confirmed'),
(96000.00, 'Bank Transfer', 89, 4, 0, 'payment_proof_89.jpg', 'Confirmed'),
(180000.00, 'Online Payment', 92, 2, 0, 'payment_proof_92.jpg', 'Confirmed'),
(98000.00, 'Bank Transfer', 95, 5, 0, 'payment_proof_95.jpg', 'Confirmed'),
(110000.00, 'Online Payment', 98, 3, 0, 'payment_proof_98.jpg', 'Confirmed'),
(190000.00, 'Bank Transfer', 100, 5, 0, 'payment_proof_100.jpg', 'Confirmed'),
-- Partial payments
(24000.00, 'Bank Transfer', 77, 2, 0, 'payment_proof_77.jpg', 'Confirmed'),
(47500.00, 'Online Payment', 81, 1, 0, 'payment_proof_81.jpg', 'Confirmed'),
(41000.00, 'Cash', 84, 4, 0, NULL, 'Confirmed'),
(46000.00, 'Bank Transfer', 87, 2, 0, 'payment_proof_87.jpg', 'Confirmed'),
(87500.00, 'Online Payment', 90, 5, 0, 'payment_proof_90.jpg', 'Confirmed'),
(47500.00, 'Bank Transfer', 93, 3, 0, 'payment_proof_93.jpg', 'Confirmed'),
(48000.00, 'Online Payment', 96, 1, 0, 'payment_proof_96.jpg', 'Confirmed'),
(57500.00, 'Bank Transfer', 99, 4, 0, 'payment_proof_99.jpg', 'Confirmed');

-- Insert trip_distances for each booking
INSERT INTO `trip_distances` (`origin`, `destination`, `distance`, `booking_id`)
VALUES
('Manila', 'Rizal', 25.00, 76),
('Quezon City', 'Laguna', 60.00, 77),
('Makati', 'Cavite', 35.00, 78),
('Pasig', 'Batangas', 110.00, 79),
('Taguig', 'Marinduque', 235.00, 80),
('Manila', 'Romblon', 340.00, 81),
('Quezon City', 'Palawan', 595.00, 82),
('Makati', 'Mindoro', 195.00, 83),
('Pasig', 'Albay', 450.00, 84),
('Taguig', 'Camarines Norte', 420.00, 85),
('Manila', 'Camarines Sur', 385.00, 86),
('Quezon City', 'Sorsogon', 520.00, 87),
('Makati', 'Masbate', 510.00, 88),
('Pasig', 'Catanduanes', 525.00, 89),
('Taguig', 'Samar', 780.00, 90),
('Manila', 'Leyte', 810.00, 91),
('Quezon City', 'Cebu', 850.00, 92),
('Makati', 'Bohol', 785.00, 93),
('Pasig', 'Negros Oriental', 890.00, 94),
('Taguig', 'Negros Occidental', 865.00, 95),
('Manila', 'Panay', 750.00, 96),
('Quezon City', 'Siquijor', 920.00, 97),
('Makati', 'Mindanao', 1450.00, 98),
('Pasig', 'Davao', 1520.00, 99),
('Taguig', 'Cagayan de Oro', 1380.00, 100);

-- Insert rejected_trips for rejected bookings
INSERT INTO `rejected_trips` (`reason`, `type`, `booking_id`, `user_id`)
VALUES
('Route not serviceable during requested dates due to road construction', 'Booking', 85, 5),
('Destination too far for land travel, air travel recommended', 'Booking', 97, 2);

-- Insert canceled_trips for canceled bookings
INSERT INTO `canceled_trips` (`reason`, `booking_id`, `user_id`, `amount_refunded`, `canceled_by`)
VALUES
('Family emergency', 88, 3, 0.00, 'Client');

-- Insert rebooking_request for rebooking records
INSERT INTO `rebooking_request` (`booking_id`, `rebooking_id`, `status`, `user_id`)
VALUES
(10, 41, 'Confirmed', 1),
(35, 56, 'Confirmed', 1),
(62, 73, 'Confirmed', 3),
(88, 91, 'Pending', 1); 