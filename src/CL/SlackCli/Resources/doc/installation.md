# Installation

## Step 1) Downloading the Slack CLI Executable

There are in short, two ways to install the Slack CLI:
- [Locally](#locally); as part of your project, or;
- [Globally](#globally); as a system-wide executable

Windows Users should follow steps [here](#windows-users).

### Locally

Installing Slack CLI locally is a matter of just running the installer in your
project directory:

```sh
curl -sS http://cleentfaar.github.io/slack-cli/installer | php
```

> **Note:** If the above fails for some reason, you can download the installer
> with `php` instead:

```sh
php -r "readfile('http://cleentfaar.github.io/slack-cli/installer');" | php
```

The installer will just check a few PHP settings and then download the latest `slack.phar`
to your working directory. This file is the Slack CLI binary. It is a PHAR (PHP
archive), which is an archive format for PHP which can be run on the command
line, amongst other things.

You can install Slack CLI to a specific directory by using the `--install-dir`
option and providing a target directory (it can be an absolute or relative path):

```sh
curl -sS http://cleentfaar.github.io/slack-cli/installer | php -- --install-dir=bin
```

### Globally

You can place this file anywhere you wish. If you put it in your `PATH`,
you can access it globally. On unixy systems you can even make it
executable and invoke it without `php`.

You can run these commands to easily access `slack` from anywhere on your system:

```sh
curl -sS http://cleentfaar.github.io/slack-cli/installer | php
mv slack.phar /usr/local/bin/slack
```

> **Note:** If the above fails due to permissions, run the `mv` line
> again with sudo.

Then, just run `slack` in order to run Slack CLI instead of `php slack.phar`.

### Windows users

Change to a directory on your `PATH` and run the install snippet to download
slack.phar:

```sh
C:\Users\username>cd C:\bin
C:\bin>php -r "readfile('http://cleentfaar.github.io/slack-cli/installer');" | php
```

Create a new `slack.bat` file alongside `slack.phar`:

```sh
C:\bin>echo @php "%~dp0slack.phar" %*>slack.bat
```

Close your current terminal. Test usage with a new terminal:

```sh
C:\Users\username>slack -V
Slack CLI version 0.14.1
```

## Step 2) (Optional) Configure the API token to use by default

All API commands require you to supply the API token using the `--token` option.
However, if you are planning on using the same token you can easily configure the application to use a token set before-hand:

```sh
$ slack.phar config:set default_token your-token-here
```

If you do not have an API token yet, you should generate one on the Slack API website (you need to be logged in first): [https://api.slack.com/web#authentication](https://api.slack.com/web#authentication)


# Ready?

Let's start up this puppy! Check out the [usage documentation](https://github.com/cleentfaar/slack-cli/blob/master/src/CL/SlackCli/Resources/doc/usage.md)!
