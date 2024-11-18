<?php
$this->assign('title', $post->post_title);
$this->Html->meta('description', $post->post_excerpt, ['block' => true]);
$externalCss = $blog->getConfig('externalCss');
if ($externalCss && !empty($externalCss) && is_array($externalCss)) {
    foreach (array_keys($externalCss) as $key) {
        $route = \Cake\Routing\Router::url([
            '_name' => 'Blog:ContentStylesheet',
            '_ext' => 'css',
            'symbol' => $blog->getSymbol(),
            'key' => $key,
        ]);
        $id = sprintf('externalCss-%s', strval($key));
        $this->Html->css($route, ['block' => true, 'ext' => false, 'id' => $id]);
    }
    unset($externalCss, $route, $key, $id);
}
?>
<article itemscope itemtype="https://schema.org/Article" itemid="<?= $post->guid?>">
    <h1 itemprop="name headline"><?= $post->post_title?></h1>
    <div itemprop="articleBody">
<?= $post->the_content()?>
    </div>
</article>
<style type="text/css">
body > main > article > h1 {
    display: none;
}
</style>