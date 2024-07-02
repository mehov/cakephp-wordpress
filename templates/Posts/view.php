<h1><?= $post->post_title?></h1>
<p>
    <a href="<?= $post->url?>"><?= $post->post_modified->toFormattedDateString()?></a>
    in
<?php foreach ($post->categories as $category): ?>
    <a href="<?= $category->url?>"><b><?= $category->term->name?></b></a>
<?php endforeach; ?>
</p>
<hr>
<div>
<?= $post->post_content?>
</div>
<div>
<?php foreach($post->post_tags as $post_tag): ?>
    <a href="<?= $post_tag->url?>" class="button button-outline">#<?= $post_tag->term->name?></a>
<?php endforeach; ?>
</div>
