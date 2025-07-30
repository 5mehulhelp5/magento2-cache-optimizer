<?php
declare(strict_types=1);

namespace Triplewood\CacheOptimizer\Plugin;

use Magento\Customer\Model\Context as ContextModel;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Http\Context;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Response\Http;
use Magento\Store\Model\ScopeInterface;

class ResponsePlugin
{
    public function __construct(
        private readonly RequestInterface $request,
        private readonly Context $httpContext,
        private readonly ScopeConfigInterface $scopeConfig
    ) {
    }

    public function aroundSendResponse(Http $subject, callable $proceed)
    {
        $isEnabled =  $this->scopeConfig->getValue(
            'guest_cache_optimization/settings/active',
            ScopeInterface::SCOPE_STORE
        );

        if (!$isEnabled) {
            return $proceed();
        }

        $isLoggedIn = $this->httpContext->getValue(ContextModel::CONTEXT_AUTH);
        $moduleName = $this->request->getModuleName();
        $moduleAction = $this->request->getActionName();

        if ($this->request->isGet()
            && !$isLoggedIn
            && (
                ($moduleName === 'catalog' && $moduleAction === 'view')
                || ($moduleName === 'cms' && $moduleAction === 'view')
            )
        ) {
            $header = $this->scopeConfig->getValue(
                'guest_cache_optimization/settings/cache_header',
                ScopeInterface::SCOPE_STORE
            ) ?? 'public, max-age=600, s-maxage=3600';

            $subject->setHeader('Cache-Control', $header, true);
            $subject->clearHeader('Pragma');
            $subject->setHeader('Pragma', '', true);
            $subject->clearHeader('Expires');
            $subject->setHeader('Expires', '', true);
        }

        return $proceed();
    }
}
