<?php

namespace ColdTrick\Ideation;

use Elgg\Notifications\NotificationEventHandler;

class CreateIdeaEventHandler extends NotificationEventHandler {
	
	/**
	 * {@inheritDoc}
	 */
	protected function getNotificationSubject(\ElggUser $recipient, string $method): string {
		return elgg_echo('ideation:notification:create:subject', [$this->event->getObject()->getDisplayName()], $recipient->getLanguage());
	}
	
	/**
	 * {@inheritDoc}
	 */
	protected function getNotificationSummary(\ElggUser $recipient, string $method): string {
		return elgg_echo('ideation:notification:create:summary', [$this->event->getObject()->getDisplayName()], $recipient->getLanguage());
	}
	
	/**
	 * {@inheritDoc}
	 */
	protected function getNotificationBody(\ElggUser $recipient, string $method): string {
		return elgg_echo('ideation:notification:create:body', [
			$this->event->getActor()->getDisplayName(),
			$this->event->getObject()->getDisplayName(),
			$this->event->getObject()->getURL(),
		], $recipient->getLanguage());
	}
}
