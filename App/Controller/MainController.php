<?php

namespace App\Controller;

use App\Model\UserOwnerModel;
use Exception;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use App\Controller\OwnerInfoController;
use App\Sys\SuperGlobals;

class MainController
{
    protected FilesystemLoader $loader;
    protected Environment $twig;
    protected UserController $userController;
    public array $toDump = [];
    protected array $twigData = [];
    private UserOwnerModel $userOwnerModel;
    private OwnerInfoController $ownerInfoController;
    private SuperGlobals $superGlobals;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->initTwig();
        $this->superGlobals = new SuperGlobals();
    }

    public function __destruct()
    {
        if ($this->superGlobals->getEnv('MODE_DEV') === 'true') {
            $this->showDump();
        }
    }

    /** -------------------------------------------------- Methods -------------------------------------------------  */

    /**
     * Check if a value is set
     * @param mixed $value
     * @return bool
     */
    protected function isSet($value)
    {
        return isset($value) && !empty($value);
    }

    /**
     * Check if a string is alphanumeric, - and _
     * @param string $value
     * @return bool
     */
    protected function isAlphaNumPlus(string $value)
    {
        return preg_match("/^[a-zA-Z0-9_\-]+$/", $value);
    }

    /**
     * Check if a string is alphanumeric, "-", "_" and spaces
     * @param string $value
     * @return bool
     */
    protected function isAlphaNumSpacesPonct(string $value)
    {
        return preg_match("/^[\w\d,!(). \-]*$/", $value);
    }

    /**
     * Check if a string is between 2 lengths
     * @param string $value
     * @param int $min
     * @param int $max
     * @return bool
     */
    protected function isBetween(string $value, int $min, int $max)
    {
        return strlen($value) >= $min && strlen($value) <= $max;
    }

    /**
     * Check if a string is a valid email
     * @param string $value
     * @return bool
     */
    protected function isEmail(string $value)
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }

    /**
     * Every dump is stored inside the $toDump array.
     * It will be displayed using showDump() method called in the __destruct() method of MainController class.
     * Must be called using parent::dump($var) in the child class
     * in order to have all the dumped variables displayed in the same block.
     * @param $dumpThis
     * @return void
     */
    public function dump($dumpThis): void
    {
        $caller = debug_backtrace()[0];
        preg_match('/.*(root.*)/', $caller['file'], $match);
        $this->toDump[] = [
            'data' => $dumpThis,
            'caller_file' => $match[1] . ':' . $caller['line'],
            'caller_line' => $caller['line'],
            'caller_function' => $caller['function'],
            'caller_class' => $caller['class'],
            'caller_object' => $caller['object'],
        ];
    }

    /**
     * Displays all dumped information using dump() method inside a pre tag.
     * This method is called in the __destruct() method.
     * Should be called using parent::showDump() in the child class
     * in order to have all the dumped variables displayed in the same block.
     * @return void
     *
     * TODO : Faire plus propre
     */
    protected function showDump(): void
    {
        if (empty($this->toDump) === false) {
            echo "<pre class='vardump abs-center'>";
            echo "<h1>Debug</h1>";
            echo "<div class='vardump-close' onclick='this.parentElement.remove()'>
                    <i class='fa fa-window-close' aria-hidden='true'></i>
                  </div>";

            foreach ($this->toDump as $dumpLine) {
                echo "<p<strong>Dumped from : {$dumpLine['caller_file']}</strong></p>";
                var_dump($dumpLine['data']);
            }
            echo "</pre>";
        }
    }

    /**
     * Redirects to the given route
     * @param string $route
     * @param int $delay
     * @return void
     */
    protected function redirectTo(string $route, int $delay): void
    {
        header("Refresh:$delay; url=$route");
    }

    /**
     * Refresh current page after a given number of seconds
     * @param int $seconds
     * @return void
     */
    protected function refresh(int $seconds = 5): void
    {
        header("Refresh:$seconds");
    }

    /**
     * Refresh instantly current page
     * @return void
     */
    protected function refreshNow(): void
    {
        header("Refresh:0");
    }

    /**
     * Generates a random key
     * @param int $length
     * @return string
     * @throws Exception
     */
    public function generateKey(int $length): string
    {
        return (bin2hex(random_bytes($length)));
    }

    /**
     * Initializes Twig
     * @return void
     */
    protected function initTwig(): void
    {
        $this->loader = new FilesystemLoader(__DIR__ . '/../View/templates');

        $this->twig = new Environment($this->loader, [
            'cache' => false,
            'debug' => true,
        ]);

        if (isset($_SESSION) === true) {
            // Current user info
            (isset($_SESSION['userid']) === true) ?
                $this->twig->addGlobal('userid', $_SESSION['userid']) :
                $this->twig->addGlobal('userid', null);

            // Current User Object
            (isset($_SESSION['userobj']) === true) ?
                $this->twig->addGlobal('userobj', $_SESSION['userobj']) :
                $this->twig->addGlobal('userobj', null);

            // Owner info displayed in the header to all visitors
            isset($_SESSION['ownerinfo']) === true ?
                $this->twig->addGlobal('ownerinfo', $_SESSION['ownerinfo']) :
                $this->twig->addGlobal('ownerinfo', null);
        } else {
            $this->twig->addGlobal('userid', null);
        }
    }
}
