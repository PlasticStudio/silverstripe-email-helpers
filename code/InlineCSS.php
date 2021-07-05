<?php

namespace MarkGuinn\EmailHelpers;

use Pelago\Emogrifier\CssInliner;
use SilverStripe\Control\Director;
use Pelago\Emogrifier\HtmlProcessor\HtmlPruner;
use Pelago\Emogrifier\HtmlProcessor\CssToAttributeConverter;

/**
 * Inline CSS
 */
class InlineCSS
{
    /**
     * Inline both the embedded css, and css from an external file, into html
     *
     * @param  HTML $htmlContent
     * @param string $cssFile path and filename
     * @return HTML with inlined CSS
     */
    public static function convert($html, $cssfile)
    {
        // $emog = new Emogrifier($htmlContent);
        

        // Apply the css file to Emogrifier
        if ($cssfile) {
            $cssFileLocation = join(DIRECTORY_SEPARATOR, array(Director::baseFolder(), $cssfile));
            $cssFileHandler = fopen($cssFileLocation, 'r');
            $css = fread($cssFileHandler, filesize($cssFileLocation));
            fclose($cssFileHandler);
            // $emog->setCss($css);

            $domDocument = CssInliner::fromHtml($html)->inlineCss($css)->getDomDocument();
            HtmlPruner::fromDomDocument($domDocument)->removeElementsWithDisplayNone();
            $html = CssToAttributeConverter::fromDomDocument($domDocument)
                ->convertCssToVisualAttributes()->render();
        }
        else {
            $html = CssInliner::fromHtml($html)->inlineCss()->render();
        }

        return $html;
        // return $emog->emogrify();
    }
}
