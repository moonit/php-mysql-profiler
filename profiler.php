<?php

class Profiler {

	static $instance;
	static $checkpoints;
	static $queries;
	static $lastCheckpointTime;

	static function getTime(){
		$miliseconds = strstr(microtime(), ' ', TRUE);
		return time() . strstr($miliseconds, '.');
	}

	static function mark($name){
		$now = self::getTime();

		if (self::$lastCheckpointTime) {
			$timePassedFromLastCheckpoint = $now - self::$lastCheckpointTime;
		}
		else {
			$timePassedFromLastCheckpoint = 0;
		}

		self::$lastCheckpointTime = $now;

		self::$checkpoints[] = array(
			'name'   => $name,
			'marked' => $now,
			'timing' => number_format($timePassedFromLastCheckpoint, 4) . ' ms'
		);
	}

	static function log_query($query, $started = NULL){
		$now = self::getTime();

		if ($started) {
			$timing = $started - $now;
		}

		self::$queries[] = array(
			'query'  => $query,
			'marked' => $now,
			'timing' => ($started === NULL ? 'unknown' : number_format($timing, 4) . ' ms')
		);
	}

	static function start() {
		self::mark('Profiling Started');
	}

	static function time_passed_from_start() {
		$started = self::$checkpoints[0]['marked'];
		$now = self::getTime();
		return number_format($now - $started, 4, '.', '');
	}

	static function total_timing() {
		$started = self::$checkpoints[0]['marked'];
		$last = self::$checkpoints[count(self::$checkpoints) - 1]['marked'];
		return number_format($last - $started, 4);
	}

	static function end($return_report = FALSE) {
		self::mark('Profiling Ended');

		if ($return_report) return self::getReport();
	}

	static function getReport() {
		$total_timing = self::total_timing();

		$_checkpoints = array();
		foreach (self::$checkpoints as $_mark) {
			$_checkpoints[] = $_mark['timing'] . ' -> ' .  $_mark['name'];
		}

		return array(
			'total_script_execution_time' => $total_timing,
			'checkpoint_report'           => $_checkpoints,
//			'checkpoints'                 => self::$checkpoints,
			'queries'                     => self::$queries,
		);
	}

}