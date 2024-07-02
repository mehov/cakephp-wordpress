<?php
$this->assign('title', $post->post_title);
$this->Html->meta('description', $post->post_excerpt, ['block' => true]);
$this->Html->meta(
    'keywords',
    implode(',', \Cake\Utility\Hash::extract($post->post_tags, '{n}.term.name')),
    ['block' => true]
);
?>
<article itemscope itemtype="https://schema.org/Article" itemid="<?= $post->guid?>">
    <h1 itemprop="name headline"><?= $post->post_title?></h1>
    <p>
        <a href="<?= $post->url?>" itemprop="url"><time itemprop="dateModified" datetime="<?= $post->post_modified->toIso8601String()?>"><?= $post->post_modified->toFormattedDateString()?></time></a>
        in
<?php foreach ($post->categories as $category): ?>
        <a href="<?= $category->url?>"><b><?= $category->term->name?></b></a>
<?php endforeach; ?>
    </p>
    <hr>
    <div itemprop="articleBody">
<?= $post->post_content?>
    </div>
    <div>
<?php foreach($post->post_tags as $post_tag): ?>
        <a href="<?= $post_tag->url?>" class="button button-outline">
            #<span itemprop="keywords"><?= $post_tag->term->name?></span>
        </a>
<?php endforeach; ?>
    </div>
</article>
