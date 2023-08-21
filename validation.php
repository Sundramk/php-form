<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $_POST["fullname"];
    $phone = $_POST["phone"];
    $email = $_POST["email"];
    $subject = $_POST["subject"];
    $message = $_POST["message"];

    // Perform basic form validation
    if (empty($fullname) || empty($phone) || empty($email) || empty($subject) || empty($message)) {
        echo "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
    } else {
        // Connect to MySQL database
        $db_host = "localhost";
        $db_user = "";
        $db_pass = "";
        $db_name = "validation";

        $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Insert form data into the database
        $sql = "INSERT INTO contact_form (fullname, phone, email, subject, message) VALUES ('$fullname', '$phone', '$email', '$subject', '$message')";

        if ($conn->query($sql) === TRUE) {
            // Send email notification to the user
            $to = $email;
            $subject = "Thank you for contacting us";
            $message = "Dear $fullname,\n\nThank you for contacting us. We have received your message and will get back to you as soon as possible.\n\nBest regards,\nThe Support Team";
            $headers = "From: codeone.sk@gmail.com";

            mail($to, $subject, $message, $headers);

            echo "Form submitted successfully! You will receive an email notification.";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        $conn->close();
    }
}
?>
