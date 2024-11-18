<?php
namespace CakePHPWordpress\Controller;

/**
 * Class StylesheetsController
 *
 * Fetches and caches external CSS files
 *
 * @package CakePHPWordpress\Controller
 */
class StylesheetsController extends AppController
{

    /**
     * Finds external CSS file URL in config by blog symbol and externalCss key,
     * then fetches and caches it locally
     *
     * @param $blogSymbol blog symbol, as defined in config/CakePHPWordpress.php
     * @param $key externalCss config is an array, this should be a valid key
     * @return string contents of external CSS file
     */
    public function output($blogSymbol, $key)
    {
        // Get blog by received blog symbol
        $blog = new \CakePHPWordpress\Connector($blogSymbol);
        // Get external CSS configuration for that blog
        $externalCss = $blog->getConfig('externalCss');
        if (!$externalCss || empty($externalCss) || !is_array($externalCss)) {
            throw new \Cake\Http\Exception\InternalErrorException('No stylesheets defined');
        }
        if (!isset($externalCss[$key])) {
            throw new \Cake\Http\Exception\NotFoundException(sprintf('Key %s was not defined', $key));
        }
        // Prepare local cache key for this blog symbol and file key
        $cacheKey = sprintf('%s.externalCss.%s.%s', $this->getPlugin(), $blogSymbol, $key);
        // Try reading the cache
        $contents = \Cake\Cache\Cache::read($cacheKey);
        // If nothing has been cached yet, fetch contents and save to cache
        if ($contents === null) {
            $url = $externalCss[$key];
            $contents = self::fetch($url);
            \Cake\Cache\Cache::write($cacheKey, $contents);
        }
        // Respond as CSS
        $this->response = $this->response->withType('css');
        $this->response = $this->response->withStringBody($contents);
        return $this->response;
    }

    /**
     * Fetch contents for given URL; follow redirects; throw exception on fail
     *
     * @param string $url
     * @return string response body
     */
    private static function fetch($url)
    {
        // If the URL looks like `//example.com/path`
        if (substr($url, 0, 2) === '//') {
            $url = 'http:'.$url;
        }
        $http = new \Cake\Http\Client();
        $response = $http->get($url);
        if ($response->isRedirect()) {
            $location = $response->getHeaderLine('Location');
            // Prevent infinite loop if redirect URL same as URL already tried
            if ($url === $location) {
                throw new \Cake\Http\Exception\InternalErrorException('Ran into redirect loop while trying to fetch the stylesheet');
            }
            // Follow the redirect
            return self::fetch($location);
        }
        // If not a 2XX response, throw exception
        if (!$response->isSuccess()) {
            throw new \Cake\Http\Exception\InternalErrorException('Could not fetch the stylesheet, got '.$response->getStatusCode());
        }
        return $response->getStringBody();
    }
}