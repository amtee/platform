<?php declare(strict_types=1);

namespace Shopware\Storefront\Controller\Widgets;

use Shopware\Core\Checkout\CheckoutContext;
use Shopware\Core\Framework\ORM\RepositoryInterface;
use Shopware\Core\Framework\ORM\Search\Criteria;
use Shopware\Core\Framework\ORM\Search\EntitySearchResult;
use Shopware\Core\Framework\ORM\Search\Query\TermQuery;
use Shopware\Storefront\Controller\StorefrontController;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends StorefrontController
{
    /**
     * @var RepositoryInterface
     */
    private $currencyRepository;

    /**
     * @var RepositoryInterface
     */
    private $languageRepository;

    public function __construct(RepositoryInterface $currencyRepository, RepositoryInterface $languageRepository)
    {
        $this->currencyRepository = $currencyRepository;
        $this->languageRepository = $languageRepository;
    }

    /**
     * @Route("/widgets/index/shopMenu", name="widgets/shopMenu", methods={"GET"})
     *
     * @param CheckoutContext $context
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function shopMenuAction(CheckoutContext $context)
    {
        return $this->render('@Storefront/widgets/index/shop_menu.html.twig', [
            'application' => $context->getSalesChannel(),
            'currencies' => $this->getCurrencies($context),
            'currency' => $context->getSalesChannel()->getCurrency(),
            'languages' => $this->getLanguages($context),
            'language' => $context->getSalesChannel()->getLanguage(),
        ]);
    }

    private function getLanguages(CheckoutContext $context): EntitySearchResult
    {
        $criteria = new Criteria();
        $criteria->addFilter(new TermQuery('language.salesChannels.id', $context->getSalesChannel()->getId()));

        return $this->languageRepository->search($criteria, $context->getContext());
    }

    private function getCurrencies(CheckoutContext $context): EntitySearchResult
    {
        $criteria = new Criteria();
        $criteria->addFilter(new TermQuery('currency.salesChannels.id', $context->getSalesChannel()->getId()));

        return $this->currencyRepository->search($criteria, $context->getContext());
    }
}
