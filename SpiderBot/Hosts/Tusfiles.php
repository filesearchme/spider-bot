<?php
namespace SpiderBot\Hosts;
use Sunra\PhpSimple\HtmlDomParser;
use \SpiderBot\Base;
/**
 *
 */
class Tusfiles extends Base
{
    /**
     * The curl command timeout in seconds.
     *
     * @var integer
     */
    const timeout = 5;

    public function parse($job)
    {
        /**
         * Fetch source from URL.
         */
        $data = $this->fetch( $job->url );

        if( !strlen($data) || strpos( $data, 'File not found') !== false )
        {
            return ['error' => true];
        }

        /**
         * Load data into html dom parser.
         */
        $dom = HtmlDomParser::str_get_html( $data );

        /**
         * Grab filename.
         */
        $return['filename'] = trim($dom->find('.p-a-xs img',0)->attr['alt']);

        /**
         * Grab file size.
         */
        $return['size'] = trim(str_replace(['(',')'],'',$dom->find('span.l-h-1x .text-xs small b',0)->plaintext));

        /**
         * Return results.
         */
        return $return;
    }
}