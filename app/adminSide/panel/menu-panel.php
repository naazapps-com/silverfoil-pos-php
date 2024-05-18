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
                    <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl ">Menu</h1><br />
                    <div style="display:inline-flex">
                        <a href="../menuCrud/createItem.php" class="btn btn-outline-dark"><i class="fa fa-plus"></i> Add
                            Item</a>
                        <a href="menu-panel.php" style="margin-left:10px;width: 140px;"
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
                            <div class="col-8">
                                <select name="search" id="search"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                    <option value="">Select Item Type or Item Category</option>
                                    <option value="Main Dishes">Main Dishes</option>
                                    <option value="Side Snacks">Side Snacks</option>
                                    <option value="Drinks">Drinks</option>
                                    <option value="Steak & Ribs">Steak & Ribs</option>
                                    <option value="Seafood">Seafood</option>
                                    <option value="Pasta">Pasta</option>
                                    <option value="Lamb">Lamb</option>
                                    <option value="Chicken">Chicken</option>
                                    <option value="Burgers & Sandwiches">Burgers & Sandwiches</option>
                                    <option value="Bar Bites">Bar Bites</option>
                                    <option value="House Dessert">House Dessert</option>
                                    <option value="Salad">Salad</option>
                                    <option value="Shoney Kid">Shoney Kid</option>
                                    <option value="Side Dishes">Side Dishes</option>
                                    <option value="Classic Cocktails">Classic Cocktails</option>
                                    <option value="Cold Pressed Juice">Cold Pressed Juice</option>
                                    <option value="House Cocktails">House Cocktails</option>
                                    <option value="Mocktails">Mocktails</option>
                                </select>
                            </div>
                            <div class="col-2">
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
                            <div class="col" style=" display:flex;">

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

                        $sql = "SELECT * FROM Menu WHERE item_type LIKE '%$search%' OR item_category LIKE '%$search%' OR item_name LIKE '%$search%' OR item_id LIKE '%$search%' ORDER BY item_id;";
                    } else {
                        // Default query to fetch all items
                        $sql = "SELECT * FROM Menu ORDER BY item_id;";
                    }
                } else {
                    // Default query to fetch all items
                    $sql = "SELECT * FROM Menu ORDER BY item_id;";
                }

                if ($result = mysqli_query($link, $sql)) {
                    if (mysqli_num_rows($result) > 0) {
                        // Display cards
                        echo ' <div class="flex flex-wrap">';
                        echo '<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-1">';
                        while ($row = mysqli_fetch_array($result)) {
                        
                            echo '<div class="flex-shrink-0 m-2 relative overflow-hidden bg-[silver] rounded-lg max-w-xs shadow-lg">';
                            echo '<a style="position:absolute;top:10px;right:10px;padding:6px 7px 0px 6px;border-radius:50%;border:1.5px solid black" href="../menuCrud/updateItemVerify.php?id=' . $row['item_id'] . '" title="Modify Record" data-toggle="tooltip"'
                            . 'onclick="return confirm(\'Admin permission Required!\n\nAre you sure you want to Edit this Item?\')">'
                            . '<i class="fa fa-pencil" aria-hidden="true"></i></a>';
                            echo '<svg class="absolute bottom-0 left-0 mb-8" viewBox="0 0 375 283" fill="none" style="transform: scale(1.5); opacity: 0.1;">';
                            echo '<rect x="159.52" y="175" width="152" height="152" rx="8" transform="rotate(-45 159.52 175)" fill="white" />';
                            echo '<rect y="107.48" width="152" height="152" rx="8" transform="rotate(-45 0 107.48)" fill="white" />';
                            echo '</svg>';
                            echo '<div class="pt-10 px-10 flex items-center justify-center">';
                            echo '<div class="block absolute w-48 h-48 bottom-0 left-0 -mb-24 ml-3" style="background: radial-gradient(rgb(40,53,146), transparent 60%); transform: rotate3d(0, 0, 1, 20deg) scale3d(1, 0.6, 1); opacity: 0.2;">';
                            echo '</div>';
                            // echo '<img class="relative w-40" src="./images/table.png" alt="">';
                            echo '</div>';
                            echo '<div class="relative text-white px-6 pb-6 mt-6">';
                            echo '<span class="block opacity-75 -mb-1 text-black">Item #'. $row['item_id'] .'</span>';
                            echo '<span class="block font-semibold text-xl" style="color:black"> ' . $row['item_name'] . '</span>';
                            echo '<span class="block font-semibold text-sm" style="color:black"> <span class="font-semibold text-sm" style="color:black"> ' . $row['item_type'] . '</span>, ' . $row['item_category'] . '</span>';
                            echo '<br/>';
                            // Modify link with the pencil icon
                            $update_sql = "UPDATE Menu SET item_name=?, item_type=?, item_category=?, item_price=?, item_description=? WHERE item_id=?";
                           
                            echo '<div class="flex justify-between">';
                                echo '<span class="block bg-white rounded-full text-orange-500 text-xl font-bold px-3 py-2 leading-none flex items-center">';
                                echo $row['item_price'];  // Display availability status instead of price
                                echo '</span>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                        }
                        echo '</div>';
                        echo '</div>';
                        // Free result set
                        mysqli_free_result($result);
                        // echo '<table class="table table-bordered table-striped">';
                        // echo "<thead>";
                        // echo "<tr>";
                        // echo "<th>Item ID</th>";
                        // echo "<th>Name</th>";
                        // echo "<th>Type</th>";
                        // echo "<th>Category</th>";
                        // echo "<th>Price</th>";
                        // echo "<th>Description</th>";
                        // echo "<th>Edit</th>";
                        // //echo "<th>Delete</th>";
                        // echo "</tr>";
                        // echo "</thead>";
                        // echo "<tbody>";
                        // while ($row = mysqli_fetch_array($result)) {
                        //     echo "<tr>";
                        //     echo "<td>" . $row['item_id'] . "</td>";
                        //     echo "<td>" . $row['item_name'] . "</td>";
                        //     echo "<td>" . $row['item_type'] . "</td>";
                        //     echo "<td>" . $row['item_category'] . "</td>";
                        //     echo "<td>" . $row['item_price'] . "</td>";
                        //     echo "<td>" . $row['item_description'] . "</td>";
                        //     echo "<td>";
                        //     // Modify link with the pencil icon
                        //     $update_sql = "UPDATE Menu SET item_name=?, item_type=?, item_category=?, item_price=?, item_description=? WHERE item_id=?";
                        //     echo '<a href="../menuCrud/updateItemVerify.php?id=' . $row['item_id'] . '" title="Modify Record" data-toggle="tooltip"'
                        //         . 'onclick="return confirm(\'Admin permission Required!\n\nAre you sure you want to Edit this Item?\')">'
                        //         . '<i class="fa fa-pencil" aria-hidden="true"></i></a>';
                        //     echo "</td>";
                
                        //     /*echo "<td>";
                        //     $deleteSQL = "DELETE FROM items WHERE item_id = '" . $row['item_id'] . "';";
                        //     echo '<a href="../menuCrud/deleteMenuVerify.php?id='. $row['item_id'] .'" title="Delete Record" data-toggle="tooltip" '
                        //             . 'onclick="return confirm(\'Admin permission Required!\n\nAre you sure you want to delete this Item?\n\nThis will alter other modules related to this Item!\n\nYou see unwanted changes in bills.\')"><span class="fa fa-trash text-black"></span></a>';
                        //     echo "</td>";
                        //      */
                        //     echo "</tr>";
                        // }
                        // echo "</tbody>";
                        // echo "</table>";
                        // // Free result set
                        // mysqli_free_result($result);
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