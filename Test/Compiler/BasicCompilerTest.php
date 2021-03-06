<?php declare(strict_types=1);
/**
 * Part of Windwalker project Test files.  @codingStandardsIgnoreStart
 *
 * @copyright  Copyright (C) 2019 LYRASOFT Taiwan, Inc.
 * @license    LGPL-2.0-or-later
 */

namespace Windwalker\Router\Test\Compiler;

use Windwalker\Router\Compiler\BasicCompiler;
use Windwalker\Router\RouteHelper;

/**
 * Test class of BasicCompiler
 *
 * @since 2.0
 */
class BasicCompilerTest extends \PHPUnit\Framework\TestCase
{
    /**
     * regexList
     *
     * @return  array
     */
    public function regexList()
    {
        return [
            [
                '/flower/(id)',
                '/flower/(?P<id>\d+)',
                '/flower/25',
                ['id' => 25],
                __LINE__,
            ],
            [
                '/flower/caesar/(id)/(alias)',
                '/flower/caesar/(?P<id>\d+)/(?P<alias>[^/]+)',
                '/flower/caesar/25/othello',
                ['id' => 25, 'alias' => 'othello'],
                __LINE__,
            ],
            [
                '/flower/caesar/(id)-(alias)',
                '/flower/caesar/(?P<id>\d+)-(?P<alias>[^/]+)',
                '/flower/caesar/25-othello',
                ['id' => 25, 'alias' => 'othello'],
                __LINE__,
            ],
            [
                '/flower(/id)',
                '/flower(/(?P<id>\d+))?',
                '/flower/33',
                ['id' => 33],
                __LINE__,
            ],
            [
                '/flower(/id)',
                '/flower(/(?P<id>\d+))?',
                '/flower',
                [],
                __LINE__,
            ],
            [
                '/flower/caesar(/id,alias)',
                '/flower/caesar(/(?P<id>\d+)(/(?P<alias>[^/]+))?)?',
                '/flower/caesar/25/othello',
                ['id' => 25, 'alias' => 'othello'],
                __LINE__,
            ],
            [
                '/flower/caesar(/id,alias)',
                '/flower/caesar(/(?P<id>\d+)(/(?P<alias>[^/]+))?)?',
                '/flower/caesar/25',
                ['id' => 25],
                __LINE__,
            ],
            [
                '/flower/caesar(/id,alias)',
                '/flower/caesar(/(?P<id>\d+)(/(?P<alias>[^/]+))?)?',
                '/flower/caesar',
                [],
                __LINE__,
            ],
            [
                '/king(/foo,bar,baz,yoo)',
                '/king(/(?P<foo>[^/]+)(/(?P<bar>[^/]+)(/(?P<baz>[^/]+)(/(?P<yoo>[^/]+))?)?)?)?',
                '/king/john/troilus/and/cressida',
                ['foo' => 'john', 'bar' => 'troilus', 'baz' => 'and', 'yoo' => 'cressida'],
                __LINE__,
            ],
            [
                '/king/(*tags)',
                '/king/(?P<tags>.*)',
                '/king/john/troilus/and/cressida',
                ['tags' => ['john', 'troilus', 'and', 'cressida']],
                __LINE__,
            ],
            [
                '/king/(*tags)/and/(alias)',
                '/king/(?P<tags>.*)/and/(?P<alias>[^/]+)',
                '/king/john/troilus/and/cressida',
                ['tags' => ['john', 'troilus'], 'alias' => 'cressida'],
                __LINE__,
            ],
            [
                '/king(/*tags)',
                '/king(/(?P<tags>.*))?',
                '/king',
                [],
                __LINE__,
            ],
            [
                '/king(/*tags)',
                '/king(/(?P<tags>.*))?',
                '/king/john/troilus/and/cressida',
                ['tags' => ['john', 'troilus', 'and', 'cressida']],
                __LINE__,
            ],
            [
                '/king(/*tags)/and/(alias)',
                '/king(/(?P<tags>.*))?/and/(?P<alias>[^/]+)',
                '/king/john/troilus/and/cressida',
                ['tags' => ['john', 'troilus'], 'alias' => 'cressida'],
                __LINE__,
            ],
        ];
    }

    /**
     * Method to test compile().
     *
     * @param string $pattern
     * @param string $expected
     * @param int    $line
     *
     * @return void
     *
     * @covers        \Windwalker\Router\Compiler\BasicCompiler::compile
     *
     * @dataProvider  regexList
     */
    public function testCompile($pattern, $expected, $route, $expectedMatches, $line)
    {
        $regex = BasicCompiler::compile($pattern, ['id' => '\d+']);

        $this->assertEquals(
            '/^' . $expected . '$/',
            str_replace(chr(1), '/', $regex),
            'Fail at: ' . $line
        );

        preg_match($regex, $route, $matches);

        $this->assertNotEmpty($matches);

        $vars = RouteHelper::getVariables($matches);

        $this->assertEquals($expectedMatches, $vars);
    }
}
