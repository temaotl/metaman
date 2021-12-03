# MetaMan-Laravel

MetaMan (short for Metadata Manager) is [Laravel](https://laravel.com)-based web application intended for managing and storing SAML metadata for identity federations within [Git](https://git-scm.com) version control system.

[![Actions Status](https://github.com/JanOppolzer/metaman-laravel/workflows/Laravel/badge.svg)](https://github.com/JanOppolzer/metaman-laravel/actions)

## Requirements

This application is written in Laravel 8 and thus it could be sufficient to use [PHP 7.3](https://laravel.com/docs/8.x/releases#support-policy), however, it is developed and run on PHP 7.4 and so 7.3 is in fact not supported. [You do not want to run PHP 7.3 anyway](https://www.php.net/supported-versions.php). On the other hand, using PHP 8.0 and 8.1 should be safe according to various automatic tests included albeit not really tested in production.

Authentication is expected to be managed by locally running Shibboleth Service Provider, so Apache web server is highly recommended as there is an official Shibboleth module for it. There is also an unofficial Shibboleth SP module for nginx web server, however, it has not been tested and so it is not recommended.

- PHP 7.4+
- Shibboleth SP 3.x
- Apache 2.4
- Supervisor 4.1

The above mentioned requirements can be easily achieved by using Ubuntu 20.04 LTS.

## Installation

The easiest way to install MetaMan is to use [Envoy](https://laravel.com/docs/8.x/envoy) script in [metaman-envoy](https://github.com/JanOppolzer/metaman-envoy) repository. The repository also contains configuration snippets for Apache, Shibboleth SP and Supervisor daemons.
