<?php


/**
 * Quantum PHP Framework
 *
 * An open source software development framework for PHP
 *
 * @package Quantum
 * @author Arman Ag. <arman.ag@softberg.org>
 * @copyright Copyright (c) 2018 Softberg LLC (https://softberg.org)
 * @link http://quantum.softberg.org/
 * @since 2.6.0
 */

namespace Base\Commands;

use Quantum\Console\QtCommand;

/**
 * Class ServeCommand
 * @package Base\Commands
 */
class ServeCommand extends QtCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'serve';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Serve the application on the PHP development server';

    /**
     * The current port offset.
     *
     * @var int
     */
    protected $portOffset = 0;

     /**
     * Command arguments
     * @var \string[][]
     */
    protected $args = [
        ['host', 'optional', 'host'],
        ['port', 'optional', 'port'],
    ];

     /**
     * Command constructor
     */
    public function __construct()
    {
        parent::__construct();
    
    }

    /**
     * Executes the command
     * @throws \Quantum\Exceptions\DiException
    */
    public function exec()
    {
        $connection = @fsockopen($this->host(), $this->port(), $errno, $errstr, 30);
       
        if (is_resource($connection))
        {
            $this->portOffset += 1;
            $server = $this->serverCommand();
            $port = $this->port();
            
            fclose($connection);
            return $this->exec();
    
        }
        else
        {
            $server = $this->serverCommand();

            switch (PHP_OS) {
                case 'Linux':
                    $command = 'xdg-open http://';
                    break;
                case 'WINNT':
                    $command = 'start http://';
                    break;
                case 'Darwin':
                    $command = 'open -a http://';
                    break;
                default:
                    $this->error('The OS could not be found');
                    return;
            }

            exec($command.$this->host().':'.$this->port().' && '. $server. ' -t public');            
        }
 
    }

    protected function serverCommand()
    {
        return 'php -S'. ' '.$this->host().':'.$this->port();
        
    }

    protected function host()
    {
        [$host, ] = $this->getHostAndPort();
        
        return $host ?: '127.0.0.1';
    }

    protected function port()
    {
        $port = $this->getArgument('port');

        if (is_null($port)) {
            [, $port] = $this->getHostAndPort();
        }

        $port = $port ?: 8100;

        return $port + $this->portOffset;
    }

    protected function getHostAndPort()
    {
        $hostParts = explode(':', $this->getArgument('host'));

        return [
            $hostParts[0],
            $hostParts[1] ?? null,
        ];
    }

}

