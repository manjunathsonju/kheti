<?php

//database credentials
define('DBHOST','localhost'); //HOSTNAME
define('DBUSER','dvexcellus'); //DATABASE USERNAME
define('DBPASS','dvexcElluS123ktm'); //DATABASE PASSWORD
define('DBNAME','khet_kheti_farm_opencart'); //DATABASE NAME

//database connection
try {
	//create PDO connection 
	$db = new PDO("mysql:host=".DBHOST.";port=3306;dbname=".DBNAME, DBUSER, DBPASS);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} 
catch(PDOException $e) {
	//show error
    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
    exit;
}

//print_r($_POST);exit;

//process quotation form
if(isset($_POST['submitQuotation'])) {
	$vegetables = $_POST['vegetables'];
	$quantities = $_POST['quantities'];
	
	//combine
	$veg_quantity = array_combine($vegetables, $quantities);
	
	$name = $_POST['name'];
	$phone = $_POST['phone'];
	$email = $_POST['email'];
	$address = $_POST['address'];
	
	try {
		$stmt = $db->prepare(" INSERT INTO `oc_quotations` (`veg_quantity`,`name`, `phone`, `email`, `address`)
					VALUES (:veg_quantity, :name, :phone, :email, :address) ");

		$stmt->execute(array(
			':veg_quantity' => json_encode($veg_quantity),
			':name' => $name,
			':phone' => $phone,
			':email' => $email,
			':address' => $address
		));	
	}	
	catch(PDOException $e) {
		//show error
		echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		exit;
	}
	
	echo "<div class='alert alert-success text-center' role='alert'>
	  Quotation sent successfully. Our sales representative will contact you soon!
	</div>";			
}
				
?>