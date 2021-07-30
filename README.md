## How To Install

- git clone https://github.com/abdomf/orcas-task.git
- cd orcas-task
- composer install
- cp .env.example .env
- configure your database in .env
- php artisan migrate
- php artisan db:seed --class=GenerateTestingApiKeys 
- php artisan key:generate
- php artisan serve
- php artisan sync:users
- visit http://localhost:8000/api/v1/usres or http://localhost:8000/api/v1/uesrs/search?keyword=word in postman.

Note: don't forget to add X-Authorization => 'LPO67QIcaluOQVfUVjMBbF7HAm7EGTqgmou5yT3k50BEEfXpJbjW9FpWjJl2DRiI' header

## Tests    

You can run php artisan test

## Tests List :
✓ it can get users paginated
✓ it can search in users by first name
✓ it can search in users by last name
✓ it can search in users by email
✓ it can store bulk users
✓ it can get valid users
✓ it can reject user with empty first name
✓ it can reject user with empty last name
✓ it can reject user with empty email or email already in database
✓ it can reject user with empty avatar

