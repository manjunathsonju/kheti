<script>	
	window.CellPayPayment("init", "9841362180", {
		merchant_image_url: 'https://dvexcellus.com/wp-content/themes/excellus/images/dv-excellus-logo.jpg',
		merchant_name: 'D.V. EXCELLUS PVT.LTD',
		payment_type: 1,
		live: true,
		onSuccess: function(state){
			console.log(state);
			//hide payment button
			document.getElementById("cellpay-payment-button").style.display = "none";
			window.location = "process.php?orderId=" + <?php echo $orderId;?> + "&cellPayId=" + state.cellpay_id;
		},
		onError: function(state){
			console.log(state);
			//redirect khetifood failure url
			window.location = "https://khetifood.com/index.php?route=checkout/failure";
		},
		onWindowClose: function(){

		}
	})

	window.CellPayPayment("price", {
		price: <?php echo $amount;?>,
		invoice_id: <?php echo $orderId;?>,
	});
	
	window.addEventListener('load', (event) => {
		document.querySelectorAll('#cellpay-payment-button button')[0].click();
	});
		
	
</script>
</body>
</html>
