<?php

/*
 * This file is part of the slack-cli package.
 *
 * (c) Cas Leentfaar <info@casleentfaar.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CL\SlackCli\Command;

use CL\Slack\Payload\OauthAccessPayload;
use CL\Slack\Payload\OauthAccessPayloadResponse;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class OauthAccessCommand extends AbstractApiCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('oauth:access');
        $this->setDescription('Exchange a temporary OAuth code for an API access token');
        $this->addArgument('client-id', InputArgument::REQUIRED, 'Issued when you created your application');
        $this->addArgument('client-secret', InputArgument::REQUIRED, 'Issued when you created your application');
        $this->addArgument('code', InputArgument::REQUIRED, 'The code param returned via the OAuth callback');
        $this->addOption('redirect-uri', null, InputOption::VALUE_REQUIRED, 'This must match the originally submitted URI (if one was sent)');
        $this->setHelp(<<<EOT
The <info>oauth:access</info> command allows you to exchange a temporary OAuth code for an API access token.
This is used as part of the OAuth authentication flow.

For more information about the related API method, check out the official documentation:
<comment>https://api.slack.com/methods/oauth.access</comment>
EOT
        );
    }

    /**
     * @return OauthAccessPayload
     */
    protected function createPayload()
    {
        $payload = new OauthAccessPayload();
        $payload->setClientId($this->input->getArgument('client-id'));
        $payload->setClientSecret($this->input->getArgument('client-secret'));
        $payload->setCode($this->input->getArgument('code'));
        $payload->setRedirectUri($this->input->getOption('redirect-uri'));

        return $payload;
    }

    /**
     * {@inheritdoc}
     *
     * @param OauthAccessPayloadResponse $payloadResponse
     */
    protected function handleResponse($payloadResponse)
    {
        if ($payloadResponse->isOk()) {
            $this->writeOk('Successfully authenticated through oauth!');
            $this->output->writeln('Access token: <comment>%s</comment>', $payloadResponse->getAccessToken());
            $this->output->writeln('Scope: <comment>%s</comment>', $payloadResponse->getScope());
        } else {
            $this->writeError(sprintf('Failed to be authenticated through oauth. %s', lcfirst($payloadResponse->getErrorExplanation())));
        }
    }
}
