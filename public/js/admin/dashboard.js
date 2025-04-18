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
        
        // Check if the response contains an error message
        if (response && response.error) {
            console.error("Error from server:", response.error);
            return null;
        }
        
        return response;
    } catch (error) {
        console.error("Error fetching data: ", error);
        return null;
    }
}

async function renderPaymentMethodChart() {
    try {
        const paymentMethodData = await getPaymentMethodData();
        
        console.log("Original Payment Method Data:", paymentMethodData);
        
        if (!paymentMethodData || !paymentMethodData.labels || !paymentMethodData.counts) {
            console.error("Invalid payment method data received:", paymentMethodData);
            displayChartError("paymentMethodChart", "Unable to load payment method data. Please try again later.");
            return;
        }
        
        // If there's no data, show a message
        if (paymentMethodData.labels.length === 0 || paymentMethodData.counts.every(count => count === 0)) {
            displayChartError("paymentMethodChart", "No payment data available yet.");
            return;
        }

        const ctx = $("#paymentMethodChart")[0].getContext("2d");

        new Chart(ctx, {
            type: "doughnut",
            data: {
                labels: paymentMethodData.labels,
                datasets: [{
                    label: "Payment Methods",
                    data: paymentMethodData.counts,
                    backgroundColor: [
                        'rgb(255, 99, 132)',
                        'rgb(54, 162, 235)',
                        'rgb(255, 205, 86)',
                        'rgb(75, 192, 192)'
                    ],
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const value = context.raw;
                                const total = context.dataset.data.reduce((acc, val) => acc + val, 0);
                                const percentage = ((value / total) * 100).toFixed(1);
                                const amount = paymentMethodData.amounts[context.dataIndex];
                                return `${context.label}: ${value} (${percentage}%) - ₱${amount.toLocaleString()}`;
                            }
                        }
                    }
                }
            }
        });
    } catch (error) {
        console.error("Error rendering payment method chart:", error);
        displayChartError("paymentMethodChart", "Error rendering chart. Please try again.");
    }
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
    try {
        const trendsData = await getMonthlyTrendsData();
        
        console.log("Monthly Trends Data:", trendsData);
        
        if (!trendsData || !trendsData.labels || !trendsData.counts) {
            console.error("Invalid monthly trends data received:", trendsData);
            displayChartError("monthlyTrendsChart", "Unable to load monthly trend data. Please try again later.");
            return;
        }
        
        const ctx = $("#monthlyTrendsChart")[0].getContext("2d");
        
        new Chart(ctx, {
            type: "line",
            data: {
                labels: trendsData.labels,
                datasets: [
                    {
                        label: "Bookings",
                        data: trendsData.counts,
                        fill: false,
                        borderColor: 'rgb(75, 192, 192)',
                        tension: 0.4,
                        yAxisID: 'y'
                    },
                    {
                        label: "Revenue",
                        data: trendsData.revenues,
                        fill: false,
                        borderColor: 'rgb(255, 99, 132)',
                        tension: 0.4,
                        yAxisID: 'y1'
                    }
                ]
            },
            options: {
                responsive: true,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: `Monthly Bookings (${trendsData.year})`,
                        font: {
                            size: 16,
                            weight: 'bold'
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.dataset.label;
                                const value = context.raw;
                                if (label === "Revenue") {
                                    return `${label}: ₱${value.toLocaleString()}`;
                                }
                                return `${label}: ${value}`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        type: 'linear',
                        display: true,
                        position: 'left',
                        title: {
                            display: true,
                            text: 'Number of Bookings'
                        },
                        ticks: {
                            precision: 0
                        }
                    },
                    y1: {
                        beginAtZero: true,
                        type: 'linear',
                        display: true,
                        position: 'right',
                        title: {
                            display: true,
                            text: 'Revenue (₱)'
                        },
                        grid: {
                            drawOnChartArea: false
                        },
                        ticks: {
                            callback: function(value) {
                                return '₱' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    } catch (error) {
        console.error("Error rendering monthly trends chart:", error);
        displayChartError("monthlyTrendsChart", "Error rendering chart. Please try again.");
    }
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
        
        console.log("Top Destinations Data:", destinationsData);
        
        if (!destinationsData || !destinationsData.labels || !destinationsData.counts) {
            console.error("Invalid destination data received:", destinationsData);
            displayChartError("destinationsChart", "Unable to load destination data. Please try again later.");
            return;
        }
        
        // Check if there's real data
        if (destinationsData.labels.length === 0 || destinationsData.labels[0] === 'No Data Available') {
            displayChartError("destinationsChart", "No destination data available yet.");
            return;
        }
        
        const ctx = $("#destinationsChart")[0].getContext("2d");
        
        new Chart(ctx, {
            type: "polarArea",
            data: {
                labels: destinationsData.labels,
                datasets: [{
                    data: destinationsData.counts,
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
                                const index = context.dataIndex;
                                const count = destinationsData.counts[index];
                                const revenue = destinationsData.revenues[index];
                                const totalBookings = destinationsData.counts.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((count / totalBookings) * 100);
                                
                                return [
                                    `Bookings: ${count} (${percentage}%)`,
                                    `Revenue: ₱${revenue.toLocaleString()}`
                                ];
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
    try {
        const statusData = await getBookingStatusData();
        
        console.log("Booking Status Data:", statusData);
        
        if (!statusData || !statusData.labels || !statusData.counts) {
            console.error("Invalid booking status data received:", statusData);
            displayChartError("bookingStatusChart", "Unable to load booking status data. Please try again later.");
            return;
        }
        
        if (statusData.labels.length === 0) {
            displayChartError("bookingStatusChart", "No booking status data available yet.");
            return;
        }
        
        const ctx = $("#bookingStatusChart")[0].getContext("2d");
        
        new Chart(ctx, {
            type: "pie",
            data: {
                labels: statusData.labels,
                datasets: [{
                    data: statusData.counts,
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.7)', // Confirmed
                        'rgba(255, 206, 86, 0.7)', // Pending
                        'rgba(75, 192, 192, 0.7)', // Completed
                        'rgba(255, 99, 132, 0.7)', // Canceled
                        'rgba(153, 102, 255, 0.7)' // Rejected
                    ],
                    borderColor: [
                        'rgb(54, 162, 235)', // Confirmed
                        'rgb(255, 206, 86)', // Pending
                        'rgb(75, 192, 192)', // Completed
                        'rgb(255, 99, 132)', // Canceled
                        'rgb(153, 102, 255)' // Rejected
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label;
                                const value = context.raw;
                                const totalBookings = statusData.counts.reduce((a, b) => a + b, 0);
                                const percentage = ((value / totalBookings) * 100).toFixed(1);
                                const totalValue = statusData.values[context.dataIndex];
                                
                                return [
                                    `${label}: ${value} (${percentage}%)`,
                                    `Total Value: ₱${totalValue.toLocaleString()}`
                                ];
                            }
                        }
                    },
                    title: {
                        display: true,
                        text: 'Booking Status Distribution',
                        font: {
                            size: 16,
                            weight: 'bold'
                        }
                    }
                }
            }
        });
    } catch (error) {
        console.error("Error rendering booking status chart:", error);
        displayChartError("bookingStatusChart", "Error rendering chart. Please try again.");
    }
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
    try {
        const revenueData = await getRevenueTrendsData();
        
        console.log("Revenue Trends Data:", revenueData);
        
        if (!revenueData || !revenueData.labels || !revenueData.revenues) {
            console.error("Invalid revenue trends data received:", revenueData);
            displayChartError("revenueTrendsChart", "Unable to load revenue trend data. Please try again later.");
            return;
        }
        
        if (revenueData.labels.length === 0) {
            displayChartError("revenueTrendsChart", "No revenue data available yet.");
            return;
        }
        
        const ctx = $("#revenueTrendsChart")[0].getContext("2d");
        
        new Chart(ctx, {
            type: "bar",
            data: {
                labels: revenueData.labels,
                datasets: [
                    {
                        type: 'bar',
                        label: "Revenue",
                        data: revenueData.revenues,
                        backgroundColor: 'rgba(75, 192, 192, 0.7)',
                        borderColor: 'rgb(75, 192, 192)',
                        borderWidth: 1,
                        yAxisID: 'y'
                    },
                    {
                        type: 'line',
                        label: "Bookings",
                        data: revenueData.counts,
                        fill: false,
                        borderColor: 'rgb(255, 99, 132)',
                        tension: 0.4,
                        borderWidth: 2,
                        pointStyle: 'circle',
                        pointRadius: 5,
                        pointHoverRadius: 8,
                        yAxisID: 'y1'
                    }
                ]
            },
            options: {
                responsive: true,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Revenue & Booking Trends',
                        font: {
                            size: 16,
                            weight: 'bold'
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.dataset.label;
                                const value = context.raw;
                                if (label === "Revenue") {
                                    return `${label}: ₱${value.toLocaleString()}`;
                                }
                                return `${label}: ${value}`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        type: 'linear',
                        display: true,
                        position: 'left',
                        title: {
                            display: true,
                            text: 'Revenue (₱)'
                        },
                        ticks: {
                            callback: function(value) {
                                return '₱' + value.toLocaleString();
                            }
                        }
                    },
                    y1: {
                        beginAtZero: true,
                        type: 'linear',
                        display: true,
                        position: 'right',
                        title: {
                            display: true,
                            text: 'Number of Bookings'
                        },
                        grid: {
                            drawOnChartArea: false
                        },
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    } catch (error) {
        console.error("Error rendering revenue trends chart:", error);
        displayChartError("revenueTrendsChart", "Error rendering chart. Please try again.");
    }
}