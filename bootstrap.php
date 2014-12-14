<?php

if (!defined('SLACK_CLI_BIN_DIR')) {
    throw new \RuntimeException('The constant "SLACK_CLI_BIN_DIR" must be defined before running the application');
}

$token     = null;
$inBin     = basename(SLACK_CLI_BIN_DIR) === 'bin';
$parentDir = dirname(SLACK_CLI_BIN_DIR);
$vendorDir = $inBin ? $parentDir . '/vendor/' : $parentDir . '/../../../../vendor';
$appDir    = $inBin ? $parentDir . '/app/' : $parentDir . '/../../../../app';

if (file_exists($appDir.'/bootstrap.php.cache')) {
    require_once $appDir.'/bootstrap.php.cache';
    require_once $appDir.'/AppKernel.php';

    if (class_exists('CL\Bundle\SlackBundle\CLSlackBundle')) {
        $input = new \Symfony\Component\Console\Input\ArgvInput();
        $env   = $input->getParameterOption(array('--env', '-e'), getenv('SYMFONY_ENV') ?: 'dev');
        $debug = getenv('SYMFONY_DEBUG') !== '0' && !$input->hasParameterOption(array('--no-debug', '')) && $env !== 'prod';

        if ($debug) {
            \Symfony\Component\Debug\Debug::enable();
        }

        $kernel = new \AppKernel($env, $debug);
        $kernel->boot();

        $token = $kernel->getContainer()->getParameter('cl_slack.api_token');
    }
} else {
    require_once $vendorDir . '/autoload.php';
}

\Doctrine\Common\Annotations\AnnotationRegistry::registerAutoloadNamespace(
    'JMS\Serializer\Annotation',
    $vendorDir . "/jms/serializer/src"
);

define('SLACK_CLI_DEFAULT_TOKEN', $token);
