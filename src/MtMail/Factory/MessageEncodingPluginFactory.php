<?php

namespace MtMail\Factory;

use MtMail\Plugin\MessageEncoding;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MessageEncodingPluginFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return MessageEncoding
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->getServiceLocator()->get('Configuration');
        $plugin = new MessageEncoding();
        if (isset($config['mt_mail']['message_encoding'])) {
            $plugin->setEncoding($config['mt_mail']['message_encoding']);
        }
        return $plugin;
    }
}