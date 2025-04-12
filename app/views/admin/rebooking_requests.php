<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Management</title>
</head>
<body>
    <?php include_once __DIR__ . "/../assets/admin_sidebar.php"; ?>

    <div class="content collapsed" id="content">
        <div class="container-fluid py-4 px-4 px-xl-5">
            <div class="container-fluid d-flex justify-content-between align-items-center flex-wrap p-0 m-0">
                <h3>Booking Management</h3>
                <?php include_once __DIR__ . "/../assets/admin_profile.php"; ?>
            </div>
            <?php include_once __DIR__ . "/../assets/admin_navtab.php"; ?>
            <div class="input-group w-25 w-md-50 my-3">
                <span class="input-group-text bg-success-subtle" id="basic-addon1">Filter by Remarks</span>
                <select name="status" id="statusSelect" class="form-select">
                    <option value="All">All</option>
                    <option value="Pending">Pending</option>
                    <option value="Confirmed">Confirmed</option>
                    <option value="Rejected">Rejected</option>
                </select>
            </div>
            <div class="table-responsive-xl ">
                <table class="table table-hover border overflow-hidden rounded">
                    <thead>
                        <tr>
                            <th class="sort" data-order="asc" data-column="client_name">Client Name</th>
                            <th class="sort" data-order="asc" data-column="contact_number">Contact Number</th>
                            <th class="sort" data-order="asc" data-column="email">Email Address</th>
                            <th class="sort" data-order="asc" data-column="date_of_tour">Date of Tour</th>
                            <th class="sort" data-order="asc" data-column="status">Remarks</th>
                            <th class="sort" data-order="asc" style="text-align: center; width: 15%;">Action</th>
                        </tr> 
                    </thead>
                    <tbody id="tableBody" class="fs-6">

                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="../../../public/js/assets/sidebar.js"></script>
    <script src="../../../public/js/admin/rebooking_requests.js"></script>
    <script src="../../../public/css/bootstrap/bootstrap.bundle.min.js"></script>
</body>
</html>