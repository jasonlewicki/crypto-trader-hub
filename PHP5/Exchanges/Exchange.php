<?php

interface Exchange{
	public function bid($price, $quantity);
	public function ask($price, $quantity);
	public function cancelOrder();
}
