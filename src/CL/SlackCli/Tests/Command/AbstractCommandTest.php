<?php

namespace CL\SlackCli\Tests\Command;

use CL\SlackCli\Command\AbstractCommand;
use CL\SlackCli\Console\Application;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Tester\CommandTester;

abstract class AbstractCommandTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        $command     = $this->createCommand();
        $commandName = $this->getExpectedName();

        $application = new Application();
        $application->add($command);

        $this->assertTrue(
            $application->has($commandName),
            sprintf('The command does not seem to be registered under the expected name: %s', $commandName)
        );

        $this->assertArguments($command);
        $this->assertOptions($command);

        $expectedAliases = $this->getExpectedAliases();
        $this->assertCount(
            count($expectedAliases),
            $command->getAliases(),
            'Actual number of aliases does not match expected number of aliases'
        );

        foreach ($expectedAliases as $expectedAlias) {
            $this->assertTrue(
                $application->has($expectedAlias),
                sprintf('The command does not seem to be registered under the expected name: %s', $expectedAlias)
            );

            $this->assertEquals(
                $command,
                $application->get($expectedAlias),
                sprintf('The command does not seem to be the same instance when accessed using the alias "%s"', $expectedAlias)
            );
        }
    }

    /**
     * @param AbstractCommand $command
     */
    private function assertArguments(AbstractCommand $command)
    {
        $expected = $this->getExpectedArguments();
        $actual   = array_keys($command->getDefinition()->getArguments());

        $this->assertEquals([], array_diff($expected, $actual), 'There are less arguments than expected');
        $this->assertEquals([], array_diff($actual, $expected), 'There are more arguments than expected');
    }

    /**
     * @param AbstractCommand $command
     */
    private function assertOptions(AbstractCommand $command)
    {
        $expected = array_merge($this->getDefaultOptions(), $this->getExpectedOptions());
        $actual   = array_keys($command->getDefinition()->getOptions());

        $this->assertEquals([], array_diff($expected, $actual), 'There are more options than expected');
        $this->assertEquals([], array_diff($actual, $expected), 'There are less options than expected');
    }

    /**
     * @return array
     */
    protected function getDefaultOptions()
    {
        return [
            'configuration-path',
        ];
    }

    /**
     * @param array       $input
     * @param string|null $expectedOutput
     */
    protected function assertExecutionSucceedsWith(array $input, $expectedOutput = null)
    {
        $commandTester = $this->createCommandTester();
        $input         = array_merge(
            $this->getDefaultSuccessfulInput(),
            $input
        );

        $returnCode = $commandTester->execute($input, ['verbosity' => OutputInterface::VERBOSITY_VERY_VERBOSE]);

        $this->assertNotEquals(
            1,
            $returnCode,
            sprintf('Command was expected to succeed, but it returned "1" with: %s', $commandTester->getDisplay())
        );

        if ($expectedOutput !== null) {
            if (is_array($expectedOutput)) {
                foreach ($expectedOutput as $output) {
                    $this->assertContains($output, $commandTester->getDisplay());
                }

                return;
            }

            $this->assertContains($expectedOutput, $commandTester->getDisplay());
        }
    }

    /**
     * @param array       $input
     * @param string|null $expectedOutput
     */
    protected function assertExecutionFailsWith(array $input, $expectedOutput = null)
    {
        $commandTester = $this->createCommandTester();
        $input         = array_merge(
            $this->getDefaultFailureInput(),
            $input
        );

        $returnCode = $commandTester->execute($input);

        $this->assertEquals(
            1,
            $returnCode,
            sprintf('Command was expected to fail, but it did not return "1" with: %s', $commandTester->getDisplay())
        );

        if ($expectedOutput !== null) {
            $this->assertContains($expectedOutput, $commandTester->getDisplay());
        }
    }

    /**
     * @return string
     */
    protected function getConfigurationPath()
    {
        return __DIR__ . '/../slack.json';
    }

    /**
     * @return array
     */
    protected function getDefaultSuccessfulInput()
    {
        return [
            '--env'                => 'test-success',
            '--configuration-path' => $this->getConfigurationPath(),
        ];
    }

    /**
     * @return array
     */
    protected function getDefaultFailureInput()
    {
        return [
            '--env'                => 'test-failure',
            '--configuration-path' => $this->getConfigurationPath(),
        ];
    }

    /**
     * @return array
     */
    protected function getExpectedAliases()
    {
        return [];
    }

    /**
     * @return CommandTester
     */
    private function createCommandTester()
    {
        $command     = $this->createCommand();
        $application = new Application();
        $application->add($command);

        return new CommandTester($application->get($command->getName()));
    }

    abstract public function testExecute();

    /**
     * @return AbstractCommand
     */
    abstract protected function createCommand();

    /**
     * @return string
     */
    abstract protected function getExpectedName();

    /**
     * @return array
     */
    abstract protected function getExpectedArguments();

    /**
     * @return array
     */
    abstract protected function getExpectedOptions();
}
