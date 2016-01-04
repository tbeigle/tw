<?php

namespace Wooclover\Core;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

class IntelligenceReportManager {

	private $mapper;

	public function __construct() {

		$this->mapper = new Mappers\IntelligenceReportMapper();
	}

	public function getOptions() {

		return $this->mapper->getOptions();
	}

	public function getRunningDays() {
		return $this->mapper->getDaysOfTheWeek();
	}

	/**
	 * Save options
	 * @param array $options
	 */
	public function saveOptions( $options ) {
		$this->mapper->saveOptions( $options );
	}

	public function getSendReportTo() {
		return $this->mapper->getSendReportTo();
	}

	public function hasToRun() {


		// First check if it is enabled
		if ( ! $this->mapper->isEnabled() ) {
			return false;
		}

		// Check if is the day
		$days = $this->getRunningDays();

		if ( Utils::isEmpty( $days ) ) {
			$days = array();
		}


		return in_array( strtolower( date( 'l' ) ), $days );
	}

	public function sendDailyReport() {

		//if ( !$this->hasToRun() ) {
		//	return false;
		//}

		$registry = Settings\GfRegistry::instance();


		$message = "";

		$endDate = New \DateTime();
		$endDate = $endDate->sub( new \DateInterval( 'P1D' ) );
		$endDate = $endDate->format( "Y-m-d" ) . " 23:59:59";

		$startDate = New \DateTime();
		$startDate = $startDate->sub( new \DateInterval( 'P1D' ) );
		$startDate = $startDate->format( "Y-m-d" ) . " 00:00:00";

		//apply filters to change report dates
		$startDate = apply_filters( 'gf-marketing/report/start', $startDate );
		$endDate = apply_filters( 'gf-marketing/report/end', $endDate );

		$data = $this->getReportData( $startDate, $endDate );

		foreach ( $data as $element ) {
			$message .= $element->toHtml();
		}

		$headers = "From: Intelligence GF Report <{$this->getSendReportTo()}> \r\n";
		$headers .= "Content-Type: text/html; charset=UTF-8\r\n";

		$subject = "Activity Report";

		$message = \GFSeoMarketingAddOn\Core\MailFormatter::prepareContentEmail( $message );
		die( $message );

		//Send notification
		return \wp_mail( $this->getSendReportTo(), $subject, $message, $headers );
	}

	private function getReportData( $startDate, $endDate ) {

		/* $startDate = New \DateTime();
		  $startDate = $startDate->format( "Y-m-d" ) . " 00:00:00";
		  $endDate = new \DateTime();
		  $endDate = $endDate->add( new \DateInterval( 'P1D' ) );
		  $endDate = $endDate->format( "Y-m-d" ) . " 00:00:00"; */
		$start = new \DateTime( $startDate );
		$end = new \DateTime( $endDate );

		$forms = GravityFormWrapper::getForms();
		$data = array();

		$headLine = new \GFSeoMarketingAddOn\Core\Domain\IntelligenceReport\HeadLine();

		if ( $start->format( 'F jS, Y' ) == $end->format( 'F jS, Y' ) ) {
			$headlineText = $start->format( 'F jS, Y' );
		} else {
			$headlineText = $start->format( 'F jS, Y' ) . " - " . $end->format( 'F jS, Y' );
		}

		$headLine->setText( 'Gravity Forms Activity (' . $headlineText . ")" );
		$data[] = $headLine;

		$table = new \GFSeoMarketingAddOn\Core\Domain\IntelligenceReport\Table();

		$table->setTitle( 'Form Activity' );

		$table->addColumn( "" );
		$table->addColumn( "# of Entries" );
		$table->addColumn( "# of Views" );
		$table->addColumn( "Conversion Rate" );

		foreach ( $forms as $form ) {

			$entryCount = GravityFormWrapper::getLeadsCount( $form->id, $startDate, $endDate );
			$viewCount = GravityFormWrapper::getCountViewsPerDay( $form->id, $startDate, $endDate );

			$conversion = "0%";
			if ( $viewCount > 0 ) {
				$conversion = (number_format( $entryCount / $viewCount, 3 ) * 100) . "%";
			} else {
				$viewCount = "0";
			}

			$formTitle = '#' . $form->id . ' ' . $form->title;

			$table->addRow( array( $formTitle, $entryCount, $viewCount, $conversion ) );
		}
		$data[] = $table;

		// Referrals
		$referralTable = new \GFSeoMarketingAddOn\Core\Domain\IntelligenceReport\Table();
		$referralTable->setTitle( 'Top referrals' );

		$entryMapper = new Mappers\EntryMapper();
		$referrals = $entryMapper->getDifferentReferrals( $startDate, $endDate );

		$referralTable->addColumn( "Referral Source" );
		$referralTable->addColumn( "#" );

		foreach ( $referrals as $referral ) {

			$referralTable->addRow( array( $referral->referral_type, $referral->count ) );
		}

		$data[] = $referralTable;

		foreach ( $forms as $form ) {

			$submissionsTable = new \GFSeoMarketingAddOn\Core\Domain\IntelligenceReport\Table();
			$formTitle = "<a href='" . admin_url( "page=gf_entries&id={$form->id}" ) . "'> " . "{$form->title} [ID=#" . $form->id . "]" . "</a>";
			$submissionsTable->setTitle( $formTitle );

			$submissionsTable->addColumn( "Id" );
			$submissionsTable->addColumn( "Date" );
			$submissionsTable->addColumn( "Contact" );
			$submissionsTable->addColumn( "Referral Source" );

			$leads = GravityFormWrapper::getLeads( $form->id, $startDate, $endDate );

			//TODO: mayba a filter to select the limit of entries to display
			$limit = 5;
			foreach ( $leads as $lead ) {

				$lead = GravityFormWrapper::getLead( $lead[ 'id' ] );
				$entry = $entryMapper->get( $lead[ 'id' ] );
				$contact = "";
				for ( $i = 1; $i <= 2; $i ++  ) {
					if ( $contact ) {
						$contact .= ((isset( $lead[ $i ] ) && $lead[ $i ] ) ? (" - " . $lead[ $i ]) : '');
					} else {
						$contact .= isset( $lead[ $i ] ) ? $lead[ $i ] : '';
					}
				}

				$date = new \DateTime( $lead[ 'date_created' ] );

				$entryLink = "<a href='" . admin_url( "admin.php?page=gf_entries&view=entry&id={$form->id}&lid={$lead[ 'id' ]}&filter=&paged=1&pos=0&field_id=&operator=" ) . "'>" . "Entry #" . $lead[ 'id' ] . " </a>";

				$referral = $entry->getReferral();
				if ( strlen( $referral ) > 40 ) {
					$referral = substr( $referral, 0, 40 ) . '...';
				}

				$submissionsTable->addRow( array( $entryLink, date_format( $date, 'h:i:s A' ), $contact, $referral ) ); //$entry->getReferral() ) );

				$limit --;
				if ( $limit == 0 ) {
					break;
				}
			}

			if ( $leads && count( $leads ) > 0 ) {
				$data[] = $submissionsTable;
			}
		}



		return $data;
	}

}
