<?php
namespace SpiderBot\Api;
use \GuzzleHttp\Client;
/**
 *
 */
class Spider extends Work
{
    public function spider($job,$hosts)
    {
        $data = ['spider'=>[],'parse'=>[]];
        /**
         * Parse website URL to find base domain.
         */
        $pu = parse_url( $job->url );
        $pu = explode('.',$pu['host']);
        $domain = $pu[count($pu)-2] . "." . $pu[count($pu)-1];

        /**
         * Grab source code from website.
         */
        $client = new Client(['http_errors' => false]);
        $res = $client->request('GET', $job->url);

        /**
         * Check for valid 200 response.
         */
        if( $res->getStatusCode() == 200 )
        {
            $page_title = $this->parseTitle($res->getBody());
            $page_description = $this->parseDescription($res->getBody());
            /**
             * Find all URLs on page using regex.
             */
            preg_match_all($this->regex, $res->getBody(), $matches, PREG_SET_ORDER, 0);
            /**
             * Remove any duplicate URLs.
             */
            $matches = array_unique($matches, SORT_REGULAR);
            /**
             * Filter each URL.
             */
            foreach( $matches AS $m )
            {
                $url_ = $m[0];

                /**
                 * don't reprocess current URL.
                 */
                if( $url_ == $job->url ) continue;

                if( $this->contains( $url_, $this->filters ) ) continue;

                /**
                 * Parse URL to find base domain.
                 */
                $pu_ = parse_url( $url_ );
                $pu_ = explode('.',$pu_['host']);
                $domain_ = $pu_[count($pu_)-2] . "." . $pu_[count($pu_)-1];

                /**
                 * Compare website domain to URL domain. If same domain add to spider queue array.
                 */
                if( $domain == $domain_ )
                {
                    if( $job->depth >= $job->website->depth ) continue;
                    $data['spider'][] = [
                        'depth' => ($job->depth+1),
                        'url' => $url_
                    ];
                }
                else
                {
                    /**
                     * Remote URL found, is it a download link?
                     */
                    foreach( $hosts AS $host )
                    {
                        foreach( $host->hostnames AS $hostname )
                        {
                            if( $domain_ == $hostname )
                            {
                                $data['parse'][] = [
                                    'host' => $host->id,
                                    'url' => $url_,
                                    'page_title' => $page_title,
                                    'page_description' => $page_description,
                                    'page_url' => $job->url
                                ];
                            }
                        }
                    }
                }
            }
        }

        return $data;
    }
}