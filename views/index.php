<!DOCTYPE html>
<html>
<?php
$PAGE_URL = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$self_file = basename($_SERVER['PHP_SELF']);
$BASE_URL = stristr($PAGE_URL, $self_file, true);



if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $buyer_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} elseif (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $buyer_ip = $_SERVER['HTTP_CLIENT_IP'];
} else {
    $buyer_ip = $_SERVER['REMOTE_ADDR'];
}

?>

<head>
    <title>Form Validation Example</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <nav class="navbar navbar-light bg-light mb-5">
        <a class="navbar-brand ps-2" href="<?php echo $BASE_URL; ?>">Home Page</a>
        <h1 class="mx-auto">Orders Panel</h1>
    </nav>
    <div id="confirmation" class="text-md-end fw-bold">

    </div>
    <div class="container">
        <form id="orderForm" method="post" action="">
            <div class="row">
                <div class="col-4">
                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount:</label>
                        <input type="number" id="amount" name="amount" class="form-control" required>
                    </div>
                </div>
                <div class="col-4">
                    <div class="mb-3">
                        <label for="buyer" class="form-label">Buyer:</label>
                        <input type="text" id="buyer" name="buyer" class="form-control" required>
                    </div>
                </div>
                <div class="col-4">
                    <div class="mb-3">
                        <label for="receipt_id" class="form-label">Receipt ID:</label>
                        <input type="text" id="receipt_id" name="receipt_id" class="form-control" required>
                    </div>
                </div>
            </div>


            <div id="itemsContainer" class="mb-3">
                <label for="items" class="form-label">Items:</label>
                <input type="text" id="items" name="items[]" class="form-control" required>
            </div>

            <button type="button" onclick="addItem()" class="btn btn-primary mb-3">Add Item</button>
            <div class="row">
                <div class="col-4">
                    <div class="mb-3">
                        <label for="buyer_email" class="form-label">Buyer Email:</label>
                        <input type="email" id="buyer_email" name="buyer_email" class="form-control" required>
                    </div>
                </div>
                <div class="col-4">
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone:</label>
                        <input type="text" id="phone" name="phone" class="form-control" required>
                    </div>

                </div>
                <div class="col-4">

                    <div class="mb-3">
                        <label for="city" class="form-label">City:</label>
                        <input type="text" id="city" name="city" class="form-control" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-8">
                    <div class="mb-3">
                        <label for="note" class="form-label">Note:</label>
                        <textarea id="note" name="note" class="form-control" required></textarea>
                    </div>
                </div>
                <div class="col-4">
                    <div class="mb-3">
                        <label for="entry_by" class="form-label">Entry By:</label>
                        <input type="text" id="entry_by" name="entry_by" class="form-control" required>
                    </div>
                </div>
            </div>






            <button type="submit" name="form_submit" class="btn btn-primary">Submit</button>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            $('#orderForm').submit(function(event) {
                event.preventDefault(); // Prevent form submission
                var userIpAddress = '<?php echo $buyer_ip;?>'
                if (validateForm()) {

                    var formData = $(this).serialize(); // Serialize form data
                    if (checkSubmissionCookie(userIpAddress)) {
                        $("#confirmation").text("You have already submitted within the last 24 hours.");
                    } else {
                        var now = new Date();
                        now.setTime(now.getTime() + 24 * 60 * 60 * 1000);
                        document.cookie = "submission=true; expires=" + now.toUTCString() + "; path=/";

                        $.ajax({
                            url: '<?php echo $BASE_URL . 'index.php?req=data_entry_handler'; ?>',
                            type: 'POST',
                            data: formData,
                            dataType: 'json',
                            success: function(response) {
                                $("#confirmation").text(response.message);
                                $('input[type="text"]').val('');
                                $('input[type="number"]').val('');
                                $('input[type="email"]').val('');
                                $('textarea').val('');
                                setSubmissionCookie(userIpAddress);
                            },
                            error: function(xhr, status, error) {
                                $("#confirmation").text(xhr.responseText);
                            }
                        });
                    }
                }

            });
        });

        function checkSubmissionCookie(ipAddress) {
            var cookies = document.cookie.split(';');
            for (var i = 0; i < cookies.length; i++) {
                var cookie = cookies[i].trim();
                if (cookie.startsWith("submission=")) {
                    var value = cookie.substring("submission=".length);
                    if (value === ipAddress) {
                        return true;
                    }
                }
            }
            return false;
        }

        function setSubmissionCookie(ipAddress) {
            var now = new Date();
            now.setTime(now.getTime() + 24 * 60 * 60 * 1000);
            document.cookie = "submission=" + ipAddress + "; expires=" + now.toUTCString() + "; path=/";
        }

        function addItem() {
            var itemsContainer = document.getElementById('itemsContainer');

            // Create a new input field for the item
            var inputField = document.createElement('input');
            inputField.type = 'text';
            inputField.name = 'items[]'; // Use an array notation for multiple items
            inputField.className = 'form-control mt-3';
            inputField.required = true;

            // Append the input field to the container
            itemsContainer.appendChild(inputField);
        }

        function validateForm() {
            // Frontend validation
            var amount = $('#amount').val();
            var buyer = $('#buyer').val();
            var receipt_id = $('#receipt_id').val();
            var itemInputs = document.getElementsByName('items[]');
            var buyer_email = $('#buyer_email').val();
            var note = $('#note').val();
            var city = $('#city').val();
            var phone = $('#phone').val();
            var entry_by = $('#entry_by').val();


            for (var i = 0; i < itemInputs.length; i++) {
                var itemValue = itemInputs[i].value.trim();
                // Check if the value contains any non-alphabetic characters
                if (!/^[a-zA-Z]+$/.test(itemValue)) {
                    alert('Invalid item. Please enter only text.');
                    return false;
                }
            }

            // Validate amount: only numbers
            if (!$.isNumeric(amount)) {
                alert('Invalid amount. Please enter only numbers.');
                return false;
            }

            // Validate buyer: only text, spaces, and numbers, not more than 20 characters
            if (!/^[a-zA-Z0-9 ]{1,20}$/.test(buyer)) {
                alert('Invalid buyer. Please enter only text, spaces, and numbers (not more than 20 characters).');
                return false;
            }

            // Validate receipt_id: only text
            if (!/^[a-zA-Z]+$/.test(receipt_id)) {
                alert('Invalid receipt ID. Please enter only text.');
                return false;
            }

            // Validate buyer_email: only emails
            if (!isValidEmail(buyer_email)) {
                alert('Invalid buyer email. Please enter a valid email address.');
                return false;
            }

            // Validate note: up to 30 words
            var words = note.trim().split(/\s+/).length;
            if (words > 30) {
                alert('Exceeded word limit for note. Please enter up to 30 words.');
                return false;
            }

            // Validate city: only text and spaces
            if (!/^[a-zA-Z\s]+$/.test(city)) {
                alert('Invalid city. Please enter only text and spaces.');
                return false;
            }

            // Validate phone: only numbers
            if (!/^\d+$/.test(phone)) {
                alert('Invalid phone number. Please enter only numbers.');
                return false;
            }

            // Validate entry_by: only numbers
            if (!/^\d+$/.test(entry_by)) {
                alert('Invalid entry by. Please enter only numbers.');
                return false;
            }

            return true;
        }

        function isValidEmail(email) {
            // Email validation regex pattern
            var pattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return pattern.test(email);
        }
        document.getElementById('phone').addEventListener('input', function() {
            // Get the input value
            var phoneNumber = this.value;

            // Remove any non-numeric characters
            phoneNumber = phoneNumber.replace(/\D/g, '');

            // Remove leading "0" if present
            if (phoneNumber.startsWith('0')) {
                phoneNumber = phoneNumber.substr(1);
            }

            // Prepend "880" if it is not already present
            if (!phoneNumber.startsWith('880')) {
                phoneNumber = '880' + phoneNumber;
            }

            // Update the input value
            this.value = phoneNumber;
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>

</body>

</html>