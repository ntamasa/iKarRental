# iKarRental

University coding assignment, representing a car rental page made in PHP.

# Car Reservation System

## Overview

This project is a PHP-based car reservation system where users can browse a list of cars, view details about each car, and reserve cars for specific time slots. Additionally, an admin panel allows admins to add, modify, and delete car records. Users can register and log in to make reservations, and their booking history is available on their profile page. The project includes authentication, error handling, and a mobile-friendly design.

## Features

### Minimum Requirements
- **Home Page**: Displays a list of all cars with basic details.
- **Car Page**: Each car has a dedicated page showing more details and images.
- **Filtering**: Users can filter cars based on specific criteria.
- **Admin Panel**: Admins can add, modify, and delete cars with proper error handling.
- **User Authentication**: Users can register and log in with error handling. After successful login, users can see that they are logged in.
- **Booking System**: Users can book a car for a time slot, with success or failure notifications. The booking is saved.
- **Profile Page**: Displays a user's past bookings.
- **Admin Features**: Admins can view all bookings and delete them if needed.
  
### Extra Features
- **Calendar View**: Available time slots for a car are shown in a calendar, with only free slots selectable for booking.
- **AJAX Integration**: After a booking, a success or failure message is shown in a modal, without redirecting the page.

## Installation

1. Clone this repository:
```bash
git clone https://github.com/username/iKarRental.git
```

2. Navigate to the project directory:
```bash
cd iKarRental
```

3. Make sure your web server supports PHP (e.g., Apache or Nginx) and that PHP is installed on your system.

4. Set up the database. The project uses a MySQL database. You need to create a database and import the SQL schema provided in the `database` folder.

5. Configure database settings in the `config.php` file.

6. Run the web server, and open your browser to view the site.

## Authentication

- **Registration**: Users can register by providing their name, email, and password. Passwords are hashed for security.

- **Login**: Registered users can log in by entering their email and password. Upon successful login, the user is redirected to their profile page.

- **Logout**: Users can log out from any page. After logging out, they will be redirected to the homepage.

## Admin Panel

- Admins can log in to the admin panel and manage the car data.
- Admins can view all bookings, and they have the ability to delete them if necessary.
- Admins can also edit car details and delete cars.

## User Profile

- Users can view their previous bookings from their profile page.
- Each booking includes the car details and time slots.

## Technologies Used

- PHP
- MySQL
- HTML/CSS
- JavaScript (AJAX)
- Bootstrap for responsive design

## Contributions

Feel free to fork the repository and contribute by submitting pull requests. Contributions are welcome to improve the system, fix bugs, or add additional features.

## License

This project is licensed under the MIT License.
