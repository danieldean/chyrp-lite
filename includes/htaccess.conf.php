<?php header("Status: 403"); exit("Access denied."); ?>
# Template to enable clean URLs for the Apache web server.

<IfModule mod_rewrite.c>
RewriteEngine On
RewriteBase /%{CHYRP_PATH}/
RewriteRule ^.+\.twig$ index.php [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_URI} ^/%{CHYRP_PATH}/admin/.+
ReWriteRule ^.+$ /%{CHYRP_PATH}/admin/index.php [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^.+$ index.php [L]
</IfModule>
