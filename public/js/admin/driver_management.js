document.addEventListener('DOMContentLoaded', function() {
    // Initialize modals
    const driverModal = new bootstrap.Modal(document.getElementById('driverModal'));
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteDriverModal'));
    
    // Make functions globally accessible
    window.driverModal = driverModal;
    window.deleteModal = deleteModal;
    window.editDriver = editDriver;
    window.confirmDelete = confirmDelete;
    
    // Load initial data
    loadDrivers();
    loadDriverStatistics();
    loadMostActiveDrivers();
    loadExpiringLicenses();
    
    // Event listeners
    document.getElementById('refreshDriversBtn').addEventListener('click', loadDrivers);
    document.getElementById('addDriverBtn').addEventListener('click', showAddDriverModal);
    document.getElementById('saveDriverBtn').addEventListener('click', saveDriver);
    document.getElementById('confirmDeleteBtn').addEventListener('click', deleteDriver);
    
    // Photo preview
    document.getElementById('profile_photo').addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('photoPreview').src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    });
    
    /**
     * Load all drivers
     */
    function loadDrivers() {
        fetch('/admin/api/drivers/all')
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json();
            })
            .then(response => {
                if (response.success) {
                    renderDriversTable(response.data);
                } else {
                    showAlert('error', 'Error', response.message || 'Failed to load drivers');
                }
            })
            .catch((error) => {
                console.error('Error loading drivers:', error);
                showAlert('error', 'Error', 'Failed to connect to the server');
            });
    }
    
    /**
     * Load driver statistics
     */
    function loadDriverStatistics() {
        fetch('/admin/api/drivers/statistics')
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json();
            })
            .then(response => {
                if (response.success) {
                    updateStatistics(response.data);
                }
            })
            .catch(error => console.error('Error loading statistics:', error));
    }
    
    /**
     * Load most active drivers
     */
    function loadMostActiveDrivers() {
        fetch('/admin/api/drivers/most-active')
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json();
            })
            .then(response => {
                if (response.success) {
                    renderMostActiveDrivers(response.data);
                } else {
                    document.getElementById('mostActiveDriversList').innerHTML = '<li class="list-group-item text-center">No data available</li>';
                }
            })
            .catch((error) => {
                console.error('Error loading most active drivers:', error);
                document.getElementById('mostActiveDriversList').innerHTML = '<li class="list-group-item text-center">Failed to load data</li>';
            });
    }
    
    /**
     * Load drivers with expiring licenses
     */
    function loadExpiringLicenses() {
        fetch('/admin/api/drivers/expiring-licenses')
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json();
            })
            .then(response => {
                if (response.success) {
                    renderExpiringLicenses(response.data);
                } else {
                    document.getElementById('expiringLicensesList').innerHTML = '<li class="list-group-item text-center">No expiring licenses</li>';
                }
            })
            .catch((error) => {
                console.error('Error loading expiring licenses:', error);
                document.getElementById('expiringLicensesList').innerHTML = '<li class="list-group-item text-center">Failed to load data</li>';
            });
    }
    
    /**
     * Render drivers table
     */
    function renderDriversTable(drivers) {
        const tableBody = document.getElementById('driverTableBody');
        
        if (!drivers || drivers.length === 0) {
            tableBody.innerHTML = '<tr><td colspan="8" class="text-center py-3">No drivers found</td></tr>';
            return;
        }
        
        let html = '';
        drivers.forEach(driver => {
            // Format license expiry date
            let expiryDate = driver.license_expiry ? new Date(driver.license_expiry) : null;
            let expiryFormatted = expiryDate ? expiryDate.toLocaleDateString() : 'N/A';
            
            // Check if license is expiring soon (within 30 days)
            let isExpiringSoon = false;
            if (expiryDate) {
                const today = new Date();
                const daysUntilExpiry = Math.ceil((expiryDate - today) / (1000 * 60 * 60 * 24));
                isExpiringSoon = daysUntilExpiry <= 30 && daysUntilExpiry >= 0;
            }
            
            // Status badge class
            let statusClass = '';
            switch (driver.status) {
                case 'Active':
                    statusClass = 'status-active';
                    break;
                case 'Inactive':
                    statusClass = 'status-inactive';
                    break;
                case 'On Leave':
                    statusClass = 'status-on-leave';
                    break;
            }
            
            // Availability badge class
            let availabilityClass = '';
            switch (driver.availability) {
                case 'Available':
                    availabilityClass = 'availability-available';
                    break;
                case 'Assigned':
                    availabilityClass = 'availability-assigned';
                    break;
            }
            
            html += `
                <tr>
                    <td>
                        ${driver.profile_photo 
                            ? `<img src="${driver.profile_photo}" alt="${driver.full_name}" class="driver-avatar">` 
                            : `<div class="driver-avatar-placeholder"><i class="bi bi-person"></i></div>`
                        }
                    </td>
                    <td>${driver.full_name}</td>
                    <td>${driver.license_number}</td>
                    <td>${driver.contact_number || 'N/A'}</td>
                    <td class="${isExpiringSoon ? 'license-expiring' : ''}">${expiryFormatted}</td>
                    <td><span class="status-badge ${statusClass}">${driver.status}</span></td>
                    <td><span class="availability-badge ${availabilityClass}">${driver.availability}</span></td>
                    <td>
                        <div class="actions-compact">
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="editDriver(${driver.driver_id})">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="confirmDelete(${driver.driver_id})">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        });
        
        tableBody.innerHTML = html;
    }
    
    /**
     * Update statistics
     */
    function updateStatistics(stats) {
        document.getElementById('totalDriversCount').textContent = stats.total || 0;
        document.getElementById('activeDriversCount').textContent = stats.active || 0;
        document.getElementById('inactiveDriversCount').textContent = stats.inactive || 0;
        document.getElementById('onLeaveDriversCount').textContent = stats.on_leave || 0;
        document.getElementById('availableDriversCount').textContent = stats.available || 0;
        document.getElementById('assignedDriversCount').textContent = stats.assigned || 0;
    }
    
    /**
     * Render most active drivers
     */
    function renderMostActiveDrivers(drivers) {
        const listElement = document.getElementById('mostActiveDriversList');
        
        if (!drivers || drivers.length === 0) {
            listElement.innerHTML = '<li class="list-group-item text-center">No data available</li>';
            return;
        }
        
        let html = '';
        drivers.forEach(driver => {
            html += `
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span>${driver.full_name}</span>
                    <span class="badge bg-success rounded-pill">${driver.trip_count} trips</span>
                </li>
            `;
        });
        
        listElement.innerHTML = html;
    }
    
    /**
     * Render expiring licenses
     */
    function renderExpiringLicenses(drivers) {
        const listElement = document.getElementById('expiringLicensesList');
        
        if (!drivers || drivers.length === 0) {
            listElement.innerHTML = '<li class="list-group-item text-center">No expiring licenses</li>';
            return;
        }
        
        let html = '';
        drivers.forEach(driver => {
            const expiryDate = new Date(driver.license_expiry);
            const today = new Date();
            const daysUntilExpiry = Math.ceil((expiryDate - today) / (1000 * 60 * 60 * 24));
            
            html += `
                <li class="list-group-item">
                    <div class="d-flex justify-content-between">
                        <span>${driver.full_name}</span>
                        <span class="license-expiring">${daysUntilExpiry} days</span>
                    </div>
                    <small class="text-muted">Expires: ${expiryDate.toLocaleDateString()}</small>
                </li>
            `;
        });
        
        listElement.innerHTML = html;
    }
    
    /**
     * Show add driver modal
     */
    function showAddDriverModal() {
        // Reset form
        document.getElementById('driverForm').reset();
        document.getElementById('driver_id').value = '';
        document.getElementById('photoPreview').src = '/public/images/icons/user-placeholder.png';
        document.getElementById('driverModalLabel').textContent = 'Add New Driver';
        
        // Set default values
        document.getElementById('status').value = 'Active';
        document.getElementById('availability').value = 'Available';
        document.getElementById('date_hired').value = new Date().toISOString().split('T')[0];
        
        driverModal.show();
    }
    
    /**
     * Edit driver
     */
    function editDriver(driverId) {
        console.log('Editing driver with ID:', driverId);
        
        // Show loading state
        document.getElementById('driverModalLabel').textContent = 'Loading driver data...';
        driverModal.show();
        
        fetch(`/admin/api/drivers/get?id=${driverId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json();
            })
            .then(response => {
                if (response.success) {
                    const driver = response.data;
                    console.log('Driver data:', driver);
                    
                    // Populate form
                    document.getElementById('driver_id').value = driver.driver_id;
                    document.getElementById('full_name').value = driver.full_name;
                    document.getElementById('license_number').value = driver.license_number;
                    document.getElementById('contact_number').value = driver.contact_number || '';
                    document.getElementById('address').value = driver.address || '';
                    document.getElementById('status').value = driver.status || 'Active';
                    document.getElementById('availability').value = driver.availability || 'Available';
                    
                    // Handle dates - ensure they're in the correct format
                    if (driver.date_hired) {
                        document.getElementById('date_hired').value = formatDateForInput(driver.date_hired);
                    }
                    
                    if (driver.license_expiry) {
                        document.getElementById('license_expiry').value = formatDateForInput(driver.license_expiry);
                    }
                    
                    document.getElementById('notes').value = driver.notes || '';
                    
                    // Set photo preview
                    if (driver.profile_photo) {
                        document.getElementById('photoPreview').src = driver.profile_photo;
                    } else {
                        document.getElementById('photoPreview').src = '/public/images/icons/user-placeholder.png';
                    }
                    
                    document.getElementById('driverModalLabel').textContent = 'Edit Driver';
                } else {
                    driverModal.hide();
                    showAlert('error', 'Error', response.message || 'Failed to load driver details');
                }
            })
            .catch((error) => {
                console.error('Error fetching driver:', error);
                driverModal.hide();
                showAlert('error', 'Error', 'Failed to connect to the server. Please check the console for details.');
            });
    }
    
    /**
     * Format date for input field (YYYY-MM-DD)
     */
    function formatDateForInput(dateString) {
        const date = new Date(dateString);
        if (isNaN(date.getTime())) {
            return '';
        }
        return date.toISOString().split('T')[0];
    }
    
    /**
     * Save driver (add or update)
     */
    function saveDriver() {
        // Validate form
        if (!validateDriverForm()) {
            return;
        }
        
        const driverId = document.getElementById('driver_id').value;
        const isNewDriver = !driverId;
        const formData = new FormData(document.getElementById('driverForm'));
        
        fetch(isNewDriver ? '/admin/api/drivers/add' : '/admin/api/drivers/update', {
            method: 'POST',
            body: formData
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json();
            })
            .then(response => {
                if (response.success) {
                    driverModal.hide();
                    showAlert('success', 'Success', isNewDriver ? 'Driver added successfully' : 'Driver updated successfully');
                    loadDrivers();
                    loadDriverStatistics();
                    loadMostActiveDrivers();
                    loadExpiringLicenses();
                } else {
                    showAlert('error', 'Error', response.message || 'Failed to save driver');
                }
            })
            .catch((error) => {
                console.error('Error saving driver:', error);
                showAlert('error', 'Error', 'Failed to connect to the server');
            });
    }
    
    /**
     * Confirm delete driver
     */
    function confirmDelete(driverId) {
        document.getElementById('delete_driver_id').value = driverId;
        deleteModal.show();
    }
    
    /**
     * Delete driver
     */
    function deleteDriver() {
        const driverId = document.getElementById('delete_driver_id').value;
        const formData = new FormData();
        formData.append('driver_id', driverId);
        
        fetch('/admin/api/drivers/delete', {
            method: 'POST',
            body: formData
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json();
            })
            .then(response => {
                deleteModal.hide();
                
                if (response.success) {
                    showAlert('success', 'Success', 'Driver deleted successfully');
                    loadDrivers();
                    loadDriverStatistics();
                    loadMostActiveDrivers();
                    loadExpiringLicenses();
                } else {
                    showAlert('error', 'Error', response.message || 'Failed to delete driver');
                }
            })
            .catch((error) => {
                console.error('Error deleting driver:', error);
                deleteModal.hide();
                showAlert('error', 'Error', 'Failed to connect to the server');
            });
    }
    
    /**
     * Validate driver form
     */
    function validateDriverForm() {
        const fullName = document.getElementById('full_name').value.trim();
        const licenseNumber = document.getElementById('license_number').value.trim();
        const contactNumber = document.getElementById('contact_number').value.trim();
        
        if (!fullName) {
            showAlert('error', 'Validation Error', 'Full name is required');
            return false;
        }
        
        if (!licenseNumber) {
            showAlert('error', 'Validation Error', 'License number is required');
            return false;
        }
        
        if (!contactNumber) {
            showAlert('error', 'Validation Error', 'Contact number is required');
            return false;
        }
        
        return true;
    }
    
    /**
     * Show alert
     */
    function showAlert(icon, title, text) {
        Swal.fire({
            icon: icon,
            title: title,
            text: text,
            timer: 3000,
            timerProgressBar: true
        });
    }
});
