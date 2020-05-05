<?php

require __DIR__ . '/../src/helpers.php';

@mkdir(storage_path('logs'), 0777, true);
@mkdir(storage_path('data'), 0777, true);

if (!file_exists(base_path('.env'))) {
    copy(base_path('.env.example'), base_path('.env'));
}