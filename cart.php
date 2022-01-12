<?php 


if(!isset($_SESSION['carrinho_rodagem'])) {
	$_SESSION['carrinho_rodagem'] = array();
}

function addCart($id, $quantity) {
	if(!isset($_SESSION['carrinho_rodagem'][$id])){ 
		$_SESSION['carrinho_rodagem'][$id] = $quantity; 
	}
}

function deleteCart($id) {
	if(isset($_SESSION['carrinho_rodagem'][$id])){ 
		unset($_SESSION['carrinho_rodagem'][$id]); 
	} 
}

function updateCart($id, $quantity) {
	if(isset($_SESSION['carrinho_rodagem'][$id])){ 
		if($quantity > 0) {
			$_SESSION['carrinho_rodagem'][$id] = $quantity;
		} else {
		 	deleteCart($id);
		}
	}
}

function getContentCart($pdo) {
	
	$results = array();
	
	if($_SESSION['carrinho_rodagem']) {
		
		$cart = $_SESSION['carrinho_rodagem'];
		$products =  getProductsByIds($pdo, implode(',', array_keys($cart)));

		foreach($products as $product) {

			$results[] = array(
							  'id' => $product['produto_id'],
							  'name' => $product['produto_descricao'],
							  'price' => $product['produto_preco'],
							  'quantity' => $cart[$product['produto_id']],
							  'subtotal' => $cart[$product['produto_id']] * $product['produto_preco'],
						);
		}
	}
	
	return $results;
}

function getTotalCart($pdo) {
	
	$total = 0;

	foreach(getContentCart($pdo) as $product) {
		$total += $product['subtotal'];
	} 
	return $total;
}