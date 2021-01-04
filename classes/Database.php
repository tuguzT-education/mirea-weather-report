<?php

namespace WeatherReport {
	class Database {
		private const HOSTNAME = 'localhost';
		private const USERNAME = 'root';
		private const PASSWORD = '';

		private \mysqli $mysqli;
		private static Database $instance;

		public static function connect(): Database {
			if (!isset(self::$instance)) {
				self::$instance = new Database();
			}
			return self::$instance;
		}

		public function setDatabase(string $database): void {
			$this->mysqli->select_db($database);
		}

		public function query(string $query, ...$args) {
			if (empty($args)) {
				return $this->mysqli->query($query);
			}
			$stmt = $this->mysqli->prepare($query);

			$types = '';
			foreach ($args as $arg) {
				switch (\gettype($arg)) {
					case 'integer':
						$types .= 'i';
						break;
					case 'double':
						$types .= 'd';
						break;
					case 'string':
						$types .= 's';
						break;
				}
			}
			$stmt->bind_param($types, ...$args);

			$stmt->execute();
			$result = $stmt->get_result();
			$stmt->close();
			return $result;
		}

		private function __construct() {
			$driver = new \mysqli_driver();
			$driver->report_mode = MYSQLI_REPORT_ALL & ~MYSQLI_REPORT_INDEX;

			$this->mysqli = new \mysqli(
				self::HOSTNAME,
				self::USERNAME,
				self::PASSWORD
			);
			$this->mysqli->set_charset('utf8mb4');
		}

		public function __destruct() {
			$this->mysqli->close();
			unset($this->mysqli);
		}
	}
}
