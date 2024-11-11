# WebsiteLaravel_FlyHighProject

FlyHigh Project is a Laravel-based web application developed to provide a seamless flight booking experience. The project includes user authentication, a booking system, real-time data handling, and a responsive design for optimal use across various devices.

## Table of Contents

- [Features](#features)
- [Technologies Used](#technologies-used)
- [Installation and Setup](#installation-and-setup)
- [Usage](#usage)
- [Contributing](#contributing)
- [License](#license)

## Features

- **User Authentication**: Secure login and registration features for users.
- **Flight Booking System**: Allows users to search and book flights with real-time data processing.
- **Real-Time Data Updates**: Ensures the latest availability and schedule information for flights.
- **Responsive Design**: Optimized for use on desktops, tablets, and smartphones.

## Technologies Used

- **Backend**: Laravel framework for handling backend processes and routing.
- **Frontend**: Blade templating engine, HTML, CSS, and JavaScript.
- **Database**: MySQL (or compatible databases) for data storage.

## Installation and Setup

To set up this project locally, follow these steps:

1. **Clone the Repository**:
   ```bash
   git clone https://github.com/FebianFelix/WebsiteLaravel_FlyHighProject.git
   ```

2. **Navigate to the Project Directory**:
   ```bash
   cd WebsiteLaravel_FlyHighProject
   ```

3. **Install Dependencies**:
   ```bash
   composer install
   ```

4. **Set Up Environment Variables**:
   - Copy `.env.example` and rename it to `.env`.
   - Configure the database and other necessary settings in the `.env` file.

5. **Generate Application Key**:
   ```bash
   php artisan key:generate
   ```

6. **Run Database Migrations**:
   ```bash
   php artisan migrate
   ```

7. **Start the Development Server**:
   ```bash
   php artisan serve
   ```
   - You can now access the application at [http://localhost:8000](http://localhost:8000).

## Usage

1. Visit the homepage at `http://localhost:8000`.
2. Register or log in to access flight booking features.
3. Search and book flights using the available options.

## Contributing

Contributions are welcome! Please follow these steps to contribute:

1. Fork the repository.
2. Create a new branch (`git checkout -b feature/YourFeature`).
3. Commit your changes (`git commit -m 'Add your feature'`).
4. Push to the branch (`git push origin feature/YourFeature`).
5. Open a Pull Request.

## License

This project is licensed under the MIT License. See the [LICENSE.md](LICENSE.md) file for details.
