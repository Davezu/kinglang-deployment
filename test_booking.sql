-- Insert a test booking for today
INSERT INTO bookings (
    destination, 
    pickup_point, 
    date_of_tour, 
    end_of_tour, 
    number_of_days, 
    number_of_buses, 
    user_id, 
    status
) VALUES (
    'Test Urgent Review', 
    'Test Pickup Point', 
    CURDATE(), 
    CURDATE(), 
    1, 
    1, 
    1, 
    'Pending'
); 