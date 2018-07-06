<?php

namespace Drupal\dino\Twig;

use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\ProfilerExtension;
use Twig\Profiler\Profile;

class MetadataRenderProfiler extends ProfilerExtension
{
    /** @var string */
    private $currentTemplate;

    /** @var int */
    private $nestingLevel = 0;

    /** @var RequestStack */
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    const ENABLE_FLAG_COOKIE_ID = 'enableMarkupToolkit';

    /**
     * @param Profile $profile
     */
    public function enter(Profile $profile)
    {
        if (!$this->isEnabled()) {
            return;
        }
        ob_start();
    }

    /**
     * @param Profile $profile
     */
    public function leave(Profile $profile)
    {
        if (!$this->isEnabled()) {
            return;
        }

        $content = ob_get_clean();

        $isHtml = $this->isHtml($content);

        if ($isHtml) {
            if (strpos($content, $this->currentTemplate) === false) {
                $this->nestingLevel = 0;
                $this->boxDrawingsIndex = 0;
                $content = $this->startComment($profile) . $content . $this->endComment($profile);
            } else {
                if (trim($content) !== trim($this->currentTemplate)) {
                    $this->incrementBoxDrawingsIndex();
                }
                $this->nestingLevel++;
                $content = $this->startComment($profile) . $content . $this->endComment($profile);
            }

            $this->currentTemplate = $content;

        }
        echo $content;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'metadata_render_profiler';
    }

    /**
     * @param string $string
     * @return bool
     */
    protected function isHtml(string $string): bool
    {
        // Check if string contains any html tags and doesn't contain backbone tags
        return preg_match('/<\s?[^\>]*\/?\s?>/i', $string) && false === strpos($string, "<%");
    }

    /**
     * @param Profile $profile
     * @return string
     */
    private function startComment(Profile $profile): string
    {
        $delimiter = $this->getBoxDrawing()[0];
        $delimiter .= str_repeat($this->getBoxDrawing()[1], $this->nestingLevel);

        return $this->getComment($delimiter, $profile);

    }

    /**
     * @param Profile $profile
     * @return string
     */
    private function endComment(Profile $profile): string
    {
        $delimiter = $this->getBoxDrawing()[2];
        $delimiter .= str_repeat($this->getBoxDrawing()[1], $this->nestingLevel);

        return $this->getComment($delimiter, $profile);
    }

    public function isEnabled()
    {
        return true;
        $request = $this->requestStack->getCurrentRequest();

        return $request ? $request->cookies->getBoolean(self::ENABLE_FLAG_COOKIE_ID) : false;
    }

    /**
     * @param string $delimiter
     * @param string $template
     * @param string $name
     * @return string
     */
    protected function getComment(string $delimiter, Profile $profile): string
    {
        if ($profile->isTemplate()) {
            return '<!-- ' . $delimiter . ' ' . $profile->getName() . ' -->';
        }

        return '<!-- ' . $delimiter . ' ' . $profile->getName() . ' [' . $profile->getTemplate() . '] -->';
    }


    /**
     *
     * box drawing options
     *
     */

    const BOX_DRAWINGS = [
        ['┏', '━', '┗'],
        ['╭', '─', '╰'],
        ['╔', '═', '╚'],
        ['┎', '─', '┖'],
    ];

    /** @var int */
    private $boxDrawingsIndex = 0;

    private function getBoxDrawing(): array
    {
        return self::BOX_DRAWINGS[$this->boxDrawingsIndex];
    }

    private function incrementBoxDrawingsIndex()
    {
        $this->boxDrawingsIndex++;
        if (count(self::BOX_DRAWINGS) - 1 === $this->boxDrawingsIndex) {
            $this->boxDrawingsIndex = 0;
        }
    }
}
