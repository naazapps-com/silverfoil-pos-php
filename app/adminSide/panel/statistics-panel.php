<?php
session_start(); // Ensure session is started
require_once '../posBackend/checkIfLoggedIn.php';
?>
<?php include '../inc/dashHeader.php';
require_once '../config.php';

// Get current date
$currentDate = date('Y-m-d');

// Calculate total revenue for today
$totalRevenueTodayQuery = "SELECT SUM(item_price * quantity) AS total_revenue FROM Bill_Items
                           INNER JOIN Menu ON Bill_Items.item_id = Menu.item_id
                           INNER JOIN Bills ON Bill_Items.bill_id = Bills.bill_id
                           WHERE DATE(Bills.bill_time) = '$currentDate'";
$totalRevenueTodayResult = mysqli_query($link, $totalRevenueTodayQuery);
$totalRevenueTodayRow = mysqli_fetch_assoc($totalRevenueTodayResult);
$totalRevenueToday = $totalRevenueTodayRow['total_revenue'];

// Calculate total revenue for this week (assuming week starts on Monday)
$currentWeekStart = date('Y-m-d', strtotime('monday this week'));
$totalRevenueThisWeekQuery = "SELECT SUM(item_price * quantity) AS total_revenue FROM Bill_Items
                             INNER JOIN Menu ON Bill_Items.item_id = Menu.item_id
                             INNER JOIN Bills ON Bill_Items.bill_id = Bills.bill_id
                             WHERE DATE(Bills.bill_time) >= '$currentWeekStart'";
$totalRevenueThisWeekResult = mysqli_query($link, $totalRevenueThisWeekQuery);
$totalRevenueThisWeekRow = mysqli_fetch_assoc($totalRevenueThisWeekResult);
$totalRevenueThisWeek = $totalRevenueThisWeekRow['total_revenue'];

// Calculate total revenue for this month
$currentMonthStart = date('Y-m-01');
$totalRevenueThisMonthQuery = "SELECT SUM(item_price * quantity) AS total_revenue FROM Bill_Items
                              INNER JOIN Menu ON Bill_Items.item_id = Menu.item_id
                              INNER JOIN Bills ON Bill_Items.bill_id = Bills.bill_id
                              WHERE DATE(Bills.bill_time) >= '$currentMonthStart'";
$totalRevenueThisMonthResult = mysqli_query($link, $totalRevenueThisMonthQuery);
$totalRevenueThisMonthRow = mysqli_fetch_assoc($totalRevenueThisMonthResult);
$totalRevenueThisMonth = $totalRevenueThisMonthRow['total_revenue'];
?>

<style>
    .wrapper {
        width: 100%;
        background-color: rgba(233, 234, 244, 0.3);
        min-height: 95vh;
        padding-left: 275px;
        padding-top: 20px
    }

    @media only screen and (max-width: 768px) {
        .wrapper {
            padding-left: 0;
            padding-top: 0px
        }
    }
</style>

<div class="wrapper container-fluid">
    <div class="mt-10 container-fluid">
        <div class="">
            <div class="">


                <?php
                require_once '../config.php';

                // Calculate total revenue
                $totalRevenueQuery = "SELECT SUM(item_price * quantity) AS total_revenue FROM Bill_Items
                                     INNER JOIN Menu ON Bill_Items.item_id = Menu.item_id";
                $totalRevenueResult = mysqli_query($link, $totalRevenueQuery);
                $totalRevenueRow = mysqli_fetch_assoc($totalRevenueResult);
                $totalRevenue = $totalRevenueRow['total_revenue'];
                ?>

                <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl ">Revenue Statistics</h1>
                <br />
                <table class="table table-auto w-full bg-white p-10 ">
                    <thead class="text-xs font-semibold uppercase">
                        <tr>
                            <th class="p-2 whitespace-nowrap">Metric</th>
                            <th scope="col">Amount (RM)</th>
                        </tr>
                    </thead>
                    <tbody>

                        <tr>
                            <td scope="row">Total Revenue Today</td>
                            <td><?php echo number_format($totalRevenueToday, 2); ?></td>
                        </tr>
                        <tr>
                            <td scope="row">Total Revenue This Week</td>
                            <td><?php echo number_format($totalRevenueThisWeek, 2); ?></td>
                        </tr>
                        <tr>
                            <td scope="row">Total Revenue This Month</td>
                            <td><?php echo number_format($totalRevenueThisMonth, 2); ?></td>
                        </tr>
                        <tr>
                            <td scope="row">Total Revenue</td>
                            <td><?php echo number_format($totalRevenue, 2); ?></td>
                        </tr>
                    </tbody>
                </table>
                <a href="../report/generate_report.php" style="margin-left: 40%;width: 220px;"
                    class="flex items-center border border-black justify-center bg-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white rounded-md px-4 py-2 text-[#283592] hover:text-[#283592] text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg>


                    &nbsp
                    <span>Download Report</span>
                </a>
                <!-- <a href="../report/generate_report.php" style="width: 10em;" class="btn btn-dark" >Download Report</a> -->
                <div class="container pt-5 pl-600 row">
                    <div class="container pt-5 pl-600 ">
                        <!-- Bar Chart Payment Method -->
                        <div id="paymentMethodChart" style="width: 100%; max-width: 1200px; height: 500px;"></div>
                    </div>
                    <div class="container pt-5 pl-600 ">
                        <!-- Donut Chart Payment Method -->
                        <div id="paymentMethodDonutChart" style="width: 100%; max-width: 1200px; height: 500px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
// ...

<?php
$currentMonthStart = date('Y-m-01');
$currentMonthEnd = date('Y-m-t');

// Get the current month and year in the format 'YYYY-MM'
$currentMonth = date('Y-m');
// Modify the SQL query to calculate card revenue for the current month
$cardQuery = "
    SELECT
        IFNULL(SUM(bi.quantity * m.item_price), 0) AS card_revenue
    FROM
        Bills b
    LEFT JOIN
        Bill_Items bi ON b.bill_id = bi.bill_id
    LEFT JOIN
        Menu m ON bi.item_id = m.item_id
    WHERE
        b.payment_method LIKE 'Card'
        AND b.bill_time BETWEEN '$currentMonthStart 00:00:00' AND '$currentMonthEnd 23:59:59';
";

// Modify the SQL query to calculate cash revenue for the current month
$cashQuery = "
    SELECT
        IFNULL(SUM(bi.quantity * m.item_price), 0) AS cash_revenue
    FROM
        Bills b
    LEFT JOIN
        Bill_Items bi ON b.bill_id = bi.bill_id
    LEFT JOIN
        Menu m ON bi.item_id = m.item_id
    WHERE
        b.payment_method LIKE 'Cash'
        AND b.bill_time BETWEEN '$currentMonthStart 00:00:00' AND '$currentMonthEnd 23:59:59';
";

$cardResult = $link->query($cardQuery);
$cashResult = $link->query($cashQuery);

if ($cardResult->num_rows > 0) {
    $cardRow = $cardResult->fetch_assoc();
    $cardRevenue = $cardRow['card_revenue'];
} else {
    $cardRevenue = 0;
}

if ($cashResult->num_rows > 0) {
    $cashRow = $cashResult->fetch_assoc();
    $cashRevenue = $cashRow['cash_revenue'];
} else {
    $cashRevenue = 0;
}
?>

<script>
    // Load the Google Charts library
    google.charts.load('current', { packages: ['corechart'] });
    google.charts.setOnLoadCallback(paymentMethodCharts);

    function paymentMethodCharts() {
        // Create the data table for bar chart
        const barChartData = new google.visualization.DataTable();
        barChartData.addColumn('string', 'Payment Method');
        barChartData.addColumn('number', 'Revenue');
        barChartData.addRows([
            ['Card', <?php echo $cardRevenue; ?>],
            ['Cash', <?php echo $cashRevenue; ?>]
        ]);

        // Create the data table for donut chart
        const donutChartData = new google.visualization.DataTable();
        donutChartData.addColumn('string', 'Payment Method');
        donutChartData.addColumn('number', 'Revenue');
        donutChartData.addRows([
            ['Card', <?php echo $cardRevenue; ?>],
            ['Cash', <?php echo $cashRevenue; ?>]
        ]);

        // Set chart options for both charts
        const barChartOptions = {
            title: 'Revenue Generated - <?php echo date('F Y'); ?>',
            bars: 'vertical'
        };

        const donutChartOptions = {
            title: 'Revenue Percentage - <?php echo date('F Y'); ?>',
            pieHole: 0.4
        };

        // Instantiate and draw the charts
        const barChart = new google.visualization.BarChart(document.getElementById('paymentMethodChart'));
        barChart.draw(barChartData, barChartOptions);

        const donutChart = new google.visualization.PieChart(document.getElementById('paymentMethodDonutChart'));
        donutChart.draw(donutChartData, donutChartOptions);
    }
</script>