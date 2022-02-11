# MetaMan-Laravel

MetaMan (short for Metadata Manager) is [Laravel](https://laravel.com)-based web application intended for managing and storing SAML metadata for identity federations within [Git](https://git-scm.com) version control system.

[![Actions Status](https://github.com/JanOppolzer/metaman-laravel/workflows/Laravel/badge.svg)](https://github.com/JanOppolzer/metaman-laravel/actions)

## Requirements

This application is written in Laravel 9 and thus it requires PHP version 8.0.2 or newer.

Authentication is expected to be managed by locally running Shibboleth Service Provider, so Apache web server is highly recommended as there is an official Shibboleth module for it. There is also an unofficial Shibboleth SP module for nginx web server, however, it has not been tested and so it is not recommended.

- PHP 8.0.2+
- Shibboleth SP 3.x
- Apache 2.4
- Supervisor 4.1

The above mentioned requirements can be easily achieved by using Ubuntu 20.04 LTS and using [Ondřej Surý's PPA repository](https://launchpad.net/~ondrej/+archive/ubuntu/php/).

## Installation

The easiest way to install MetaMan is to use [Envoy](https://laravel.com/docs/9.x/envoy) script in [metaman-envoy](https://github.com/JanOppolzer/metaman-envoy) repository. The repository also contains configuration snippets for Apache, Shibboleth SP and Supervisor daemons.
