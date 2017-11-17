<?php
namespace SpiderBot\Hosts;
use Sunra\PhpSimple\HtmlDomParser;
use \SpiderBot\Base;
/**
 *
 */
class Mediafire extends Base
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

        if( !strlen($data) || strpos( $data, 'Invalid or Deleted File') !== false )
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
        $return['filename'] = trim($dom->find('meta[NAME=description]',0)->attr['content']);

        /**
         * Grab file size.
         */
        $return['size'] = trim($dom->find('.dlInfo-Details li span',0)->plaintext);

        /**
         * Return results.
         */
        return $return;
    }
}