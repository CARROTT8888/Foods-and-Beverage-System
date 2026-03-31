<?php
session_start();
include './database/fnbdb.php';
$errorMessage = "";

if (isset($_SESSION['userId'])) {
    header("Location: home.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirmPassword']);
    $contactNumber = $_POST['contactNumber'];
    /*$address = $_POST['address'];*/

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMessage = "Invalid email format."; // validate email, displaying the error message if invalid email format
    } elseif (strlen($password) < 5 || !preg_match('/[\W_]/', $password)) {
        $errorMessage = "Password must be at least 5 characters and include a symbol."; // validate password, displaying the error message if password is less than 5 characters or not include a symbol
    } elseif (!preg_match('/^01\d{8,9}$/', $contactNumber)) {
        $errorMessage = "Invalid Malaysian contact number."; // validate contact number, displaying the error message if invalid malaysian contact number
    } elseif ($confirmPassword !== $password) {
        $errorMessage = "Password does not match."; // displaying the error message when the confirm password is not match with password
    } else {
        // if all user inputs are valid
        $emailCheckStmt = $conn->prepare("SELECT userId FROM user WHERE email = ?");
        $emailCheckStmt->bind_param("s", $email);
        $emailCheckStmt->execute();
        $emailCheckStmt->store_result();
        if ($emailCheckStmt->num_rows > 0) {
            $emailAlreadyExists = true;
            $errorMessage = "The email address has already registered."; // displaying the error message if the email address is registered by other user
            $emailCheckStmt->close();
        } else {
            $emailCheckStmt->close();
            $userQuery = "INSERT INTO user (name, email, password, contactNumber) VALUES (?, ?, ?, ?)";
            $emailCheckStmt = $conn->prepare($userQuery);
            $emailCheckStmt->bind_param("ssss", $name, $email, $password, $contactNumber);
            if ($emailCheckStmt->execute()) {
                header("Location: setup.php");
                exit();
            }
        }
    }
}
?>

<div class="w-full max-w-md bg-white rounded-lg border border-secondary shadow-lg p-6">
        <div class="mb-5">
            <h2 class="text-xl font-bold text-foreground">
                Sign Up
            </h2>
            <p class="text-mutedForeground text-sm">
                Create your account below
            </p>
        </div>
        <form class="space-y-4" method="POST" action="" class="">
            <div>
                <label class="text-sm text-foreground" for="name">
                    Name
                </label>
                <input type="text" name="name" placeholder="Jane Doe" required
                    class="w-full border border-secondary rounded-custom px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-primary" />
            </div>
            <div>
                <label class="text-sm text-foreground" for="email">
                    Email
                </label>
                <input type="text" name="email" placeholder="janedoe@gmail.com" required
                    class="w-full border border-secondary rounded-custom px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-primary" />
            </div>
            <div>
                <label class="text-sm text-foreground" for="password">
                    Password
                </label>
                <input type="password" name="password" placeholder="********" required
                    class="w-full border border-secondary rounded-custom px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-primary" />
            </div>
            <div>
                <label class="text-sm text-foreground" for="confirmPassword">
                    Confirm Password
                </label>
                <input type="password" name="confirmPassword" placeholder="********" required
                    class="w-full border border-secondary rounded-custom px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-primary" />
            </div>
            <div>
                <label class="text-sm text-foreground">
                    Contact Number
                </label>
                <input type="text" name="contactNumber" placeholder="012-3456789" required pattern="01\d{8,9}"
                    maxlength="11" inputmode="numeric"
                    class="w-full border border-secondary rounded-custom px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-primary" />
            </div>
            <button type="submit"
                class="w-full rounded-md border bg-primary px-4 py-2 text-center text-sm font-medium text-black transition hover:bg-amber-300">
                Sign Up
            </button>
        </form>
        <div class="mt-4 text-center">
            <?php if (!empty($errorMessage)): ?>
                <div class="text-destructive text-sm">
                    <?php echo htmlspecialchars($errorMessage) ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="text-center mt-5 text-sm text-mutedForeground">
            Already have an account?
            <a href="sign-in.php" class="text-primary hover:underline ml-1">
                Sign In
            </a>
        </div>
    </div>