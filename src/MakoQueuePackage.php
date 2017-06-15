<?php
namespace softr\MakoQueue;

use \softr\MakoQueue\QueueManager;

/**
 * MakoQueue package.
 *
 * @author     Agência Softr Ltda
 * @copyright  (c) 2017
 */
class MakoQueuePackage extends \mako\application\Package
{
    /**
     * Package name.
     *
     * @var string
     */
    protected $packageName = 'softr/mako-queue';

    /**
     * Package namespace.
     *
     * @var string
     */
    protected $fileNamespace = 'mako-queue';

    /**
     * Register the service.
     *
     * @access  protected
     */
    protected function bootstrap()
    {
        $this->container->registerSingleton(['softr\MakoQueue\QueueManager', 'queueManager'], function($container)
        {
            return new QueueManager($container, $container->get('database'));
        });
    }
}