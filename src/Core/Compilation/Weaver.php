<?php

namespace Manadev\Core\Compilation;

use Manadev\Core\Classes\Statements;
use Manadev\Core\Object_;
use PhpParser\Node;
use PhpParser\NodeVisitor\NameResolver;
use PhpParser\ParserFactory;

/**
 * @property Class_ $class_ @temp
 */
class Weaver extends Object_
{
    public function weave() {
        $this->parseNamespace();
        $this->parseMembers();
    }

    protected function parseNamespace() {
        if (($pos = strrpos($this->class_->name, '\\')) !== false) {
            $this->class_->namespace = substr($this->class_->name, 0, $pos);
            $this->class_->short_name = substr($this->class_->name, $pos + 1);
        }
        else {
            $this->class_->namespace = '';
            $this->class_->short_name = $this->class_->name;
        }
    }

    protected function parseMembers() {
        $this->class_->property_names = [];
        $this->class_->method_names = [];
        $this->class_->aliases = [];
        $this->class_->methods = [];

        $reflection = new \ReflectionClass($this->class_->name);
        $this->class_->abstract = $reflection->isAbstract();

        foreach ($reflection->getProperties() as $property) {
            $this->class_->property_names[$property->getName()] = $this->class_->name;
        }

        foreach ($reflection->getMethods() as $method) {
            $this->class_->method_names[$method->getName()] = $this->class_->name;
        }

        foreach ($this->class_->traits as $trait) {
            $this->parseTraitMembers($trait);
        }
    }

    protected function parseTraitMembers($trait) {
        $reflection = new \ReflectionClass($trait);

        foreach ($reflection->getProperties() as $property) {
            if (isset($this->class_->property_names[$property->getName()])) {
                $this->addAlias($trait, $property);
                continue;
            }

            $this->class_->property_names[$property->getName()] = $trait;
        }

        foreach ($reflection->getMethods() as $method) {
            if (isset($this->class_->method_names[$method->getName()])) {
                $this->addAlias($trait, $method);
                continue;
            }

            if (strpos($method->getName(), 'around_') === 0) {
                $alias = $this->addAlias($trait, $method);
                $this->addMethodAdvice(substr($method->getName(), strlen('around_')), $alias);
                continue;
            }

            $this->class_->method_names[$method->getName()] = $trait;
        }
    }

    /**
     * @param string $trait
     * @param \ReflectionMethod|\ReflectionProperty $member
     * @return Alias
     */
    protected function addAlias($trait, $member) {
        $name = str_replace('\\', '_', $trait) . '_' . $member->getName();

        return $this->class_->aliases[$name] = Alias::new([
            'class_' => $trait,
            'member' => $member->getName(),
        ], $name);
    }

    /**
     * @param string $method
     * @param Alias $alias
     */
    protected function addMethodAdvice($method, $alias) {
        if (!isset($this->class_->method_names[$method])) {
            return;
        }

        if (!isset($this->class_->methods[$method])) {
            $reflection = new \ReflectionMethod($this->class_->method_names[$method], $method);

            $this->class_->methods[$method] = Method::new([
                'access' => $reflection->getModifiers() | \ReflectionMethod::IS_PUBLIC != 0
                    ? 'public'
                    : 'protected',
                'advices' => [],
                'uses_func_get_args' => $this->uses_func_get_args($reflection),
            ], $method);

            if (!is_a($this->class_->name, $this->class_->method_names[$method], true)) {
                $this->class_->methods[$method]->trait = $this->class_->method_names[$method];
                $this->addAlias($this->class_->method_names[$method], $reflection);
            }

            $this->parseParameters($this->class_->methods[$method], $reflection);
        }

        $this->class_->methods[$method]->advices[] = $alias->name;
    }

    /**
     * @param Method $method
     * @param \ReflectionMethod $reflection
     */
    protected function parseParameters($method, $reflection) {
        $params = '';
        $args = '';
        $use = '';

        foreach ($reflection->getParameters() as $parameter) {
            /* @var \ReflectionParameter $parameter */
            if ($args) {
                $params .= ', ';
                $args .= ', ';
                $use .= ', ';
            }

            if ($parameter->getClass()) {
                $params .= '\\' . $parameter->getClass()->getName() . ' ';
            }

            if ($parameter->isArray()) {
                $params .= 'array ';
            }

            if ($parameter->isCallable()) {
                $params .= 'callable ';
            }

            if ($parameter->isPassedByReference()) {
                $params .= '&';
                $use .= '&';
            }

            if ($parameter->isVariadic()) {
                $params .= '...';
                $args .= '...';
                $use .= '...';
            }

            $params .= '$' . $parameter->getName();
            $args .= '$' . $parameter->getName();
            $use .= '$' . $parameter->getName();

            if ($parameter->isDefaultValueAvailable()) {
                $params .= ' = ' . var_export($parameter->getDefaultValue(), true);
            }
        }

        $method->use_parameters = $use ? "use ($use) " : '';
        $method->parameters = $params;
        $method->arguments = $args;
    }

    /**
     * @param \ReflectionMethod $reflection
     * @return bool
     */
    protected function uses_func_get_args($reflection) {
        $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);

        $funcGetArgsCall = (new Statements($parser->parse(file_get_contents($reflection->getFileName()))))
            ->each(new NameResolver())
            ->find(function($node) use ($reflection) {
                if (!($node instanceof Node\Expr\FuncCall)) {
                    return false;
                }

                if (!($node->name instanceof Node\Name)) {
                    return false;
                }

                if ($node->name->toString() != 'func_get_args') {
                    return false;
                }

                if ($node->getAttribute('endLine') < $reflection->getStartLine()) {
                    return false;
                }

                if ($node->getAttribute('startLine') > $reflection->getEndLine()) {
                    return false;
                }

                return true;
            });

        return count($funcGetArgsCall->stmts) > 0;
    }
}