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


#### Using the Symfony Framework?:
You should be using the [SlackBundle](https://github.com/cleentfaar/CLSlackBundle) package which was specially made for
it, the commands in this package will then automatically use the token you have configured in your `app/config.yml`:
```yaml
# app/config/config.yml
cl_slack:
    api_token: xoxp-1234567890-1234567890-1234567890-1a1234 # replace with your own (see: https://api.slack.com/tokens)
```


# Ready?

Let's start interacting with the Slack API! Check out the [usage documentation](usage.md)!
