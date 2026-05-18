<?php
session_start();
include './database/fnbdb.php';
$errorMessage = "";

if (isset($_SESSION['userId'])) {
    header("Location: home.php");
    exit();
}

if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMessage = "Plase enter a valid email address and password.";
    } else {
        $stmt = $conn->prepare("SELECT * FROM user WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            if ($password == $row['password']) {
                $_SESSION['userId'] = $row['userId'];
                $_SESSION['email'] = $row['email'];
                $_SESSION['name'] = $row['name'];
                $_SESSION['role'] = $row['role'];
                $_SESSION['image'] = $row['image'];

                header("Location: home.php");
                exit();
            } else {
                $errorMessage = "Password does not match.";
            }
        } else {
            $errorMessage = "No account found with the email address.";
        }
    }
}
?>

<div class="w-full max-w-md bg-white p-8 rounded-lg shadow-lg border border-secondary">
    <div class="text-center mb-6">
        <h1 class="text-2xl font-bold text-foreground">
            Sign In
        </h1>
        <p class="text-mutedForeground text-sm mt-2">
            Enter your email below to login to your account.
        </p>
    </div>
    <form method="POST" action="" class="space-y-5">
        <div>
            <label class="block text-sm font-medium text-foreground mb-1">
                Email
            </label>
            <input type="text" name="email" placeholder="jane.doe@gmail.com" required
                class="w-full border border-secondary rounded-custom px-4 py-2 focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition" />
        </div>
        <div>
            <div class="flex justify-between items-center text-sm">
                <label class="block text-sm font-medium text-foreground mb-1">
                    Password
                </label>
                <a href="forgot-password.php" class="text-primary hover:underline">
                    Forgot Password?
                </a>
            </div>
            <input type="password" name="password" placeholder="********" required
                class="w-full border border-secondary rounded-custom px-4 py-2 focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition" />
        </div>
        <button type="submit" name="login"
            class="w-full rounded-md border bg-primary px-4 py-2 text-center text-sm font-medium text-black transition hover:bg-amber-300">
            Sign In
        </button>
    </form>
    <div class="mt-4 text-center">
        <?php if (!empty($errorMessage)): ?>
            <div class="text-destructive text-sm">
                <?php echo htmlspecialchars($errorMessage) ?>
            </div>
        <?php endif; ?>
    </div>
    <div class="text-center mt-6 text-sm text-mutedForeground">
        If you don't have an account,
        <a href="sign-up.php" class="text-primary hover:underline ml-1">
            Sign Up
        </a>
    </div>
</div>