<?php
session_start(); // Ensure session is started
require_once '../posBackend/checkIfLoggedIn.php';
?>
<?php include '../inc/dashHeader.php'; ?>
<style>
    .wrapper {
        width: 100%;
        background-color: rgba(233, 234, 244, 0.3);
        min-height:95vh;
        padding-left: 275px;
        padding-top: 20px;
    }

    @media only screen and (max-width: 768px) {
        .wrapper {
            padding-left: 0;
            padding-top: 0px
        }
    }
</style>
<div class="wrapper ">
    <div class="container-fluid pt-5 pl-600">
        <div class="row">
            <div class="m-50">
                <div class="mt-1 mb-3">
                    <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl ">Reservations</h1><br />
                    <a href="../reservationsCrud/createReservation.php" class="btn btn-outline-dark"><i
                            class="fa fa-plus"></i> Add Reservation</a>
                </div>
                <div class="mb-3">
                    <form method="POST" action="#">
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" name="email" id="products-search"
                                    class="bg-white border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5"
                                    required type="text" id="search" name="search"
                                    placeholder="Search by Reservation ID, Customer Name, Reservation Date (2023-09)">
                            </div>
                            <div class="col-md-3">
                                <button type="submit"
                                    class="p-2.5 ms-2 text-sm font-medium text-white bg-blue-700 rounded-lg border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                    <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 20 20">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                    </svg>
                                    <span class="sr-only">Search</span>
                                </button>
                            </div>
                            <div class="col" style="text-align: right;">
                                <a href="reservation-panel.php" style="margin-left: 10px;width: 140px;"
                                    class="flex items-center border border-black justify-center bg-white hover:bg-[#283592] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white rounded-md px-4 py-2 text-[#283592] hover:text-white text-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                    &nbsp
                                    <span>Show All</span>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                <?php
                // Include config file
                require_once "../config.php";
                $sql = "SELECT * FROM reservations ORDER BY reservation_id;";

                if (isset($_POST['search'])) {
                    if (!empty($_POST['search'])) {
                        $search = $_POST['search'];

                        $sql = "SELECT * FROM reservations WHERE reservation_date LIKE '%$search%' OR reservation_id LIKE '%$search%' OR customer_name LIKE '%$search%'";
                    } else {
                        // Default query to fetch all reservations
                        $sql = "SELECT * FROM reservations ORDER BY reservation_date DESC, reservation_time DESC;";
                    }
                } else {
                    $sql = "SELECT * FROM reservations ORDER BY reservation_date DESC, reservation_time DESC;";

                }

                if ($result = mysqli_query($link, $sql)) {
                    if (mysqli_num_rows($result) > 0) {
                        echo '<table class="table table-bordered table-striped">';
                        echo "<thead>";
                        echo "<tr>";
                        echo "<th>Reservation ID</th>";
                        echo "<th>Customer Name</th>";
                        echo "<th>Table ID</th>";
                        echo "<th>Reservation Time</th>";
                        echo "<th>Reservation Date</th>";
                        echo "<th>Head Count</th>";
                        echo "<th>Special Request</th>";
                        echo "<th>Delete</th>";
                        echo "<th>Receipt</th>";
                        echo "</tr>";
                        echo "</thead>";
                        echo "<tbody>";
                        while ($row = mysqli_fetch_array($result)) {
                            echo "<tr>";
                            echo "<td>" . $row['reservation_id'] . "</td>";
                            echo "<td>" . $row['customer_name'] . "</td>";
                            echo "<td>" . $row['table_id'] . "</td>";
                            echo "<td>" . $row['reservation_time'] . "</td>";
                            echo "<td>" . $row['reservation_date'] . "</td>";
                            echo "<td>" . $row['head_count'] . "</td>";
                            echo "<td>" . $row['special_request'] . "</td>";
                            echo "<td>";
                            echo '<a href="../reservationsCrud/deleteReservationVerify.php?id=' . $row['reservation_id'] . '" title="Delete Record" data-toggle="tooltip" '
                                . 'onclick="return confirm(\'Admin permission Required!\n\nAre you sure you want to delete this Reservation?\n\nThis will alter other modules related to this Reservation!\n\')"><span class="fa fa-trash text-black"></span></a>';
                            echo "</td>";
                            echo "<td>";
                            echo '<a href="../reservationsCrud/reservationReceipt.php?reservation_id=' . $row['reservation_id'] . '" title="Receipt" data-toggle="tooltip"><span class="fa fa-receipt text-black"></span></a>';
                            echo "</td>";
                            echo "</tr>";
                        }
                        echo "</tbody>";
                        echo "</table>";
                    } else {
                        echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
                    }
                } else {
                    echo "Oops! Something went wrong. Please try again later.";
                }

                // Close connection
                mysqli_close($link);
                ?>
            </div>
        </div>
    </div>
</div>

<?php include '../inc/dashFooter.php'; ?>