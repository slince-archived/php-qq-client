<?php
/**
 * PHP QQ Client Library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\PHPQQClient\Console;

use Slince\PHPQQClient\Configuration;
use Slince\PHPQQClient\Console\Command\BootstrapCommand;
use Slince\PHPQQClient\Console\Command\ShowFriendsCommand;
use Slince\PHPQQClient\Console\Panel\Panel;
use Slince\PHPQQClient\Loop;
use Symfony\Component\Console\Application as BaseApplication;
use Slince\PHPQQClient\Client;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

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
     * @var Client
     */
    protected $client;

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
     * 所有panel
     * @var Panel[]
     */
    protected $panels = [];

    public function __construct(Configuration $configuration = null, Client $client = null)
    {
        parent::__construct(static::NAME);
        $this->configuration = $configuration;
        $this->client = $client ?: new Client($this->configuration);
        $this->loop = new Loop();
        $this->setDefaultCommand('bootstrap', true);
    }

    /**
     * @return Configuration
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @return Style
     */
    public function getStyle()
    {
        return $this->style;
    }

    /**
     * @param $panelClass
     * @param $data
     * @return mixed
     */
    public function createPanel($panelClass, $data)
    {
        $panel = new $panelClass($data, $this->style);
        $this->panels[] = $panel;
        return $panel;
    }

    public function run(InputInterface $input = null, OutputInterface $output = null)
    {
        if (is_null($input)) {
            $input = new ArgvInput();
            $input->setStream(fopen('php://stdin', 'r') ?: STDIN);
        }
        return parent::run($input, $output);
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

    /**
     * {@inheritdoc}
     */
    public function doRun(InputInterface $input, OutputInterface $output)
    {
        $this->style = new Style($input, $output);
        $this->input = $input;
        $this->output = $output;
        $this->writeLogo();
        parent::doRun($input, $output);
        $this->loop->run(function() {
            $commandName = fread($this->input->getStream(), 4096);
            $command = $this->find($commandName);
            $this->doRunCommand($command, $this->input, $this->output);
        });
    }

    protected function writeLogo()
    {
        $this->output->writeln(static::$logo);
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefaultCommands()
    {
        return array_merge(parent::getDefaultCommands(), [
            new BootstrapCommand(),
            new ShowFriendsCommand(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefaultInputDefinition()
    {
        $inputDefinition =  parent::getDefaultInputDefinition();
        $inputDefinition->addOption(new InputOption('config', 'c', InputOption::VALUE_OPTIONAL, '配置文件'));
        return $inputDefinition;
    }
}