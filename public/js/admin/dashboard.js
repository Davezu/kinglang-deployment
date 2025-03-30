$(renderSummaryMetrics);
$(renderPaymentMethodChart);

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
    const summaryMetrics = await getSummaryMetrics();

    console.log(summaryMetrics);

    $("#totalBookings").text(summaryMetrics.total_bookings);
    $("#totalRevenue").text(parseFloat(summaryMetrics.total_revenue).toLocaleString());
    $("#upcomingTrips").text(summaryMetrics.upcoming_trips);
    $("#pendingBookings").text(summaryMetrics.pending_bookings);
    $("#flaggedBookings").text(summaryMetrics.flagged_bookings); 
}

// doughnut chart
async function getPaymentMethodData() {
    try {
        const response = await $.ajax({
            url: "/admin/payment-method-data",
            type: "GET",
            dataType: "json"
        });

        console.log(response);

        return response;
    } catch (error) {
        console.error("Error fetching data: ", error);
    }
}

async function renderPaymentMethodChart() {
    const paymentMethodData = await getPaymentMethodData();

    const paymentMethod = Object.keys(paymentMethodData).map(key => key);
    const numberOfUse = Object.values(paymentMethodData).map(value => value);

    const ctx = $("#paymentMethodChart")[0].getContext("2d");

    const paymentMethodChart = new Chart(ctx, {
        type: "line",
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
        }
    })
}