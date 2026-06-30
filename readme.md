## About Ultimate POS

Ultimate POS is a POS application by [Ultimate Fosters](http://ultimatefosters.com), a brand of [The Web Fosters](http://thewebfosters.com).

## Installation & Documentation
You will find installation guide and documentation in the downloaded zip file.
Also, For complete updated documentation of the ultimate pos please visit online [documentation guide](http://ultimatefosters.com/ultimate-pos/).

## Security Vulnerabilities

If you discover a security vulnerability within ultimate POS, please send an e-mail to support at thewebfosters@gmail.com. All security vulnerabilities will be promptly addressed.

## License

The Ultimate POS software is licensed under the [Codecanyon license](https://codecanyon.net/licenses/standard).

## Manual Installation

1. 1st clone the project source code.
2. Go to project directory and run `composer install`. or  `composer install ignore platform reqs`
3. Copy `.env.example` to `.env` and update the database credentials.
4. Run migrations `php artisan migrate`.
5. Run seeders `php artisan db:seed`.
6. Alternatively you can import the database dump file `ultimatepos.sql` from the `database/backup` folder.
7. If want to login as superadmin update in `.env` file `ADMINISTRATOR_USERNAMES = username'`. Here username is the username of superadmin.
8. Import the database dump file `ultimatepos_super_admin_pos.sql` from the `database/backup` folder for superadmin.

Businbess:
Username: admin
Pass: 12345678