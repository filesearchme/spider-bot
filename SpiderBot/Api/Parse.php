<?php
namespace SpiderBot\Api;
/**
 *
 */
class Parse extends Work
{
    public function parse($job)
    {
        switch ($job->host_id) {
            case 1:
                /**
                 * Parse Suprafiles URL and return filename/size.
                 */
                $return = \SpiderBot\Hosts\Suprafiles::parse($job);
                break;
            case 2:
                /**
                 * Parse Cloudyfiles URL and return filename/size.
                 */
                $return = \SpiderBot\Hosts\Cloudyfiles::parse($job);
                break;
            case 3:
                /**
                 * Parse Dopefile URL and return filename/size.
                 */
                $return = \SpiderBot\Hosts\Dopefile::parse($job);
                break;
            case 4:
                /**
                 * Parse Zippyshare URL and return filename/size.
                 */
                $return = \SpiderBot\Hosts\Zippyshare::parse($job);
                break;
            case 5:
                /**
                 * Parse Dbree URL and return filename/size.
                 */
                $return = \SpiderBot\Hosts\Dbree::parse($job);
                break;
            case 6:
                /**
                 * Parse Tusfiles URL and return filename/size.
                 */
                $return = \SpiderBot\Hosts\Tusfiles::parse($job);
                break;
            case 7:
                /**
                 * Parse Uploadboy URL and return filename/size.
                 */
                $return = \SpiderBot\Hosts\Uploadboy::parse($job);
                break;
            case 8:
                /**
                 * Parse Rapidgator URL and return filename/size.
                 */
                $return = \SpiderBot\Hosts\Rapidgator::parse($job);
                break;
            case 9:
                /**
                 * Parse Turbobit URL and return filename/size.
                 */
                $return = \SpiderBot\Hosts\Turbobit::parse($job);
                break;
            case 10:
                /**
                 * Parse Mediafire URL and return filename/size.
                 */
                $return = \SpiderBot\Hosts\Mediafire::parse($job);
                break;
        }
        /**
         * Handle error response.
         */
        if( isset( $return['error'] ) ) return $return;
        /**
         * Convert size to kilobytes.
         */
        $return['size'] = $this->convert_size( trim( $return['size'] ) );
        /**
         * Extract extension from filename.
         */
        $return['extension'] = pathinfo($return['filename'], PATHINFO_EXTENSION);
        /**
         * Return result
         */
        return $return;
    }
}