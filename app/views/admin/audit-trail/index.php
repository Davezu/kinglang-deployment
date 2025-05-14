<?php
$pageTitle = "Audit Trail Management";
// require_once __DIR__ . '/../../assets/header.php';
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Audit Trail Management</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
                        <li class="breadcrumb-item active">Audit Trail</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Audit Trail Records</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Filter Form -->
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <div class="card card-info">
                                        <div class="card-header">
                                            <h3 class="card-title">Filters</h3>
                                            <div class="card-tools">
                                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <form id="auditFilterForm">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="actionFilter">Action</label>
                                                            <select class="form-control" id="actionFilter" name="action">
                                                                <option value="">All Actions</option>
                                                                <option value="create">Create</option>
                                                                <option value="update">Update</option>
                                                                <option value="delete">Delete</option>
                                                                <option value="login">Login</option>
                                                                <option value="logout">Logout</option>
                                                                <option value="view">View</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="entityTypeFilter">Entity Type</label>
                                                            <select class="form-control" id="entityTypeFilter" name="entity_type">
                                                                <option value="">All Entities</option>
                                                                <option value="booking">Booking</option>
                                                                <option value="payment">Payment</option>
                                                                <option value="user">User</option>
                                                                <option value="bus">Bus</option>
                                                                <option value="setting">Setting</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="dateFromFilter">Date From</label>
                                                            <input type="date" class="form-control" id="dateFromFilter" name="date_from">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="dateToFilter">Date To</label>
                                                            <input type="date" class="form-control" id="dateToFilter" name="date_to">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="entityIdFilter">Entity ID</label>
                                                            <input type="number" class="form-control" id="entityIdFilter" name="entity_id" placeholder="Enter entity ID">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="userIdFilter">User ID</label>
                                                            <input type="number" class="form-control" id="userIdFilter" name="user_id" placeholder="Enter user ID">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 d-flex align-items-end">
                                                        <div class="form-group">
                                                            <button type="submit" class="btn btn-primary mr-2">Apply Filters</button>
                                                            <button type="button" id="resetFilters" class="btn btn-secondary mr-2">Reset</button>
                                                            <button type="button" id="exportAuditTrails" class="btn btn-success">
                                                                <i class="fas fa-file-export"></i> Export CSV
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Audit Trail Table -->
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped" id="auditTrailTable">
                                    <thead>
                                        <tr>
                                            <th width="5%">ID</th>
                                            <th width="10%">User</th>
                                            <th width="10%">Role</th>
                                            <th width="10%">Action</th>
                                            <th width="15%">Entity</th>
                                            <th width="10%">Entity ID</th>
                                            <th width="15%">Date/Time</th>
                                            <th width="10%">IP Address</th>
                                            <th width="15%">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Data will be loaded dynamically via AJAX -->
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination Container -->
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <div id="pageInfo" class="dataTables_info" role="status" aria-live="polite">
                                        Showing 0 to 0 of 0 entries
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div id="paginationContainer" class="dataTables_paginate paging_simple_numbers float-right">
                                        <ul class="pagination">
                                            <!-- Pagination links will be dynamically generated -->
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Audit Details Modal -->
<div class="modal fade" id="auditDetailsModal" tabindex="-1" role="dialog" aria-labelledby="auditDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="auditDetailsModalLabel">Audit Record Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Audit ID:</label>
                            <p id="auditDetailId" class="form-control-static"></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Date/Time:</label>
                            <p id="auditDetailDateTime" class="form-control-static"></p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>User:</label>
                            <p id="auditDetailUser" class="form-control-static"></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Role:</label>
                            <p id="auditDetailRole" class="form-control-static"></p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Action:</label>
                            <p id="auditDetailAction" class="form-control-static"></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Entity:</label>
                            <p id="auditDetailEntity" class="form-control-static"></p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>IP Address:</label>
                            <p id="auditDetailIP" class="form-control-static"></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>User Agent:</label>
                            <p id="auditDetailUserAgent" class="form-control-static"></p>
                        </div>
                    </div>
                </div>
                
                <div class="row mt-3">
                    <div class="col-md-12">
                        <h5>Changed Values</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered" id="changesTable">
                                <thead>
                                    <tr>
                                        <th width="30%">Field</th>
                                        <th width="35%">Old Value</th>
                                        <th width="35%">New Value</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Changes will be dynamically added here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Entity History Modal -->
<div class="modal fade" id="entityHistoryModal" tabindex="-1" role="dialog" aria-labelledby="entityHistoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="entityHistoryModalLabel">Entity History</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="entityHistoryTable">
                        <thead>
                            <tr>
                                <th width="5%">ID</th>
                                <th width="15%">User</th>
                                <th width="10%">Role</th>
                                <th width="10%">Action</th>
                                <th width="15%">Date/Time</th>
                                <th width="10%">IP Address</th>
                                <th width="20%">Changes</th>
                                <th width="15%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- History will be dynamically added here -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Global variables
    let currentPage = 1;
    const perPage = 20;
    let totalPages = 0;
    let currentFilters = {};
    
    // Initial load of audit trails
    loadAuditTrails();
    
    // Filter form submission
    $("#auditFilterForm").on("submit", function(e) {
        e.preventDefault();
        currentPage = 1; // Reset to first page when filtering
        currentFilters = $(this).serializeArray().reduce(function(obj, item) {
            if (item.value) {
                obj[item.name] = item.value;
            }
            return obj;
        }, {});
        
        loadAuditTrails();
    });
    
    // Reset filters
    $("#resetFilters").on("click", function() {
        $("#auditFilterForm")[0].reset();
        currentPage = 1;
        currentFilters = {};
        loadAuditTrails();
    });
    
    // Export audit trails
    $("#exportAuditTrails").on("click", function() {
        // Build query string from current filters
        const queryParams = new URLSearchParams();
        for (const key in currentFilters) {
            queryParams.append(key, currentFilters[key]);
        }
        
        // Redirect to export endpoint
        window.location.href = '/admin/export-audit-trails?' + queryParams.toString();
    });
    
    // Pagination click handler
    $(document).on("click", ".page-link", function(e) {
        e.preventDefault();
        if ($(this).hasClass('disabled')) {
            return;
        }
        
        if ($(this).data('page') === 'prev') {
            if (currentPage > 1) {
                currentPage--;
            }
        } else if ($(this).data('page') === 'next') {
            if (currentPage < totalPages) {
                currentPage++;
            }
        } else {
            currentPage = parseInt($(this).data('page'));
        }
        
        loadAuditTrails();
    });
    
    // Load audit trails with pagination and filters
    function loadAuditTrails() {
        // Show loading indicator
        $("#auditTrailTable tbody").html('<tr><td colspan="9" class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading...</td></tr>');
        
        // Prepare data to send
        const data = {
            page: currentPage,
            per_page: perPage,
            ...currentFilters
        };
        
        // AJAX request
        $.ajax({
            url: "/admin/get-audit-trails",
            method: "POST",
            data: data,
            dataType: "json",
            success: function(response) {
                // Update table with records
                displayAuditTrails(response.records);
                
                // Update pagination
                totalPages = Math.ceil(response.total / response.per_page);
                updatePagination(response.page, totalPages, response.total, response.per_page);
            },
            error: function() {
                $("#auditTrailTable tbody").html('<tr><td colspan="9" class="text-center text-danger">Error loading data. Please try again.</td></tr>');
            }
        });
    }
    
    // Display audit trails in the table
    function displayAuditTrails(records) {
        const tbody = $("#auditTrailTable tbody");
        tbody.empty();
        
        if (records.length === 0) {
            tbody.html('<tr><td colspan="9" class="text-center">No records found</td></tr>');
            return;
        }
        
        for (const record of records) {
            const row = $("<tr>");
            row.append(`<td>${record.audit_id}</td>`);
            row.append(`<td>${record.username || 'Unknown'}</td>`);
            row.append(`<td>${record.user_role || 'N/A'}</td>`);
            row.append(`<td>${formatAction(record.action)}</td>`);
            row.append(`<td>${formatEntityType(record.entity_type)}</td>`);
            row.append(`<td>${record.entity_id || 'N/A'}</td>`);
            row.append(`<td>${record.created_at_formatted}</td>`);
            row.append(`<td>${record.ip_address}</td>`);
            
            // Action buttons
            const actionsCell = $("<td>");
            
            // View details button
            const viewBtn = $(`<button class="btn btn-info btn-sm mr-1 view-audit-details" data-id="${record.audit_id}">
                <i class="fas fa-eye"></i>
            </button>`);
            
            // Entity history button
            const historyBtn = $(`<button class="btn btn-secondary btn-sm view-entity-history" 
                data-entity-type="${record.entity_type}" 
                data-entity-id="${record.entity_id}">
                <i class="fas fa-history"></i>
            </button>`);
            
            actionsCell.append(viewBtn);
            actionsCell.append(historyBtn);
            row.append(actionsCell);
            
            tbody.append(row);
        }
    }
    
    // Update pagination controls
    function updatePagination(currentPage, totalPages, totalRecords, perPage) {
        const pagination = $("#paginationContainer .pagination");
        pagination.empty();
        
        // Show record info
        const start = (currentPage - 1) * perPage + 1;
        const end = Math.min(currentPage * perPage, totalRecords);
        $("#pageInfo").text(`Showing ${start} to ${end} of ${totalRecords} entries`);
        
        // Previous button
        const prevLi = $('<li class="page-item">');
        const prevLink = $('<a class="page-link" href="#" data-page="prev">Previous</a>');
        if (currentPage === 1) {
            prevLi.addClass('disabled');
        }
        prevLi.append(prevLink);
        pagination.append(prevLi);
        
        // Page numbers
        const maxPages = 5; // Maximum number of page links to show
        let startPage = Math.max(1, currentPage - Math.floor(maxPages / 2));
        let endPage = Math.min(totalPages, startPage + maxPages - 1);
        
        if (endPage - startPage + 1 < maxPages) {
            startPage = Math.max(1, endPage - maxPages + 1);
        }
        
        for (let i = startPage; i <= endPage; i++) {
            const pageLi = $('<li class="page-item">');
            if (i === currentPage) {
                pageLi.addClass('active');
            }
            const pageLink = $(`<a class="page-link" href="#" data-page="${i}">${i}</a>`);
            pageLi.append(pageLink);
            pagination.append(pageLi);
        }
        
        // Next button
        const nextLi = $('<li class="page-item">');
        const nextLink = $('<a class="page-link" href="#" data-page="next">Next</a>');
        if (currentPage === totalPages || totalPages === 0) {
            nextLi.addClass('disabled');
        }
        nextLi.append(nextLink);
        pagination.append(nextLi);
    }
    
    // View audit details
    $(document).on("click", ".view-audit-details", function() {
        const auditId = $(this).data("id");
        
        // AJAX request to get audit details
        $.ajax({
            url: "/admin/get-audit-details",
            method: "POST",
            data: { audit_id: auditId },
            dataType: "json",
            success: function(response) {
                if (response.error) {
                    alert(response.error);
                    return;
                }
                
                // Fill modal with data
                $("#auditDetailId").text(response.audit_id);
                $("#auditDetailDateTime").text(response.created_at_formatted);
                $("#auditDetailUser").text(response.username || 'Unknown');
                $("#auditDetailRole").text(response.user_role || 'N/A');
                $("#auditDetailAction").text(formatAction(response.action));
                $("#auditDetailEntity").text(`${formatEntityType(response.entity_type)} (ID: ${response.entity_id || 'N/A'})`);
                $("#auditDetailIP").text(response.ip_address);
                $("#auditDetailUserAgent").text(response.user_agent || 'N/A');
                
                // Display changes if available
                const changesTable = $("#changesTable tbody");
                changesTable.empty();
                
                let hasChanges = false;
                
                // For create actions, show new values
                if (response.action === 'create' && response.new_values) {
                    for (const key in response.new_values) {
                        const row = $("<tr>");
                        row.append(`<td>${formatFieldName(key)}</td>`);
                        row.append(`<td>N/A (new record)</td>`);
                        row.append(`<td>${formatValue(response.new_values[key])}</td>`);
                        changesTable.append(row);
                        hasChanges = true;
                    }
                }
                // For delete actions, show old values
                else if (response.action === 'delete' && response.old_values) {
                    for (const key in response.old_values) {
                        const row = $("<tr>");
                        row.append(`<td>${formatFieldName(key)}</td>`);
                        row.append(`<td>${formatValue(response.old_values[key])}</td>`);
                        row.append(`<td>N/A (deleted)</td>`);
                        changesTable.append(row);
                        hasChanges = true;
                    }
                }
                // For update actions, compare old and new values
                else if (response.action === 'update' && response.old_values && response.new_values) {
                    for (const key in response.new_values) {
                        if (JSON.stringify(response.old_values[key]) === JSON.stringify(response.new_values[key])) {
                            continue; // Skip unchanged values
                        }
                        
                        const row = $("<tr>");
                        row.append(`<td>${formatFieldName(key)}</td>`);
                        row.append(`<td>${formatValue(response.old_values[key])}</td>`);
                        row.append(`<td>${formatValue(response.new_values[key])}</td>`);
                        changesTable.append(row);
                        hasChanges = true;
                    }
                }
                
                if (!hasChanges) {
                    changesTable.html('<tr><td colspan="3" class="text-center">No changes recorded</td></tr>');
                }
                
                // Show the modal
                $("#auditDetailsModal").modal("show");
            },
            error: function() {
                alert("Error fetching audit details. Please try again.");
            }
        });
    });
    
    // View entity history
    $(document).on("click", ".view-entity-history", function() {
        const entityType = $(this).data("entity-type");
        const entityId = $(this).data("entity-id");
        
        if (!entityType || !entityId) {
            alert("Entity information is missing");
            return;
        }
        
        // Update modal title
        $("#entityHistoryModalLabel").text(`History for ${formatEntityType(entityType)} #${entityId}`);
        
        // AJAX request to get entity history
        $.ajax({
            url: "/admin/get-entity-history",
            method: "POST",
            data: { 
                entity_type: entityType,
                entity_id: entityId
            },
            dataType: "json",
            success: function(response) {
                // Display entity history
                const tbody = $("#entityHistoryTable tbody");
                tbody.empty();
                
                if (response.length === 0) {
                    tbody.html('<tr><td colspan="8" class="text-center">No history records found</td></tr>');
                    return;
                }
                
                for (const record of response) {
                    const row = $("<tr>");
                    row.append(`<td>${record.audit_id}</td>`);
                    row.append(`<td>${record.username || 'Unknown'}</td>`);
                    row.append(`<td>${record.user_role || 'N/A'}</td>`);
                    row.append(`<td>${formatAction(record.action)}</td>`);
                    row.append(`<td>${record.created_at_formatted}</td>`);
                    row.append(`<td>${record.ip_address}</td>`);
                    
                    // Summarize changes
                    const changesSummary = generateChangesSummary(record);
                    row.append(`<td>${changesSummary}</td>`);
                    
                    // View details button
                    const actionsCell = $("<td>");
                    const viewBtn = $(`<button class="btn btn-info btn-sm view-audit-details" data-id="${record.audit_id}">
                        <i class="fas fa-eye"></i> Details
                    </button>`);
                    actionsCell.append(viewBtn);
                    row.append(actionsCell);
                    
                    tbody.append(row);
                }
                
                // Show the modal
                $("#entityHistoryModal").modal("show");
            },
            error: function() {
                alert("Error fetching entity history. Please try again.");
            }
        });
    });
    
    // Helper functions
    function formatAction(action) {
        if (!action) return 'Unknown';
        
        // Capitalize first letter
        return action.charAt(0).toUpperCase() + action.slice(1);
    }
    
    function formatEntityType(entityType) {
        if (!entityType) return 'Unknown';
        
        // Capitalize first letter
        return entityType.charAt(0).toUpperCase() + entityType.slice(1);
    }
    
    function formatFieldName(fieldName) {
        if (!fieldName) return '';
        
        // Convert snake_case to Title Case
        return fieldName
            .split('_')
            .map(word => word.charAt(0).toUpperCase() + word.slice(1))
            .join(' ');
    }
    
    function formatValue(value) {
        if (value === null || value === undefined) {
            return '<em>NULL</em>';
        }
        
        if (value === '') {
            return '<em>Empty</em>';
        }
        
        if (typeof value === 'object') {
            return '<pre>' + JSON.stringify(value, null, 2) + '</pre>';
        }
        
        if (typeof value === 'boolean') {
            return value ? 'Yes' : 'No';
        }
        
        return value.toString();
    }
    
    function generateChangesSummary(record) {
        let summary = '';
        let count = 0;
        
        // For create actions
        if (record.action === 'create' && record.new_values) {
            const fields = Object.keys(record.new_values);
            count = fields.length;
            summary = `Created with ${count} field${count !== 1 ? 's' : ''}`;
        }
        // For delete actions
        else if (record.action === 'delete' && record.old_values) {
            const fields = Object.keys(record.old_values);
            count = fields.length;
            summary = `Deleted record with ${count} field${count !== 1 ? 's' : ''}`;
        }
        // For update actions
        else if (record.action === 'update' && record.old_values && record.new_values) {
            const changedFields = [];
            
            for (const key in record.new_values) {
                if (!record.old_values[key] || JSON.stringify(record.old_values[key]) !== JSON.stringify(record.new_values[key])) {
                    changedFields.push(formatFieldName(key));
                    count++;
                }
            }
            
            if (count > 0) {
                if (count <= 3) {
                    summary = `Changed: ${changedFields.join(', ')}`;
                } else {
                    summary = `Changed ${count} fields including: ${changedFields.slice(0, 3).join(', ')}...`;
                }
            } else {
                summary = 'No changes detected';
            }
        }
        // For other actions
        else {
            summary = 'No detailed information available';
        }
        
        return summary;
    }
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?> 