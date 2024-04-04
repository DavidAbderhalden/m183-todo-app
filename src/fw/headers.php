<?php

function set_cors_headers(): void {
    header("Content-Security-Policy: ".
        "default-src 'self';".
        "script-src 'self' https://cdnjs.cloudflare.com;".
        "style-src 'self';".
        "img-src 'self';"
    );
}

set_cors_headers();