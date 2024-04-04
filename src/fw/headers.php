<?php

function set_cors_headers(): void {
    header("Content-Security-Policy: default-src 'self'");
}

set_cors_headers();