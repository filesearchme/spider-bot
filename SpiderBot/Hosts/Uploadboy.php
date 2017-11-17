<?php
namespace SpiderBot\Hosts;
use Sunra\PhpSimple\HtmlDomParser;
use \SpiderBot\Base;
/**
 *
 */
class Uploadboy extends Base
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

        if( !strlen($data) || strpos( $data, 'could not be found') !== false )
        {
            return ['error' => true];
        }

        /**
         * Load data into html dom parser.
         */
        $dom = HtmlDomParser::str_get_html( $data );

        /**
         * Grab file size.
         */
        $size = $dom->find('.xs-block h4 span',0)->plaintext;
        $return['size'] = trim(str_replace(['(',')'],'',$size));

        /**
         * Grab filename.
         */
        $return['filename'] = trim(str_replace($size,'',$dom->find('.xs-block h4',0)->plaintext));

        /**
         * Return results.
         */
        return $return;
    }
}