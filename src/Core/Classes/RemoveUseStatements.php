<?php

namespace Manadev\Core\Classes;

use PhpParser\Node\Stmt\Use_;
use PhpParser\NodeVisitorAbstract;

class RemoveUseStatements extends NodeVisitorAbstract
{
    public function beforeTraverse(array $nodes) {
        return array_values(array_filter($nodes, function ($node) {
            return !($node instanceof Use_);
        }));
    }
}