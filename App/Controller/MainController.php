<?php

namespace App\Controller;

use Exception;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use App\Sys\SuperGlobals;

/**
 * Class MainController - Main tools.
 * Used as a parent class for other controllers.
 * Used to load Twig and set global variables.
 */
class MainController
{
    /**
     * @var FilesystemLoader
     */
    protected FilesystemLoader $loader;

    /**
     * @var Environment
     */
    protected Environment $twig;

    /**
     * @var array
     */
    public array $toDump = [];

    /**
     * @var array
     */
    protected array $twigData = [];

    /**
     * @var SuperGlobals
     */
    public SuperGlobals $sGlob;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->sGlob = new SuperGlobals();
        $this->initTwig();
    }


    /**
     * Destructor
     * Show the dump
     */
    public function __destruct()
    {
        $this->showDump();
    }


    /** -------------------------------------------------- Methods -------------------------------------------------  */
    /**
     * Check if a value is set
     *
     * @param mixed $value - The value to check.
     * @return bool
     */
    protected function isSet(mixed $value): bool
    {
        return isset($value) && !empty($value);
    }


    /**
     * Check if a string is alphanumeric and -
     *
     * @param string $value - The value to check.
     * @return bool
     */
    protected function isAlphaNumDash(string $value): bool
    {
        return preg_match("/^[a-zA-Z0-9\-]+$/", $value);
    }


    /**
     * Check if a string is alphanumeric, - and _
     *
     * @param string $value - The value to check.
     * @return bool
     */
    protected function isAlphaNumDashUnderscore(string $value): bool
    {
        return preg_match("/^[a-zA-Z0-9_\-]+$/", $value);
    }


    /**
     * Check if a string is alphanumeric, "-", "_" and spaces
     *
     * @param string $value - The value to check.
     * @return bool
     */
    protected function isAlphaNumSpacesPunct(string $value): bool
    {
        // \pL = Unicode letter (including accents).
        // \pP = Unicode punctuation.
        // Cf. https://www.php.net/manual/en/regexp.reference.unicode.php.
        return preg_match("/^[\w\s\pL\pP+]*$/u", $value);
    }


    /**
     * Check if a string is between 2 lengths
     *
     * @param string $value - The value to check.
     * @param int $min - The minimum length.
     * @param int $max - The maximum length.
     * @return bool
     */
    protected function isBetween(string $value, int $min, int $max): bool
    {
        return strlen($value) >= $min && strlen($value) <= $max;
    }


    /**
     * Check if a string is a valid email
     *
     * @param string $value - The value to check.
     * @return bool
     */
    protected function isEmail(string $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }


    /**
     * Check if a string is a valid URL
     * Add http:// if not present
     *
     * @param string $value - The value to check.
     * @return string|null
     */
    protected function validateUrl(string $value): string|null
    {
        // Sanitize url.
        $value = filter_var($value, FILTER_SANITIZE_URL);

        // Validate url.
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return true;
        } else {
            // Add http:// if not present.
            $value = 'http://' . $value;

            // Re-validate url.
            if (filter_var($value, FILTER_VALIDATE_URL)) {
                return $value;
            } else {
                return null;
            }
        }
    }


    /**
     * Every dump is stored inside the $toDump array.
     * It will be displayed using showDump() method called in the __destruct() method of MainController class.
     * Must be called using parent::dump($var) in the child class
     * in order to have all the dumped variables displayed in the same block.
     *
     * @param $dumpThis - The variable to dump.
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
     *
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
     *
     * @param string $route - The route to redirect to.
     * @param int $delay - The delay before redirecting.
     * @return void
     */
    protected function redirectTo(string $route, int $delay = 3): void
    {
        header("Refresh:$delay; url=$route");
    }


    /**
     * Refresh current page after a given number of seconds
     *
     * @param int $seconds - The number of seconds before refreshing.
     * @return void
     */
    protected function refresh(int $seconds = 5): void
    {
        header("Refresh:$seconds");
    }


    /**
     * Refresh instantly current page
     *
     * @return void
     */
    protected function refreshNow(): void
    {
        header("Refresh:0");
    }


    /**
     * Generates a random key
     *
     * @param int $length - The length of the key to generate.
     * @return string
     * @throws Exception
     */
    public function generateKey(int $length): string
    {
        return (bin2hex(random_bytes($length)));
    }


    /**
     * Initializes Twig
     *
     * @return void
     */
    protected function initTwig(): void
    {
        $this->loader = new FilesystemLoader(__DIR__ . '/../View/templates');

        $this->twig = new Environment($this->loader, [
                                                        'cache' => false,
                                                        'debug' => true,
                                                       ]);
        // Display dates in French timezone
        $this->twig->getExtension(\Twig\Extension\CoreExtension::class)->setTimezone('Europe/Paris');

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
