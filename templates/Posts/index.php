<?php foreach ($posts as $post): ?>
<article>
    <h2>
        <a href="<?= $post->url ?>"><?= $post->post_title ?></a>
    </h2>
    <b><?= $post->ID ?></b> <b><?= $post->url ?></b>
</article>
<?php endforeach; ?>
