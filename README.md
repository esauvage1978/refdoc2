# refdoc2
1. Create file .env.local
2. paste this code in this file
DATABASE_URL=mysql://root:@127.0.0.1:3306/refdoc2?serverVersion=5.7
###< doctrine/doctrine-bundle ###
#mail.manuso.fr
ES_APP_NAME="RefDoc"
ES_APP_CONTENT="Référentiel documentaire de la CPAM des Flandres"
ES_MAILER_OBJECT_PREFIXE="[ RefDoc ]"
ES_MAILER_SMTP_HOST=mail.manuso.fr
ES_MAILER_SMTP_PORT=465
ES_MAILER_USER_NAME=refdoc@manuso.fr
ES_MAILER_USER_MAIL=refdoc@manuso.fr
ES_MAILER_USER_PASSWORD=
ES_NEWS_TIME=8
ES_TREE_UNDEVELOPPED_NBR=100
ES_MAILER_WORKFLOW_TORESUME=0
ES_MAILER_WORKFLOW_TOVALIDATE=0
ES_MAILER_WORKFLOW_TOCONTROL=0
ES_MAILER_WORKFLOW_TOCHECK=0
ES_MAILER_WORKFLOW_PUBLISHED=0
ES_MAILER_WORKFLOW_TOREVISE=0
ES_MAILER_WORKFLOW_INREVIEW=0

3. change configuration of BDD and email
4. run 
composer install
composer update
php bin/console doctrine:database:create
php bin/console doctrine:schema:update --force
php bin/console app:loadfixtures
5. change in file .env dev to prod
enjoy