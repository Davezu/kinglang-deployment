<?php 
// Require authentication check (included from controller)
require_once __DIR__ . "/../../models/admin/Settings.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Settings - Kinglang Booking</title>
    <link rel="stylesheet" href="../../../public/css/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="../../../public/icons/bootstrap-icons.css">
    <style>
        .setting-card {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            margin-bottom: 1rem;
        }
        
        .setting-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
        }
        
        .side-nav-card {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            height: 100%;
        }
        
        .settings-main-card {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }
        
        .nav-pills .nav-link.active {
            background-color: #19BC3F;
            color: white;
        }
        
        .nav-pills .nav-link {
            color: #212529;
            padding: 0.8rem 1rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: all 0.2s;
        }
        
        .nav-pills .nav-link:hover {
            background-color: #d1f7c4;
        }
        
        .badge-public {
            background-color: #19BC3F;
            color: white;
            padding: 0.35rem 0.65rem;
            border-radius: 0.5rem;
            font-size: 0.75rem;
        }
        
        .badge-private {
            background-color: #6c757d;
            color: white;
            padding: 0.35rem 0.65rem;
            border-radius: 0.5rem;
            font-size: 0.75rem;
        }
        
        .settings-icon {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: rgba(255, 245, 225, 1);
            color: #19BC3F;
            border-radius: 50%;
            margin-right: 10px;
        }
        
        .icon-bg {
            background-color: rgba(255, 245, 225, 1);
            border-radius: 50%;
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
        }
        
        .btn-primary {
            background-color: #19BC3F;
            border-color: #19BC3F;
        }
        
        .btn-primary:hover {
            background-color: #148f32;
            border-color: #148f32;
        }
        
        .btn-success {
            background-color: #19BC3F;
            border-color: #19BC3F;
        }
        
        .btn-success:hover {
            background-color: #148f32;
            border-color: #148f32;
        }
        
        .content {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
    <?php include_once __DIR__ . "/../assets/admin_sidebar.php"; ?>

    <div class="content collapsed" id="content">
        <div class="container-fluid py-4 px-4">
            <div class="container-fluid d-flex justify-content-between align-items-center flex-wrap p-0 m-0">
                <div class="d-flex align-items-center">
                    <div class="settings-icon">
                        <i class="bi bi-gear-fill fs-5"></i>
                    </div>
                    <h3>System Settings</h3>
                </div>
                <?php include_once __DIR__ . "/../assets/admin_profile.php"; ?>
            </div>
            <p class="text-muted">Manage application settings and configurations</p>
            <hr>

            <div class="row mt-4">
                <div class="col-md-3 mb-4">
                    <div class="side-nav-card">
                        <div class="card-header bg-white py-3 border-bottom">
                            <div class="d-flex align-items-center">
                                <div class="icon-bg">
                                    <i class="bi bi-layers fs-5 text-success"></i>
                                </div>
                                <h5 class="mb-0">Setting Groups</h5>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                <?php
                                $first = true;
                                foreach (array_keys($groupedSettings) as $group) {
                                    $groupName = ucfirst($group);
                                    $active = $first ? 'active' : '';
                                    $icon = 'gear';
                                    
                                    // Assign specific icons based on group name
                                    if ($group == 'general') $icon = 'sliders';
                                    if ($group == 'booking') $icon = 'calendar-check';
                                    if ($group == 'payment') $icon = 'credit-card';
                                    if ($group == 'notification') $icon = 'bell';
                                    if ($group == 'security') $icon = 'shield-lock';
                                    if ($group == 'api') $icon = 'code-slash';
                                    
                                    echo "<a class='nav-link $active' id='v-pills-$group-tab' data-bs-toggle='pill' href='#v-pills-$group' role='tab' aria-controls='v-pills-$group' aria-selected='true'>
                                            <i class='bi bi-$icon me-2'></i>$groupName
                                        </a>";
                                    $first = false;
                                }
                                ?>
                                <a class="nav-link" id="v-pills-new-tab" data-bs-toggle="pill" href="#v-pills-new" role="tab" aria-controls="v-pills-new" aria-selected="false">
                                    <i class='bi bi-plus-circle me-2'></i>Add New Setting
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-9 mb-4">
                    <div class="settings-main-card">
                        <div class="card-body p-4">
                            <div class="tab-content" id="v-pills-tabContent">
                                <?php
                                $first = true;
                                foreach ($groupedSettings as $group => $settings) {
                                    $active = $first ? 'show active' : '';
                                    $icon = 'gear';
                                    
                                    // Assign specific icons based on group name
                                    if ($group == 'general') $icon = 'sliders';
                                    if ($group == 'booking') $icon = 'calendar-check';
                                    if ($group == 'payment') $icon = 'credit-card';
                                    if ($group == 'notification') $icon = 'bell';
                                    if ($group == 'security') $icon = 'shield-lock';
                                    if ($group == 'api') $icon = 'code-slash';
                                    
                                    echo "<div class='tab-pane fade $active' id='v-pills-$group' role='tabpanel' aria-labelledby='v-pills-$group-tab'>";
                                    echo "<div class='d-flex align-items-center mb-3'>";
                                    echo "<div class='icon-bg'><i class='bi bi-$icon fs-5 text-success'></i></div>";
                                    echo "<h4 class='mb-0'>" . ucfirst($group) . " Settings</h4>";
                                    echo "</div>";
                                    echo "<div class='alert alert-info d-flex align-items-center'>";
                                    echo "<i class='bi bi-info-circle me-2'></i><span>These settings control " . strtolower(ucfirst($group)) . " functionality of the application.</span>";
                                    echo "</div>";
                                    echo "<form id='form-$group'>";
                                    
                                    foreach ($settings as $setting) {
                                        $inputType = 'text';
                                        $value = htmlspecialchars($setting['setting_value']);
                                        $isPublic = $setting['is_public'] ? 'true' : 'false';
                                        $publicBadge = $setting['is_public'] ? '<span class="badge badge-public ms-2">Public</span>' : '<span class="badge badge-private ms-2">Private</span>';
                                        
                                        // Determine input type based on value or key name
                                        if (is_numeric($value) && strpos($value, '.') === false) {
                                            $inputType = 'number';
                                        } elseif ($value === '0' || $value === '1' || $setting['setting_key'] === 'enable_email_notifications' || $setting['setting_key'] === 'enable_sms_notifications' || $setting['setting_key'] === 'allow_rebooking') {
                                            $inputType = 'checkbox';
                                            $checked = $value == '1' ? 'checked' : '';
                                        } elseif (stripos($setting['setting_key'], 'password') !== false) {
                                            $inputType = 'password';
                                        } elseif (stripos($setting['setting_key'], 'email') !== false) {
                                            $inputType = 'email';
                                        } elseif (strlen($value) > 100) {
                                            $inputType = 'textarea';
                                        }
                                        
                                        echo "<div class='setting-card mb-3'>";
                                        echo "<div class='card-body p-3'>";
                                        echo "<label class='form-label fw-bold mb-2'>" . ucwords(str_replace('_', ' ', $setting['setting_key'])) . $publicBadge . "</label>";
                                        
                                        if ($inputType === 'textarea') {
                                            echo "<textarea class='form-control' name='{$setting['setting_key']}' rows='3'>$value</textarea>";
                                        } elseif ($inputType === 'checkbox') {
                                            echo "<div class='form-check form-switch'>";
                                            echo "<input class='form-check-input' type='checkbox' id='{$setting['setting_key']}' name='{$setting['setting_key']}' value='1' $checked>";
                                            echo "</div>";
                                        } else if ($setting['setting_key'] === 'diesel_price') {
                                            // Special handling for diesel price to allow decimals
                                            echo "<div class='input-group'>";
                                            echo "<span class='input-group-text'>£</span>";
                                            echo "<input type='number' step='0.01' class='form-control' name='{$setting['setting_key']}' value='$value'>";
                                            echo "</div>";
                                        } else {
                                            echo "<input type='$inputType' class='form-control' name='{$setting['setting_key']}' value='$value'>";
                                        }
                                        
                                        echo "<small class='form-text text-muted mt-2'>Setting ID: {$setting['setting_key']}</small>";
                                        echo "</div>";
                                        echo "</div>";
                                    }
                                    
                                    echo "<div class='d-flex justify-content-end mt-4'>";
                                    echo "<button type='submit' class='btn btn-primary px-4'><i class='bi bi-save me-2'></i>Save " . ucfirst($group) . " Settings</button>";
                                    echo "</div>";
                                    echo "</form>";
                                    echo "</div>";
                                    
                                    $first = false;
                                }
                                ?>
                                
                                <!-- Add New Setting Tab -->
                                <div class="tab-pane fade" id="v-pills-new" role="tabpanel" aria-labelledby="v-pills-new-tab">
                                    <div class='d-flex align-items-center mb-3'>
                                        <div class='icon-bg'><i class='bi bi-plus-circle fs-5 text-success'></i></div>
                                        <h4 class='mb-0'>Add New Setting</h4>
                                    </div>
                                    <div class="alert alert-warning d-flex align-items-center">
                                        <i class="bi bi-exclamation-triangle me-2"></i>
                                        <span>Adding new settings should be done with care. Use existing settings when possible.</span>
                                    </div>
                                    <form id="form-new-setting" class="mt-4">
                                        <div class="setting-card mb-3">
                                            <div class="card-body p-3">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Setting Key</label>
                                                    <input type="text" class="form-control" name="key" required placeholder="e.g. new_feature_enabled">
                                                    <small class="form-text text-muted">Use lowercase letters, numbers, and underscores only.</small>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Setting Value</label>
                                                    <input type="text" class="form-control" name="value" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Setting Group</label>
                                                    <select class="form-select" name="group" required>
                                                        <?php foreach (array_keys($groupedSettings) as $group): ?>
                                                            <option value="<?= $group ?>"><?= ucfirst($group) ?></option>
                                                        <?php endforeach; ?>
                                                        <option value="custom">Create New Group</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3 d-none" id="custom-group-container">
                                                    <label class="form-label fw-bold">New Group Name</label>
                                                    <input type="text" class="form-control" name="custom_group" placeholder="e.g. security">
                                                </div>
                                                <div class="mb-3 form-check">
                                                    <input type="checkbox" class="form-check-input" name="is_public" id="is_public">
                                                    <label class="form-check-label" for="is_public">Make this setting public</label>
                                                    <small class="form-text text-muted d-block">Public settings are accessible to the frontend/client-side.</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-end mt-4">
                                            <button type="submit" class="btn btn-success px-4"><i class="bi bi-plus-circle me-2"></i>Add Setting</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="../../../public/css/bootstrap/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../../../public/js/assets/sidebar.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Show/hide custom group input when 'Create New Group' is selected
            const groupSelect = document.querySelector('select[name="group"]');
            const customGroupContainer = document.getElementById('custom-group-container');
            
            if (groupSelect) {
                groupSelect.addEventListener('change', function() {
                    if (this.value === 'custom') {
                        customGroupContainer.classList.remove('d-none');
                        document.querySelector('input[name="custom_group"]').required = true;
                    } else {
                        customGroupContainer.classList.add('d-none');
                        document.querySelector('input[name="custom_group"]').required = false;
                    }
                });
            }
            
            // Handle saving settings for each group
            <?php foreach (array_keys($groupedSettings) as $group): ?>
            document.getElementById('form-<?= $group ?>').addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const form = this;
                const formData = new FormData(form);
                const settings = {};
                
                for (const [key, value] of formData.entries()) {
                    settings[key] = value;
                }
                
                // Handle checkboxes that aren't checked (they won't be in FormData)
                const checkboxes = form.querySelectorAll('input[type="checkbox"]');
                checkboxes.forEach(checkbox => {
                    if (!formData.has(checkbox.name)) {
                        settings[checkbox.name] = '0';
                    }
                });
                
                try {
                    const response = await fetch('/admin/update-settings', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({ settings }),
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: `${data.message}`,
                            timer: 2000,
                            timerProgressBar: true
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message || 'Failed to update settings'
                        });
                    }
                } catch (error) {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An unexpected error occurred'
                    });
                }
            });
            <?php endforeach; ?>
            
            // Handle adding a new setting
            document.getElementById('form-new-setting').addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                const key = formData.get('key');
                const value = formData.get('value');
                let group = formData.get('group');
                
                if (group === 'custom') {
                    group = formData.get('custom_group');
                    if (!group) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Please enter a group name'
                        });
                        return;
                    }
                }
                
                const isPublic = formData.get('is_public') ? true : false;
                
                try {
                    const response = await fetch('/admin/add-setting', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({ 
                            key, 
                            value, 
                            group, 
                            is_public: isPublic 
                        }),
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Setting added successfully',
                            confirmButtonText: 'Reload Page',
                            confirmButtonColor: '#19BC3F'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.reload();
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message || 'Failed to add setting'
                        });
                    }
                } catch (error) {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An unexpected error occurred'
                    });
                }
            });
        });
    </script>
</body>
</html> 