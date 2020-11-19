<?php

/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PHPUnit\Util\TestDox;

use function array_key_exists;
use function array_keys;
use function array_map;
use function array_values;
use function get_class;
use function gettype;
use function in_array;
use function is_bool;
use function is_float;
use function is_int;
use function is_numeric;
use function is_object;
use function is_scalar;
use function is_string;
use function ord;
use PHPUnit\Framework\TestCase;
use PHPUnit\Util\Test;
use function preg_quote;
use function preg_replace;
use ReflectionException;
use ReflectionMethod;
use ReflectionObject;
use SebastianBergmann\Exporter\Exporter;
use function sprintf;
use function str_replace;
use function strlen;
use function strpos;
use function strripos;
use function strtolower;
use function strtoupper;
use function substr;
use function trim;

/**
 * Prettifies class and method names for use in TestDox documentation.
 */
final class NamePrettifier
{
    /**
     * @var array
     */
    private $strings = [];

    /**
     * Prettifies the name of a test class.
     */
    public function prettifyTestClass(string $className): string
    {
        try {
            $annotations = Test::parseTestMethodAnnotations($className);

            if (isset($annotations['class']['testdox'][0])) {
                return $annotations['class']['testdox'][0];
            }
        } catch (ReflectionException $e) {
        }

        $result = $className;

        if ('Test' === substr($className, -1 * strlen('Test'))) {
            $result = substr($result, 0, strripos($result, 'Test'));
        }

        if (0 === strpos($className, 'Tests')) {
            $result = substr($result, strlen('Tests'));
        } elseif (0 === strpos($className, 'Test')) {
            $result = substr($result, strlen('Test'));
        }

        if ('\\' === $result[0]) {
            $result = substr($result, 1);
        }

        return $result;
    }

    /**
     * @throws ReflectionException
     */
    public function prettifyTestCase(TestCase $test): string
    {
        $annotations = $test->getAnnotations();
        $annotationWithPlaceholders = false;

        $callback = static function (string $variable): string {
            return sprintf('/%s(?=\b)/', preg_quote($variable, '/'));
        };

        if (isset($annotations['method']['testdox'][0])) {
            $result = $annotations['method']['testdox'][0];

            if (false !== strpos($result, '$')) {
                $annotation = $annotations['method']['testdox'][0];
                $providedData = $this->mapTestMethodParameterNamesToProvidedDataValues($test);
                $variables = array_map($callback, array_keys($providedData));

                $result = trim(preg_replace($variables, $providedData, $annotation));

                $annotationWithPlaceholders = true;
            }
        } else {
            $result = $this->prettifyTestMethod($test->getName(false));
        }

        if ($test->usesDataProvider() && !$annotationWithPlaceholders) {
            $result .= $test->getDataSetAsString(false);
        }

        return $result;
    }

    /**
     * Prettifies the name of a test method.
     */
    public function prettifyTestMethod(string $name): string
    {
        $buffer = '';

        if (!is_string($name) || '' === $name) {
            return $buffer;
        }

        $string = preg_replace('#\d+$#', '', $name, -1, $count);

        if (in_array($string, $this->strings)) {
            $name = $string;
        } elseif (0 === $count) {
            $this->strings[] = $string;
        }

        if (0 === strpos($name, 'test_')) {
            $name = substr($name, 5);
        } elseif (0 === strpos($name, 'test')) {
            $name = substr($name, 4);
        }

        if ('' === $name) {
            return $buffer;
        }

        $name[0] = strtoupper($name[0]);

        if (false !== strpos($name, '_')) {
            return trim(str_replace('_', ' ', $name));
        }

        $max = strlen($name);
        $wasNumeric = false;

        for ($i = 0; $i < $max; ++$i) {
            if ($i > 0 && ord($name[$i]) >= 65 && ord($name[$i]) <= 90) {
                $buffer .= ' '.strtolower($name[$i]);
            } else {
                $isNumeric = is_numeric($name[$i]);

                if (!$wasNumeric && $isNumeric) {
                    $buffer .= ' ';
                    $wasNumeric = true;
                }

                if ($wasNumeric && !$isNumeric) {
                    $wasNumeric = false;
                }

                $buffer .= $name[$i];
            }
        }

        return $buffer;
    }

    /**
     * @throws ReflectionException
     */
    private function mapTestMethodParameterNamesToProvidedDataValues(TestCase $test): array
    {
        $reflector = new ReflectionMethod(get_class($test), $test->getName(false));
        $providedData = [];
        $providedDataValues = array_values($test->getProvidedData());
        $i = 0;

        foreach ($reflector->getParameters() as $parameter) {
            if (!array_key_exists($i, $providedDataValues) && $parameter->isDefaultValueAvailable()) {
                $providedDataValues[$i] = $parameter->getDefaultValue();
            }

            $value = $providedDataValues[$i++] ?? null;

            if (is_object($value)) {
                $reflector = new ReflectionObject($value);

                if ($reflector->hasMethod('__toString')) {
                    $value = (string) $value;
                }
            }

            if (!is_scalar($value)) {
                $value = gettype($value);
            }

            if (is_bool($value) || is_int($value) || is_float($value)) {
                $exporter = new Exporter();

                $value = $exporter->export($value);
            }

            $providedData['$'.$parameter->getName()] = $value;
        }

        return $providedData;
    }
}
