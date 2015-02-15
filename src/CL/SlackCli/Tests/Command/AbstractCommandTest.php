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

        $this->assertEmpty(
            array_diff($this->getExpectedArguments(), array_keys($command->getDefinition()->getArguments())),
            'There should be no difference between the expected arguments and the actual arguments'
        );

        $expectedOptions = array_merge($this->getDefaultOptions(), $this->getExpectedOptions());
        $this->assertEmpty(
            array_diff($expectedOptions, array_keys($command->getDefinition()->getOptions())),
            'There should be no difference between the expected options and the actual options'
        );

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
     * @return array
     */
    protected function getDefaultOptions()
    {
        return [
            'token',
            'configuration-path',
        ];
    }

    /**
     * @return array
     */
    protected function getExpectedArguments()
    {
        return [];
    }

    /**
     * @return array
     */
    protected function getExpectedOptions()
    {
        return [];
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
     * @return array
     */
    protected function getDefaultSuccessfulInput()
    {
        return [];
    }

    /**
     * @return array
     */
    protected function getDefaultFailureInput()
    {
        return [];
    }

    /**
     * @return array
     */
    protected function getExpectedAliases()
    {
        return [];
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
     * @return CommandTester
     */
    private function createCommandTester()
    {
        $command     = $this->createCommand();
        $application = new Application();
        $application->add($command);

        return new CommandTester($application->get($command->getName()));
    }
}
