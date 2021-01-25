<?php

namespace WeatherReport {
	class TextArea {
		private string $name;
		private string $placeholder;
		private string $title;
		private string $value;
		private string $class;
		private string $error_message;
		private int $maxlength;

		public function __construct(string $name, string $placeholder, string $title,
									int $maxlength, string $class = '',
									string $error_message = '', string $value = '') {
			$this->name = $name;
			$this->placeholder = $placeholder;
			$this->title = $title;
			$this->value = $value;
			$this->class = $class;
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
			echo '<div class="input">' .
				"<textarea id='{$this->name}' name='{$this->name}' " .
				"title='{$this->title}' class='{$this->class}' '";
			if ($this->maxlength > 0) {
				echo "maxlength='{$this->maxlength}' ";
			}
			echo "required placeholder=' '>{$this->value}</textarea>" .
				"<label for='{$this->name}'>{$this->placeholder}</label>";

			if ($this->error_message !== '') {
				echo '<span class="error center_parent_align margin_1_top larger_font">' .
					"{$this->error_message}</span>";
			}
			echo '</div>';
		}

		public function __serialize(): array {
			return [
				'name' => $this->name,
				'placeholder' => $this->placeholder,
				'title' => $this->title,
				'value' => $this->value,
				'class' => $this->class,
				'error_message' => $this->error_message,
				'maxlength' => $this->maxlength
			];
		}

		public function __unserialize(array $data): void {
			$this->name = $data['name'];
			$this->placeholder = $data['placeholder'];
			$this->title = $data['title'];
			$this->value = $data['value'];
			$this->class = $data['class'];
			$this->error_message = $data['error_message'];
			$this->maxlength = $data['maxlength'];
		}
	}
}
