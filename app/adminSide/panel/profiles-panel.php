<?php
session_start(); // Ensure session is started
require_once '../posBackend/checkIfLoggedIn.php';
include '../inc/dashHeader.php';
require_once '../config.php';

?>

<style>
    .wrapper {
        width: 100%;
       background-color: rgba(233, 234, 244, 0.3);
min-height:95vh;
        padding-left: 275px;
        padding-top: 70px;

    }

    @media only screen and (max-width: 768px) {
        .wrapper {
            padding-left: 0;
            padding-top: 70px
        }
    }
</style>


<div class="wrapper">

    <div class="container-fluid">

        <div class="row">

            <div class="col-6">
            <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl ">Member Profiles</h1><br />
                <form method="get" action="#">
                    <div class="row">
                        <div class="col-md-6">
                            <input required type="text" id="member_id" style="width:150px" name="member_id"
                                class="form-control" placeholder="Enter Member ID">
                        </div>
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-dark">Search</button>
                        </div>
                    </div>
                </form><br>

                <?php
                require_once '../config.php';
                $currentMonthStart = date('Y-m-01');
                $currentMonthEnd = date('Y-m-t');

                // Get the current month and year in the format 'YYYY-MM'
                $currentMonth = date('Y-m');

                $memberId = isset($_GET['member_id']) ? $_GET['member_id'] : 1;
                // Get member's most ordered items
                $mostOrderedItemsQuery = "SELECT Menu.item_name, SUM(Bill_Items.quantity) AS order_count
                                      FROM Bill_Items
                                      INNER JOIN Menu ON Bill_Items.item_id = Menu.item_id
                                      INNER JOIN Bills ON Bill_Items.bill_id = Bills.bill_id
                                      WHERE Bills.member_id = $memberId
                                      GROUP BY Bill_Items.item_id
                                      ORDER BY order_count DESC";
                $mostOrderedItemsResult = mysqli_query($link, $mostOrderedItemsQuery);
                // Check if any results were returned
                if (mysqli_num_rows($mostOrderedItemsResult) == 0) {
                    echo "Member ID not found.";
                } else {
                    ?>
                    <section class="antialiased bg-gray-100 text-gray-600">
                        <div class="flex flex-col h-full">
                            <!-- Table -->
                            <div class="w-full bg-white shadow-sm rounded-sm border border-gray-200">
                                <header class="px-5 py-4 border-b border-gray-100">
                                    <h2 class="font-semibold text-gray-800">Most ordered Items of Member -
                                        <?php echo $memberId; ?>
                                    </h2>
                                </header>
                                <div class="">
                                    <div class="overflow-x-auto">
                                        <table class="table-auto w-full">
                                            <thead class="text-xs font-semibold uppercase text-gray-400 bg-gray-50">
                                                <tr>
                                                    <th class="p-2 whitespace-nowrap">
                                                        <div class="font-semibold text-left">Item Name</div>
                                                    </th>
                                                    <th class="p-2 whitespace-nowrap">
                                                        <div class="font-semibold text-left">Quantity</div>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody class="text-sm divide-y divide-gray-100">
                                                <?php while ($row = mysqli_fetch_assoc($mostOrderedItemsResult)): ?>
                                                    <tr>
                                                        <td class="p-2 whitespace-nowrap">
                                                            <div class="flex items-center">
                                                                <div class="w-10 h-10 flex-shrink-0 mr-2 sm:mr-3"><img
                                                                        class="rounded-full"
                                                                        src="https://res.cloudinary.com/delivery-com/image/fetch/w_380,h_285,c_fill,f_auto/https%3A%2F%2Fs3.amazonaws.com%2Fs3.delivery.com%2Fimages%2Fplaceholder%2Ffood.png"
                                                                        width="40" height="40" alt="Alex Shatov"></div>
                                                                <div class="font-medium text-gray-800">
                                                                    <?php echo $row['item_name']; ?></div>
                                                            </div>
                                                        </td>
                                                        <td class="p-2 whitespace-nowrap">
                                                            <div class="text-left font-medium text-green-500">
                                                                <?php echo $row['order_count']; ?></div>
                                                        <?php endwhile; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                  
                <?php } ?>
            </div>
            <div class="col-6 pt-5">
                <h3>Top 5 Favourites - (All Time)</h3>
                <div >
                    <canvas id="mostOrderedItemsChart"></canvas>
                </div>
            </div>
        </div>

    </div>


</div>





<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Get data for the donut chart
    <?php
    $chartLabels = [];
    $chartData = [];

    $chartItemsResult = mysqli_query($link, $mostOrderedItemsQuery);
    $itemCount = 0;

    while ($row = mysqli_fetch_assoc($chartItemsResult)) {
        if ($itemCount >= 5) {
            break;
        }
        array_push($chartLabels, $row['item_name']);
        array_push($chartData, $row['order_count']);
        $itemCount++;
    }
    ?>

    // Create the donut chart
    var ctx = document.getElementById('mostOrderedItemsChart');
    
    var mostOrderedItemsChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: <?php echo json_encode($chartLabels); ?>,
            datasets: [{
                data: <?php echo json_encode($chartData); ?>,
                backgroundColor: [
                    'rgb(8, 32, 50)',
                          'rgb(255, 76, 41)',
                    'rgb(13, 18, 130)',
                    'rgb(143, 67, 238)',
                    'rgb(179, 19, 18)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: {
                display: true,
                position: 'right'
            },
            is3D:true
        }
    });
</script>



<?php include '../inc/dashFooter.php';  // Include your footer file here ?>
