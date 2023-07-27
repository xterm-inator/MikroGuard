# MicroGuard

MicroGuard is a web-based tool for managing WireGuard VPN clients on MikroTik routers. With MicroGuard, 
you can easily add new users, revoke access, and view connection statistics.
Currently, users can only login using google sso.

> More details to come for setting up sso

## Features

- Create and manage WireGuard clients on MikroTik routers
- View real-time connection statistics and usage information
- Revoke access for individual clients

## Requirements

- MikroTik router running RouterOS 7.0 or later
- Docker (for running the MicroGuard server)

## Adding a User to MicroTik for MicroGuard
### Using Winbox
1. Log in to your MikroTik router using Winbox.
2. Navigate to the "System" menu and select "Users".
3. Click on the "Groups" tab.
4. Click on the "Add New" button to create a new user group.
5. Enter a name for the group such as "microguard-group" and click on the "OK" button.
6. In the "Permissions" tab, select the desired permissions for the group. For MicroGuard, the user group should have read, write, and API access.
7. Click on the "Apply" button to save the changes.
8. Click on the "Users" tab.
10. Enter the user's details, such as their name and password.

> **warning:** Make sure to enter your local subnet into allowed address (unless you know what you are doing).

11. In the "Groups" tab, select the "microguard-group" group you just created.
12. Click on the "Apply" button to save the changes.

### Or using cmd (Hasn't been fully tested)

Enter the following commands to create a new user group:

>This will create a new user group named "microguard-group" with the necessary permissions for MicroGuard.
```sh
/user group add name=microguard-group policy=local,read,write,test,api,winbox,password
```

Enter the following command to create a new user:
>Replace "username" with the desired username and "userpassword" with the desired password for the new user.
```sh
/user add name=username group=microguard-group password=userpassword
````

> **warning:** Please see above warning as that also applies for cmd.

## Creating a Road Warrior Wireguard interface for MicroGuard

1. Log in to your MikroTik router using Winbox.
2. Navigate to the "WireGuard" menu.
3. Click on the "Add" button to create a new WireGuard interface.
4. Enter a name for the interface such as "wireguard road warrior" and click on the "OK" button.
5. Find the WireGuard server just created and take note of the public key as this will be required

## Installation on a server

1. Install Docker on your server.
2. Generate an app key using https://generate-random.org/laravel-key-generator and use that in following commands for APP_KEY
4. Start the MicroGuard server using the following command:
> Please read the following before running as some action is required.
````bash
docker run -d
--name microguard
-p 80:80
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
ghcr.io/xterm-inator/microguard:master
````
Or as a docker-compose.yml file
```yml
version: '3.8'
services:
  microguard:
    image: ghcr.io/xterm-inator/microguard:master
    container_name: microguard
    restart: always
    ports:
      - 80:80
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

## Usage

1. Access the MicroGuard web interface by navigating to `http://your-server-ip` in your web browser.
2. Click on the "Users" tab
3. Click Add User

## Contributing

If you would like to contribute to the development of MicroGuard, you can submit a pull request on GitHub. We welcome bug reports, feature requests, and code contributions from the community.

## License

MicroGuard is licensed under the GNU General Public License v3.0 - see the [LICENSE.md](LICENSE.md) file for details.
