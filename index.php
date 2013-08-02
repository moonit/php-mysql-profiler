<?php

require_once 'profiler.php';

Profiler::start();

// Your php code starts

for ($i = 1; $i < 10; $i++) {
	usleep(rand(1000, 100000));

	Profiler::mark('Checkpoint #' . $i);
}

// Your php code ends

Profiler::end();

// Print profiling results

print '<pre>';
print_r(Profiler::getReport());