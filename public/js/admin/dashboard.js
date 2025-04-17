$(renderSummaryMetrics);
$(document).ready(function() {
    renderCharts();
});

async function renderCharts() {
    try {
        await renderSummaryMetrics();
        await renderPaymentMethodChart();
        await renderMonthlyTrendsChart();
        await renderTopDestinationsChart();
        await renderBookingStatusChart();
        await renderRevenueTrendsChart();
    } catch (error) {
        console.error("Error rendering charts:", error);
    }
}

// Helper function to display error message in chart container
function displayChartError(containerId, message) {
    const container = $(`#${containerId}`).parent();
    container.html(`
        <div class="text-center py-5">
            <div class="text-danger mb-2"><i class="bi bi-exclamation-triangle fs-3"></i></div>
            <p class="text-muted">${message}</p>
            <button class="btn btn-sm btn-outline-secondary mt-2" onclick="location.reload()">
                <i class="bi bi-arrow-clockwise"></i> Reload
            </button>
        </div>
    `);
}

async function getSummaryMetrics() {
    try {
        const response = await $.ajax({
            url: "/admin/summary-metrics",
            type: "GET",
            dataType: "json"
        });
    
        return response;
    } catch (error) {
        console.error("Error fetching data: ", error);
    }
}

async function renderSummaryMetrics() {
    try {
        const summaryMetrics = await getSummaryMetrics();
        
        if (!summaryMetrics) {
            console.error("Invalid summary metrics received:", summaryMetrics);
            return;
        }

        console.log(summaryMetrics);

        $("#totalBookings").text(summaryMetrics.total_bookings || 0);
        $("#totalRevenue").text(parseFloat(summaryMetrics.total_revenue || 0).toLocaleString());
        $("#upcomingTrips").text(summaryMetrics.upcoming_trips || 0);
        $("#pendingBookings").text(summaryMetrics.pending_bookings || 0);
        $("#flaggedBookings").text(summaryMetrics.flagged_bookings || 0); 
    } catch (error) {
        console.error("Error rendering summary metrics:", error);
        
        // Set default values in case of error
        $("#totalBookings").text("0");
        $("#totalRevenue").text("0");
        $("#upcomingTrips").text("0");
        $("#pendingBookings").text("0");
        $("#flaggedBookings").text("0");
    }
}

// Payment Method Chart
async function getPaymentMethodData() {
    try {
        const response = await $.ajax({
            url: "/admin/payment-method-data",
            type: "GET",
            dataType: "json"
        });

        console.log("Payment Method Data:", response);
        return response;
    } catch (error) {
        console.error("Error fetching data: ", error);
    }
}

async function renderPaymentMethodChart() {
    const paymentMethodData = await getPaymentMethodData();
    
    if (!paymentMethodData) {
        console.error("Invalid payment method data received:", paymentMethodData);
        return;
    }

    const paymentMethod = Object.keys(paymentMethodData);
    const numberOfUse = Object.values(paymentMethodData);

    const ctx = $("#paymentMethodChart")[0].getContext("2d");

    new Chart(ctx, {
        type: "doughnut",
        data: {
            labels: paymentMethod,
            datasets: [{
                label: "Payment Methods",
                data: numberOfUse,
                backgroundColor: [
                    'rgb(255, 99, 132)',
                    'rgb(54, 162, 235)',
                    'rgb(255, 205, 86)'
                ],
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });
}

// Monthly Booking Trends Chart
async function getMonthlyTrendsData() {
    try {
        const response = await $.ajax({
            url: "/admin/monthly-booking-trends",
            type: "GET",
            dataType: "json"
        });

        console.log("Monthly Trends Data:", response);
        return response;
    } catch (error) {
        console.error("Error fetching monthly trends data: ", error);
    }
}

async function renderMonthlyTrendsChart() {
    const trendsData = await getMonthlyTrendsData();
    
    if (!trendsData || !trendsData.labels || !trendsData.data) {
        console.error("Invalid monthly trends data received:", trendsData);
        return;
    }
    
    const ctx = $("#monthlyTrendsChart")[0].getContext("2d");
    
    new Chart(ctx, {
        type: "line",
        data: {
            labels: trendsData.labels,
            datasets: [{
                label: "Monthly Bookings",
                data: trendsData.data,
                fill: false,
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });
}

// Top Destinations Chart
async function getTopDestinationsData() {
    try {
        const response = await $.ajax({
            url: "/admin/top-destinations",
            type: "GET",
            dataType: "json"
        });

        console.log("Top Destinations Data:", response);
        return response;
    } catch (error) {
        console.error("Error fetching top destinations data: ", error);
    }
}

async function renderTopDestinationsChart() {
    try {
        const destinationsData = await getTopDestinationsData();
        
        if (!destinationsData || !destinationsData.labels || !destinationsData.data) {
            console.error("Invalid destination data received:", destinationsData);
            displayChartError("destinationsChart", "Unable to load destination data. Please try again later.");
            return;
        }
        
        const ctx = $("#destinationsChart")[0].getContext("2d");
        
        new Chart(ctx, {
            type: "polarArea",
            data: {
                labels: destinationsData.labels,
                datasets: [{
                    data: destinationsData.data,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 206, 86, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(153, 102, 255, 0.7)'
                    ],
                    borderColor: [
                        'rgb(255, 99, 132)',
                        'rgb(54, 162, 235)',
                        'rgb(255, 206, 86)',
                        'rgb(75, 192, 192)',
                        'rgb(153, 102, 255)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                animation: {
                    animateRotate: true,
                    animateScale: true,
                    duration: 2000
                },
                scales: {
                    r: {
                        ticks: {
                            display: false
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)'
                        },
                        angleLines: {
                            color: 'rgba(0, 0, 0, 0.1)'
                        },
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        position: 'right',
                        align: 'center',
                        labels: {
                            boxWidth: 15,
                            padding: 15
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `${context.parsed.r} bookings (${Math.round(context.parsed.r / context.dataset.data.reduce((a, b) => a + b, 0) * 100)}%)`;
                            }
                        }
                    },
                    title: {
                        display: true,
                        text: 'Popular Destinations',
                        font: {
                            size: 16,
                            weight: 'bold'
                        },
                        padding: {
                            top: 10,
                            bottom: 20
                        }
                    }
                }
            }
        });
    } catch (error) {
        console.error("Error rendering top destinations chart:", error);
        displayChartError("destinationsChart", "Error rendering chart. Please try again.");
    }
}

// Booking Status Distribution Chart
async function getBookingStatusData() {
    try {
        const response = await $.ajax({
            url: "/admin/booking-status-distribution",
            type: "GET",
            dataType: "json"
        });

        console.log("Booking Status Data:", response);
        return response;
    } catch (error) {
        console.error("Error fetching booking status data: ", error);
    }
}

async function renderBookingStatusChart() {
    const statusData = await getBookingStatusData();
    
    if (!statusData) {
        console.error("Invalid booking status data received:", statusData);
        return;
    }
    
    const statuses = Object.keys(statusData);
    const counts = Object.values(statusData);
    
    const ctx = $("#bookingStatusChart")[0].getContext("2d");
    
    new Chart(ctx, {
        type: "pie",
        data: {
            labels: statuses,
            datasets: [{
                data: counts,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.7)',
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(255, 206, 86, 0.7)',
                    'rgba(75, 192, 192, 0.7)',
                    'rgba(153, 102, 255, 0.7)'
                ],
                borderColor: [
                    'rgb(255, 99, 132)',
                    'rgb(54, 162, 235)',
                    'rgb(255, 206, 86)',
                    'rgb(75, 192, 192)',
                    'rgb(153, 102, 255)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
}

// Revenue Trends Chart
async function getRevenueTrendsData() {
    try {
        const response = await $.ajax({
            url: "/admin/revenue-trends",
            type: "GET",
            dataType: "json"
        });

        console.log("Revenue Trends Data:", response);
        return response;
    } catch (error) {
        console.error("Error fetching revenue trends data: ", error);
    }
}

async function renderRevenueTrendsChart() {
    const revenueData = await getRevenueTrendsData();
    
    if (!revenueData || !revenueData.labels || !revenueData.data) {
        console.error("Invalid revenue trends data received:", revenueData);
        return;
    }
    
    const ctx = $("#revenueTrendsChart")[0].getContext("2d");
    
    new Chart(ctx, {
        type: "bar",
        data: {
            labels: revenueData.labels,
            datasets: [{
                label: "Revenue",
                data: revenueData.data,
                backgroundColor: 'rgba(75, 192, 192, 0.7)',
                borderColor: 'rgb(75, 192, 192)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '₱' + value.toLocaleString();
                        }
                    }
                }
            },
            tooltips: {
                callbacks: {
                    label: function(tooltipItem) {
                        return '₱' + tooltipItem.raw.toLocaleString();
                    }
                }
            }
        }
    });
}