<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[WebReinvent](https://webreinvent.com/)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Jump24](https://jump24.co.uk)**
- **[Redberry](https://redberry.international/laravel/)**
- **[Active Logic](https://activelogic.com)**
- **[byte5](https://byte5.de)**
- **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).


Admin	admin@icommerce-gabon.com	password
Client	client@test.com	password

Deploiement iCommerce Gabon sur o2switch
1. Creer le sous-domaine dans cPanel
Connecte-toi a ton cPanel o2switch
Va dans Sous-domaines
Cree : icommerce-gabon.ml-market.com
Note le dossier racine cree (par defaut : /home/TONUSER/icommerce-gabon.ml-market.com)
2. Creer la base de donnees MySQL
Dans cPanel > Bases de donnees MySQL
Cree une base : TONUSER_icommerce
Cree un utilisateur : TONUSER_icuser avec un mot de passe fort
Associe l'utilisateur a la base avec tous les privileges
3. Se connecter en SSH et cloner le projet

ssh TONUSER@TONSERVEUR.o2switch.net

# Cloner le repo dans un dossier separe
cd ~
git clone https://github.com/TON-USERNAME/icommerce_gabon.git icommerce_gabon

# Supprimer le dossier du sous-domaine et le remplacer par un lien symbolique
rm -rf ~/icommerce-gabon.ml-market.com
ln -s ~/icommerce_gabon/public ~/icommerce-gabon.ml-market.com
4. Installer les dependances

cd ~/icommerce_gabon

# PHP (o2switch a composer pre-installe)
composer install --no-dev --optimize-autoloader

# Node.js (utiliser la version de o2switch)
npm ci
npm run build
5. Configurer l'environnement

cp .env.example .env
php artisan key:generate
nano .env
Remplis ces valeurs dans le .env :


APP_URL=https://icommerce-gabon.ml-market.com

DB_DATABASE=TONUSER_icommerce
DB_USERNAME=TONUSER_icuser
DB_PASSWORD=TON_MOT_DE_PASSE

MAIL_HOST=TONSERVEUR.o2switch.net
MAIL_PORT=465
MAIL_USERNAME=noreply@ml-market.com
MAIL_PASSWORD=MOT_DE_PASSE_EMAIL
MAIL_ENCRYPTION=ssl
6. Lancer les migrations et le seeder

php artisan migrate --force
php artisan db:seed --force
7. Permissions des dossiers

chmod -R 775 storage bootstrap/cache
8. Cache de production

php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan storage:link
9. SSL (HTTPS)
Dans cPanel > Let's Encrypt SSL ou AutoSSL
Active le certificat pour icommerce-gabon.ml-market.com
Il est generalement active automatiquement sur o2switch
10. Mises a jour futures
Apres un git push sur GitHub, connecte-toi en SSH et lance :


cd ~/icommerce_gabon
bash deploy.sh