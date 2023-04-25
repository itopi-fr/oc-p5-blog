<?php

namespace App\Controller;

use Exception;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use App\Sys\SuperGlobals;

class MainController
{
    protected FilesystemLoader $loader;
    protected Environment $twig;
    protected UserController $userController;
    public array $toDump = [];
    protected array $twigData = [];
    public SuperGlobals $sGlob;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->sGlob = new SuperGlobals();
        $this->initTwig();
    }

    public function __destruct()
    {
        $this->showDump();
    }

    /** -------------------------------------------------- Methods -------------------------------------------------  */

    /**
     * Check if a value is set
     * @param mixed $value
     * @return bool
     */
    protected function isSet(mixed $value): bool
    {
        return isset($value) && !empty($value);
    }


    /**
     * Check if a string is alphanumeric and -
     * @param string $value
     * @return bool
     */
    protected function isAlphaNumDash(string $value): bool
    {
        return preg_match("/^[a-zA-Z0-9\-]+$/", $value);
    }


    /**
     * Check if a string is alphanumeric, - and _
     * @param string $value
     * @return bool
     */
    protected function isAlphaNumDashUnderscore(string $value): bool
    {
        return preg_match("/^[a-zA-Z0-9_\-]+$/", $value);
    }


    /**
     * Check if a string is alphanumeric, "-", "_" and spaces
     * @param string $value
     * @return bool
     */
    protected function isAlphaNumSpacesPunct(string $value): bool
    {
        // \pL = Unicode letter (including accents).
        // \pP = Unicode punctuation.
        // Cf. https://www.php.net/manual/en/regexp.reference.unicode.php.
        return preg_match("/^[\s\pL\pP+]*$/u", $value);
    }

    /**
     * Check if a string is between 2 lengths
     * @param string $value
     * @param int $min
     * @param int $max
     * @return bool
     */
    protected function isBetween(string $value, int $min, int $max): bool
    {
        return strlen($value) >= $min && strlen($value) <= $max;
    }

    /**
     * Check if a string is a valid email
     * @param string $value
     * @return bool
     */
    protected function isEmail(string $value): bool
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
     */
    protected function showDump(): void
    {
        if ($this->sGlob->getEnv('MODE_DEV') === 'true') {
            if (empty($this->toDump) === false) {
                $html = "<pre class='vardump abs-center'>";
                $html .= "<h1>Debug</h1>";
                $html .= "<div class='vardump-close' onclick='this.parentElement.remove()'>
                    <i class='fa fa-window-close' aria-hidden='true'></i>
                  </div>";

                foreach ($this->toDump as $dumpLine) {
                    $html .= "<p><strong>Dumped from : {$dumpLine['caller_file']}</strong></p>";
                    $html .= var_export($dumpLine['data'], true);
                }
                $html .= "</pre>";
                $this->twig->display('blocks/block_fo_debug.twig', ['html' => $html]);
            }
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

        if (empty($this->sGlob->getSesAll() === false)) {
            // Current user info
            (empty($this->sGlob->getSes('usrid')) === false) ?
                $this->twig->addGlobal('usrid', $this->sGlob->getSes('usrid')) :
                $this->twig->addGlobal('usrid', null);

            // Current User Object
            (empty($this->sGlob->getSes('userobj')) === false) ?
                $this->twig->addGlobal('userobj', $this->sGlob->getSes('userobj')) :
                $this->twig->addGlobal('userobj', null);

            // Owner info displayed in the header to all visitors
            empty($this->sGlob->getSes('ownerinfo')) === false ?
                $this->twig->addGlobal('ownerinfo', $this->sGlob->getSes('ownerinfo')) :
                $this->twig->addGlobal('ownerinfo', null);
        } else {
            $this->twig->addGlobal('usrid', null);
        }
    }
}
