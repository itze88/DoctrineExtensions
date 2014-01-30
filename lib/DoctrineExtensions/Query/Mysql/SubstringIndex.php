<?php
/**
 * DoctrineExtensions Mysql Function Pack
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to kontakt@beberlei.de so I can send you a copy immediately.
 */

namespace DoctrineExtensions\Query\Mysql;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;

class SubstringIndex extends FunctionNode
{
    public $str = null;
    public $delim = null;
    public $count = null;

    public function parse(\Doctrine\ORM\Query\Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);

        $this->str = $parser->StringPrimary();

        $parser->match(Lexer::T_COMMA);

        $this->delim = $parser->StringPrimary();

        $parser->match(Lexer::T_COMMA);

        $this->count = $parser->simpleArithmeticExpression();

        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    public function getSql(\Doctrine\ORM\Query\SqlWalker $sqlWalker)
    {

        return 'SUBSTRING_INDEX(' .
        $this->str->dispatch($sqlWalker) . ', ' .
        $this->delim->dispatch($sqlWalker) . ', ' .
        $this->count->dispatch($sqlWalker) .
        ')';
    }
}