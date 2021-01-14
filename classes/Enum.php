<?php

abstract class Enum implements \JsonSerializable {
	protected static array $cache = array();
	protected static array $instances = array();
	protected $value;

	public function __construct($value) {
		if ($value instanceof static) {
			$value = $value->getValue();
		}

		if (!$this->isValid($value)) {
			throw new \UnexpectedValueException(
				"Value '$value' is not part of the enum "
				. static::class);
		}

		$this->value = $value;
	}

	public function getValue() {
		return $this->value;
	}

	public static function isValid($value): bool {
		return \in_array($value, static::toArray(), true);
	}

	public static function toArray() {
		$class = static::class;

		if (!isset(static::$cache[$class])) {
			$reflection = new \ReflectionClass($class);
			static::$cache[$class] = $reflection->getConstants();
		}

		return static::$cache[$class];
	}

	public static function keys(): array {
		return \array_keys(static::toArray());
	}

	public static function values(): array {
		$values = array();

		foreach (static::toArray() as $key => $value) {
			$values[$key] = new static($value);
		}

		return $values;
	}

	public static function isValidKey($key): bool {
		$array = static::toArray();

		return isset($array[$key]) || \array_key_exists($key, $array);
	}

	public static function __callStatic($name, $arguments): Enum {
		$class = static::class;
		if (!isset(self::$instances[$class][$name])) {
			$array = static::toArray();
			if (!isset($array[$name]) && !\array_key_exists($name, $array)) {
				$message = "No static method or enum constant '{$name}' in class " . static::class;
				throw new \BadMethodCallException($message);
			}
			return self::$instances[$class][$name] = new static($array[$name]);
		}
		return clone self::$instances[$class][$name];
	}

	public function getKey() {
		return static::search($this->value);
	}

	public static function search($value) {
		return \array_search($value, static::toArray(), true);
	}

	public function __toString(): string {
		return (string) $this->value;
	}

	final public function equals($variable = null): bool {
		return $variable instanceof self
			&& $this->getValue() === $variable->getValue()
			&& static::class === \get_class($variable);
	}

	public function jsonSerialize() {
		return $this->value;
	}

	public function __serialize(): array {
		return ['value' => $this->value];
	}

	public function __unserialize(array $data): void {
		$this->value = $data['value'];
	}
}
