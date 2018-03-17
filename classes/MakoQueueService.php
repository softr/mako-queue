<?php
namespace softr\MakoQueue;

use \softr\MakoQueue\QueueManager;

/**
 * MakoQueue package.
 *
 * @author     Agência Softr Ltda
 * @copyright  (c) 2018
 */
class MakoQueueService extends \mako\application\services\Service
{
    /**
     * Registers the service.
     *
     * @access  public
     */
    public function register()
    {
        $this->container->registerSingleton(['softr\MakoQueue\QueueManager', 'queueManager'], function($container)
        {
            return new QueueManager($container, $container->get('database'));
        });
    }
}