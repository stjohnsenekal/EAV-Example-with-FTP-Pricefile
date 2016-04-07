<?php

/* The distributor adapter interface */

interface IAdapter
{
	/* updates a product already in system */
	public function updateProduct($product, $existingProduct); 

	/* creates a new product */
	public function insertProduct($product);

	/* starts process and calls the above */
	public function process($xml);
}

?>