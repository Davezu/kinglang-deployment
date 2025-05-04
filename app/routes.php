// Admin booking management routes
$router->post('/admin/bookings', 'BookingManagementController@getAllBookings');
$router->post('/admin/search-bookings', 'BookingManagementController@searchBookings');
$router->post('/admin/unpaid-bookings', 'BookingManagementController@getUnpaidBookings');
$router->post('/admin/partially-paid-bookings', 'BookingManagementController@getPartiallyPaidBookings');
$router->post('/admin/confirm-booking', 'BookingManagementController@confirmBooking');
$router->post('/admin/reject-booking', 'BookingManagementController@rejectBooking');
$router->post('/admin/cancel-booking', 'BookingManagementController@cancelBooking');
$router->post('/admin/calendar-bookings', 'BookingManagementController@getCalendarBookings');
$router->get('/admin/booking-stats', 'BookingManagementController@getBookingStats');
$router->post('/admin/get-booking', 'BookingManagementController@getBooking');
$router->post('/admin/get-booking-details', 'BookingManagementController@getBookingDetails');
$router->get('/admin/export-bookings', 'BookingManagementController@exportBookings'); 

// New admin create booking routes with AdminBookingController
$router->get('/admin/create-booking', 'AdminBookingController@showBookingForm');
$router->post('/admin/create-booking', 'AdminBookingController@createBooking');
$router->post('/admin/get-address', 'AdminBookingController@getAddress');
$router->post('/admin/get-distance', 'AdminBookingController@getDistance');
$router->post('/admin/process-coordinates', 'AdminBookingController@processCoordinates');
$router->get('/admin/diesel-price', 'AdminBookingController@getDieselPrice');
$router->post('/admin/calculate-cost', 'AdminBookingController@getTotalCost'); 