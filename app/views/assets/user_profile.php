<div class="p-2 d-flex align-items-center">
    <a href="/home/book" class="text-success"><i class="bi bi-plus-square-fill me-2 fs-5"></i></a>
    <i class="bi bi-bell-fill me-2 fs-5 text-success"></i>
    <img src="../../../public/images/profile.png" alt="profile" class="me-2" height="35px">
    <div class="text-sm">
        <div class="name" style="font-size: 12px"><?= $_SESSION["client_name"]; ?> </div>
        <div class="email" style="font-size: 8px"><?= $_SESSION["email"]; ?></div>
    </div>
</div>