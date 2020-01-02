<?php


namespace App\Service;


use http\Header\Parser;
use Michelf\MarkdownInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\Security\Core\Security;

class MarkdownHelper
{

    /**
     * @var AdapterInterface
     */
    private $cache;
    /**
     * @var MarkdownInterface
     */
    private $markdown;
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var Security
     */
    private $security;

    public function __construct(AdapterInterface $cache, MarkdownInterface $markdown, LoggerInterface $markdownLogger, Security $security)
    {
        $this->cache = $cache;
        $this->markdown = $markdown;
        $this->logger = $markdownLogger;
        $this->security = $security;
    }

    public function parse(string $source): string
    {
        if(strpos($source, 'beacon') !== false) {
            $this->logger->info('Beacon comes again ....', [
                'user' => $this->security->getUser()
            ]);
        }

        $item = $this->cache->getItem('markdown_'.md5($source));
        if (!$item->isHit()) {
            $item->set($this->markdown->transform($source));
            $this->cache->save($item);
        }
        return $item->get();
    }
}