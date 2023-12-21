## Getting Started

1. Create `.env` file from provided template `.env_example`
2. Run `docker compose build --no-cache` to build fresh images
3. Run `docker compose up --pull always -d --wait` to start the project
4. Run `docker compose down --remove-orphans` to stop the Docker containers.

## 1. Stádo koní v ohradě

Command `php bin/console herdSize <the size of the side of the chessboard>`
- The command has one mandatory parameter of type integer.
- The command calculates the highest possible amount of knights on a chessboard with dimensions based on the parameter.

## 2. Počasí

1. Run `php bin/console doctrine:fixtures:load` to generate testing Cities.

Command `php bin/console updateWeather <city name>`
- The command has optional parameter of type string.
- If city names are entered, only the specified cities are updated, otherwise all cities in the database are updated.
- It is possible to enter multiple names of cities at once (`php bin/console updateWeather <city name> <city name> <city name>`).

## 3. Kurzy měn

1. Open `https://localhost`
2. Select **Mena** and **Kurz ku dnu**. After submission the app returns rate for selected date.
