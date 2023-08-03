<?php

declare(strict_types=1);

namespace Werkraum\SitePackage\Frontend\DataProcessing;

use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Context\Exception\AspectNotFoundException;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Domain\Repository\PageRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

/**
 * @see \GeorgRinger\News\DataProcessing\AddNewsToMenuProcessor
 */
class BreadcrumbProcessor implements DataProcessorInterface
{
    public const configuration = [
        ['pluginNamespace' => 'tx_news_pi1', 'argumentName' => 'news', 'tablename' => 'tx_news_domain_model_news'],
    ];

    public function process(ContentObjectRenderer $cObj, array $contentObjectConfiguration, array $processorConfiguration, array $processedData): array
    {
        if (!$processorConfiguration['menus']) {
            return $processedData;
        }
        $record = $this->findRecord();
        if ($record) {
            $menus = GeneralUtility::trimExplode(',', $processorConfiguration['menus'], true);
            foreach ($menus as $menu) {
                if (isset($processedData[$menu])) {
                    $this->addRecordToMenu($record, $processedData[$menu]);
                }
            }
        }
        return $processedData;
    }

    protected function findRecord(): ?array
    {
        foreach (self::configuration as $configuration) {
            $record = $this->getRecord($configuration['pluginNamespace'], $configuration['argumentName'], $configuration['tablename']);
            if ($record !== null) {
                return [...$record, ...$configuration];
            }
        }
        return null;
    }

    /**
     * Add the news record to the menu items
     *
     * @param array $record
     * @param array $menu
     */
    protected function addRecordToMenu(array $record, array &$menu): void
    {
        $level = 0;
        foreach ($menu as &$menuItem) {
            $menuItem['current'] = 0;
            $level = $menuItem['level'];
        }

        $title = $record['title'];

        if ($record['tablename'] === 'tt_address') {
            $list = [
                $record['title'],
                $record['first_name'],
                $record['middle_name'],
                $record['last_name'],
            ];

            $title = implode(' ', array_filter($list));
            if ($record['title_suffix']) {
                $title .= ', ' . $record['title_suffix'];
            }
        }

        $menu[] = [
            'data' => $record,
            'title' => $title,
            'active' => 1,
            'current' => 1,
            'isCurrentPage' => true,
            'isInRootline' => true,
            'hasSubpages' => false,
            'level' => $level + 1 ,
            'link' => GeneralUtility::getIndpEnv('TYPO3_REQUEST_URL'),
            'pluginNamespace' => $record['pluginNamespace'],
            'argumentName' => $record['argumentName'],
            'tablename' => $record['tablename'],
        ];
    }

    protected function getRecord(string $pluginNamespace = 'tx_news_pi1', string $argumentName = 'news', string $tablename = 'tx_news_domain_model_news'): ?array
    {
        $recordId = 0;
        $vars = GeneralUtility::_GET($pluginNamespace);
        if (isset($vars[$argumentName])) {
            $recordId = (int)$vars[$argumentName];
        }

        if ($recordId) {
            $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
                ->getQueryBuilderForTable($tablename);
            $row = $queryBuilder
                ->select('*')
                ->from($tablename)
                ->where(
                    $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($recordId, \PDO::PARAM_INT))
                )
                ->execute()
                ->fetchAssociative();

            if ($row) {
                if ($this->getTsfe()->sys_page instanceof PageRepository) {
                    $getRecordOverlay = new \ReflectionMethod(PageRepository::class, 'getRecordOverlay');
                    $getRecordOverlay->setAccessible(true);
                    $row = $getRecordOverlay->invokeArgs($this->getTsfe()->sys_page, [$tablename, $row, $this->getCurrentLanguage()]);
                }
            }

            if (is_array($row) && !empty($row)) {
                return $row;
            }
        }
        return null;
    }

    /**
     * Get current language
     *
     * @return int
     */
    protected function getCurrentLanguage(): int
    {
        $languageId = 0;
        $context = GeneralUtility::makeInstance(Context::class);
        try {
            $languageId = $context->getPropertyFromAspect('language', 'contentId');
        } catch (AspectNotFoundException $e) {
            // do nothing
        }

        return (int)$languageId;
    }

    /**
     * @return TypoScriptFrontendController
     */
    protected function getTsfe(): TypoScriptFrontendController
    {
        return $GLOBALS['TSFE'];
    }
}
