<?php
session_start();

if (!isset($_SESSION['customer_email'])) {
    echo "<script>window.open('../checkout.php','_self')</script>";
} else {
    include("includes/db.php");
    include("includes/header.php");
    include("functions/functions.php");
    include("includes/main.php");

    if (isset($_GET['order_id'])) {
        $order_id = $_GET['order_id'];
    }
?>

<div id="content"><!-- content Starts -->
    <div class="container"><!-- container Starts -->
        <div class="col-md-3"><!-- col-md-3 Starts -->
            <?php include("includes/sidebar.php"); ?>
        </div><!-- col-md-3 Ends -->

        <div class="col-md-9"><!-- col-md-9 Starts -->
            <div class="box"><!-- box Starts -->
                <h1 align="center">Please Confirm Your Payment</h1>
                <form action="confirm.php?update_id=<?php echo $order_id; ?>" method="post" enctype="multipart/form-data">
                    <!--- form Starts -->
                    <div class="form-group"><!-- form-group Starts -->
                        <label>Select Payment Mode:</label>
                        <select name="payment_mode" class="form-control" onchange="toggleFields(this.value)">
                            <option value="Select Payment Mode">Select Payment Mode</option>
                            <option value="Bank Code">Bank Code</option>
                            <option value="Western Union">Western Union</option>
                            <option value="COD">COD</option>
                        </select>
                    </div><!-- form-group Ends -->
                    <div class="form-group"><!-- form-group Starts -->
                        <label>Invoice No:</label>
                        <input type="text" class="form-control" name="invoice_no" id="invoice_no" disabled>
                    </div><!-- form-group Ends -->
                    <div class="form-group"><!-- form-group Starts -->
                        <label>Amount Sent:</label>
                        <input type text="class="form-control" name="amount_sent" id="amount_sent" disabled>
                    </div><!-- form-group Ends -->
                    <div class="form-group"><!-- form-group Starts -->
                        <label>Transaction/Reference Id:</label>
                        <input type="text" class="form-control" name="ref_no" id="ref_no" disabled>
                    </div><!-- form-group Ends -->
                    <div class="form-group"><!-- form-group Starts -->
                        <label>Payment Date:</label>
                        <input type="text" class="form-control" name="date" id="date" disabled>
                    </div><!-- form-group Ends -->
                    <div class="form-group"><!-- form-group Starts -->
                        <label>COD Address:</label>
                        <input type="text" class="form-control" name="cod_address" id="cod_address" disabled>
                    </div><!-- form-group Ends -->
                    <div class="text-center"><!-- text-center Starts -->
                        <button type="submit" name="confirm_payment" class="btn btn-primary btn-lg">
                            <i class="fa fa-user-md"></i> Confirm Payment
                        </button>
                    </div><!-- text-center Ends -->
                </form><!--- form Ends -->
                <?php
                if (isset($_POST['confirm_payment'])) {
                    $update_id = $_GET['update_id'];
                    $invoice_no = $_POST['invoice_no'];
                    $amount = $_POST['amount_sent'];
                    $payment_mode = $_POST['payment_mode'];
                    $ref_no = $_POST['ref_no'];
                    $code = $_POST['code'];
                    $payment_date = $_POST['date'];
                    $complete = "Complete";
                    $insert_payment = "insert into payments (invoice_no,amount,payment_mode,ref_no,code,payment_date) values ('$invoice_no','$amount','$payment_mode','$ref_no','$code','$payment_date')";
                    $run_payment = mysqli_query($con, $insert_payment);
                    $update_customer_order = "update customer_orders set order_status='$complete' where order_id='$update_id'";
                    $run_customer_order = mysqli_query($con, $update_customer_order);
                    $update_pending_order = "update pending_orders set order_status='$complete' where order_id='$update_id'";
                    $run_pending_order = mysqli_query($con, $update_pending_order);
                    if ($run_pending_order) {
                        echo "<script>alert('Your payment has been received. The order will be completed within 24 hours.')</script>";
                        echo "<script>window.open('my_account.php?my_orders','_self')</script>";
                    }
                }
                ?>
            </div><!-- box Ends -->
        </div><!-- col-md-9 Ends -->
    </div><!-- container Ends -->
</div><!-- content Ends -->
<?php
include("includes/footer.php");
?>
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script>
    function toggleFields(paymentMode) {
        var invoiceNo = document.getElementById("invoice_no");
        var amountSent = document.getElementById("amount_sent");
        var refNo = document.getElementById("ref_no");
        var date = document.getElementById("date");
        var codAddress = document.getElementById("cod_address");
        
        invoiceNo.disabled = true;
        amountSent.disabled = true;
        refNo.disabled = true;
        date.disabled = true;
        codAddress.disabled = true;

        if (paymentMode === "Bank Code" || paymentMode === "Western Union") {
            invoiceNo.disabled = false;
            amountSent.disabled = false;
            refNo.disabled = false;
            date.disabled = false;
        } else if (paymentMode === "COD") {
            codAddress.disabled = false;
        }
    }
</script>
</body>
</html>
<?php
}
?>
