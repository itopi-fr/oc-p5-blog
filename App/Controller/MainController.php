<?php

namespace App\Controller;

use Exception;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class MainController
{
    protected FilesystemLoader $loader;
    protected Environment $twig;
    public array $toDump = [];

    protected array $twigData = [
        'posts' => [1 => ['id' => 1, 'title' => 'Article 1', 'content' => 'Content 1', 'date' => '2023-01-01'],
            2 => ['id' => 2, 'title' => 'Article 2', 'content' => 'Content 2', 'date' => '2022-12-15'],
            3 => ['id' => 3, 'title' => 'Article 3', 'content' => 'Content 3', 'date' => '2023-02-01'],
            4 => ['id' => 4, 'title' => 'Article 4', 'content' => 'Content 4', 'date' => '2023-01-01'],
            5 => ['id' => 5, 'title' => 'Article 5', 'content' => 'Content 5', 'date' => '2023-01-01'],
            6 => ['id' => 6, 'title' => 'Article 6', 'content' => 'Content 6', 'date' => '2023-01-01'],
            7 => ['id' => 7, 'title' => 'Article 7', 'content' => 'Content 7', 'date' => '2023-01-01'],
            8 => ['id' => 8, 'title' => 'Article 8', 'content' => 'Content 8', 'date' => '2023-01-01'],
            9 => ['id' => 9, 'title' => 'Article 9', 'content' => 'Content 9', 'date' => '2023-01-01'],
            10 => ['id' => 10, 'title' => 'Article 10', 'content' => 'Content 10', 'date' => '2023-01-01']]
    ];

    public function __construct()
    {
        $this->initTwig();
    }

    public function __destruct()
    {
        $this->showDump();
    }

    /** -------------------------------------------------- Methods -------------------------------------------------  */

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
        if (!empty($this->toDump)) {
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
        header("refresh:$delay;url=$route");
    }

    /**
     * Refresh current page after 2 seconds (to display a message before refreshing)
     * @return void
     */
    protected function refresh(): void
    {
        header("Refresh:2");
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

        if (isset($_SESSION)) {
            isset($_SESSION['userid']) ?
                $this->twig->addGlobal('userid', $_SESSION['userid']) :
                $this->twig->addGlobal('userid', -1);
        } else {
            $this->twig->addGlobal('userid', -1);
        }
    }
}
