<?php
$this->loadHelper('Backend.Tabs');
$this->extend('/Admin/Content/edit');

// EXTEND: TOOLBAR
$this->Toolbar->addLink(
    __d('banana','Delete'),
    ['action' => 'delete', $content->id],
    ['icon' => 'trash', 'confirm' => __d('banana','Are you sure you want to delete # {0}?', $content->id)]
);
$this->Toolbar->addLink(__d('banana','Edit {0}', __d('banana','Page')), ['action' => 'edit', $content->id], ['icon' => 'edit']);
$this->Toolbar->addLink(__d('banana','Preview'), ['action' => 'preview', $content->id], ['icon' => 'eye', 'target' => '_preview']);
$this->Toolbar->addLink(__d('banana','List {0}', __d('banana','Pages')), ['action' => 'index'], ['icon' => 'list']);
$this->Toolbar->addLink(__d('banana','New {0}', __d('banana','Page')), ['action' => 'add'], ['icon' => 'add']);


// HEADING
$this->assign('heading', __d('banana','Page: {0}', $content->title));
$this->assign('title', sprintf('[%s] %s (#%s)', 'Pages', $content->title, $content->id));

// CONTENT
?>
<style>
    /*
    .related-posts .card {
        max-height: 300px;
        overflow-y: scroll;
    }
    */
</style>
<div class="pages">

    <div class="ui basic segment">
        Slug: <?= h($content->slug); ?><br />
        Type: <?= h($content->type); ?><br />
        Published: <?= $this->Ui->statusLabel($content->is_published); ?><br />
        <?= $this->Html->link(__('Edit {0}', __('Page')), ['action' => 'edit', $content->id]); ?>
    </div>
    <div class="ui divider"></div>

    <?php $this->Tabs->start(); ?>

    <?php $this->Tabs->add(__d('banana','Related Posts')); ?>

    <h3>Related Posts</h3>

    <div class="related-posts">
    <?php foreach($content->posts as $post): ?>

    <!-- -->
    <div class="ui fluid card">
        <div class="content">

            <?= $this->Html->link($post->title,
                ['controller' => 'Posts', 'action' => 'edit', $post->id],
                ['class' => 'left floated header']
            ); ?>

            <span class="right floated edit">
                <i class="edit icon"></i>
                <?= $this->Html->link(__('Edit'), ['controller' => 'Posts', 'action' => 'edit', $post->id]); ?>
            </span>

        </div>

        <div class="extra">
            <span class="ui left floated">
                <?= $this->Ui->statusLabel($post->is_published, ['label' => [__('Unpublished'), __('Published')]]); ?>
            </span>
            <!--
            <?php if ($post->is_published): ?>
                <span class="right floated">
                    <i class="hide icon"></i>
                    <?= $this->Html->link(__('Unpublish'), ['controller' => 'Posts', 'action' => 'publish', $post->id]); ?>
                </span>
            <?php else: ?>
                <span class="right floated">
                    <i class="green unhide icon"></i>
                    <?= $this->Html->link(__('Publish'), ['controller' => 'Posts', 'action' => 'unpublish', $post->id]); ?>
                </span>
            <?php endif; ?>
            -->
        </div>

        <div class="content">
            <h5 class="">Teaser</h5>
            <div class="description">
                <?= strip_tags($post->teaser_html); ?>
            </div>
        </div>

        <div class="content">
            <h5 class="">Content</h5>
            <div class="description">
                <?= strip_tags($post->body_html); ?>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    </div>
    <!--
    <table class="ui compact table">
        <thead>
        <tr>
            <th>Order</th>
            <th>Title</th>
            <th>Is Published</th>
            <th class="actions">Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($content->posts as $post): ?>
            <tr>
                <td><?= h($post->order); ?></td>
                <td><?= h($post->title); ?></td>
                <td><?= $this->Ui->statusLabel($post->is_published); ?></td>
                <td class="actions">
                    <?= $this->Ui->link('Edit',
                        ['controller' => 'Posts', 'action' => 'edit', $post->id],
                        ['class' => 'ui mini button', 'icon' => 'edit']
                    ); ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
     -->

    <div class="actions">
        <?= $this->Ui->link('Add Post',
            ['controller' => 'Posts', 'action' => 'add', 'refid' => $content->id, 'refscope' => 'Banana.Pages'],
            ['class' => 'ui button', 'icon' => 'add']
        ); ?>
    </div>


    <!-- RELATED CONTENT MODULES -->
    <?php $this->Tabs->add('Related Content Modules'); ?>
        <h3>Related content modules</h3>
        <?= $this->element('Banana.Admin/Content/related_content_modules', compact('content', 'sections')); ?>
        <br />
        <?= $this->Ui->link('Build a new module for this page', [
            'controller' => 'ModuleBuilder',
            'action' => 'build2',
            'refscope' => 'Banana.Pages',
            'refid' => $content->id
        ], ['class' => 'ui button', 'icon' => 'add']); ?>


    <?php $this->Tabs->add('Link existing module'); ?>
        <h3>Link existing module</h3>
        <div class="ui form">
            <?= $this->Form->create(null, ['url' => ['action' => 'linkModule', $content->id]]); ?>
            <?= $this->Form->input('refscope', ['default' => 'Banana.Pages']); ?>
            <?= $this->Form->input('refid', ['default' => $content->id]); ?>
            <?= $this->Form->input('module_id', ['options' => $availableModules]); ?>
            <?= $this->Form->input('section'); ?>
            <?= $this->Form->submit('Link module'); ?>
            <?= $this->Form->end(); ?>
        </div>

    <?php echo $this->Tabs->render(); ?>

</div>

<?php $this->append('scriptBottom'); ?>
    <script>
        /*
        $(document).ready(function() {

            //$('.content-modules .menu .item:first-child').addClass('active');
            //$('.content-modules .tab:first-child').addClass('active');
            $('.content-modules .menu .item').tab();
            $('.content-modules .menu .item:first-child').trigger('click');

            $('.select-type').hide();
            $('#select-type').change(function() {
                var type = $(this).val();
                $('.select-type').fadeOut();
                $('.select-type-' + type).fadeIn();
            }).trigger('change');
        })
        */
    </script>
<?php $this->end(); ?>