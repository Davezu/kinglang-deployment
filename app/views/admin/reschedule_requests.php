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
            <h3>Bookings</h3>
            <?php include_once __DIR__ . "/../assets/admin_navtab.php"; ?>
            <table class="table table-hover">
                <thead>
                    <tr><th>Client Name</th><th>Contact Number</th><th>New Date of Tour</th><th>New End of Tour</th><th>Remarks</th><th style="text-align: center">Action</th></tr>
                </thead>
                <tbody id="tableBody">

                </tbody>
            </table>
        </div>
    </div>

    <script src="../../../public/js/admin/sidebar.js"></script>
    <script src="../../../public/js/admin/resched_request.js"></script>
    <script src="../../../public/css/bootstrap/bootstrap.bundle.min.js"></script>
</body>
</html>