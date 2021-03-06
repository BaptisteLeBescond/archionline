<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Booking;

/**
 * BookingRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class BookingRepository extends \Doctrine\ORM\EntityRepository
{
	public function findBookingByCountry($country)
	{
		$em = $this->getEntityManager();
		$repositoryLocation = $em->getRepository('AppBundle:Location');
		$repositoryHouse = $em->getRepository('AppBundle:House');
		$repositoryBooking = $em->getRepository('AppBundle:Booking');

		$countryLocations = $repositoryLocation->findByCountry($country);

		$countryHouses = $repositoryHouse->findByLocation($countryLocations);

		$result = $repositoryBooking->findByHouse($countryHouses);

		return $result;
	}

	public function findOngoingBookingByCountry($country)
	{
		$em = $this->getEntityManager();
		$repositoryLocation = $em->getRepository('AppBundle:Location');
		$repositoryHouse = $em->getRepository('AppBundle:House');
		$repositoryBooking = $em->getRepository('AppBundle:Booking');

		$countryLocations = $repositoryLocation->findByCountry($country);

		$countryHouses = $repositoryHouse->findByLocation($countryLocations);

		$result = $repositoryBooking->findBy(
			array(
				'house' => $countryHouses,
				'status' => Booking::STATUS_ONGOING,
			)
		);

		return $result;
	}
}
