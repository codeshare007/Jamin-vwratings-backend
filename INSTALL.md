### Install
* composer install
* php artisan migrate
* php artisan key:generate
* php artisan jwt:secret
* php artisan storage:link
* setup mailer and db in env

env string OLD_DATABASE for migrating from old yii table

### cron:
*/5 * * * * cd /var/www/vwratings/vwratings-backend && php artisan unclaim >> /dev/null 2>&1
