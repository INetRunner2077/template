<?php

namespace Bitrix\Im\V2\Bot;

use Bitrix\Im;
use Bitrix\Im\V2\Chat;
use Bitrix\Im\V2\Message;
use Bitrix\Im\V2\Common\ContextCustomer;
use Bitrix\Im\V2\Message\Send\SendingConfig;

class BotService
{
	use ContextCustomer;

	private SendingConfig $sendingConfig;

	/**
	 * @param SendingConfig|null $sendingConfig
	 */
	public function __construct(?SendingConfig $sendingConfig = null)
	{
		if ($sendingConfig === null)
		{
			$sendingConfig = new SendingConfig();
		}
		$this->sendingConfig = $sendingConfig;
	}

	/**
	 * @param Chat $chat
	 * @param Message $message
	 * @return void
	 */
	public function runMessageCommand(Chat $chat, Message $message): void
	{
		$arFields = array_merge(
			$message->toArray(),
			$this->sendingConfig->toArray(),
			[
				'COMMAND_CONTEXT' => 'TEXTAREA'
			]
		);
		$result = Im\Command::onCommandAdd($message->getMessageId(), $arFields);
		if (!$result)
		{
			Im\Bot::onMessageAdd($message->getMessageId(), $arFields);
		}
	}
}