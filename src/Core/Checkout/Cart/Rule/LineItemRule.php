<?php declare(strict_types=1);

namespace Shopware\Core\Checkout\Cart\Rule;

use Shopware\Core\Framework\Rule\Match;
use Shopware\Core\Framework\Rule\Rule;
use Shopware\Core\Framework\Rule\RuleScope;

class LineItemRule extends Rule
{
    /**
     * @var string[]
     */
    protected $identifiers;

    /**
     * @param string[] $identifiers
     */
    public function __construct(array $identifiers)
    {
        $this->identifiers = $identifiers;
    }

    public function match(RuleScope $scope): Match
    {
        if (!$scope instanceof LineItemScope) {
            return new Match(
                false,
                ['Invalid Match Context. CartRuleScope expected']
            );
        }

        return new Match(
            \in_array($scope->getLineItem()->getKey(), $this->identifiers, true),
            ['Line item not in cart']
        );
    }

    public function getIdentifiers(): array
    {
        return $this->identifiers;
    }
}
