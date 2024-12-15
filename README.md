# News Aggregator

The repository contains the source code for the News Aggregator Backend API's.

## Prerequisites
**docker and docker compose installed**

## Cloning the Repository

To clone the repository, run the following command:

**git clone git@github.com:SheikhGhani/news-aggregator.git**

## Checking Out into the Project Folder

Navigate into the project folder:

**cd news-aggregator**

**Create a .env file using the .env.example file in the root of the project directory**

## Setting Up the Project

To set up the project using Docker Compose, run the following command:

**docker-compose up --build -d**

## Entering the App Container

To enter the app container and execute the bash entrypoint file, run:

- **docker exec -it news-aggregator bash**
- **bash entrypoint.sh**
- **This command will generate the app key, run the migrations, run the Tests, seed The database with fake users and articles fetched from the sources and generate the swagger docs.**

## Swagger UI Documentation

The Swagger UI for API documentation is available at:

**http://localhost:8080/api/documentation**

## Endpoints Implementation

The application currently implements the following endpoints:

## Authentication

1. **POST /api/auth/register**: Register a new user.
2. **POST /api/auth/login**: Logged in the user and returns the authetication token.
3. **POST /api/auth/logout**: Logged Out the currently logged in user.

## Article

1. **GET /api/articles/**: Get all the articles with pagination and accept query param for filtering the data.
2. **GET /api/auarticlesth/{id}**: Return the article data by id.

## User Preferences

1. **POST /api/preferences/**: Store the User preference.
2. **GET /api/preferences/**: Returns all the preferences of the user
2. **GET /api/preferences/news-feed**: Returns the personalized feed of the user based on preferences

## Implementation

1. When containers are up and we execute the entrypoint file inside the container these things are going to happen:
- **Database migrations to make the required schema**
- **Unit/Feature Testing by laravel package**
- **Database Seeding for demo users with default password Click@123 and Fresh Articles fetched from the news sources**
- **Generating the swagger docs**

2. **Database Design**
- **Database schema is designed for efficient storage of the articles from different sources**
- **articles table for storing the articles, user_preferences table for storing the preferences, users and personal_access_tokens tables for authentication of the user**

3. **Coding Architecture**
Following is the high level information regarding the coding practices.
- **I have used the thin controller approach where all the logic resides inside the Service Class relevant to that controller to keep the controller as clean as possible**
- **I have created a separate route file for each Resourse like Artcile/UserPreference/Auth to keep the main api.php file more maintainable and clean. As the Application grows this approach helps a lot to write maintainable and clean code**
- **Created a Base Class for services to return the Success and Failure JSON response to follow a similiar response structure for all API endpoints**
- **Created the Console command and scheduled it at midnight  everyday to fetch artciles from three different sources**
**For Request Validation I have created the Request classed to seperate the validation logic from the service class.**

4. **Security**

- **Sanctum is used for user authentication All routes are protected by auth:sanctum except register/login routes**
- **Used Eloquent to avoid SQL injection**

5. **Caching**

- **Used Redis for caching the data to improve the performace of the endpoints**

6. **Docker**
- **Dockerized the application using docker-compose to impelement multiconatiner environment i.e app_container, mysql_container and redis_container**

7. **News Sources Used**

- **NewsAPI**
- **New York Times**
- **The Guardian**

8. **Stopping the containers**
- **docker-compose down -v will stop and remove all the containers,volumes and networks**