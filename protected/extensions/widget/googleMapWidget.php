<?php
class googleMapWidget extends CWidget
{
	public $userAddress;
	public function run()
	{
		// this method is called by CController::endWidget()
		$gMap = new EGMap();
		$gMap->setWidth(500);
		$gMap->setHeight(400);
		$gMap->zoom = 10;

		// Create geocoded address
		$geocodedAddress = new EGMapGeocodedAddress($this->userAddress);
		$geocodedAddress->geocode($gMap->getGMapClient());
		 
		// Center the map on geocoded address
		 $gMap->setCenter($geocodedAddress->getLat(), $geocodedAddress->getLng());
		 
		// Add marker on geocoded address
		$gMap->addMarker(
		     new EGMapMarker($geocodedAddress->getLat(), $geocodedAddress->getLng())
		);
		$gMap->renderMap();
	}
}
?>