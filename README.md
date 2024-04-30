# MicroGuard

MicroGuard is a robust web-based management tool designed to streamline the handling of WireGuard VPN clients on MikroTik routers. It simplifies user addition, access revocation, and provides a real-time view of connection statistics.


| Login | Users | Connection |
| --- | --- | --- |
![login](https://github.com/xterm-inator/microguard/assets/7698065/3ea6b5b0-b9ca-4b1b-a546-955724d5bedf) | ![user](https://github.com/xterm-inator/microguard/assets/7698065/4864029e-c176-4577-96f1-20bf3e982b53) | ![connection](https://github.com/xterm-inator/microguard/assets/7698065/1f44b3b7-f4c6-4bd1-819a-b1e19fdf619c)


## Table of Contents
- [Features](#features)
- [Requirements](#requirements)
- [Setup](#setup)
    - [Adding a User to MikroTik for MicroGuard](#adding-a-user-to-mikrotik-for-microguard)
    - [Creating a Road Warrior Wireguard Interface for MicroGuard](#creating-a-road-warrior-wireguard-interface-for-microguard)
    - [Server Installation](#server-installation)
- [Usage](#usage)
- [Local Development and Testing with Docker Compose](#local-development-and-testing-with-docker-compose)
- [Contributing](#contributing)
- [License](#license)

## Features

- **Client Management**: Streamline the creation and management of WireGuard clients on MikroTik routers.
- **Real-time Monitoring**: Access real-time connection statistics and usage insights.
- **Access Control**: Easily grant or revoke access for individual clients.
- **Google SSO**: Secure sign-in using Google's Single Sign-On system.

## Requirements

- MikroTik router with RouterOS version 7.0 or newer.
- Docker for running the MicroGuard server.

## Setup

### Adding a User to MikroTik for MicroGuard

**Using Winbox**:

1. Log in to your MikroTik router using Winbox.
2. Navigate to the "System" menu and select "Users".
3. Click on the "Groups" tab.
4. Click on the "Add New" button to create a new user group.
5. Enter a name for the group, such as "microguard-group" and click on the "OK" button.
6. In the "Permissions" tab, select the desired permissions for the group. For MicroGuard, the user group should have read, write, and API access.
7. Click on the "Apply" button to save the changes.
8. Click on the "Users" tab.
10. Enter the user's details, such as their name and password.

> **Warning**: Always input the correct local subnet into the allowed address. If unsure about the configuration, seek expert advice.


11. In the "Groups" tab, select the "microguard-group" group you just created.
12. Click on the "Apply" button to save the changes.

**Using Command Line**:

To set up a new user group and user, input:
>This will create a new user group named "microguard-group" with the necessary permissions for MicroGuard.
```sh
/user group add name=microguard-group policy=local,read,write,test,api,winbox,password
```

Enter the following command to create a new user:
>Replace "username" with the desired username and "userpassword" with the desired password for the new user.
```sh
/user add name=username group=microguard-group password=userpassword
````

> **Warning**: Ensure correct subnet configuration as highlighted in the Winbox method.

### Creating a Road Warrior WireGuard Interface for MicroGuard

1. Access your MikroTik router via Winbox.
2. Go to "WireGuard" > "Add".
3. Label the interface (e.g., "wireguard road warrior") and click Apply.
4. Document the public key of the freshly created WireGuard server for subsequent use.

### Server Installation

1. Ensure Docker is up and running on your server.
2. Generate an app key via [this generator](https://generate-random.org/laravel-key-generator) for use in upcoming commands.
3. Deploy MicroGuard using:

> If you don't want to use google sso remove the google environment variables from the following commands

**Docker Command**

````bash
docker run -d
--name microguard
-p 8000:8000
-v /path/to/data:/opt/app/storage
-e APP_KEY=
-e GOOGLE_CLIENT_ID=
-e GOOGLE_CLIENT_SECRET=
-e GOOGLE_REDIRECT_URL='https://my.public.address/api/auth/oauth/google/callback'
-e ROUTEROS_HOST='192.168.0.1'
-e ROUTEROS_PORT='8728'
-e ROUTEROS_USER='wireguard'
-e ROUTEROS_PASS='wireguard pass'
-e ROUTEROS_WIREGUARD_INTERFACE='wireguard' #wireguard interface name 
-e ROUTEROS_WIREGUARD_ENDPOINT='192.168.0.1:13231' #ip:port for wireguard interface
-e APP_URL='https://my.public.address'
ghcr.io/xterm-inator/microguard:latest
````

**Docker Compose**:
```yml
version: '3.8'
services:
  microguard:
    image: ghcr.io/xterm-inator/microguard:latest
    container_name: microguard
    restart: always
    ports:
      - 8000:8000
    volumes:
      - /path/to/data:/opt/app/storage
    environment:
      - APP_KEY=
      - GOOGLE_CLIENT_ID=
      - GOOGLE_CLIENT_SECRET=
      - GOOGLE_REDIRECT_URL=https://my.public.address/api/auth/oauth/google/callback
      - ROUTEROS_HOST=192.168.0.1
      - ROUTEROS_PORT=8728
      - ROUTEROS_USER=wireguard
      - ROUTEROS_PASS=wireguard pass
      - ROUTEROS_WIREGUARD_INTERFACE=wireguard
      - ROUTEROS_WIREGUARD_ENDPOINT=192.168.0.1:13231
      - APP_URL=https://my.public.address

```


**Environment Varibles**:
This is a list of the most useful environment variables. To find all available look in api/config files

| Variable | Description | Default |
| --- | --- | --- |
| APP_KEY* | App key used mainly for encryption, set using [this generator](https://generate-random.org/laravel-key-generator) |  |
| APP_URL* | The url this instance will be accessed from, can be localhost, an IP address or a domain eg. http://localhost:8000 | http://localhost:8000 |
| GOOGLE_CLIENT_ID | Google OAuth Client ID (Only required if using google auth) |  |
| GOOGLE_CLIENT_SECRET | Google OAuth Client Secret (Only required if using google auth) |  |
| GOOGLE_REDIRECT_URL | Google OAuth Redirect Url, eg. https://my.public.address/api/auth/oauth/google/callback (Only required if using google auth) |  |
| ROUTEROS_HOST* | IP address of your MikroTik router |  |
| ROUTEROS_PORT* | API port to access the router |  |
| ROUTEROS_USER* | User to log into the router |  |
| ROUTEROS_PASS* | Password to use to log into the router |  |
| ROUTEROS_WIREGUARD_INTERFACE* | WireGuard interface name, must match the wireguard interface name created on the MikroTik router eg. wireguard Road Warrior |  |
| ROUTEROS_WIREGUARD_ENDPOINT* | Your public IP clients use to connect to your WireGurad server on your MikroTik Router including the port eg. 123.123.123.123:12345 |  |
| ROUTEROS_WIREGUARD_SERVER_NAME | Default server name given to clients, can be anything | WireGuard Server |
| ROUTEROS_WIREGUARD_DNS | Client DNS server to use | 1.1.1.1 |
| ROUTEROS_WIREGUARD_ALLOWED_IPS | Client IPs to forward, Defaults to everything | 0.0.0.0/0 |

*Required

## Initial User
   To create the initial user run: (replace: admin@xterm.me with your email)
   ```bash
   docker compose exec microguard php artisan app:create-user admin@xterm.me admin 
   ```

## Local Development and Testing with Docker Compose

To facilitate local development and testing, we've incorporated Docker Compose. This allows developers to run the entire MicroGuard stack locally without complex setups.

### Prerequisites:

- Docker
- Docker Compose
- Git

### Steps:

1. Clone the Repository:
   Use Git to clone the MicroGuard repository to your local machine:
   ```bash
   git clone git@github.com:xterm-inator/microguard.git
   # Navigate into the repository directory:
   cd microguard
   ```
   
2. Set Up Environment Variables:
   Before starting the services using Docker Compose, you may need to configure some environment variables. Copy the sample environment file and adjust the settings as necessary:
   ```bash
   cp api/.env.example api/.env
   ```
   Edit the .env file with appropriate values. Make sure to generate and set values for necessary keys.

3. Build Containers:
   ```bash
   docker compose build --parallel
   ```

4. Install Dependencies:
   Using docker run there are some dependencies that need to be setup:
   ```bash
   docker compose run api composer install
   docker compose run api npm i
   docker compose run api php artisan key:generate
   docker compose run api php artisan migrate
   docker compose run vue npm i
   ```
   
5. Create an Initial User:
   ```bash
   docker compose run api php artisan app:create-user admin@email.com admin
   ```

6. Run with Docker Compose:
   Start the MicroGuard stack using Docker Compose:
   ```bash
   docker compose up
   ```
   This will build and start all necessary containers. Once done, the MicroGuard interface should be accessible at http://localhost:3000.

7. Shutdown and Cleanup:
   When you're done with local development/testing, you can stop the Docker Compose services:

   ```bash
   docker compose down
   ```

## Troubleshooting
### Error: Session store not set on request
This error is normally caused when the browser is accessing the server from an address that is different to the APP_URL set in the environment variables.

Try setting this to the address you are using, can be localhost, an IP address or a domain eg. http://localhost:8000

## Contributing
Contribute to MicroGuard by submitting a pull request or issue on GitHub. We welcome bug reports, feature suggestions, and code enhancements from the community.

## License
MicroGuard is licensed under the GNU General Public License v3.0. Details are in the [LICENSE.md](LICENSE.md) file.
