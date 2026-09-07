<?php

/** @var \Throwable $exception */
echo 'Application Error: ' . ($exception?->getMessage() ?? 'An error occurred.') . PHP_EOL;
