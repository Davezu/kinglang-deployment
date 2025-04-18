-- Import sample data for Kinglang Booking System
-- This file imports all sample data parts in order

-- Import 2024 data first (bookings 236-271)
SOURCE sample_data_2024.sql;

-- Import part 1 (bookings 1-25)
SOURCE sample_data_part1.sql;

-- Import part 2 (bookings 26-50)
SOURCE sample_data_part2.sql;

-- Import part 3 (bookings 51-75)
SOURCE sample_data_part3.sql;

-- Import part 4 (bookings 76-100)
SOURCE sample_data_part4.sql;

-- Update statistics to reflect new data
ANALYZE TABLE bookings;
ANALYZE TABLE booking_buses;
ANALYZE TABLE booking_stops;
ANALYZE TABLE payments;
ANALYZE TABLE trip_distances;
ANALYZE TABLE rebooking_request;
ANALYZE TABLE rejected_trips;
ANALYZE TABLE canceled_trips;

SELECT 'Sample data import completed successfully.' AS 'Status'; 