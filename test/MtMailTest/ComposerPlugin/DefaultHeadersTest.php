<?php
/**
 * MtMail - e-mail module for Zend Framework 2
 *
 * @link      http://github.com/mtymek/MtMail
 * @copyright Copyright (c) 2013-2014 Mateusz Tymek
 * @license   BSD 2-Clause
 */

namespace MtMailTest\Plugin;

use MtMail\Event\ComposerEvent;
use MtMail\ComposerPlugin\DefaultHeaders;
use MtMailTest\Test\HeadersProviderTemplate;
use MtMailTest\Test\HeaderObjectProviderTemplate;
use Zend\Mail\Header\Subject;
use Zend\Mail\Message;

class DefaultHeadersTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var DefaultHeaders
     */
    protected $plugin;

    public function setUp()
    {
        $this->plugin = new DefaultHeaders();
    }

    public function testLayoutHeadersIsMutable()
    {
        $this->plugin->setHeaders(['from' => 'sender@domain.tld']);
        $this->assertEquals(['from' => 'sender@domain.tld'], $this->plugin->getHeaders());
    }

    public function testInjectDefaultHeadersSetsHeaders()
    {
        $this->plugin->setHeaders([
                'from' => 'sender@domain.com',
                'subject' => 'Hello!',
            ]);
        $headers = $this->getMock('Zend\Mail\Headers', ['addHeaderLine']);
        $headers->expects($this->at(0))->method('addHeaderLine')->with('from', 'sender@domain.com');
        $headers->expects($this->at(1))->method('addHeaderLine')->with('subject', 'Hello!');
        $message = new Message();
        $message->setHeaders($headers);
        $event = new ComposerEvent();
        $event->setMessage($message);
        $this->plugin->injectDefaultHeaders($event);
    }

    public function testPluginCanInjectTemplateSpecificHeaders()
    {
        $headers = $this->getMock('Zend\Mail\Headers', ['addHeaderLine']);
        $headers->expects($this->at(0))->method('addHeaderLine')->with('subject', 'Default subject');
        $template = new HeadersProviderTemplate();
        $message = new Message();
        $message->setHeaders($headers);
        $event = new ComposerEvent();
        $event->setMessage($message);
        $event->setTemplate($template);
        $this->plugin->injectDefaultHeaders($event);
    }

    public function testPluginCanInjectHeaderObjects()
    {
        $subject = (new Subject())->setSubject('Hello!');
        $this->plugin->setHeaders([
            'subject' => $subject,
        ]);

        $headers = $this->getMock('Zend\Mail\Headers', ['addHeader']);
        $headers->expects($this->at(0))->method('addHeader')->with($subject);
        $message = new Message();
        $message->setHeaders($headers);
        $event = new ComposerEvent();
        $event->setMessage($message);
        $this->plugin->injectDefaultHeaders($event);
    }

    public function testPluginCanInjectTemplateSpecificHeaderObjects()
    {
        $headers = $this->getMock('Zend\Mail\Headers', ['addHeader']);
        $headers->expects($this->at(0))->method('addHeader')->with((new Subject())->setSubject('Default subject'));
        $template = new HeaderObjectProviderTemplate();
        $message = new Message();
        $message->setHeaders($headers);
        $event = new ComposerEvent();
        $event->setMessage($message);
        $event->setTemplate($template);
        $this->plugin->injectDefaultHeaders($event);
    }
}
