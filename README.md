# uni
Uni is a management system designed for clubs, associations, organizations, and university administrations to enhance their communication and assist them in digitally managing their data.Features
User Management

    User Roles: Users can be assigned roles such as 'President', 'Vice President', 'Team Leader', and more, granting different levels of access and responsibilities.
    Multiple Club Registration: Users can register with the same email address for multiple clubs and choose the club they want to log into.
    User Authentication: Secure user authentication and session management ensure authorized access to the system.

Club Management

    Club Types: Clubs can be categorized based on their type, such as academic, sports, cultural, etc.
    Club Size and Needs: Clubs can have information about their size, resource requirements, and specific needs.
    Club Events: Manage club events, including event scheduling, acceptance or refusal of event details, and tracking event statuses.

Club Departments

    Departments: Clubs can have different departments or sub-groups based on their activities or focus areas.
    Meetings: Organize meetings for department members and keep track of meeting details, attendees, and minutes of the meetings.

Member Management

    Club Members: Manage club members, including their roles, positions, and contact information.
    Membership Management: Add or remove members from clubs, assign roles and positions within the club.

Notification System

    Alerting Users: Implement a notification system to alert users about important changes, such as event updates, meeting reminders, or club announcements.

Installation

To set up the Club Management System locally, follow these steps:

    Clone the repository: https://github.com/oussemakh1/uni.git

shell

git clone https://github.com/oussemakh1/uni.git

    Configure the database connection:
        Open the config.php file.
        Update the database credentials (host, username, password, database) with your own MySQL database settings.

    Import the database schema:
        In your MySQL database management tool, create a new database.
        Import the SQL file provided (database.sql) into the newly created database.

    Start a local web server:

        You can use PHP's built-in web server by navigating to the project directory and running the following command:

        shell

        php -S localhost:8000

        Alternatively, you can configure a local development environment like XAMPP or WAMP.

    Access the Club Management System in your web browser at http://localhost:8000.

Contributing

Contributions to the Club Management System are welcome! If you want to contribute to the project, please follow the guidelines outlined in the CONTRIBUTING.md file. You can contribute by submitting bug reports, suggesting new features, or implementing improvements and fixes.
License

The  System is open-source software released under the MIT License. Feel free to use, modify, and distribute the project as per the terms of the license.
