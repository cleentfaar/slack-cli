# Installation

## Step 1) Install the .phar file

### Method 1) Locally

Download the `slack.phar` file and store it somewhere on your computer.


### Method 2) Globally (manual)

You can run the commands below to easily access ``slack`` from anywhere on your system.
Take care to replace the `[VERSION]` placeholder with the version number you would like to use (see [releases](https://github.com/cleentfaar/slack-cli/releases/))
```bash
$ wget http://cleentfaar.github.io/slack-cli/downloads/slack-[VERSION].phar -O slack
```

or with curl:
```bash
$ curl http://cleentfaar.github.io/slack-cli/downloads/slack-[VERSION].phar -o slack
```

then:
```bash
$ sudo chmod a+x slack
$ sudo mv php-cs-fixer /usr/local/bin/slack
```

Then, just run ``slack``.


### Method 3) Globally (composer)

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


## Step 2) (Optional) Configure the API token to use by default

All commands require you to supply the API token using the `--token` option.
However, if you are planning on using the same token you can easily configure the application to use a token set before-hand:

```bash
$ slack.phar config.set default_token your-token-here
```

If you do not have an API token yet, you should generate one on the Slack API website: [https://api.slack.com/web](https://api.slack.com/web)


# Ready?

Let's start interacting with the Slack API! Check out the [usage documentation](usage.md)!
