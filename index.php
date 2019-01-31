<?php
namespace DrdPlus\AttackSkeleton;

use DrdPlus\CalculatorSkeleton\CalculatorApplication;
use DrdPlus\CalculatorSkeleton\CalculatorConfiguration;
use DrdPlus\RulesSkeleton\Dirs;
use DrdPlus\RulesSkeleton\HtmlHelper;

\error_reporting(-1);
if ((!empty($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] === '127.0.0.1') || PHP_SAPI === 'cli') {
    \ini_set('display_errors', '1');
} else {
    \ini_set('display_errors', '0');
}

$documentRoot = $documentRoot ?? (PHP_SAPI !== 'cli' ? \rtrim(\dirname($_SERVER['SCRIPT_FILENAME']), '\/') : \getcwd());
$vendorRoot = $vendorRoot ?? $documentRoot . '/vendor';

/** @noinspection PhpIncludeInspection */
require_once $vendorRoot . '/autoload.php';

$dirs = Dirs::createFromGlobals();
$htmlHelper = HtmlHelper::createFromGlobals($dirs);
$calculatorConfiguration = CalculatorConfiguration::createFromYml($dirs);
$servicesContainer = new AttackServicesContainer($calculatorConfiguration, $htmlHelper);
$calculatorApplication = new CalculatorApplication($servicesContainer);
$calculatorApplication->run();