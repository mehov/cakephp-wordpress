<?php
$this->assign('title', $post->post_title);
$this->Html->meta('description', $post->post_excerpt, ['block' => true]);
$externalCss = $blog->getExternalCss();
if ($externalCss && !empty($externalCss) && is_array($externalCss)) {
    foreach ($externalCss as $key => $url) {
        $id = sprintf('externalCss-%s', strval($key));
        $this->Html->css($url, ['block' => true, 'ext' => false, 'id' => $id]);
    }
    unset($externalCss, $key, $url, $id);
}
?>
<article itemscope itemtype="https://schema.org/Article" itemid="<?= $post->guid?>">
    <h1 itemprop="name headline"><?= $post->post_title?></h1>
    <hr>
    <div itemprop="articleBody">
<?= $post->the_content()?>
    </div>
</article>
