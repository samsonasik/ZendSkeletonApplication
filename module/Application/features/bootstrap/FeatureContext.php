<?php

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Zend\Console\Console;
use Zend\Mvc\Application;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
        Console::overrideIsConsole(false);
        $appConfig = include __DIR__ . '/../../../../config/application.config.php';
        $this->application = Application::init($appConfig);

        $events = $this->application->getEventManager();
        $this->application->getServiceManager()
                          ->get('SendResponseListener')
                          ->detach($events);
    }

    /**
     * @Given I am on the index page
     */
    public function iAmOnTheIndexPage()
    {
        $request     = $this->application->getRequest();
        $request->setMethod('GET');
        $request->setUri('/');
    }

    /**
     * @Then I should see :arg1
     */
    public function iShouldSee($arg1)
    {
        $app =  $this->application->run();

        \PHPUnit_Framework_Assert::assertContains(
            $arg1,
            $app->getResponse()->toString()
        );
    }
}
