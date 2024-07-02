<?php foreach ($posts as $post): ?>
<article>
    <h3>
        <a href="<?= $post->url ?>"><?= $post->post_title ?></a>
    </h3>
    <p>
        <?= $post->post_modified->toFormattedDateString()?> in
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
