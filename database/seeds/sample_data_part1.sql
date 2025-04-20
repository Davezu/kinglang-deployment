-- Sample data for Kinglang Booking System - Part 1 of 4

-- Insert sample users if they don't exist already
INSERT IGNORE INTO `users` (`first_name`, `last_name`, `email`, `contact_number`, `password`, `role`)
VALUES
('John', 'Doe', 'john.doe@example.com', '09123456789', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Client'),
('Jane', 'Smith', 'jane.smith@example.com', '09234567890', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Client'),
('Michael', 'Johnson', 'michael.j@example.com', '09345678901', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Client'),
('Sarah', 'Williams', 'sarah.w@example.com', '09456789012', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Client'),
('David', 'Brown', 'david.b@example.com', '09567890123', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Client');

-- Insert sample buses if they don't exist already
INSERT IGNORE INTO `buses` (`name`, `capacity`, `status`)
VALUES
('Bus 101', '49', 'Active'),
('Bus 102', '49', 'Active'),
('Bus 103', '49', 'Active'),
('Bus 104', '49', 'Active'),
('Bus 105', '49', 'Maintenance');

-- Set a diesel price if not exists
INSERT IGNORE INTO `diesel_per_liter` (`price`, `date`)
VALUES (65.50, NOW());

-- Insert bookings 1-25
INSERT INTO `bookings` 
(`destination`, `pickup_point`, `date_of_tour`, `end_of_tour`, `number_of_days`, 
`number_of_buses`, `total_cost`, `balance`, `status`, `payment_status`, `user_id`, 
`is_rebooking`, `is_rebooked`, `booked_at`) 
VALUES
-- Booking 1
('Tagaytay', 'Manila', '2025-05-15', '2025-05-16', 2, 1, 25000.00, 0.00, 'Completed', 'Paid', 1, 0, 0, '2025-04-20 08:30:00'),
-- Booking 2
('Baguio', 'Quezon City', '2025-05-18', '2025-05-20', 3, 2, 75000.00, 0.00, 'Completed', 'Paid', 2, 0, 0, '2025-04-20 09:45:00'),
-- Booking 3
('Batangas', 'Makati', '2025-05-25', '2025-05-26', 2, 1, 30000.00, 0.00, 'Confirmed', 'Paid', 3, 0, 0, '2025-04-21 10:15:00'),
-- Booking 4
('La Union', 'Pasig', '2025-06-01', '2025-06-03', 3, 1, 45000.00, 0.00, 'Confirmed', 'Paid', 4, 0, 0, '2025-04-21 14:20:00'),
-- Booking 5
('Subic', 'Taguig', '2025-06-05', '2025-06-06', 2, 1, 28000.00, 0.00, 'Confirmed', 'Paid', 5, 0, 0, '2025-04-22 11:10:00'),
-- Booking 6
('Bohol', 'Manila', '2025-06-10', '2025-06-13', 4, 2, 120000.00, 30000.00, 'Confirmed', 'Partially Paid', 1, 0, 0, '2025-04-22 16:25:00'),
-- Booking 7
('Ilocos', 'Quezon City', '2025-06-15', '2025-06-18', 4, 2, 100000.00, 100000.00, 'Pending', 'Unpaid', 2, 0, 0, '2025-04-23 09:05:00'),
-- Booking 8
('Palawan', 'Makati', '2025-06-20', '2025-06-24', 5, 1, 85000.00, 42500.00, 'Confirmed', 'Partially Paid', 3, 0, 0, '2025-04-23 13:40:00'),
-- Booking 9
('Boracay', 'Pasig', '2025-06-25', '2025-06-29', 5, 2, 150000.00, 150000.00, 'Rejected', 'Unpaid', 4, 0, 0, '2025-04-24 10:30:00'),
-- Booking 10
('Cebu', 'Taguig', '2025-07-01', '2025-07-05', 5, 1, 90000.00, 90000.00, 'Canceled', 'Unpaid', 5, 0, 0, '2025-04-24 15:20:00'),
-- Booking 11
('Davao', 'Manila', '2025-07-06', '2025-07-10', 5, 1, 95000.00, 47500.00, 'Confirmed', 'Partially Paid', 1, 0, 0, '2025-04-25 08:50:00'),
-- Booking 12
('Camiguin', 'Quezon City', '2025-07-12', '2025-07-15', 4, 1, 75000.00, 0.00, 'Confirmed', 'Paid', 2, 0, 0, '2025-04-25 14:15:00'),
-- Booking 13
('Siargao', 'Makati', '2025-07-18', '2025-07-22', 5, 2, 180000.00, 180000.00, 'Processing', 'Unpaid', 3, 0, 0, '2025-04-26 09:30:00'),
-- Booking 14
('Baler', 'Pasig', '2025-07-24', '2025-07-26', 3, 1, 45000.00, 22500.00, 'Confirmed', 'Partially Paid', 4, 0, 0, '2025-04-26 16:45:00'),
-- Booking 15
('Zambales', 'Taguig', '2025-07-28', '2025-07-30', 3, 1, 42000.00, 0.00, 'Confirmed', 'Paid', 5, 0, 0, '2025-04-27 10:20:00'),
-- Booking 16
('Bataan', 'Manila', '2025-08-01', '2025-08-03', 3, 1, 40000.00, 40000.00, 'Pending', 'Unpaid', 1, 0, 0, '2025-04-27 13:35:00'),
-- Booking 17
('Pangasinan', 'Quezon City', '2025-08-05', '2025-08-07', 3, 2, 85000.00, 42500.00, 'Confirmed', 'Partially Paid', 2, 0, 0, '2025-04-28 09:10:00'),
-- Booking 18
('Cagayan Valley', 'Makati', '2025-08-10', '2025-08-14', 5, 1, 80000.00, 0.00, 'Confirmed', 'Paid', 3, 0, 0, '2025-04-28 15:25:00'),
-- Booking 19
('Bicol', 'Pasig', '2025-08-16', '2025-08-19', 4, 2, 110000.00, 110000.00, 'Processing', 'Unpaid', 4, 0, 0, '2025-04-29 11:40:00'),
-- Booking 20
('Dumaguete', 'Taguig', '2025-08-22', '2025-08-26', 5, 1, 85000.00, 85000.00, 'Pending', 'Unpaid', 5, 0, 0, '2025-04-29 16:15:00'),
-- Booking 21
('Siquijor', 'Manila', '2025-08-28', '2025-09-01', 5, 1, 90000.00, 45000.00, 'Confirmed', 'Partially Paid', 1, 0, 0, '2025-04-30 08:55:00'),
-- Booking 22
('Coron', 'Quezon City', '2025-09-03', '2025-09-07', 5, 2, 170000.00, 0.00, 'Confirmed', 'Paid', 2, 0, 0, '2025-04-30 14:30:00'),
-- Booking 23
('El Nido', 'Makati', '2025-09-10', '2025-09-15', 6, 1, 110000.00, 110000.00, 'Pending', 'Unpaid', 3, 0, 0, '2025-05-01 10:05:00'),
-- Booking 24
('Puerto Princesa', 'Pasig', '2025-09-18', '2025-09-22', 5, 1, 95000.00, 47500.00, 'Confirmed', 'Partially Paid', 4, 0, 0, '2025-05-01 15:50:00'),
-- Booking 25
('Albay', 'Taguig', '2025-09-25', '2025-09-28', 4, 1, 70000.00, 0.00, 'Confirmed', 'Paid', 5, 0, 0, '2025-05-02 09:25:00');

-- Insert booking_buses for each booking
-- For bookings 1-25
INSERT INTO `booking_buses` (`booking_id`, `bus_id`)
VALUES
(1, 1),
(2, 1), (2, 2),
(3, 3),
(4, 1),
(5, 2),
(6, 3), (6, 4),
(7, 1), (7, 2),
(8, 3),
(9, 1), (9, 2),
(10, 3),
(11, 4),
(12, 1),
(13, 2), (13, 3),
(14, 4),
(15, 1),
(16, 2),
(17, 3), (17, 4),
(18, 1),
(19, 2), (19, 3),
(20, 4),
(21, 1),
(22, 2), (22, 3),
(23, 4),
(24, 1),
(25, 2);

-- Insert booking_stops for some bookings
INSERT INTO `booking_stops` (`stop_order`, `location`, `booking_id`)
VALUES
-- Booking 1 stops
(1, 'Alabang', 1),
(2, 'Silang', 1),
-- Booking 2 stops
(1, 'EDSA', 2),
(2, 'NLEX Toll', 2),
(3, 'Tarlac', 2),
-- Booking 3 stops
(1, 'SLEX', 3),
(2, 'Lipa', 3),
-- Booking 6 stops
(1, 'NAIA Terminal 3', 6),
(2, 'Cebu City', 6),
-- Booking 8 stops
(1, 'NAIA Terminal 2', 8),
(2, 'Puerto Princesa City', 8);

-- Insert payments for bookings marked as Paid or Partially Paid
INSERT INTO `payments` (`amount`, `payment_method`, `booking_id`, `user_id`, `is_canceled`, `proof_of_payment`, `status`)
VALUES
-- Full payments
(25000.00, 'Bank Transfer', 1, 1, 0, 'payment_proof_1.jpg', 'Confirmed'),
(75000.00, 'Bank Transfer', 2, 2, 0, 'payment_proof_2.jpg', 'Confirmed'),
(30000.00, 'Online Payment', 3, 3, 0, 'payment_proof_3.jpg', 'Confirmed'),
(45000.00, 'Bank Transfer', 4, 4, 0, 'payment_proof_4.jpg', 'Confirmed'),
(28000.00, 'Cash', 5, 5, 0, NULL, 'Confirmed'),
(75000.00, 'Bank Transfer', 12, 2, 0, 'payment_proof_12.jpg', 'Confirmed'),
(42000.00, 'Online Payment', 15, 5, 0, 'payment_proof_15.jpg', 'Confirmed'),
(80000.00, 'Bank Transfer', 18, 3, 0, 'payment_proof_18.jpg', 'Confirmed'),
(170000.00, 'Bank Transfer', 22, 2, 0, 'payment_proof_22.jpg', 'Confirmed'),
(70000.00, 'Online Payment', 25, 5, 0, 'payment_proof_25.jpg', 'Confirmed'),
-- Partial payments
(90000.00, 'Bank Transfer', 6, 1, 0, 'payment_proof_6.jpg', 'Confirmed'),
(42500.00, 'Online Payment', 8, 3, 0, 'payment_proof_8.jpg', 'Confirmed'),
(47500.00, 'Cash', 11, 1, 0, NULL, 'Confirmed'),
(22500.00, 'Bank Transfer', 14, 4, 0, 'payment_proof_14.jpg', 'Confirmed'),
(42500.00, 'Online Payment', 17, 2, 0, 'payment_proof_17.jpg', 'Confirmed'),
(45000.00, 'Bank Transfer', 21, 1, 0, 'payment_proof_21.jpg', 'Confirmed'),
(47500.00, 'Online Payment', 24, 4, 0, 'payment_proof_24.jpg', 'Confirmed');

-- Insert trip_distances for each booking
INSERT INTO `trip_distances` (`origin`, `destination`, `distance`, `booking_id`)
VALUES
('Manila', 'Tagaytay', 66.00, 1),
('Quezon City', 'Baguio', 245.00, 2),
('Makati', 'Batangas', 108.00, 3),
('Pasig', 'La Union', 276.00, 4),
('Taguig', 'Subic', 127.00, 5),
('Manila', 'Bohol', 675.00, 6),
('Quezon City', 'Ilocos', 408.00, 7),
('Makati', 'Palawan', 592.00, 8),
('Pasig', 'Boracay', 445.00, 9),
('Taguig', 'Cebu', 852.00, 10),
('Manila', 'Davao', 1450.00, 11),
('Quezon City', 'Camiguin', 1325.00, 12),
('Makati', 'Siargao', 1245.00, 13),
('Pasig', 'Baler', 231.00, 14),
('Taguig', 'Zambales', 156.00, 15),
('Manila', 'Bataan', 154.00, 16),
('Quezon City', 'Pangasinan', 215.00, 17),
('Makati', 'Cagayan Valley', 432.00, 18),
('Pasig', 'Bicol', 390.00, 19),
('Taguig', 'Dumaguete', 925.00, 20),
('Manila', 'Siquijor', 880.00, 21),
('Quezon City', 'Coron', 562.00, 22),
('Makati', 'El Nido', 642.00, 23),
('Pasig', 'Puerto Princesa', 598.00, 24),
('Taguig', 'Albay', 456.00, 25);

-- Insert rejected_trips for rejected bookings
INSERT INTO `rejected_trips` (`reason`, `type`, `booking_id`, `user_id`)
VALUES
('No available buses for requested dates', 'Booking', 9, 4);

-- Insert canceled_trips for canceled bookings
INSERT INTO `canceled_trips` (`reason`, `booking_id`, `user_id`, `amount_refunded`, `canceled_by`)
VALUES
('Client requested cancellation due to scheduling conflict', 10, 5, 0.00, 'Client'); 