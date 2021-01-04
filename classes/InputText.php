<?php

namespace {
	require_once 'InputTextType.php';
}

namespace WeatherReport {
	use WeatherReport\InputText\Type;

	class InputText {
		private Type $type;
		private string $name;
		private string $placeholder;
		private string $title;
		private string $pattern;
		private string $value;
		private string $error_message;
		private int $maxlength;

		public function __construct(Type $type, string $name, string $placeholder,
									string $title, int $maxlength, string $pattern = '',
									string $error_message = '', string $value = '') {
			$this->type = $type;
			$this->name = $name;
			$this->placeholder = $placeholder;
			$this->title = $title;
			$this->pattern = $pattern;
			$this->value = $value;
			$this->error_message = $error_message;
			$this->maxlength = $maxlength;
		}

		public function setErrorMessage(string $error_message): void {
			$this->error_message = $error_message;
		}

		public function setValue(string $value): void {
			$this->value = $value;
		}

		public function show(): void {
			echo "<div class='input'>
				<input id='{$this->name}' name='{$this->name}' title='{$this->title}' ";
			if ($this->pattern !== '') {
				echo "pattern='{$this->pattern}' ";
			}
			if ($this->maxlength > 0) {
				echo "maxlength='{$this->maxlength}' ";
			}
			echo "type='{$this->type}' required placeholder=' '>
				<label for='{$this->name}'>{$this->placeholder}</label>";
			if ($this->error_message !== '') {
				echo "<span class='error'>{$this->error_message}</span>";
			}
			echo '</div>';
		}
	}
}