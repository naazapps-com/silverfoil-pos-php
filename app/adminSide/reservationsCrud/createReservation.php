<?php
session_start(); // Ensure session is started
?>
<?php include '../inc/dashHeader.php' ?>

<?php
require_once '../config.php';

$reservationStatus = $_GET['reservation'] ?? null;
$message = '';
if ($reservationStatus === 'success') {
    $message = "Reservation successful";
}
$head_count = $_GET['head_count'] ?? 1;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Reservation </title>
    <style>
        .wrapper {
            width: 100%;
            background-color: rgba(233, 234, 244, 0.3);
            min-height: 95vh;
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
</head>

<body>
    <div class="wrapper mt-7 container-fluid">
        <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl ">Create Reservation</h1><br />
        <h3 class="text-ms text-gray-500 font-semibold" >Search for Available Time</h3><br />

        <div id="Search Table">
            <form id="reservation-form" style="background:white" class='bg-white' method="GET" action="availability.php" class="ht-600">
                <div class="form-group">
                    <label for="reservation_date">Select Date</label><br>
                    <input class="form-control" type="date" id="reservation_date" name="reservation_date" required><br>
                </div>

                <div class="form-group">
                    <label for="reservation_time">Available Reservation Times</label>
                    <div id="availability-table">
                        <?php
                        $availableTimes = array();
                        for ($hour = 10; $hour <= 20; $hour++) {
                            for ($minute = 0; $minute < 60; $minute += 60) {
                                $time = sprintf('%02d:%02d:00', $hour, $minute);
                                $availableTimes[] = $time;
                            }
                        }
                        echo '<select name="reservation_time" id="reservation_time" class="form-control" >';
                        echo '<option value="" selected disabled>Select a Time</option>';
                        foreach ($availableTimes as $time) {
                            echo "<option  value='$time'>$time</option>";
                        }
                        echo '</select>';
                        if (isset($_GET['message'])) {
                            $message = $_GET['message'];
                            echo "<p>$message</p>";
                        }
                        ?>
                    </div>
                </div>
                <input type="number" id="head_count" name="head_count" value=1 hidden required>

                <div class="form-group mt-2">
                    <input type="submit" name="submit" class="btn btn-dark" value="Search Available">
                </div>
            </form>
        </div><br />
        <!-- AFTER SEARCH -->
        <div id="insert-reservation-into-table"><br>
        <h3 class="text-ms text-gray-500 ont-semibold" >Make Reservation</h3><br />

            <form id="reservation-form" method="POST" action="insertReservation.php" class="ht-600 w-50">

                <div class="form-group">
                    <label for="customer_name">Customer Name</label><br>
                    <input type="text" id="customer_name" name="customer_name" class="form-control" required
                        placeholder="Johnny Hatsoff"><br>
                </div>
                <?php
                $defaultReservationDate = $_GET['reservation_date'] ?? date("Y-m-d");
                $defaultReservationTime = $_GET['reservation_time'] ?? "13:00:00";
                ?>

                <div class="form-group">
                    <label for="reservation_date">Reservation Date</label><br>
                    <input type="date" id="reservation_date" name="reservation_date"
                        value="<?= $defaultReservationDate ?>" readonly required>
                    <input type="time" id="reservation_time" name="reservation_time"
                        value="<?= $defaultReservationTime ?>" readonly required>
                </div>
                <br>
                <div class="form-group">
                    <label for="table_id_reserve">Pick a Table</label>
                    <select class="form-control" name="table_id" id="table_id_reserve" required>
                        <option value="" selected disabled>Select a Table</option>
                        <?php
                        $table_id_list = $_GET['reserved_table_id'];
                        $head_count = $_GET['head_count'] ?? 1;
                        $reserved_table_ids = explode(',', $table_id_list);
                        $select_query_tables = "SELECT * FROM Restaurant_Tables WHERE capacity >= '$head_count'";
                        if (!empty($reserved_table_ids)) {
                            $reserved_table_ids_string = implode(',', $reserved_table_ids);
                            $select_query_tables .= " AND table_id NOT IN ($reserved_table_ids_string)";
                        }
                        $result_tables = mysqli_query($link, $select_query_tables);
                        $resultCheckTables = mysqli_num_rows($result_tables);
                        if ($resultCheckTables > 0) {
                            while ($row = mysqli_fetch_assoc($result_tables)) {
                                echo '<option value="' . $row['table_id'] . '">For ' . $row['capacity'] . ' people. (Table Id: ' . $row['table_id'] . ')</option>';
                            }
                        } else {
                            echo '<option disabled>No tables available, please choose another time.</option>';
                            echo '<script>alert("No reservation tables found for the selected time. Please choose another time.");</script>';
                        }
                        ?>
                    </select>
                    <input type="number" id="head_count" name="head_count" value="<?= $head_count ?>" required hidden>
                </div><br>

                <div class="form-group">
                    <label for="special_request">Special request:</label><br>
                    <input type="text" id="special_request" name="special_request" class="ht-600 w-50"
                        placeholder="One baby chair"><br>
                </div>

                <div class="form-group mt-2">
                    <input type="submit" name="submit" class="btn btn-dark" value="Make Reservation">
                </div>

            </form>
        </div>
        <script>
            const viewDateInput = document.getElementById("reservation_date");
            const makeDateInput = document.getElementById("reservation_date");

            viewDateInput.addEventListener("change", function () {
                makeDateInput.value = this.value;
            });
        </script>
</body>

</html>