<?php 
require_client_auth(); // Use helper function
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@300;400;600;700;800;900&display=swap" rel="stylesheet">
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"> -->
    <link rel="stylesheet" href="/../../../public/css/bootstrap/bootstrap.min.css">  
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/../../../public/css/client/payment_styles.css">
    <link rel="stylesheet" href="/../../../public/css/assets/cancel_modal.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Bookings</title>
</head>
<body>
    <?php include_once __DIR__ . "/../assets/sidebar.php"; ?> 
    
    <div class="content collapsed" id="content">
        <div class="container-fluid py-4 px-4 px-xl-5">
            <div class="container-fluid d-flex justify-content-between align-items-center flex-wrap p-0 m-0">
                <div class="p-0">
                    <h3>My Bookings</h3>
                </div>
                <?php include_once __DIR__ . "/../assets/user_profile.php"; ?>
            </div>
            <div class="d-flex gap-3 my-3">
                <div class="input-group w-25 my-3">
                    <span class="input-group-text bg-success-subtle" id="basic-addon1">Filter by Remarks</span>
                    <select name="status" id="statusSelect" class="form-select">
                        <option value="all">All</option>
                        <option value="pending" selected>Pending</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="canceled">Canceled</option>
                        <option value="rejected">Rejected</option>
                        <option value="completed">Completed</option>
                        <option value="processing">Processing</option>
                    </select>
                </div>
                
                <div class="input-group w-25 my-3 ms-3">
                    <span class="input-group-text bg-success-subtle" id="basic-addon2">Records per page</span>
                    <select name="limit" id="limitSelect" class="form-select">
                        <option value="5">5</option>
                        <option value="10" selected>10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
            </div>
        
            <div class="table-responsive-xl">
                <table class="table table-hover overflow-hidden rounded">
                    <thead>
                        <tr>
                            <th class="sort" data-order="asc" data-column="destination" style="cursor: pointer; background-color: #d1f7c4; white-space: nowrap;">Destination</th>
                            <th class="sort" data-order="asc" data-column="date_of_tour" style="cursor: pointer; background-color: #d1f7c4; white-space: nowrap;">Date of Tour</th>
                            <th class="sort" data-order="asc" data-column="end_of_tour" style="cursor: pointer; background-color: #d1f7c4; white-space: nowrap;">End of Tour</th>
                            <th class="sort" data-order="asc" data-column="number_of_days" style="cursor: pointer; background-color: #d1f7c4; white-space: nowrap;">Days</th>
                            <th class="sort" data-order="asc" data-column="number_of_buses" style="cursor: pointer; background-color: #d1f7c4; white-space: nowrap;">Buses</th>
                            <th class="sort" data-order="asc" data-column="total_cost" style="cursor: pointer; background-color: #d1f7c4; white-space: nowrap;">Total Cost</th>
                            <th class="sort" data-order="asc" data-column="balance" style="cursor: pointer; background-color: #d1f7c4; white-space: nowrap;">Balance</th>
                            <th class="sort" data-order="asc" data-column="status" style="cursor: pointer; background-color: #d1f7c4; white-space: nowrap;">Remarks</th>
                            <th class="sort" style="text-align: center; width: 20%; background-color: #d1f7c4; white-space: nowrap;">Action</th>
                        </tr>
                    </thead>
                    <tbody class="table-group" id="tableBody">

                    </tbody>
                </table>     
            </div>
        </div>
    </div>

    <div class="modal fade payment-modal" aria-labelledby="paymentModal" tabindex="-1" id="paymentModal">
        <div class="modal-dialog modal-dialog-centered">
            <form class="payment-content modal-content" action="" id="paymentForm" method="post" enctype="multipart/form-data">

                <div class="modal-header">
                    <h3 class="modal-title"><i class="bi bi-credit-card-2-front me-2"></i>Payment Details</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body container">
                    <div class="row">
                        <!-- Left Column - Payment Options -->
                        <div class="col-md-6">
                            <p class="lead mb-4">Payment Options:</p>
                            <div class="d-flex flex-column gap-3">
                                <div class="text-bg-success p-3 rounded-3 amount-payment" id="fullAmnt">
                                    <h3>Full payment</h3>
                                    <p id="fullAmount" class="amount"></p>  
                                </div>

                                <div class="text-bg-danger p-3 rounded-3 amount-payment">
                                    <h3 id="downPayment">Down payment</h3>
                                    <p id="partialAmount" class="amount"></p>
                                </div>
                            </div>
                            
                            <div class="mt-3 total-amount">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span>Selected Amount:</span>
                                    <span id="amount" class="text-success"></span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Right Column - Payment Method & Upload -->
                        <div class="col-md-6">
                            <div class="payment-method">
                                <label for="paymentMethod" class="form-label">Payment Method</label>
                                <select name="payment_method" id="paymentMethod" class="form-select" aria-label="Payment method selection">
                                    <!-- <option value="Cash">Cash</option> -->
                                    <option value="Bank Transfer">Bank Transfer</option>
                                    <option value="Online Payment">Online Payment</option>
                                </select>
                            </div>

                            <!-- Account Information Section -->
                            <div id="accountInfoSection" class="mt-3" style="display: none;">
                                <div class="alert alert-info">
                                    <h5 class="alert-heading"><i class="bi bi-info-circle me-2"></i>Account Details</h5>
                                    <div class="mt-2">
                                        <p class="mb-1"><strong>Bank:</strong> <span id="bankName">BDO</span></p>
                                        <p class="mb-1"><strong>Name:</strong> <span id="accountName">Kinglang Bus</span></p>
                                        <p class="mb-0"><strong>Number:</strong> <span id="accountNumber">1234567890</span></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Proof of Payment Upload Section -->
                            <div id="proofUploadSection" class="mt-3" style="display: none;">
                                <label for="proofOfPayment" class="form-label">Upload Proof</label>
                                <input type="file" class="form-control" id="proofOfPayment" name="proof_of_payment" accept="image/*,.pdf">
                                <small class="text-muted">Upload receipt (JPG, PNG, PDF)</small>
                            </div>
                        </div>
                    </div>

                    <!-- Hidden inputs -->
                    <input type="hidden" name="booking_id" id="bookingID">
                    <input type="hidden" name="user_id" id="userID">
                    <input type="hidden" name="amount" id="amountInput">
                </div>
                                        
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-outline-success pay" type="submit"><i class="bi bi-check-circle me-2"></i>Confirm Payment</button>
                </div>
            </form>
        </div>
    </div>
    <div class="modal fade resched-modal" aria-labelledby="reschedModal" tabindex="-1" id="reschedModal">
        <div class="modal-dialog modal-dialog-centered">
            <form class="modal-content" id="reschedForm" action="/payment/process" method="post">

                <div class="modal-header">
                    <h3 class="modal-title">Reschedule Booking</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body container">
                    <input type="hidden" name="" id="reschedBookingId">
                    <input type="hidden" name="" id="reschedClientId">
                    <input type="hidden" name="" id="numberOfBuses">
                    <input type="hidden" name="" id="number_of_days">
                    <div class="mb-3">
                        <label for="" class="form-label">New Date of Tour</label>
                        <input type="date" name="" id="date_of_tour" class="form-control" required>
                    </div>
                </div>
                                        
                <div class="modal-footer">
                    <div class="container-fluid d-flex justify-content-between">
                        <p id="messageElement"></p>
                        <button class="btn btn-outline-success" type="submit">Reschedule</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="../../../public/js/utils/pagination.js"></script>
    <script src="../../../public/js/client/booking_request.js"></script>
    <script src="../../../public/css/bootstrap/bootstrap.bundle.min.js"></script>
    <script src="../../../public/js/assets/sidebar.js"></script>
</body>
</html>