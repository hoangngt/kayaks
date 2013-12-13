<?php 'git reset --hard HEAD'; ?>;
<?php 'git pull origin develop'; ?>;
<?php 'find . -type d -print0 | xargs -0 chmod 0755'; ?>
<?php 'find . -type f -print0 | xargs -0 chmod 0644'; ?>