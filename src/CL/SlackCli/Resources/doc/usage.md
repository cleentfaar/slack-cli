# Usage

## Introduction

I won't be explaining each and every command in this document (most of the information can be found under each command's
`--help` option anyway).

Instead, I will just show you an example for two of the commands, `chat.postMessage` (sending a message to a
Slack channel) and `auth.test` (testing token authentication for debugging purposes).

Like I said, all of the available commands have a help message that you can check out for more information,
but the examples below should help to get you started.


### About tokens (`--token`)

All commands require a value for the `--token` option:
```sh
$ slack.phar foo.bar arg1 arg2 --token=my-token
```

**TIP:** If you plan on using the same token, you can store the token in the global configuration by using the `config.set`
command:
```sh
$ slack.phar config.set default_token your-token-here
```

### About verbosity (`-v`)

All commands return the relevant information that can be expected from it's related API method. This means
you normally don't have to pass any extra options to get the information needed.

Some API methods return some information which may not be very useful to you.
These can be shown using the `normal` verbosity option (`-v`). For example, the `chat.postMessage` command
also returns the `Channel ID` and `timestamp` on which the message was posted. These would only be shown if you used
this verbosity option (`-v`).

In more advanced scenarios, you might want to get more detailed information about the actual request sent to Slack
and the response returned by it. In those cases you can use the `verbose` verbosity option (`-vv`).

It can be especially handy when you think there is a bug in this package, or when you are just unsure about what is
going wrong.

The examples below show you what difference the verbosity can make for the output of each command.


## Sending a message (`chat.postMessage`)

Here are some examples of sending a message to a Slack channel, using ther `chat.postMessage` command.

Simple example (no verbosity):
```
$ slack.phar chat.postMessage general "This is a test" --username=AcmeBot --icon-emoji=truck
✔ Successfully sent message to Slack!
```

Detailed example, (using normal verbosity `-v`):
```
$ slack.phar chat.postMessage general 'This is a test' -v
✔ Successfully sent message to Slack!
Channel ID: C01234567
Timestamp: 1234567890
```

Debugging request/response example (using verbose verbosity `-vv`):
```
$ slack.phar chat.postMessage general 'This is a test' -vv
Debug: sending payload...
+----------+----------------------------------------------+
| channel  | #general                                     |
| text     | This is a test                               |
| token    | your-token-here                              |
+----------+----------------------------------------------+
Debug: received payload response...
+---------+-------------------+
| ok      | 1                 |
| channel | C01234567         |
| ts      | 1234567890.123456 |
+---------+-------------------+
✔ Successfully sent message to Slack!
Channel ID: C01234567
Timestamp: 1234567890.123456
```

## Testing authentication (`auth.test`)

You might want to know who you are authenticated as during authorization.

```
$ slack.phar auth.test -v
✔ Successfully authenticated by the Slack API!
```

Again, the verbosity option (`-v`) comes in handy:
```
$ slack.phar auth.test -v
✔ Successfully authenticated by the Slack API!
+----------+------------+
| User ID  | U01234567  |
| Username | my-name    |
| Team ID  | T01234567  |
| Team     | my-team    |
+----------+------------+
```

## Updating the application

Once you have installed the CLI application, you can easily stay up-to-date by running the `self.update` command:
```sh
$ slack.phar self.update
```


## Got it?

That's about it for the documentation.

If you want more control over how the payloads are sent to Slack, you should check out the [library's documentation](https://github.com/cleentfaar/slack/blob/master/src/CL/Slack/Resources/doc/usage.md),
specifically the [method reference](https://github.com/cleentfaar/slack/blob/master/src/CL/Slack/Resources/doc/methods/index.md) for
detailed examples on how to access every API method in your own application.


## Contributing

I am also open to PRs if you find things you would like to see changed! Before you do this, check out the documentation
about contributing to this package [here](https://github.com/cleentfaar/slack-cli/blob/master/src/CL/SlackCli/Resources/doc/contributing.md).
