<h2 align="center">QTech Assignment</h2>

<p align="center">
Backend development for service booking
</p>

## How to install
<pre>git clone https://github.com/abdursoft/qtech.git ./</pre>

Copy this command and run into your preferred folder with your terminal. Then install the composer packages with this command
<pre>composer update</pre>
[Note] make sure you have install the composer globally 

#Database connection and migrate
Create a new file called ```.env``` in your project directory and copy all code from ```.env.example``` and paste into ```.env``` file. Now open it in your code editor and make sure you have enter the correct Database ```Hostname, User, Database Name and password```. Now you are ready to migrate the migrations files.
<pre>php artisan migrate:fresh --seed</pre>
Run this command in your terminal and make sure you have open the terminal from your project directory either it won't work. If the command successfuly run everything, you are  ready to lunch the project.

Let's run the project and test the apis
<pre>php artisan serve</pre>
Run this artisan command to run the project. By default your application will run into ```http://127.0.0.1:8000/``` url if you're in your local machine.

#Auth routes
- http://127.0.0.1:8000/api/register ```post method, fillable [name,email,password]```
- http://127.0.0.1:8000/api/login ```post method, fillable [email,password]```

#Customer authenticated routes
- http://127.0.0.1:8000/api/services ```get method, fetches all services```
- http://127.0.0.1:8000/api/bookings ```post method, fillable [user_id,service_id,booking_date,status]``` status shoulud be ```pending, booked, canceled,completed``` default ```booked```
- http://127.0.0.1:8000/api/bookings ```get method, Fetch all booking according the user ID```

#Admin authenticated routes
- http://127.0.0.1:8000/api/services create new service ```post method, fillable [name, description, price, status]``` status shoulud be ```available or not_available``` default ```available```
- http://127.0.0.1:8000/api/services/{id} update existing service ```put method, fillable [name, description, price, status]``` id should be dynamic with service ID
- http://127.0.0.1:8000/api/services/{id} delete a service ```delete method``` id should be dynamic with service ID
- http://127.0.0.1:8000/api/admin/bookings get all booking list ```get method, Fetch all booking with the user and services```

<pre>For better experiment, please import my qtech.postman_collection.json file into your postman API testing tool</pre>
Thanks, have a great day.
