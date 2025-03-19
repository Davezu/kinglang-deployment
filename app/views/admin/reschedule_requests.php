<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php include_once __DIR__ . "/../assets/admin_sidebar.php"; ?>

    <div class="content collapsed" id="content">
        <div class="container-fluid p-4">
            <h3>Reschedule Requests</h3>
            <?php include_once __DIR__ . "/../assets/admin_navtab.php"; ?>
            <div class="input-group w-25 w-md-50 my-3">
                <span class="input-group-text" id="basic-addon1">Filter by Status</span>
                <select name="status" id="statusSelect" class="form-select">
                    <option value="all">All</option>
                    <option value="pending">Pending</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="canceled">Canceled</option>
                    <option value="rejected">Rejected</option>
                    <option value="completed">Completed</option>
                </select>
            </div>
            <div class="table-responsive-xl overflow-hidden rounded">
                <table class="table table-hover border">
                    <thead>
                        <tr>
                            <th class="sort" data-order="asc" data-column="client_name">Client Name</th>
                            <th class="sort" data-order="asc" data-column="contact_number">Contact Number</th>
                            <th class="sort" data-order="asc" data-column="new_date_of_tour">New Date of Tour</th>
                            <th class="sort" data-order="asc" data-column="new_end_of_tour">New End of Tour</th>
                            <th class="sort" data-order="asc" data-column="status">Remarks</th>
                            <th class="sort" data-order="asc" style="text-align: center">Action</th>
                        </tr> 
                    </thead>
                    <tbody id="tableBody" class="fs-6">

                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="../../../public/js/assets/sidebar.js"></script>
    <script src="../../../public/js/admin/resched_request.js"></script>
    <script src="../../../public/css/bootstrap/bootstrap.bundle.min.js"></script>
</body>
</html>