# Kinglang Booking System Sample Data

This directory contains sample data for the Kinglang Booking System database. 

## Files

- `database.sql` - The main database schema file
- `sample_data_2024.sql` - Sample data for 2024 bookings (bookings 101-136) with users 16-21
- `sample_data_part1.sql` - Sample data for bookings 1-25 and related records
- `sample_data_part2.sql` - Sample data for bookings 26-50 and related records
- `sample_data_part3.sql` - Sample data for bookings 51-75 and related records
- `sample_data_part4.sql` - Sample data for bookings 76-100 and related records
- `import_sample_data.sql` - Main import file that sources all sample data files

## Importing the Sample Data

### Option 1: Using the import file (Recommended)

1. First, make sure your database schema is already imported
2. Navigate to this directory in your terminal
3. Connect to your MySQL/MariaDB server:
   ```
   mysql -u username -p kinglang_booking
   ```
4. Import the sample data using the main import file:
   ```
   source import_sample_data.sql;
   ```

### Option 2: Importing individual files

If you prefer to import the files one by one:

1. First, make sure your database schema is already imported
2. Navigate to this directory in your terminal
3. Connect to your MySQL/MariaDB server:
   ```
   mysql -u username -p kinglang_booking
   ```
4. Import each sample data file in order:
   ```
   source sample_data_2024.sql;
   source sample_data_part1.sql;
   source sample_data_part2.sql;
   source sample_data_part3.sql;
   source sample_data_part4.sql;
   ```

## Data Contents

The sample data includes:
- 36 booking records from 2024 for users 16-21 (all completed and paid)
- 100 booking records from 2025-2026 with varied statuses, dates, and payment states
- Associated booking_buses entries that link bookings to buses
- Booking stops for select itineraries
- Payment records for paid and partially paid bookings
- Trip distance entries for all bookings
- Rejected booking records
- Canceled booking records
- Rebooking request entries

This data should provide a comprehensive sample for testing all features of the Kinglang Booking System, including historical data analysis and reporting. 