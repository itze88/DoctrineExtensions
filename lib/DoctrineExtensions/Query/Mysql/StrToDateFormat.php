<?php

/**
 * DoctrineExtensions Mysql Function Pack
 */

namespace DoctrineExtensions\Query\Mysql;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer; 
/**
 * StrToDateFormatFunction ::=
 *     "str_to_date" "(" StringPrimary ", " StringPrimary ", " StringPrimary ")"
 */
class StrToDateFormat extends FunctionNode
{
    public $dateField = null;
    public $dateFormat = null;
    public $returnFormat = null;
    /**
    * Parse the query expression
    *
    * @param \Doctrine\ORM\Query\Parser $parser
    */
    public function parse(\Doctrine\ORM\Query\Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER); // (2)
        $parser->match(Lexer::T_OPEN_PARENTHESIS); // (3)
        $this->dateField = $parser->StringPrimary(); // (4)
        $parser->match(Lexer::T_COMMA); // (5)
        $this->dateFormat = $parser->StringPrimary(); // (6)
        $parser->match(Lexer::T_COMMA); // (5)
        $this->returnFormat = $parser->StringPrimary(); // (6)
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
        //return 'STR_TO_DATE( '.$this->firstRegExExpression->dispatch($sqlWalker).', "'.$this->secondRegExExpression->dispatch($sqlWalker).'")'; // (7)
        return 'date_format(str_to_date(' .
            $this->dateField->dispatch($sqlWalker) .
            ', "' .
            $this->dateFormat->dispatch($sqlWalker) .
            '"), "' .
            $this->returnFormat->dispatch($sqlWalker) .
        '") '; // (7)
    }
}