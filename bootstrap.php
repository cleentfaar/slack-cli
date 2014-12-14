<?php

$token     = null;
$vendorDir = is_dir(__DIR__ . '/vendor') ? __DIR__ . '/vendor' : __DIR__ . '/../../../../../vendor';
$appDir    = getcwd() . '/app';

if (file_exists($appDir.'/bootstrap.php.cache')) {
    // check if this is accessed within a symfony project, and the SlackBundle has been installed
    // if it has, we automatically re-use the API token that should be configured at this point

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
    // can only seem to require composer's autoloader if we aren't using symfony bootstrapping (re-declares classes)
    require_once $vendorDir . '/autoload.php';
}

\Doctrine\Common\Annotations\AnnotationRegistry::registerLoader('class_exists');

define('SLACK_CLI_DEFAULT_TOKEN', $token);
