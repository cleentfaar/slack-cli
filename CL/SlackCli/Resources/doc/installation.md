# Installation

## Step 1) Get the cli

First you need to get a hold of this package. There are two ways to do this:

### Method a) Using composer

Add the following to your ``composer.json`` (see http://getcomposer.org/)

    "require" :  {
        "cleentfaar/slack-cli": "~0.12"
    }


### Method b) Using submodules

Run the following commands to bring in the needed libraries as submodules.

```bash
git submodule add https://github.com/cleentfaar/slack-cli.git vendor/clis/CL/Cli/SlackCli
```


## Step 2) Register the namespaces

If you installed the cli by composer, use the created autoload.php  (jump to step 3).
Add the following two namespace entries to the `registerNamespaces` call in your autoloader:

``` php
<?php
// app/autoload.php
$loader->registerNamespaces(array(
    // ...
    'CL\SlackCli' => __DIR__.'/../vendor/cleentfaar/slack-cli',
    // ...
));
```


## Step 3) Get yourself an API token

If you don't have an API token yet, follow this link: [https://api.slack.com/web](https://api.slack.com/web).
It takes you to the Slack API site which (if you are logged in, then scroll down) lets you generate an API token for your account.

## Step 3) Install the .phar file

### Method 1) Locally

Download the `slack.phar` file and store it somewhere on your computer.


### Method 2) Globally (manual)

You can run the commands below to easily access ``slack`` from anywhere on your system.
Take care to replace the `[VERSION]` placeholder with the version number you would like to use (see [releases](https://github.com/cleentfaar/slack-cli/releases/))
```bash
$ wget https://github.com/cleentfaar/slack-cli/releases/[VERSION]/slack.phar -O slack
```

or with curl:
```bash
$ curl https://github.com/cleentfaar/slack-cli/releases/[VERSION]/slack.phar -o slack
```

then:
```bash
$ sudo chmod a+x slack
$ sudo mv php-cs-fixer /usr/local/bin/slack
```

Then, just run ``slack``.


#### Globally (composer)

Install [Composer](https://getcomposer.org/download/) and issue the following command:
```
$ ./composer.phar global require cleentfaar/slack-cli
```

Then, make sure you have `~/.composer/vendor/bin` in your `PATH`...
```
export PATH="$PATH:$HOME/.composer/vendor/bin"
```

And you're good to go!
```
$ slack chat.postMessage general "Hello World!" --token=your-api-token-here
```


## Step 4) (Optional) Configure the token as the default

All commands require to supply the token using the `--token` option. However, if you are planning on using the same
token you can easily configure the application to use it:

```bash
$ slack.phar config.set default_token your-token-here
```


# Ready?

Let's start interacting with the Slack API! Check out the [usage documentation](usage.md)!
