<?php
namespace CakePHPWordpress\Controller;

class SitemapsController extends AppController
{

    public function viewClasses(): array
    {
        return [\Cake\View\XmlView::class];
    }

    public function index()
    {
        $blog = new \CakePHPWordpress\Connector();
        $query = $blog->Posts->find('posts')->find('published')->limit(null);
        $urls = [];
        foreach ($query->all() as $post) {
            $urls[] = [
                'loc' => $post->url,
                'lastmod' => $post->post_modified_gmt->toDateString(),
                'changefreq' => 'weekly',
                'priority' => '0.5',
            ];
        }
        // Define a custom root node in the generated document.
        $this->viewBuilder()
            ->setOption('rootNode', 'urlset')
            ->setOption('serialize', ['@xmlns', 'url']);
        $this->set([
            // Define an attribute on the root node.
            '@xmlns' => 'http://www.sitemaps.org/schemas/sitemap/0.9',
            'url' => $urls,
        ]);
    }
}