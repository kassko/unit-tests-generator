<?php

namespace Kassko\Test\UnitTestsGenerator;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

class FuncCallCollectorVisitor extends NodeVisitorAbstract
{
    public function enterNode(Node $node) 
    {
    	if (
    		$node instanceof Node\Expression\Assign
    		||
    		$node instanceof Node\Expression\AssignOp
		) {
			//
    	} elseif ($node instanceof Node\Expression\MethodCall) {
            //
        } elseif ($node instanceof Node\Stmt\Class_) {
        	//
        }
    }
}