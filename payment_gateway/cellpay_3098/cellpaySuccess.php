<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kheti Food | Processing Payment</title>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"
        integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
</head>

<body>
    <div id="loading-image" style="
  position: fixed;
display: none;
background-color: rgb(29, 165, 77);
z-index: 9999;
width: 100%;
background-repeat: no-repeat;
background-position: center;
height: 100%;">
        <img src="payment-payment.gif" style="height: 100%;
  display: block;">
    </div>
	
<?php
$req_dump = print_r($_REQUEST, true);
$fp = file_put_contents('success_request.log', $req_dump, FILE_APPEND);

if(isset($_POST['status']) && $_POST['status'] == 'SUCCESS') {
	?>
	<script>
		$('#loading-image').show();
		$.ajax({
			url: "https://khetifood.com/payment_gateway/cellpay_3098/cellpaySuccessAPI.php",
			type: "POST",
			data: JSON.parse(JSON.stringify(<?php echo json_encode($_POST);?>)),
			success: function (redirect_link) {
				window.location.href = redirect_link;
			},
			complete: function () {
				$('#loading-image').hide();
			},
			error: function (request, status, error) {
				window.location.href = "https://khetifood.com/index.php?route=checkout/failure";
			}
		});
	</script>
	<?php
}
else {
	header("Location: https://khetifood.com/index.php?route=checkout/failure");
}
?>

</body>
</html>