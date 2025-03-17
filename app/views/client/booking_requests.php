<?php 

if (!isset($_SESSION["user_id"])) {    
    header("Location: /home");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@300;400;600;700;800;900&display=swap" rel="stylesheet">
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"> -->
    <link rel="stylesheet" href="/../../../public/css/bootstrap/bootstrap.min.css">  

    <title>Bookings</title>
</head>
<body>
    <div class="d-flex">
        <?php include_once __DIR__ . "/../assets/sidebar.php"; ?> 
        
        <div class="flex-grow-1 p-4">
            <div class="container-fluid d-flex justify-content-between align-items-center flex-wrap p-0 m-0">
                <div class="p-0">
                    <h3>My Bookings</h3>
                </div>
                <?php include_once __DIR__ . "/../assets/user_profile.php"; ?>
            </div>
            <div class="container-fluid my-3 p-0">
                <form action="" method="" class="w-25">
                    <div class="input-group">
                        <span class="input-group-text" id="basic-addon1">Status</span>
                        <select name="status" id="status" class="form-select">
                            <option value="">All</option>
                            <option value="pending">Pending</option>
                            <option value="confirmed">Confirmed</option>
                            <option value="canceled">Canceled</option>
                            <option value="rejected">Rejected</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>
                </form>
            </div>
            
            <div class="table-responsive-lg rounded">
                <table class="table table-hover">
                    <thead>
                        <tr><th style="background-color: #d1f7c4">Destination</th><th style="background-color: #d1f7c4">Date of Tour</th><th style="background-color: #d1f7c4">End of Tour</th><th style="background-color: #d1f7c4">Days</th><th style="background-color: #d1f7c4">Buses</th><th style="background-color: #d1f7c4">Total Cost</th><th style="background-color: #d1f7c4">Balance</th><th style="background-color: #d1f7c4">Remarks</th><th style="text-align: center; background-color: #d1f7c4">Action</th></tr>
                    </thead>
                    <tbody class="table-group" id="tableBody">

                    </tbody>
                </table>
            </div>
            
            <!-- <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr><th>Destination</th><th>Date of Tour</th><th>End of Tour</th><th>Days</th><th>Buses</th><th>Total Cost</th><th>Balance</th><th>Remarks</th><th>Action</th></tr>
                    </thead>
                    <tbody class="table-group-divider"  >
                        <?php if (!empty($bookings) && is_array($bookings)): ?>
                            <?php foreach ($bookings as $booking): ?>
                            <tr>
                                <td><?= htmlspecialchars($booking["destination"]); ?></td>
                                <td><?= htmlspecialchars($booking["date_of_tour"]); ?></td>
                                <td><?= htmlspecialchars($booking["end_of_tour"]); ?></td>
                                <td width="5%"><?= htmlspecialchars($booking["number_of_days"]); ?></td>
                                <td width="10%"><?= htmlspecialchars($booking["number_of_buses"]); ?></td>
                                <td class="total-cost"><?= htmlspecialchars($booking["total_cost"]); ?></td>
                                <td class="balance"><?= htmlspecialchars($booking["balance"]) ?></td>
                                <td><?= htmlspecialchars($booking["status"]) ?></td>
                                <td>
                                    <?php if ($booking["total_cost"] !== NULL && $booking["payment_status"] !== "paid" && $booking["status"] !== "completed"): ?>
                                        <form action="" method="post">
                                            <div class="btn-group w-100">
                                                <button 
                                                    data-amount="<?= $booking["total_cost"] ?>" 
                                                    
                                                    data-bookingID="<?= $booking["booking_id"] ?>" 
                                                    data-clientID="<?= $booking["client_id"]; ?>" 
                                                    class="btn btn-outline-success btn-sm open-payment-modal" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#paymentModal">
                                                    Pay</button>
                                                <button type="button" class="btn btn-outline-primary btn-sm">Reschedule</button>  
                                                <button type="button" class="btn btn-outline-danger btn-sm">Cancel</button>
                                            </div>
                                        </form>
                                    <?php elseif ($booking["status"] !== "completed"): ?>
                                        <form action="" method="post">
                                            <div class="btn-group w-100">
                                                <button type="submit" name="status" value="canceled" class="btn btn-outline-danger btn-sm d-block w-100">Cancel</button>
                                                <button type="submit" name="status" value="canceled" class="btn btn-outline-primary btn-sm d-block w-100">Reschedule</button>
                                            </div>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9">None</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div> -->
        </div>

        <div class="modal fade payment-modal" aria-labelledby="paymentModal" tabindex="-1" id="paymentModal">
            <div class="modal-dialog modal-dialog-centered">
                <form class="payment-content modal-content" action="/payment/process" method="post">

                    <div class="modal-header">
                        <h3 class="modal-title">Payment</h3>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body container">
                        <p>Please select the amount you want to pay.</p>
                        <div class="row mx-1" style="cursor: pointer">
                            <div class="col text-bg-success me-2 p-3 rounded-3 amount-payment" id="fullAmnt">
                                <h3>Full payment</h3>
                                <p id="fullAmount" class="amount"></p>  
                            </div>

                            <div class="col text-bg-danger p-3 rounded-3 amount-payment" style="cursor: pointer">
                                <h3 id="downPayment">Down payment</h3>
                                <p id="partialAmount" class="amount"></p>
                            </div>     
                        </div>

                        <input type="hidden" name="booking_id" id="bookingID">
                        <input type="hidden" name="client_id" id="clientID">
                        <input type="hidden" name="amount" id="amountInput">

                        <div class="mt-3 total-amount">Amount: <span id="amount" class="text-success"></span></div>

                        <div class="payment-method">
                            <label for="" class="mt-2">Payment method</label>
                            <select name="payment_method" id="" class="form-select mt-2" aria-label="small select example">
                                <option value="Cash">Cash</option>
                                <option value="Bank Transfer">Bank Transfer</option>
                                <option value="Online Payment">Online Payment</option>
                            </select>
                        </div>
                    </div>
                                            
                    <div class="modal-footer">
                        <button class="btn btn-outline-success pay" type="submit">Pay Now</button>
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
                            <input type="date" name="" id="date_of_tour" class="form-control">
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
    </div>

    <script src="/../../../public/css/bootstrap/bootstrap.bundle.min.js"></script>
    <script src="/../../../public/js/client/booking_request.js"></script>
</body>
</html>