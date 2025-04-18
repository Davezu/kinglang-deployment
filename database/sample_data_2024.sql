-- Sample data for Kinglang Booking System - 2024 Bookings

-- Insert additional users if they don't exist already
INSERT IGNORE INTO `users` (`user_id`, `first_name`, `last_name`, `email`, `contact_number`, `password`, `role`)
VALUES
(16, 'Robert', 'Garcia', 'robert.g@example.com', '09678901234', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Client'),
(17, 'Emily', 'Cruz', 'emily.c@example.com', '09789012345', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Client'),
(18, 'Anthony', 'Santos', 'anthony.s@example.com', '09890123456', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Client'),
(19, 'Michelle', 'Reyes', 'michelle.r@example.com', '09901234567', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Client'),
(20, 'Kevin', 'Tan', 'kevin.t@example.com', '09012345678', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Client'),
(21, 'Grace', 'Lim', 'grace.l@example.com', '09123456780', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Client');

-- Insert bookings for 2024
INSERT INTO `bookings` 
(`booking_id`, `destination`, `pickup_point`, `date_of_tour`, `end_of_tour`, `number_of_days`, 
`number_of_buses`, `total_cost`, `balance`, `status`, `payment_status`, `user_id`, 
`is_rebooking`, `is_rebooked`, `booked_at`) 
VALUES
-- January 2024
(236, 'Tagaytay', 'Makati', '2024-01-15', '2024-01-16', 2, 1, 22000.00, 0.00, 'Completed', 'Paid', 17, 0, 0, '2024-01-02 09:30:00'),
(237, 'Batangas', 'Quezon City', '2024-01-22', '2024-01-23', 2, 1, 26000.00, 0.00, 'Completed', 'Paid', 16, 0, 0, '2024-01-05 14:45:00'),
(238, 'Zambales', 'Pasig', '2024-01-28', '2024-01-30', 3, 2, 68000.00, 0.00, 'Completed', 'Paid', 17, 0, 0, '2024-01-10 11:20:00'),

-- February 2024
(239, 'Baguio', 'Manila', '2024-02-10', '2024-02-13', 4, 2, 85000.00, 0.00, 'Completed', 'Paid', 18, 0, 0, '2024-01-15 10:15:00'),
(240, 'La Union', 'Taguig', '2024-02-18', '2024-02-20', 3, 1, 42000.00, 0.00, 'Completed', 'Paid', 19, 0, 0, '2024-01-25 16:30:00'),
(241, 'Subic', 'Makati', '2024-02-24', '2024-02-25', 2, 1, 25000.00, 0.00, 'Completed', 'Paid', 20, 0, 0, '2024-01-30 08:45:00'),

-- March 2024
(242, 'Bataan', 'Quezon City', '2024-03-05', '2024-03-07', 3, 1, 38000.00, 0.00, 'Completed', 'Paid', 21, 0, 0, '2024-02-10 13:20:00'),
(243, 'Pangasinan', 'Pasig', '2024-03-15', '2024-03-17', 3, 2, 75000.00, 0.00, 'Completed', 'Paid', 17, 0, 0, '2024-02-20 09:40:00'),
(244, 'Ilocos', 'Manila', '2024-03-22', '2024-03-26', 5, 2, 110000.00, 0.00, 'Completed', 'Paid', 16, 0, 0, '2024-02-25 15:10:00'),

-- April 2024
(245, 'Rizal', 'Taguig', '2024-04-06', '2024-04-07', 2, 1, 24000.00, 0.00, 'Completed', 'Paid', 17, 0, 0, '2024-03-15 11:30:00'),
(246, 'Laguna', 'Makati', '2024-04-13', '2024-04-15', 3, 1, 35000.00, 0.00, 'Completed', 'Paid', 18, 0, 0, '2024-03-20 14:25:00'),
(247, 'Cavite', 'Quezon City', '2024-04-20', '2024-04-21', 2, 1, 22000.00, 0.00, 'Completed', 'Paid', 19, 0, 0, '2024-03-25 08:55:00'),

-- May 2024
(248, 'Batangas', 'Pasig', '2024-05-04', '2024-05-06', 3, 1, 36000.00, 0.00, 'Completed', 'Paid', 20, 0, 0, '2024-04-10 16:15:00'),
(249, 'Tagaytay', 'Manila', '2024-05-11', '2024-05-12', 2, 2, 48000.00, 0.00, 'Completed', 'Paid', 21, 0, 0, '2024-04-15 10:40:00'),
(250, 'Baler', 'Taguig', '2024-05-18', '2024-05-20', 3, 1, 42000.00, 0.00, 'Completed', 'Paid', 17, 0, 0, '2024-04-20 13:35:00'),

-- June 2024
(251, 'Zambales', 'Makati', '2024-06-08', '2024-06-10', 3, 1, 38000.00, 0.00, 'Completed', 'Paid', 16, 0, 0, '2024-05-15 09:50:00'),
(252, 'Bataan', 'Quezon City', '2024-06-15', '2024-06-17', 3, 2, 76000.00, 0.00, 'Completed', 'Paid', 17, 0, 0, '2024-05-20 15:25:00'),
(253, 'La Union', 'Pasig', '2024-06-22', '2024-06-25', 4, 1, 65000.00, 0.00, 'Completed', 'Paid', 18, 0, 0, '2024-05-28 11:45:00'),

-- July 2024
(254, 'Baguio', 'Manila', '2024-07-06', '2024-07-09', 4, 2, 90000.00, 0.00, 'Completed', 'Paid', 19, 0, 0, '2024-06-10 14:30:00'),
(255, 'Ilocos', 'Taguig', '2024-07-13', '2024-07-17', 5, 2, 115000.00, 0.00, 'Completed', 'Paid', 20, 0, 0, '2024-06-18 08:20:00'),
(256, 'Pangasinan', 'Makati', '2024-07-20', '2024-07-22', 3, 1, 45000.00, 0.00, 'Completed', 'Paid', 21, 0, 0, '2024-06-25 16:40:00'),

-- August 2024
(257, 'Subic', 'Quezon City', '2024-08-03', '2024-08-05', 3, 1, 35000.00, 0.00, 'Completed', 'Paid', 17, 0, 0, '2024-07-15 10:55:00'),
(258, 'Rizal', 'Pasig', '2024-08-10', '2024-08-11', 2, 1, 24000.00, 0.00, 'Completed', 'Paid', 16, 0, 0, '2024-07-20 13:50:00'),
(259, 'Laguna', 'Manila', '2024-08-17', '2024-08-19', 3, 2, 70000.00, 0.00, 'Completed', 'Paid', 17, 0, 0, '2024-07-25 09:15:00'),

-- September 2024
(260, 'Batangas', 'Taguig', '2024-09-07', '2024-09-09', 3, 1, 38000.00, 0.00, 'Completed', 'Paid', 18, 0, 0, '2024-08-15 15:35:00'),
(261, 'Tagaytay', 'Makati', '2024-09-14', '2024-09-15', 2, 2, 50000.00, 0.00, 'Completed', 'Paid', 19, 0, 0, '2024-08-20 10:05:00'),
(262, 'Zambales', 'Quezon City', '2024-09-21', '2024-09-23', 3, 1, 40000.00, 0.00, 'Completed', 'Paid', 20, 0, 0, '2024-08-28 14:25:00'),

-- October 2024
(263, 'Baler', 'Pasig', '2024-10-05', '2024-10-07', 3, 1, 44000.00, 0.00, 'Completed', 'Paid', 21, 0, 0, '2024-09-10 09:40:00'),
(264, 'Bataan', 'Manila', '2024-10-12', '2024-10-14', 3, 2, 78000.00, 0.00, 'Completed', 'Paid', 17, 0, 0, '2024-09-15 16:15:00'),
(265, 'La Union', 'Taguig', '2024-10-19', '2024-10-22', 4, 1, 68000.00, 0.00, 'Completed', 'Paid', 16, 0, 0, '2024-09-20 11:30:00'),

-- November-December 2024
(266, 'Baguio', 'Makati', '2024-11-09', '2024-11-12', 4, 2, 95000.00, 0.00, 'Completed', 'Paid', 17, 0, 0, '2024-10-15 13:25:00'),
(267, 'Ilocos', 'Quezon City', '2024-11-16', '2024-11-20', 5, 2, 120000.00, 0.00, 'Completed', 'Paid', 18, 0, 0, '2024-10-20 08:50:00'),
(268, 'Pangasinan', 'Pasig', '2024-11-23', '2024-11-25', 3, 1, 46000.00, 0.00, 'Completed', 'Paid', 19, 0, 0, '2024-10-28 15:45:00'),
(269, 'Tagaytay', 'Manila', '2024-12-07', '2024-12-08', 2, 2, 52000.00, 0.00, 'Completed', 'Paid', 20, 0, 0, '2024-11-15 10:20:00'),
(270, 'Batangas', 'Taguig', '2024-12-14', '2024-12-16', 3, 1, 40000.00, 0.00, 'Completed', 'Paid', 21, 0, 0, '2024-11-20 14:30:00'),
(271, 'Subic', 'Makati', '2024-12-21', '2024-12-23', 3, 2, 75000.00, 0.00, 'Completed', 'Paid', 17, 0, 0, '2024-11-25 09:55:00');

-- Insert booking_buses for each booking
INSERT INTO `booking_buses` (`booking_id`, `bus_id`)
VALUES
-- January 2024
(236, 1),
(237, 2),
(238, 3), (238, 4),
-- February 2024
(239, 1), (239, 2),
(240, 3),
(241, 4),
-- March 2024
(242, 1),
(243, 2), (243, 3),
(244, 4), (244, 1),
-- April 2024
(245, 2),
(246, 3),
(247, 4),
-- May 2024
(248, 1),
(249, 2), (249, 3),
(250, 4),
-- June 2024
(251, 1),
(252, 2), (252, 3),
(253, 4),
-- July 2024
(254, 1), (254, 2),
(255, 3), (255, 4),
(256, 1),
-- August 2024
(257, 2),
(258, 3),
(259, 4), (259, 1),
-- September 2024
(260, 2),
(261, 3), (261, 4),
(262, 1),
-- October 2024
(263, 2),
(264, 3), (264, 4),
(265, 1),
-- November-December 2024
(266, 2), (266, 3),
(267, 4), (267, 1),
(268, 2),
(269, 3), (269, 4),
(270, 1),
(271, 2), (271, 3);

-- Insert booking_stops for some bookings
INSERT INTO `booking_stops` (`stop_order`, `location`, `booking_id`)
VALUES
-- Selected stops for 2024 bookings
(1, 'Alabang', 236),
(2, 'Silang', 236),

(1, 'SLEX', 237),
(2, 'Lipa', 237),

(1, 'NLEX', 239),
(2, 'Tarlac', 239),
(3, 'Kennon Road', 239),

(1, 'NLEX', 244),
(2, 'Tarlac', 244),
(3, 'Vigan', 244),

(1, 'SLEX', 249),
(2, 'Tagaytay Ridge', 249),

(1, 'NLEX', 254),
(2, 'Tarlac', 254),
(3, 'Baguio City', 254),

(1, 'NLEX', 255),
(2, 'TPLEX', 255),
(3, 'Vigan City', 255),

(1, 'SLEX', 261),
(2, 'People\'s Park', 261),

(1, 'NLEX', 267),
(2, 'Tarlac', 267),
(3, 'La Union', 267),
(4, 'Ilocos Sur', 267);

-- Insert payments for bookings (all are paid and completed)
INSERT INTO `payments` (`amount`, `payment_method`, `booking_id`, `user_id`, `is_canceled`, `proof_of_payment`, `status`)
VALUES
-- January-March 2024
(22000.00, 'Bank Transfer', 236, 17, 0, 'payment_proof_236.jpg', 'Confirmed'),
(26000.00, 'Online Payment', 237, 16, 0, 'payment_proof_237.jpg', 'Confirmed'),
(68000.00, 'Bank Transfer', 238, 17, 0, 'payment_proof_238.jpg', 'Confirmed'),
(85000.00, 'Online Payment', 239, 18, 0, 'payment_proof_239.jpg', 'Confirmed'),
(42000.00, 'Cash', 240, 19, 0, NULL, 'Confirmed'),
(25000.00, 'Bank Transfer', 241, 20, 0, 'payment_proof_241.jpg', 'Confirmed'),
(38000.00, 'Online Payment', 242, 21, 0, 'payment_proof_242.jpg', 'Confirmed'),
(75000.00, 'Bank Transfer', 243, 17, 0, 'payment_proof_243.jpg', 'Confirmed'),
(110000.00, 'Online Payment', 244, 16, 0, 'payment_proof_244.jpg', 'Confirmed'),

-- April-June 2024
(24000.00, 'Cash', 245, 17, 0, NULL, 'Confirmed'),
(35000.00, 'Bank Transfer', 246, 18, 0, 'payment_proof_246.jpg', 'Confirmed'),
(22000.00, 'Online Payment', 247, 19, 0, 'payment_proof_247.jpg', 'Confirmed'),
(36000.00, 'Bank Transfer', 248, 20, 0, 'payment_proof_248.jpg', 'Confirmed'),
(48000.00, 'Online Payment', 249, 21, 0, 'payment_proof_249.jpg', 'Confirmed'),
(42000.00, 'Bank Transfer', 250, 17, 0, 'payment_proof_250.jpg', 'Confirmed'),
(38000.00, 'Cash', 251, 16, 0, NULL, 'Confirmed'),
(76000.00, 'Bank Transfer', 252, 17, 0, 'payment_proof_252.jpg', 'Confirmed'),
(65000.00, 'Online Payment', 253, 18, 0, 'payment_proof_253.jpg', 'Confirmed'),

-- July-September 2024
(90000.00, 'Bank Transfer', 254, 19, 0, 'payment_proof_254.jpg', 'Confirmed'),
(115000.00, 'Online Payment', 255, 20, 0, 'payment_proof_255.jpg', 'Confirmed'),
(45000.00, 'Bank Transfer', 256, 21, 0, 'payment_proof_256.jpg', 'Confirmed'),
(35000.00, 'Cash', 257, 17, 0, NULL, 'Confirmed'),
(24000.00, 'Bank Transfer', 258, 16, 0, 'payment_proof_258.jpg', 'Confirmed'),
(70000.00, 'Online Payment', 259, 17, 0, 'payment_proof_259.jpg', 'Confirmed'),
(38000.00, 'Bank Transfer', 260, 18, 0, 'payment_proof_260.jpg', 'Confirmed'),
(50000.00, 'Online Payment', 261, 19, 0, 'payment_proof_261.jpg', 'Confirmed'),
(40000.00, 'Bank Transfer', 262, 20, 0, 'payment_proof_262.jpg', 'Confirmed'),

-- October-December 2024
(44000.00, 'Cash', 263, 21, 0, NULL, 'Confirmed'),
(78000.00, 'Bank Transfer', 264, 17, 0, 'payment_proof_264.jpg', 'Confirmed'),
(68000.00, 'Online Payment', 265, 16, 0, 'payment_proof_265.jpg', 'Confirmed'),
(95000.00, 'Bank Transfer', 266, 17, 0, 'payment_proof_266.jpg', 'Confirmed'),
(120000.00, 'Online Payment', 267, 18, 0, 'payment_proof_267.jpg', 'Confirmed'),
(46000.00, 'Bank Transfer', 268, 19, 0, 'payment_proof_268.jpg', 'Confirmed'),
(52000.00, 'Cash', 269, 20, 0, NULL, 'Confirmed'),
(40000.00, 'Bank Transfer', 270, 21, 0, 'payment_proof_270.jpg', 'Confirmed'),
(75000.00, 'Online Payment', 271, 17, 0, 'payment_proof_271.jpg', 'Confirmed');

-- Insert trip_distances for each booking
INSERT INTO `trip_distances` (`origin`, `destination`, `distance`, `booking_id`)
VALUES
-- January-March 2024
('Makati', 'Tagaytay', 65.00, 236),
('Quezon City', 'Batangas', 105.00, 237),
('Pasig', 'Zambales', 160.00, 238),
('Manila', 'Baguio', 250.00, 239),
('Taguig', 'La Union', 275.00, 240),
('Makati', 'Subic', 125.00, 241),
('Quezon City', 'Bataan', 155.00, 242),
('Pasig', 'Pangasinan', 210.00, 243),
('Manila', 'Ilocos', 410.00, 244),

-- April-June 2024
('Taguig', 'Rizal', 30.00, 245),
('Makati', 'Laguna', 65.00, 246),
('Quezon City', 'Cavite', 40.00, 247),
('Pasig', 'Batangas', 110.00, 248),
('Manila', 'Tagaytay', 65.00, 249),
('Taguig', 'Baler', 230.00, 250),
('Makati', 'Zambales', 160.00, 251),
('Quezon City', 'Bataan', 155.00, 252),
('Pasig', 'La Union', 275.00, 253),

-- July-September 2024
('Manila', 'Baguio', 250.00, 254),
('Taguig', 'Ilocos', 410.00, 255),
('Makati', 'Pangasinan', 210.00, 256),
('Quezon City', 'Subic', 125.00, 257),
('Pasig', 'Rizal', 30.00, 258),
('Manila', 'Laguna', 65.00, 259),
('Taguig', 'Batangas', 110.00, 260),
('Makati', 'Tagaytay', 65.00, 261),
('Quezon City', 'Zambales', 160.00, 262),

-- October-December 2024
('Pasig', 'Baler', 230.00, 263),
('Manila', 'Bataan', 155.00, 264),
('Taguig', 'La Union', 275.00, 265),
('Makati', 'Baguio', 250.00, 266),
('Quezon City', 'Ilocos', 410.00, 267),
('Pasig', 'Pangasinan', 210.00, 268),
('Manila', 'Tagaytay', 65.00, 269),
('Taguig', 'Batangas', 110.00, 270),
('Makati', 'Subic', 125.00, 271); 