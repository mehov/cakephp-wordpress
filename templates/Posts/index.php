<?php
if (isset($title)) {
    $this->assign('title', $title);
}
if (isset($description)) {
    $this->Html->meta('description', $description, ['block' => true]);
}
?>
<div itemscope itemtype="http://schema.org/Blog">
<?php foreach ($posts as $post): ?>
    <article itemprop="blogPost" itemscope itemtype="http://schema.org/BlogPosting">
        <h3 itemprop="name headline">
            <a href="<?= $post->url ?>" itemprop="url"><?= $post->post_title ?></a>
        </h3>
        <p>
            <time itemprop="dateModified" datetime="<?= $post->post_modified->toIso8601String()?>"><?= $post->post_modified->toFormattedDateString()?></time> in
<?php foreach ($post->categories as $category): ?>
            <a href="<?= $category->url?>"><b><?= $category->term->name?></b></a>
<?php endforeach; ?>
<?php foreach($post->post_tags as $post_tag): ?>
            <a href="<?= $post_tag->url?>">#<?= $post_tag->term->name?></a>
<?php endforeach; ?>
        </p>
        <p><?= $post->post_excerpt?></p>
    </article>
    <hr>
<?php endforeach; ?>
</div>
<hr>
<div class="row">
    <p class="column">
        <?= $this->Paginator->prev('Previous') ?>
        <?= $this->Paginator->counter() ?>
        <?= $this->Paginator->next('Next') ?>
    </p>
    <p class="column">
        Pages: <?= $this->Paginator->numbers() ?>
    </p>
</div>
