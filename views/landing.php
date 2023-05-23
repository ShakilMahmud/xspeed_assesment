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
    <nav class="navbar navbar-light bg-light mb-5">
        <h1 class="mx-auto">Orders Landing Panel</h1>
    </nav>
    <div class="container">
        <div class="row">
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">This is Order Submission Panel</h5>
                        <a href="<?php echo $BASE_URL . 'index.php?req=data_entry'; ?>" class="btn btn-primary">Orders Panel</a>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">This is Report Panel</h5>
                        <a href="<?php echo $BASE_URL . 'index.php?req=data_report'; ?>" class="btn btn-primary">Report Panel</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>