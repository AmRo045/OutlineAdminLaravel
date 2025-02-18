<p align="center"> 
    <img src="extra/logo/logo.svg" width="200" alt="Outline Logo"> 
</p>

<h1 align="center">Outline Admin (Archived)</h1>

**Important:** This project has been replaced by a newer version. Please check out the updated repository [OutlineAdmin](https://github.com/AmRo045/OutlineAdmin) for the latest features, improvements, and ongoing support.

Outline Admin is a web interface for the Outline Manager API, providing a simple and user-friendly UI for managing VPN
servers.

## Added Features

- Ability to set expiration date for Access Keys
- QR Code for access keys

Feel free to contribute and make this project better!

## Installation - Docker

Before proceeding with the installation of Outline Admin, ensure that `docker` and `docker-compose` are installed on
your machine. Follow the instructions below:

```
git clone https://github.com/AmRo045/OutlineAdmin.git
cd OutlineAdmin
cp .env.example .env
docker-compose up -d
```

Once the container is up and running, you can access the admin panel by opening the following URL in your browser:

```
http://{your_server_ip_or_hostname}:9696
```

**Note** The default port is `9696`, but you can modify it in the `.env` file.

## Admin User

You can use the `agent.sh` script to manage the admin user. Run it using the following command:

```
sudo bash ./agent.sh
```

Once you run the script, you will be presented with the following options:

```
Select an option:
1. Create Admin User
2. Reset Admin Password
3. Exit
>>> 
```

## Update

If you need to update to the latest version, follow these instructions:

1. Navigate to the project root directory.
2. Run `git pull`.
3. Restart the Docker container.

## Screenshots

![Login](/extra/screenshots/login.png)
![Servers](/extra/screenshots/servers.png)
![New server form](/extra/screenshots/new-server.png)
![Server settings form](/extra/screenshots/server-settings.png)
![Access keys](/extra/screenshots/access-keys.png)
![QR Code modal](/extra/screenshots/qr-code.png)
![New access key form](/extra/screenshots/new-access-key.png)

## 💗 Donation

If you find this project useful and would like to support its development, you can make a donation.

### TON

```
UQByW0gL9r89D4oFagC3ZRCEctIoh6XjHu7zv5xU2wcPVATT
```
