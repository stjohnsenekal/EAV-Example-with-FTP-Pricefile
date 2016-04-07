<?php

/* The integration engine interface */

interface IEngine
{
	/* connects to server through protocol x */
	public function connect();

	/* retrieves the pricefiles */
	public function fetch($remotePriceFile, $relativeDirectory);

	/* loads the xml or csv into memory */
	public function load($localPriceFile); 
}

?>