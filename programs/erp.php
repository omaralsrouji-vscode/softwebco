<?php
$catalog = require __DIR__ . '/catalog.php';
$program = $catalog['erp'] ?? null;
if (!$program) { http_response_code(404); exit('Program not found.'); }
require __DIR__ . '/template.php';
