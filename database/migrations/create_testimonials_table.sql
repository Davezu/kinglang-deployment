-- Migration to create testimonials table
-- Run this SQL in your database to add testimonial functionality

CREATE TABLE IF NOT EXISTS testimonials (
    testimonial_id INT(11) NOT NULL AUTO_INCREMENT,
    user_id INT(11) NOT NULL,
    booking_id INT(11) NOT NULL,
    rating INT(1) NOT NULL CHECK (rating >= 1 AND rating <= 5),
    title VARCHAR(100) NOT NULL,
    content TEXT NOT NULL,
    is_approved TINYINT(1) DEFAULT 0,
    is_featured TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (testimonial_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (booking_id) REFERENCES bookings(booking_id) ON DELETE CASCADE,
    UNIQUE KEY unique_testimonial_per_booking (user_id, booking_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Index for better performance when fetching approved testimonials
CREATE INDEX idx_testimonials_approved ON testimonials (is_approved, created_at DESC);
CREATE INDEX idx_testimonials_featured ON testimonials (is_featured, is_approved, created_at DESC);