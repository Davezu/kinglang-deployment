<div class="p-2 d-flex align-items-center gap-2">
    <a href="#" class="text-success"><i class="bi bi-plus-square-fill me-2 fs-5"></i></a>
    <i class="bi bi-bell-fill me-2 fs-5 text-success"></i>
    <img src="../../../public/images/profile.png" alt="profile" class="me-2" height="35px">
    <div class="">
        <div class="name text-success fw-bold" style="font-size: 12px"><?= $_SESSION["admin_name"]; ?> </div>
        <div class="role" style="font-size: 10px"><?= $_SESSION["role"]; ?></div>
    </div>
</div>