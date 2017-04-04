<?php
/**
 * PHP QQ Client Library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\PHPQQClient\Console;

use Slince\PHPQQClient\Console\Command\MainCommand;
use Slince\PHPQQClient\Console\Panel\Panel;
use Symfony\Component\Console\Application as BaseApplication;
use Slince\PHPQQClient\Client;
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
     * @var Client
     */
    protected $client;

    /**
     * @var Style
     */
    protected $style;

    /**
     * 所有panel
     * @var Panel[]
     */
    protected $panels = [];

    public function __construct(Client $client = null)
    {
        parent::__construct(static::NAME);
        if (!is_null($client)) {
            $this->client = $client;
        }
        $this->setup();
    }

    protected function setup()
    {
        $command = new MainCommand();
        $this->add($command);
        $this->setDefaultCommand($command, true);
    }

    /**
     * {@inheritdoc}
     */
    protected function configureIO(InputInterface $input, OutputInterface $output)
    {
        parent::configureIO($input, $output);
        $this->style = new Style($input, $output);
    }

    protected static function createCommands()
    {
        return [

        ];
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
}