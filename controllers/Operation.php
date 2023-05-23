<?php
date_default_timezone_set("Asia/Dhaka");

class Operation
{
    private $db_conn;

    function __construct()
    {
        include_once __DIR__ . '../../DBConn.php';
        $this->db_conn = new DBConn();
    }


    function data_insert_controller()
    {
        include 'views/index.php';
    }
    function landing_controller()
    {
        include 'views/landing.php';
    }
    function data_report_controller()
    {
        include 'views/report.php';
    }
    function data_submit_controller()
    {
        $conn = $this->db_conn->connect_development_database();

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Retrieve the form data
            $buyer = $_POST["buyer"];
            $amount = $_POST["amount"];
            $receipt_id = $_POST["receipt_id"];
            $items_data = $_POST["items"];
            $buyer_email = $_POST["buyer_email"];
            $note = $_POST["note"];
            $city = $_POST["city"];
            $phone = $_POST["phone"];
            $entry_by = $_POST["entry_by"];

            if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $buyer_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } elseif (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                $buyer_ip = $_SERVER['HTTP_CLIENT_IP'];
            } else {
                $buyer_ip = $_SERVER['REMOTE_ADDR'];
            }

            $validItems = [];
            foreach ($items_data as $item) {
                $trimmedItem = trim($item);

                // Check if the value contains only alphabetic characters
                if (preg_match('/^[a-zA-Z]+$/', $trimmedItem)) {
                    $validItems[] = $trimmedItem;
                }
            }

            if (count($validItems) !== count($items_data)) {
                $response = array(
                    "status" => "error",
                    "message" => "Invalid item(s). Please enter only text."
                );
                echo json_encode($response);
                exit;
            }
            $items = implode(",", $items_data);


            // Validate the form data (backend validation)
            $errors = array();

            // Validate amount: only numbers
            if (!is_numeric($amount)) {
                $errors[] = "Invalid amount. Please enter only numbers.";
            }

            // Validate buyer: only text, spaces, and numbers, not more than 20 characters
            if (!preg_match('/^[a-zA-Z0-9 ]{1,20}$/', $buyer)) {
                $errors[] = "Invalid buyer. Please enter only text, spaces, and numbers (not more than 20 characters).";
            }

            // Validate receipt_id: only text
            if (!preg_match('/^[a-zA-Z]+$/', $receipt_id)) {
                $errors[] = "Invalid receipt ID. Please enter only text.";
            }

            // Validate buyer_email: only emails
            if (!filter_var($buyer_email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Invalid buyer email. Please enter a valid email address.";
            }

            // Validate note: up to 30 words
            $words = str_word_count($note);
            if ($words > 30) {
                $errors[] = "Exceeded word limit for note. Please enter up to 30 words.";
            }

            // Validate city: only text and spaces
            if (!preg_match('/^[a-zA-Z\s]+$/', $city)) {
                $errors[] = "Invalid city. Please enter only text and spaces.";
            }

            // Validate phone: only numbers
            if (!is_numeric($phone)) {
                $errors[] = "Invalid phone number. Please enter only numbers.";
            }

            // Validate entry_by: only numbers
            if (!is_numeric($entry_by)) {
                $errors[] = "Invalid entry by. Please enter only numbers.";
            }

            // If there are no validation errors, proceed with data processing and database operations
            if (empty($errors)) {
                $salt = "your_salt"; // Replace with a secure salt value
                $hash_key = hash('sha512', $receipt_id . $salt);

                // Get the current date and time in the local timezone
                $entry_at = new DateTime('now', new DateTimezone('Asia/Dhaka'));
                $entry_at = $entry_at->format('Y-m-d'); // Format the date as needed


                $sql = "INSERT INTO orders (amount, buyer, receipt_id, items, buyer_email, buyer_ip, note, city, phone, hash_key, entry_at, entry_by)
               VALUES ('$amount', '$buyer', '$receipt_id', '$items', '$buyer_email', '$buyer_ip', '$note', '$city', '$phone', '$hash_key', '$entry_at', '$entry_by')";
                $query = mysqli_query($conn, $sql);

                if ($query) {
                    $message = "Form submitted successfully!";
                } else {
                    $message = $conn->error;
                }
                // Close the database connection

                $conn->close();

                // Return a success message or redirect to a success page
                $response = array(
                    "status" => "success",
                    "message" => $message
                );
                echo json_encode($response);
                exit;
            } else {
                // Return the validation errors
                $response = array(
                    "status" => "error",
                    "errors" => $errors
                );
                echo json_encode($response);
                exit;
            }
        } else {
            // Return an error message if the form was not submitted
            $response = array(
                "status" => "error",
                "message" => "Invalid request"
            );
            echo json_encode($response);
            exit;
        }
    }
}
