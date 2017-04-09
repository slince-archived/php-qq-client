<?php
/**
 * PHP QQ Client Library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\PHPQQClient\Console;

use Slince\PHPQQClient\Console\Command\BootstrapCommand;
use Slince\PHPQQClient\Console\Command\MainCommand;
use Slince\PHPQQClient\Console\Command\ShowFriendsCommand;
use Slince\PHPQQClient\Console\Panel\Panel;
use Slince\PHPQQClient\Loop;
use Symfony\Component\Console\Application as BaseApplication;
use Slince\PHPQQClient\Client;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputInterface;
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
     * 所有panel
     * @var Panel[]
     */
    protected $panels = [];

    public function __construct(Client $client = null)
    {
        parent::__construct(static::NAME);
        if (is_null($client)) {
           $client = new Client();
        }
        $this->client = $client;
        $this->setDefaultCommand('bootstrap', true);
        $this->loop = new Loop();
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
        //write logo to console
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
}