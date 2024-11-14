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
if (is_array($post->post_tags)) {
    $this->Html->meta(
        'keywords',
        implode(',', \Cake\Utility\Hash::extract($post->post_tags, '{n}.term.name')),
        ['block' => true]
    );
}
?>
<article itemscope itemtype="https://schema.org/Article" itemid="<?= $post->guid?>">
    <h1 itemprop="name headline"><?= $post->post_title?></h1>
    <p>
        <a href="<?= $post->url?>" itemprop="url"><time itemprop="dateModified" datetime="<?= $post->post_modified->toIso8601String()?>"><?= $post->post_modified->toFormattedDateString()?></time></a>
<?php if (is_array($post->categories)): ?>
        in
<?php foreach ($post->categories as $category): ?>
        <a href="<?= $category->url?>"><b><?= $category->term->name?></b></a>
<?php endforeach; ?>
<?php endif; ?>
    </p>
    <hr>
    <div itemprop="articleBody">
<?= $post->the_content()?>
    </div>
<?php if (is_array($post->post_tags)): ?>
    <div>
<?php foreach($post->post_tags as $post_tag): ?>
        <a href="<?= $post_tag->url?>" class="button button-outline">
            #<span itemprop="keywords"><?= $post_tag->term->name?></span>
        </a>
<?php endforeach; ?>
    </div>
<?php endif; ?>
</article>
