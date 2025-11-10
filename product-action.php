<?php
if(!empty($_GET["action"])) 
{
$productId = isset($_GET['id']) ? htmlspecialchars($_GET['id']) : '';
$quantity = isset($_POST['quantity']) ? htmlspecialchars($_POST['quantity']) : 1;

switch($_GET["action"])
{
	case "add":
		if(!empty($productId)) {
			// Get restaurant details including discount
			$res_stmt = $db->prepare("SELECT r.* FROM restaurant r JOIN dishes d ON d.rs_id = r.rs_id WHERE d.d_id = ?");
			$res_stmt->bind_param('i', $productId);
			$res_stmt->execute();
			$restaurant = $res_stmt->get_result()->fetch_object();
			$restaurant_discount = ($restaurant->discount > 0 && strtotime($restaurant->discount_valid_until) > time()) ? $restaurant->discount : 0;

			// Get dish details with both restaurant and dish-specific discounts
			$stmt = $db->prepare("SELECT d.*, 
					dd.discount_percentage as dish_discount,
					dd.end_date as dish_discount_end,
					CASE 
						WHEN dd.discount_percentage IS NOT NULL THEN dd.discount_percentage
						WHEN ? > 0 THEN ?
						ELSE 0
					END as effective_discount,
					CASE 
						WHEN dd.discount_percentage IS NOT NULL THEN d.price * (1 - dd.discount_percentage/100)
						WHEN ? > 0 THEN d.price * (1 - ?/100)
						ELSE d.price 
					END as final_price
				FROM dishes d
				LEFT JOIN dish_discounts dd ON d.d_id = dd.dish_id 
					AND dd.status = 'active'
					AND CURRENT_DATE BETWEEN dd.start_date AND dd.end_date
				WHERE d.d_id = ?");
			
			$stmt->bind_param("ddddi", 
				$restaurant_discount, 
				$restaurant_discount,
				$restaurant_discount,
				$restaurant_discount,
				$productId
			);
			$stmt->execute();
			$productDetails = $stmt->get_result()->fetch_object();
			
			if($productDetails) {
				$itemArray = array($productDetails->d_id => array(
					'title' => $productDetails->title,
					'd_id' => $productDetails->d_id,
					'quantity' => $quantity,
					'price' => $productDetails->final_price,
					'original_price' => $productDetails->price,
					'discount' => $productDetails->effective_discount
				));
				
				if(!empty($_SESSION["cart_item"])) {
					if(in_array($productDetails->d_id, array_keys($_SESSION["cart_item"]))) {
						foreach($_SESSION["cart_item"] as $k => $v) {
							if($productDetails->d_id == $k) {
								if(empty($_SESSION["cart_item"][$k]["quantity"])) {
									$_SESSION["cart_item"][$k]["quantity"] = 0;
								}
								$_SESSION["cart_item"][$k]["quantity"] += $quantity;
							}
						}
					} else {
						$_SESSION["cart_item"] = $_SESSION["cart_item"] + $itemArray;
					}
				} else {
					$_SESSION["cart_item"] = $itemArray;
				}
			}
		}
		break;
		
	case "remove":
		if(!empty($_SESSION["cart_item"]))
			{
				foreach($_SESSION["cart_item"] as $k => $v) 
				{
					if($productId == $v['d_id'])
						unset($_SESSION["cart_item"][$k]);
				}
			}
			break;
			
	case "empty":
			unset($_SESSION["cart_item"]);
			break;
			
	case "check":
			header("location:checkout.php");
			break;
	}
}



?>

