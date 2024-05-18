<?php
session_start(); // Ensure session is started
require_once '../posBackend/checkIfLoggedIn.php';
?>
<?php include '../inc/dashHeader.php' ?>
<style>
    .wrapper {
        width: 100%;
       background-color: rgba(233, 234, 244, 0.3);
min-height:95vh;
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
<div class="wrapper">
    <div class="container-fluid pt-5 pl-600">
        <div class="row">
            <div class="m-50">
                <div class="mt-1 mb-3">
                    <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl ">Tables</h1><br />
                    <div class="col" style="text-align: right;display:flex">
                        <a href="../tableCrud/createTable.php" class="btn btn-outline-dark"><i class="fa fa-plus"></i>
                            Add Table</a>
                        <a href="table-panel.php" style="margin-left: 10px;width: 140px;"
                            class="flex items-center justify-center bg-[#283592] hover:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white rounded-md px-4 py-2 text-white text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-6 h-6">
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
                <div class="mb-3">
                    <form method="POST" action="#">
                        <div class="row">

                            <div class="col-9">
                                <input type="text" name="email" id="products-search"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5"
                                    type="text" id="search" name="search" placeholder="Enter Table ID, Capacity">
                            </div>
                            <div class="col-3">
                                <button type="submit"
                                    class="p-3.5 ms-2 text-sm font-medium text-white bg-[#283592] rounded-lg border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-silver-100">
                                    <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 20 20">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                    </svg>
                                    <span class="sr-only">Search</span>
                                </button>
    
                            </div>
                        </div>
                    </form>
                </div>
                <?php
                // Include config file
                require_once "../config.php";

                if (isset($_POST['search'])) {
                    if (!empty($_POST['search'])) {
                        $search = $_POST['search'];

                        $sql = "SELECT *
                                FROM Restaurant_Tables
                                WHERE table_id LIKE '%$search%' OR capacity LIKE '%$search%' 
                                ORDER BY table_id;";
                    } else {
                        // Default query to fetch all Restaurant_tables
                        $sql = "SELECT *
                                FROM Restaurant_Tables
                                ORDER BY table_id;";
                    }
                } else {
                    // Default query to fetch all Restaurant_tables
                    $sql = "SELECT *
                            FROM Restaurant_Tables
                            ORDER BY table_id;";
                }


                // Attempt select query execution
                //$sql = "SELECT * FROM Restaurant_Tables ORDER BY table_id;";
                if ($result = mysqli_query($link, $sql)) {
                    if (mysqli_num_rows($result) > 0) {
                        // Display cards
                        echo ' <div class="flex flex-wrap">';
                        echo '<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-1">';
                        while ($row = mysqli_fetch_array($result)) {
                            echo '<div class="flex-shrink-0 m-2 relative overflow-hidden bg-[rgba(62,72,158,0.7)] rounded-lg max-w-xs shadow-lg">';
                            echo '<svg class="absolute bottom-0 left-0 mb-8" viewBox="0 0 375 283" fill="none" style="transform: scale(1.5); opacity: 0.1;">';
                            echo '<rect x="159.52" y="175" width="152" height="152" rx="8" transform="rotate(-45 159.52 175)" fill="white" />';
                            echo '<rect y="107.48" width="152" height="152" rx="8" transform="rotate(-45 0 107.48)" fill="white" />';
                            echo '</svg>';
                            echo '<div class="relative pt-10 px-10 flex items-center justify-center">';
                            echo '<div class="block absolute w-48 h-48 bottom-0 left-0 -mb-24 ml-3" style="background: radial-gradient(rgb(40,53,146), transparent 60%); transform: rotate3d(0, 0, 1, 20deg) scale3d(1, 0.6, 1); opacity: 0.2;">';
                            echo '</div>';
                            echo '<img class="relative w-40" src="./images/table.png" alt="">';
                            echo '</div>';
                            echo '<div class="relative text-white px-6 pb-6 mt-6">';
                            echo '<span class="block opacity-75 -mb-1">Table</span>';
                            echo '<div class="flex justify-between">';
                            echo '<span class="block font-semibold text-xl"># ' . $row['table_id'] . '</span>'; // Display table ID as title
                
                            if ($row['is_available'] == true) {
                                echo '<span class="block bg-white rounded-full text-orange-500 text-xs font-bold px-3 py-2 leading-none flex items-center">';
                                echo 'Available';  // Display availability status instead of price
                                echo '</span>';
                            } else {
                                echo '<span class="block bg-white rounded-full text-green-500 text-xs font-bold px-3 py-2 leading-none flex items-center">';
                                echo 'Occupied';
                                echo '</span>';
                            }

                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                        }
                        echo '</div>';
                        echo '</div>';
                        // Free result set
                        mysqli_free_result($result);
                    } else {
                        echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
                    }
                } else {
                    echo "Oops! Something went wrong. Please try again later.";
                }
                // if ($result = mysqli_query($link, $sql)) {
                //     if (mysqli_num_rows($result) > 0) {
                //         echo '<table class="table table-bordered table-striped">';
                //         echo "<thead>";
                //         echo "<tr>";
                //         echo "<th>Table ID</th>";
                //         echo "<th>Capacity</th>";
                //         echo "<th>Availability</th>";
                //         //echo "<th>Delete</th>";
                //         echo "</tr>";
                //         echo "</thead>";
                //         echo "<tbody>";
                //         while ($row = mysqli_fetch_array($result)) {
                //             echo "<tr>";
                //             echo "<td>" . $row['table_id'] . "</td>";
                //             echo "<td>" . $row['capacity'] . " Persons </td>";
                //             if ($row['is_available'] == true) {
                //                 echo "<td>" . "Yes" . "</td>";
                //             } else {
                //                 echo "<td>" . "No" . "</td>";
                //             }
                
                //             //   echo "<td>";
                //             //  $deleteSQL = "DELETE FROM Reservations WHERE reservation_id = '" . $row['table_id'] . "';";
                //             //   echo '<a href="../tableCrud/deleteTableVerify.php?id='. $row['table_id'] .'" title="Delete Record" data-toggle="tooltip" '
                //             //           . 'onclick="return confirm(\'Admin Permissions Required!\n\nAre you sure you want to delete this Table?\n\nThis will alter other modules related to this Table!\')"><span class="fa fa-trash text-black"></span></a>';
                //             // echo "</td>";
                //             echo "</tr>";
                //         }
                //         echo "</tbody>";
                //         echo "</table>";
                //         // Free result set
                //         mysqli_free_result($result);
                //     } else {
                //         echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
                //     }
                // } else {
                //     echo "Oops! Something went wrong. Please try again later.";
                // }
                
                // Close connection
                mysqli_close($link);
                ?>
            </div>
        </div>
    </div>
</div>

<?php include '../inc/dashFooter.php' ?>