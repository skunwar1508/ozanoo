<?php 
include 'config.php';
$db = new Database();
if(!empty($_GET["action"])) {
	@$url=$_REQUEST['url'];
switch($_GET["action"]) {
	case "add":
		if(!empty($_POST["quantity"])) {
			//echo (int)$_GET["pid"];
			 $productByCode = $db->runQuery("SELECT * FROM product WHERE id='" . (int)$_GET["pid"] . "'");
			$base=$productByCode[0]['product_price'];
			$dis_per=$productByCode[0]['product_disc_per'];
			//$dis_value=($base*$dis_per)/100;			
			$subtotal = $dis_per;
			/////adding in db
			if(isset($_SESSION["ozanouser"]))
			{
				$user_ema=$_SESSION["ozanouser"];
			$query="select * from user_carts where pid='" . (int)$_GET["pid"] . "' and email='$user_ema'";
			$nums=$db->db_num($query);
			if($nums>0) { } 
			else 
			{
				$inset=$db->insertQuery("INSERT INTO user_carts(id,pid,email,qun)values(NULL,'" . (int)$_GET["pid"] . "','$user_ema','" . (int)$_POST["quantity"] . "')");
			 }
			}
			///////////////adding in db
			$itemArray = array($productByCode[0]["product_id"]=>array('name'=>$productByCode[0]["product_name"], 'id'=>$productByCode[0]["id"], 'quantity'=>$_POST["quantity"], 'price'=>$subtotal));
			
			if(!empty($_SESSION["cart_item"])) 
			{
				if(in_array($productByCode[0]["id"],$_SESSION["cart_item"])) 
				{
					foreach($_SESSION["cart_item"] as $k => $v) 
					{
							if($productByCode[0]["id"] == $k)
								$_SESSION["cart_item"][$k]["quantity"] = $_POST["quantity"];
								
					}					
				} 
				else 
				{
					$_SESSION["cart_item"] = array_merge($_SESSION["cart_item"],$itemArray);					
				}
			}
			 else 
			{
				$_SESSION["cart_item"] = $itemArray;
			}
			$_SESSION['cartmsg'] = "Your product has been successfully added to cart ! ";
			header("Location:cart.php");
		}
	break;
	case "remove":
		if(!empty($_SESSION["cart_item"])) {
			foreach($_SESSION["cart_item"] as $k => $v) {
				////removing from db
				if(isset($_SESSION["ozanouser"]))
			{
				$user_ema=$_SESSION["ozanouser"];
			$query="select * from user_carts where pid='" . (int)$_GET["pid"] . "' and email='$user_ema'";
			$nums=$db->db_num($query);
				if($nums>0) 
				{ 
					$del=$db->deleteQuery("delete from user_carts where pid='" . (int)$_GET["pid"] . "'");
				} 
			
			}
				//////removing from db 
				$productmatch = $db->runQuery("SELECT * FROM product WHERE id='" . (int)$_GET["pid"] . "'");
				$matchingid = $productmatch[0]['product_id'];
					if($matchingid == $k)
						unset($_SESSION["cart_item"][$k]);				
					if(empty($_SESSION["cart_item"]))
						unset($_SESSION["cart_item"]);
			}
			$_SESSION['coupon_total'] = NULL;
			$_SESSION['coupon_id'] = NULL;
			$_SESSION['cartmsg']= "Product has been successfully removed from cart !";
			if(isset($_GET['redirect']))
			{
				$val=$_GET['redirect'];
				header("Location:".$val);
			}
			else
			{
			header("Location:cart.php");
			}
		}
	break;
	case "update":
		if(!empty($_SESSION["cart_item"])) {
			foreach($_SESSION["cart_item"] as $k => $v) {
				//print_r($v);				
				$quantity = $_REQUEST['quantity'];
				if($quantity>0)
				{
				
				if($v['id']==$_REQUEST["pid"])
					{
					$productmatch = $db->runQuery("SELECT * FROM product WHERE id='" . (int)$_REQUEST["pid"] . "'");
					$matchingid = $productmatch[0]['total_quantity'];
						if($matchingid > $quantity || $matchingid==$quantity)
						{
							$_SESSION["cart_item"][$k]["quantity"] = $quantity;
							unset($_POST);
						}
						//print_r($v);
					}
					$_SESSION['coupon_total'] = NULL;
			$_SESSION['coupon_id'] = NULL;
			$_SESSION['cartmsg'] = "Your cart has been Updated.";
			header("Location:".$url);
				}
				else
				{
					$_SESSION['cartmsg'] = "Quantity can be less than zero !";
					header("Location:".$url);
				}
		  	}
			
		}
	break;
	case "empty":
		unset($_SESSION["cart_item"]);
		$_SESSION['cartmsg'] = "Item has been successfully removed from cart.";
		header("Location:".$url);
	break;	
}
}
?>