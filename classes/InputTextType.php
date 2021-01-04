<?php

namespace {
	require_once 'Enum.php';
}

namespace WeatherReport\InputText {
	/**
	 * @method static self TEXT()
	 * @method static self PASSWORD()
	 * @method static self EMAIL()
	 */
	class Type extends \Enum {
		private const TEXT = 'text';
		private const PASSWORD = 'password';
		private const EMAIL = 'email';
	}
}
