<?php
/**
 * PHP QQ Client Library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\PHPQQClient\Console;

use Slince\Di\Container;
use Slince\Event\Dispatcher;
use Slince\PHPQQClient\Configuration;
use Slince\PHPQQClient\Console\Command\BootstrapCommand;
use Slince\PHPQQClient\Console\Command\ChatCommand;
use Slince\PHPQQClient\Console\Command\ListDiscussMembersCommand;
use Slince\PHPQQClient\Console\Command\ListGroupMembersCommand;
use Slince\PHPQQClient\Console\Command\Service;
use Slince\PHPQQClient\Console\Command\ServiceInterface;
use Slince\PHPQQClient\Console\Command\ShowCategoriesCommand;
use Slince\PHPQQClient\Console\Command\ListDiscussesCommand;
use Slince\PHPQQClient\Console\Command\ShowFriendCommand;
use Slince\PHPQQClient\Console\Command\ListFriendsCommand;
use Slince\PHPQQClient\Console\Command\ListGroupsCommand;
use Slince\PHPQQClient\Console\Command\ShowMeCommand;
use Slince\PHPQQClient\Console\Panel\Panel;
use Slince\PHPQQClient\Console\Service\MessageService;
use Slince\PHPQQClient\Loop;
use Symfony\Component\Console\Application as BaseApplication;
use Slince\PHPQQClient\Client;
use Symfony\Component\Console\Exception\CommandNotFoundException;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;

class Application extends BaseApplication
{
    /**
     * Application Name
     * @var string
     */
    const NAME = 'phpqqclient';

    /**
     * logo
     * @var string
     */
    protected static $logo = 'PHP QQ Client';

    /**
     * @var Container
     */
    protected $container;

    /**
     * @var Dispatcher
     */
    protected $dispatcher;

    /**
     * @var Style
     */
    protected $style;

    /**
     * @var InputInterface
     */
    protected $input;

    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * @var Loop
     */
    protected $loop;

    /**
     * @var Configuration
     */
    protected $configuration;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * backend services
     * @var array
     */
    protected $services = [];

    public function __construct(Configuration $configuration)
    {
        parent::__construct(static::NAME);
        $this->setDefaultCommand('bootstrap');
        $this->configuration = $configuration;
        $this->loop = new Loop();
        $this->dispatcher = new Dispatcher();
        $this->container = new Container();
    }

    /**
     * @return Configuration
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * @return Dispatcher
     */
    public function getDispatcher()
    {
        return $this->dispatcher;
    }

    /**
     * @return Style
     */
    public function getStyle()
    {
        return $this->style;
    }

    /**
     * @return InputInterface
     */
    public function getInput()
    {
        return $this->input;
    }

    /**
     * @return OutputInterface
     */
    public function getOutput()
    {
        return $this->output;
    }

    public function registerService(ServiceInterface $service)
    {
        $this->services[$service->getName()] = $service;
    }

    /**
     * {@inheritdoc}
     */
    public function run(InputInterface $input = null, OutputInterface $output = null)
    {
        $input = $input ?: new ArgvInput();
        $input->setStream(fopen('php://stdin', 'r') ?: STDIN);
        return parent::run($input, $output);
    }

    protected function doCommandDispatch($stream)
    {
        $rawInput = fread($stream, 4096);
        $rawInput = str_replace('\\', '\\\\', rtrim($rawInput, " \t\n\r\0\x0B;"));
        $input = new StringInput($rawInput);
        try {
            $command = $this->findCommand($input);
            return $this->runCommand($command, $input, $this->output);
        } catch (CommandNotFoundException $exception) {
            $this->logger->error($exception->getMessage());
            return false;
        }
    }

    /**
     * Reads a line from input stream
     * @param string $prompt
     * @return bool|mixed|string
     */
    public function readLine($prompt = '')
    {
        $prompt = $prompt ?: $this->configuration->getPrompt();
        $this->output->write($prompt);
        $rawInput = fread($this->input->getStream(), 4096);
        $rawInput = str_replace('\\', '\\\\', rtrim($rawInput, " \t\n\r\0\x0B;"));
        return $rawInput;
    }
    /**
     * {@inheritdoc}
     */
    public function doRun(InputInterface $input, OutputInterface $output)
    {
        $this->style = new Style($input, $output);
        Panel::setStyle($this->style);
        $this->logger = new Logger($output);
        $this->input = $input;
        $this->output = $output;
        $this->writeLogo();
        parent::doRun($input, $output);
        $inputStream = $input->getStream();
        $this->loop->run(function() {
            $rawInput = $this->readLine();
            $input = new StringInput($rawInput);
            try {
                $command = $this->findCommand($input);
                return $this->runCommand($command, $input, $this->output);
            } catch (CommandNotFoundException $exception) {
                $this->logger->error($exception->getMessage());
                return false;
            }
        });
    }

    protected function writeLogo()
    {
        $this->output->writeln(static::$logo);
    }

    /**
     * 判断命令存在
     * @param StringInput $input
     * @return bool
     */
    protected function hasCommand(StringInput $input)
    {
        $commandName = $input->getFirstArgument();
        return $this->has($commandName);
    }

    /**
     * 查找命令
     * @param StringInput $input
     * @return Command
     */
    protected function findCommand(StringInput $input)
    {
        $commandName = $input->getFirstArgument();
        return $this->find($commandName);
    }

    /**
     * 执行内部命令
     * @param Command $command
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function runCommand(Command $command, InputInterface $input, OutputInterface $output)
    {
        if ($input->hasParameterOption(['--help', '-h'], true) === true) {
            $helpCommand = $this->get('help');
            $helpCommand->setCommand($command);
            $command = $helpCommand;
        }
        try {
            $statusCode = $command->run($input, $output);
        } catch (RuntimeException $exception) {
            $this->logger->error($exception->getMessage());
            $statusCode = 1;
        }
        return $statusCode;
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefaultCommands()
    {
        return array_merge(parent::getDefaultCommands(), [
            new BootstrapCommand(),
            new ListFriendsCommand(),
            new ListGroupsCommand(),
            new ListDiscussesCommand(),
            new ListGroupMembersCommand(),
            new ListDiscussMembersCommand(),
            new ShowCategoriesCommand(),
            new ShowMeCommand(),
            new ShowFriendCommand(),
            new ChatCommand(),
        ]);
    }

    protected function getDefaultServices()
    {
        return [
            new MessageService(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefaultInputDefinition()
    {
        $definition = parent::getDefaultInputDefinition();
        $definition->addOption(new InputOption('config', 'c', InputOption::VALUE_OPTIONAL, '配置文件'));
        return $definition;
    }
}