<?php
session_start(); // Ensure session is started
require_once '../posBackend/checkIfLoggedIn.php';
?>
<?php include '../inc/dashHeader.php'; ?>
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

<div class="wrapper">
    <div class="container-fluid pt-5 pl-600">
        <div class="row">
            <div class="m-50">
                <div class="mt-1 mb-3">
                    <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl ">Staff & Customers</h1><br />
                    <a href="../staffCrud/createStaff.php" class="btn btn-outline-dark"><i class="fa fa-plus"></i> Add
                        Staff</a>
                    <a href="../customerCrud/createCust.php" class="btn btn-outline-dark"><i class="fa fa-plus"></i> Add
                        Memberships</a>
                </div>

                <div class="mb-3">
                    <form method="POST" action="#">
                        <div class="row">
                            <div class="col-md-6">
                                <input required type="text" id="search" name="search"
                                    class="bg-white-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5"
                                    required type="text" id="search" name="search"
                                    placeholder="Enter Account ID, Email">
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
                                <a href="account-panel.php" style="margin-left: auto;width: 140px;"
                                    class="flex items-center border border-black justify-center bg-white hover:bg-[#283592] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white rounded-md px-4 py-2 text-[#283592] text-sm">
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

                if (isset($_POST['search'])) {
                    if (!empty($_POST['search'])) {
                        $search = $_POST['search'];

                        $sql = "SELECT *
                                FROM Accounts
                                WHERE email LIKE '%$search%' OR account_id LIKE '%$search%'
                                ORDER BY account_id;";
                    } else {
                        // Default query to fetch all accounts
                        $sql = "SELECT *
                                FROM Accounts
                                ORDER BY account_id;";
                    }
                } else {
                    // Default query to fetch all accounts
                    $sql = "SELECT *
                            FROM Accounts
                            ORDER BY account_id;";
                }

                if ($result = mysqli_query($link, $sql)) {
                    if (mysqli_num_rows($result) > 0) {
                        echo '<div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">';
                        echo '<div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">';
                        echo '<div class="w-full bg-white shadow-sm rounded-sm border border-gray-200">';
                        echo '<br/><table class="min-w-full divide-y divide-gray-200 table-fixed">';
                        echo '<thead class="py-3.5 px-4 text-sm font-normal text-left">';
                        echo '<tr>';
                        echo '<th scope="col" class="px-4 py-3.5 text-sm font-normal text-left ">Account ID</th>';
                        echo '<th scope="col" class="px-4 py-3.5 text-sm font-normal text-left">Email</th>';
                        echo '<th scope="col" class="px-4 py-3.5 text-sm font-normal text-left">Register Date</th>';
                        echo '<th scope="col" class="px-4 py-3.5 text-sm font-normal text-left">Phone Number</th>';
                        echo '<th scope="col" class="px-4 py-3.5 text-sm font-normal text-left">Password</th>';
                        //echo "<th>Account Type</th>"; // Display account type
                        // echo "<th>Delete</th>";
                        echo "</tr>";
                        echo "</thead>";
                        echo '<tbody class="bg-white divide-y"> ';
                        while ($row = mysqli_fetch_array($result)) {
                            echo '<tr class="px-4 py-2 text-sm font-medium whitespace-nowrap">';
                            echo '<td class="px-4 text-sm whitespace-nowrap">' . $row['account_id'] . "</td>";
                            echo '<td class="px-4 py-2 text-sm whitespace-nowrap">' . $row['email'] . "</td>";
                            echo '<td class="px-4 py-2 text-sm whitespace-nowrap">' . $row['register_date'] . "</td>";
                            echo '<td class="px-4 py-2 text-sm whitespace-nowrap">' . $row['phone_number'] . "</td>";
                            echo '<td class="px-4 py-2 text-sm whitespace-nowrap">' . $row['password'] . "</td>";
                            //echo "<td>" . ucfirst($row['account_type']) . "</td>"; // Display account type
                            //  echo "<td>";
                            //  $deleteSQL = "DELETE FROM Accounts WHERE account_id = '" . $row['account_id'] . "';";
                            // echo '<a href="../accountCrud/deleteAccountVerify.php?id=' . $row['account_id'] . '" title="Delete Record" data-toggle="tooltip" '
                            //         . 'onclick="return confirm(\'Admin permission Required!\n\nAre you sure you want to delete this Account?\n\nThis will alter other modules related to this Account!\n\')"><span class="fa fa-trash text-black"></span></a>';
                            // echo "</td>";
                            echo "</tr>";
                        }
                        echo "</tbody>";
                        echo "</table>";
                        echo '</div>';
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

                // Close connection
                mysqli_close($link);
                ?>
            </div>
        </div>
    </div>
</div>

<?php include '../inc/dashFooter.php'; ?>