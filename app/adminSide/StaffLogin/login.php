<?php
session_start();
if (isset($_SESSION['logged_account_id'])) {
    header("Location: ../panel/pos-panel.php");
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script
        src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio,line-clamp,container-queries"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        clifford: '#da373d',
                    }
                }
            }
        }
    </script>
    <style type="text/tailwindcss">
        @layer utilities {
      .content-auto {
        content-visibility: auto;
      }
    }

 
  </style>
    <style>
        .wrapper {
            width: 360px;
            padding: 20px;
        }

        h2 {
            text-align: center;
        }
    </style>
</head>

<body>

    <section id="signup">
        <div class="container my-6 ">
            <a class="nav-link" href="../../customerSide/home/home.php">
                <h1 class="text-center text-white font-bold">JOHNNY'S</h1><span class="sr-only"></span>
            </a>

            <div class="flex items-center justify-center p-6 min-h-screen w-full">
                <div class="w-full">
                    <div class="sm:mx-auto sm:w-full sm:max-w-md">
                        <!-- <a href="#" class="flex justify-center font-bold text-4xl"> LOGO </a> -->

                        <h2 class="mt-6 text-2xl font-extrabold text-center leading-9">Staff Login</h2>

                        <p class="mt-2 text-sm text-center leading-5 max-w">

                            <a href="#" class="font-medium transition ease-in-out duration-150"> Sign in to your account
                            </a>
                        </p>
                    </div>

                    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
                        <?php
                        if (!empty($login_err)) {
                            echo '<div class="alert alert-danger">' . $login_err . '</div>';
                        }
                        ?>

                        <form action="login_process.php" method="post">
                            <div>
                                <label for="email" class="block text-sm font-medium leading-5"> Staff Account ID
                                </label>

                                <div class="mt-1 rounded-md shadow-sm">
                                    <input type="number" id="account_id" name="account_id" autofocus
                                        placeholder="Enter Account ID" required
                                        class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 transition duration-150 ease-in-out sm:text-sm sm:leading-5"
                                        value="<?php echo $account_id; ?>">
                                    <span class="invalid-feedback"><?php echo $account_id_err; ?></span>
                                </div>
                            </div>

                            <div class="mt-6">
                                <label for="password" class="block text-sm font-medium text-gray-700 leading-5">
                                    Password </label>

                                <div class="mt-1 rounded-md shadow-sm">
                                    <input type="password" id="password" name="password" placeholder="Enter Password"
                                        required
                                        class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 transition duration-150 ease-in-out sm:text-sm sm:leading-5 <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                                </div>
                            </div>

                            <div class="flex items-center justify-between mt-6">
                                <div class="flex items-center">
                                    <input id="remember" type="checkbox"
                                        class="form-checkbox w-4 h-4 transition duration-150 ease-in-out" />
                                    <label for="remember" class="block ml-2 text-sm text-gray-900 leading-5"> Remember
                                    </label>
                                </div>

                                <div class="text-sm leading-5">
                                    <a href="#" class="font-medium transition ease-in-out duration-150"> Forgot your
                                        password? </a>
                                </div>
                            </div>

                            <div class="mt-6">
                                <span class="block w-full rounded-md shadow-sm">
                                    <button type="submit"
                                        class="flex justify-center w-full px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md hover:bg-indigo-400 focus:outline-none transition duration-150 ease-in-out">Sign
                                        in</button>
                                </span>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </section>

</body>


</html>