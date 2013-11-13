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

/**
 * Mysql RAND() implementation for Doctrine2
 */
class Rand extends FunctionNode
{
    public function getSql(\Doctrine\ORM\Query\SqlWalker $sqlWalker)
    {
        $sql = 'RAND()';
        if ( $this->randMax !== null && $this->randMin !== null ) {
            $sql = '(FLOOR('.$sql.' * (' . $this->randMax->dispatch($sqlWalker) . ' - ' . $this->randMin->dispatch($sqlWalker) . ' + 1)) + ' . $this->randMin->dispatch($sqlWalker) . ')';
        }
        return $sql;        
    }

    public function parse(\Doctrine\ORM\Query\Parser $parser)
    {
        $lexer = $parser->getLexer();

        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        // parse parameters if available
        if(Lexer::T_INTEGER === $lexer->lookahead['type'] || Lexer::T_FLOAT === $lexer->lookahead['type']){
            $this->randMin = $parser->ScalarExpression();
            $parser->match(Lexer::T_COMMA);
            $this->randMax = $parser->ScalarExpression();
        }
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);        
    }
}
