<?php

/**
 * DoctrineExtensions Mysql Function Pack
 */

namespace DoctrineExtensions\Query\Mysql;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer; 

class Replace extends FunctionNode
{
    public $columnString = null;
    public $searchString = null;
    public $replaceString = null;
    /**
    * Parse the query expression
    *
    * @param \Doctrine\ORM\Query\Parser $parser
    */
    public function parse(\Doctrine\ORM\Query\Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER); // (2)
        $parser->match(Lexer::T_OPEN_PARENTHESIS); // (3)
        $this->columnString = $parser->StringPrimary(); // (4)
        $parser->match(Lexer::T_COMMA); // (5)
        $this->searchString = $parser->StringPrimary(); // (6)
        $parser->match(Lexer::T_COMMA); // (5)
        $this->replaceString = $parser->StringPrimary(); // (6)
        $parser->match(Lexer::T_CLOSE_PARENTHESIS); // (3)
    }
    /**
    * Return the created string representation
    *
    * @param \Doctrine\ORM\Query\SqlWalker $sqlWalker
    *
    * @return string
    */
    public function getSql(\Doctrine\ORM\Query\SqlWalker $sqlWalker)
    {
        return '(REPLACE (' .
            $this->columnString->dispatch($sqlWalker) .
            ', ' .
            $this->searchString->dispatch($sqlWalker) .
            ', ' .
            $this->replaceString->dispatch($sqlWalker) .
        '))'; // (7)
    }
}