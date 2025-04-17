// Global chart instances
let bookingStatusChart = null;
let paymentMethodChart = null;
let monthlyTrendsChart = null;
let topDestinationsChart = null;

// Current filters state
const filters = {
    startDate: null,
    endDate: null,
    year: new Date().getFullYear(),
    page: 1,
    limit: 10
};

// Pagination state
let paginationData = {
    page: 1,
    limit: 10,
    total: 0,
    totalPages: 0
};

$(document).ready(function() {
    // Initialize date inputs with default values
    const today = new Date();
    const firstDayOfYear = new Date(today.getFullYear(), 0, 1);
    
    $('#startDate').val(formatDate(firstDayOfYear));
    $('#endDate').val(formatDate(today));
    
    filters.startDate = formatDate(firstDayOfYear);
    filters.endDate = formatDate(today);
    
    // Event listeners
    $('#applyFilters').on('click', applyFilters);
    $('#pageSize').on('change', function() {
        filters.limit = parseInt($(this).val());
        filters.page = 1;
        fetchDetailedBookingList();
    });
    $('#exportCsv').on('click', exportBookingReportToCsv);
    
    // Initial data load
    loadAllReports();
});

function applyFilters() {
    filters.startDate = $('#startDate').val();
    filters.endDate = $('#endDate').val();
    filters.year = $('#yearSelect').val();
    filters.page = 1;
    
    loadAllReports();
}

function loadAllReports() {
    fetchBookingSummary();
    fetchMonthlyBookingTrend();
    fetchTopDestinations();
    fetchPaymentMethodDistribution();
    fetchDetailedBookingList();
}

async function fetchBookingSummary() {
    try {
        console.log('Fetching booking summary with:', {
            start_date: filters.startDate,
            end_date: filters.endDate
        });
        
        const response = await fetch('/admin/reports/booking-summary', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                start_date: filters.startDate,
                end_date: filters.endDate
            })
        });
        
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        
        const data = await response.json();
        console.log('Booking summary data received:', data);
        updateSummaryCards(data);
        renderBookingStatusChart(data);
    } catch (error) {
        console.error('Error fetching booking summary:', error);
    }
}

function updateSummaryCards(data) {
    $('#totalBookings').text(data.total_bookings || 0);
    $('#totalRevenue').text(formatCurrency(data.total_revenue || 0));
    $('#outstandingBalance').text(formatCurrency(data.outstanding_balance || 0));
    $('#avgBookingValue').text(formatCurrency(data.average_booking_value || 0));
}

function renderBookingStatusChart(data) {
    const ctx = document.getElementById('bookingStatusChart').getContext('2d');
    
    // Destroy existing chart if it exists
    if (bookingStatusChart) {
        bookingStatusChart.destroy();
    }
    
    // Prepare data
    const labels = ['Confirmed', 'Pending', 'Completed', 'Canceled', 'Rejected'];
    const values = [
        data.confirmed_bookings || 0,
        data.pending_bookings || 0,
        data.completed_bookings || 0,
        data.canceled_bookings || 0,
        data.rejected_bookings || 0
    ];
    
    // Define colors
    const colors = [
        'rgba(40, 167, 69, 0.7)',  // Green for confirmed
        'rgba(255, 193, 7, 0.7)',  // Yellow for pending
        'rgba(23, 162, 184, 0.7)', // Teal for completed
        'rgba(220, 53, 69, 0.7)',  // Red for canceled
        'rgba(108, 117, 125, 0.7)' // Gray for rejected
    ];
    
    // Create chart
    bookingStatusChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: labels,
            datasets: [{
                data: values,
                backgroundColor: colors,
                borderColor: colors.map(color => color.replace('0.7', '1')),
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.raw || 0;
                            const total = values.reduce((a, b) => a + b, 0);
                            const percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                            return `${label}: ${value} (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });
}

async function fetchMonthlyBookingTrend() {
    try {
        const response = await fetch('/admin/reports/monthly-trend', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                year: filters.year
            })
        });
        
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        
        const data = await response.json();
        renderMonthlyTrendsChart(data);
    } catch (error) {
        console.error('Error fetching monthly trends:', error);
    }
}

function renderMonthlyTrendsChart(data) {
    const ctx = document.getElementById('monthlyTrendsChart').getContext('2d');
    
    // Destroy existing chart if it exists
    if (monthlyTrendsChart) {
        monthlyTrendsChart.destroy();
    }
    
    // Prepare data
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    const bookings = [];
    const revenue = [];
    
    data.forEach(item => {
        const monthIndex = item.month - 1;
        bookings[monthIndex] = item.total_bookings;
        revenue[monthIndex] = item.total_revenue;
    });
    
    // Create chart
    monthlyTrendsChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: months,
            datasets: [
                {
                    label: 'Bookings',
                    data: bookings,
                    backgroundColor: 'rgba(40, 167, 69, 0.5)',
                    borderColor: 'rgba(40, 167, 69, 1)',
                    borderWidth: 1,
                    yAxisID: 'y'
                },
                {
                    label: 'Revenue',
                    data: revenue,
                    type: 'line',
                    backgroundColor: 'rgba(0, 123, 255, 0.1)',
                    borderColor: 'rgba(0, 123, 255, 1)',
                    borderWidth: 2,
                    fill: true,
                    yAxisID: 'y1'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label === 'Revenue') {
                                return label + ': ' + formatCurrency(context.raw);
                            }
                            return label + ': ' + context.raw;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Number of Bookings'
                    },
                    position: 'left'
                },
                y1: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Revenue'
                    },
                    position: 'right',
                    grid: {
                        drawOnChartArea: false // only show grid for left y-axis
                    }
                }
            }
        }
    });
}

async function fetchTopDestinations() {
    try {
        const response = await fetch('/admin/reports/top-destinations', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                start_date: filters.startDate,
                end_date: filters.endDate,
                limit: 10
            })
        });
        
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        
        const data = await response.json();
        renderTopDestinationsChart(data);
    } catch (error) {
        console.error('Error fetching top destinations:', error);
    }
}

function renderTopDestinationsChart(data) {
    const ctx = document.getElementById('topDestinationsChart').getContext('2d');
    
    // Destroy existing chart if it exists
    if (topDestinationsChart) {
        topDestinationsChart.destroy();
    }
    
    // Prepare data
    const destinations = data.map(item => item.destination);
    const bookingCount = data.map(item => item.booking_count);
    const revenue = data.map(item => item.total_revenue);
    
    // Generate colors array
    const backgroundColors = generateColorGradient('#28a745', '#1e7e34', destinations.length);
    
    // Create chart
    topDestinationsChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: destinations,
            datasets: [
                {
                    label: 'Booking Count',
                    data: bookingCount,
                    backgroundColor: backgroundColors,
                    borderColor: backgroundColors,
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            indexAxis: 'y',
            scales: {
                x: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Number of Bookings'
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        afterLabel: function(context) {
                            const index = context.dataIndex;
                            return 'Revenue: ' + formatCurrency(revenue[index]);
                        }
                    }
                }
            }
        }
    });
}

async function fetchPaymentMethodDistribution() {
    try {
        const response = await fetch('/admin/reports/payment-methods', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                start_date: filters.startDate,
                end_date: filters.endDate
            })
        });
        
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        
        const data = await response.json();
        renderPaymentMethodChart(data);
    } catch (error) {
        console.error('Error fetching payment methods:', error);
    }
}

function renderPaymentMethodChart(data) {
    const ctx = document.getElementById('paymentMethodChart').getContext('2d');
    
    // Destroy existing chart if it exists
    if (paymentMethodChart) {
        paymentMethodChart.destroy();
    }
    
    // Prepare data
    const methods = data.map(item => item.payment_method);
    const counts = data.map(item => item.payment_count);
    
    // Define colors
    const colors = [
        'rgba(40, 167, 69, 0.7)',
        'rgba(0, 123, 255, 0.7)',
        'rgba(255, 193, 7, 0.7)',
        'rgba(108, 117, 125, 0.7)',
        'rgba(23, 162, 184, 0.7)'
    ];
    
    // Create chart
    paymentMethodChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: methods,
            datasets: [{
                data: counts,
                backgroundColor: colors.slice(0, methods.length),
                borderColor: colors.map(color => color.replace('0.7', '1')).slice(0, methods.length),
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.raw || 0;
                            const total = counts.reduce((a, b) => a + b, 0);
                            const percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                            return `${label}: ${value} (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });
}

async function fetchDetailedBookingList() {
    try {
        const response = await fetch('/admin/reports/detailed-bookings', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                start_date: filters.startDate,
                end_date: filters.endDate,
                page: filters.page,
                limit: filters.limit
            })
        });
        
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        
        const data = await response.json();
        renderBookingTable(data.bookings);
        updatePagination(data);
    } catch (error) {
        console.error('Error fetching detailed bookings:', error);
    }
}

function renderBookingTable(bookings) {
    const tableBody = $('#bookingReportTableBody');
    tableBody.empty();
    
    if (bookings && bookings.length > 0) {
        bookings.forEach(booking => {
            // Format the date
            const tourDate = new Date(booking.date_of_tour);
            const formattedDate = tourDate.toLocaleDateString();
            
            // Create status badge class
            let statusClass = 'bg-secondary';
            if (booking.status === 'Confirmed') statusClass = 'bg-success';
            if (booking.status === 'Pending') statusClass = 'bg-warning text-dark';
            if (booking.status === 'Canceled') statusClass = 'bg-danger';
            if (booking.status === 'Completed') statusClass = 'bg-info';
            if (booking.status === 'Rejected') statusClass = 'bg-dark';
            
            // Create payment status badge class
            let paymentStatusClass = 'bg-secondary';
            if (booking.payment_status === 'Paid') paymentStatusClass = 'bg-success';
            if (booking.payment_status === 'Partially Paid') paymentStatusClass = 'bg-warning text-dark';
            if (booking.payment_status === 'Unpaid') paymentStatusClass = 'bg-danger';
            
            tableBody.append(`
                <tr>
                    <td>${booking.client_name}</td>
                    <td>${booking.destination}</td>
                    <td>${formattedDate}</td>
                    <td>${formatCurrency(booking.total_cost)}</td>
                    <td><span class="badge ${statusClass}">${booking.status}</span></td>
                    <td><span class="badge ${paymentStatusClass}">${booking.payment_status}</span></td>
                </tr>
            `);
        });
    } else {
        tableBody.append(`
            <tr>
                <td colspan="6" class="text-center">No bookings found</td>
            </tr>
        `);
    }
}

function updatePagination(data) {
    paginationData = {
        page: data.page,
        limit: data.limit,
        total: data.total,
        totalPages: data.total_pages
    };
    
    renderPagination();
}

function renderPagination() {
    const container = $('#paginationControls');
    container.empty();
    
    if (paginationData.totalPages <= 1) {
        return;
    }
    
    let html = '<nav><ul class="pagination pagination-sm">';
    
    // Previous button
    html += `
        <li class="page-item ${paginationData.page === 1 ? 'disabled' : ''}">
            <a class="page-link" href="#" data-page="${paginationData.page - 1}">Previous</a>
        </li>
    `;
    
    // Page numbers
    const startPage = Math.max(1, paginationData.page - 2);
    const endPage = Math.min(paginationData.totalPages, paginationData.page + 2);
    
    for (let i = startPage; i <= endPage; i++) {
        html += `
            <li class="page-item ${i === paginationData.page ? 'active' : ''}">
                <a class="page-link" href="#" data-page="${i}">${i}</a>
            </li>
        `;
    }
    
    // Next button
    html += `
        <li class="page-item ${paginationData.page === paginationData.totalPages ? 'disabled' : ''}">
            <a class="page-link" href="#" data-page="${paginationData.page + 1}">Next</a>
        </li>
    `;
    
    html += '</ul></nav>';
    
    container.html(html);
    
    // Add event listeners to pagination links
    $('.page-link').on('click', function(e) {
        e.preventDefault();
        const page = parseInt($(this).data('page'));
        
        if (page > 0 && page <= paginationData.totalPages) {
            filters.page = page;
            fetchDetailedBookingList();
        }
    });
}

async function exportBookingReportToCsv() {
    try {
        // Show loading state
        const originalText = $('#exportCsv').text();
        $('#exportCsv').text('Exporting...').prop('disabled', true);
        
        const response = await fetch('/admin/reports/export-bookings', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                start_date: filters.startDate,
                end_date: filters.endDate
            })
        });
        
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        
        const data = await response.json();
        
        if (data && data.bookings && data.bookings.length > 0) {
            generateCsvDownload(data.bookings);
        } else {
            alert('No data to export.');
        }
        
        $('#exportCsv').text(originalText).prop('disabled', false);
    } catch (error) {
        console.error('Error exporting bookings:', error);
        $('#exportCsv').text(originalText).prop('disabled', false);
        alert('Error exporting data. Please try again.');
    }
}

function generateCsvDownload(bookings) {
    // CSV header
    let csv = 'Client Name,Contact Number,Destination,Pickup Point,Date of Tour,End of Tour,Days,Buses,Status,Payment Status,Total Cost,Balance\n';
    
    // Add rows
    bookings.forEach(booking => {
        const row = [
            `"${booking.client_name}"`,
            `"${booking.contact_number}"`,
            `"${booking.destination}"`,
            `"${booking.pickup_point}"`,
            `"${booking.date_of_tour}"`,
            `"${booking.end_of_tour}"`,
            booking.number_of_days,
            booking.number_of_buses,
            `"${booking.status}"`,
            `"${booking.payment_status}"`,
            booking.total_cost,
            booking.balance
        ];
        
        csv += row.join(',') + '\n';
    });
    
    // Create download link
    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    const url = URL.createObjectURL(blob);
    const link = document.createElement('a');
    
    link.setAttribute('href', url);
    link.setAttribute('download', `booking_report_${formatDateForFilename(new Date())}.csv`);
    link.style.visibility = 'hidden';
    
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

// Utility functions
function formatDate(date) {
    const d = new Date(date);
    let month = '' + (d.getMonth() + 1);
    let day = '' + d.getDate();
    const year = d.getFullYear();
    
    if (month.length < 2) month = '0' + month;
    if (day.length < 2) day = '0' + day;
    
    return [year, month, day].join('-');
}

function formatDateForFilename(date) {
    const d = new Date(date);
    let month = '' + (d.getMonth() + 1);
    let day = '' + d.getDate();
    const year = d.getFullYear();
    
    if (month.length < 2) month = '0' + month;
    if (day.length < 2) day = '0' + day;
    
    return [year, month, day].join('');
}

function formatCurrency(amount) {
    return '₱ ' + parseFloat(amount).toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

function generateColorGradient(startColor, endColor, steps) {
    // Convert hex to RGB
    const startRGB = hexToRgb(startColor);
    const endRGB = hexToRgb(endColor);
    
    // Calculate step size for each RGB component
    const stepR = (endRGB.r - startRGB.r) / (steps - 1);
    const stepG = (endRGB.g - startRGB.g) / (steps - 1);
    const stepB = (endRGB.b - startRGB.b) / (steps - 1);
    
    // Generate colors
    const colors = [];
    for (let i = 0; i < steps; i++) {
        const r = Math.round(startRGB.r + stepR * i);
        const g = Math.round(startRGB.g + stepG * i);
        const b = Math.round(startRGB.b + stepB * i);
        colors.push(`rgba(${r}, ${g}, ${b}, 0.7)`);
    }
    
    return colors;
}

function hexToRgb(hex) {
    // Remove # if present
    hex = hex.replace('#', '');
    
    // Parse the hex values
    const r = parseInt(hex.substring(0, 2), 16);
    const g = parseInt(hex.substring(2, 4), 16);
    const b = parseInt(hex.substring(4, 6), 16);
    
    return { r, g, b };
}

// Test direct fetch without jQuery
fetch('/admin/reports/booking-summary')
  .then(response => response.text())
  .then(text => {
    console.log('Direct fetch response:', text);
    try {
      const data = JSON.parse(text);
      console.log('Parsed data:', data);
    } catch (e) {
      console.error('Parse error:', e);
    }
  })
  .catch(err => console.error('Fetch error:', err)); 