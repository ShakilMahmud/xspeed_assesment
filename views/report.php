<!DOCTYPE html>
<html lang="en">
<?php
$PAGE_URL = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$self_file = basename($_SERVER['PHP_SELF']);
$BASE_URL = stristr($PAGE_URL, $self_file, true);
?>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <div>
        <nav class="navbar navbar-light bg-light mb-5">
        <a class="navbar-brand ps-2" href="<?php echo $BASE_URL; ?>">Home Page</a>
            <h1 class="mx-auto">Orders Report Panel</h1>
        </nav>
        <div class="container">
            <form method="GET" action="">
                <input type="hidden" name="req" value="data_report" />
                <div class="row mb-4">
                    <div class="col-3">
                        <label for="start_date">Start Date:</label>
                        <input class="form-control" type="date" name="start_date">

                    </div>
                    <div class="col-3">
                        <label for="end_date">End Date:</label>
                        <input class="form-control" type="date" name="end_date">

                    </div>
                    <div class="col-3">

                        <label for="user_id">User ID:</label>
                        <input class="form-control" type="text" name="user_id">

                    </div>
                    <div class="col-3 mt-4">
                    <button class="btn btn-primary" type="submit">Filter</button>

                    </div>
                </div>
               
            </form>
        </div>
        <div class="container">
            <?php
            $conn = $this->db_conn->connect_development_database();
            @$TBL_NAME = 'orders';


            @$field = array(
                'amount' => 'amount',
                'buyer' => 'buyer',
                'receipt_id' => 'receipt_id',
                'items' => 'items',
                'buyer_email' => 'buyer_email',
                'buyer_ip' => 'buyer_ip',
                'note' => 'note',
                'city' => 'city',
                'phone' => 'phone',
                // 'hash_key' => 'hash_key',
                'entry_at' => 'entry_at',
                'entry_by' => 'entry_by'
            );

            $sql = "
  SELECT * FROM $TBL_NAME where 1=1 
";

            if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {
                $start_date = $_GET['start_date'];
                $end_date = $_GET['end_date'];
                $sql .= " AND entry_at between '$start_date' and '$end_date'";
            }

            if (!empty($_GET['user_id'])) {
                $user = $_GET['user_id'];
                $sql .= " AND entry_by ='$user'";
            }
            $sql .=  " ORDER BY id desc";

            @$query = mysqli_query($conn, $sql);
            @$num_rows = mysqli_num_rows($query);

            ?>

            <div class="container">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">SL</th>
                                <?php
                                foreach ($field as $key => $val) {
                                ?>
                                    <th scope="col"><?php echo $val; ?></th>
                                <?php } ?>

                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $counting = 0;
                            while (@$rows = mysqli_fetch_array($query)) {
                            ?>
                                <tr>
                                    <td><?php echo ++$counting; ?></td>
                                    <?php foreach ($field as $key => $val) { ?>
                                        <td><?php echo $rows[trim($key)]; ?></td>
                                    <?php } ?>

                                </tr>
                            <?php }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>

</html>