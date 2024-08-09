<?php
$this->assign('title', $post->post_title);
$this->Html->meta('description', $post->post_excerpt, ['block' => true]);
?>
<article itemscope itemtype="https://schema.org/Article" itemid="<?= $post->guid?>">
    <h1 itemprop="name headline"><?= $post->post_title?></h1>
    <hr>
    <div itemprop="articleBody">
<?= $post->the_content()?>
    </div>
</article>
