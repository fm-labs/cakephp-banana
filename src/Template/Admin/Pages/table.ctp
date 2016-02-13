<?php $this->Html->addCrumb(__d('banana','Pages')); ?>
<?php $this->extend('/Admin/Content/index'); ?>
<?php
// TOOLBAR
$this->Toolbar->addLink(__d('banana','New {0}', __d('banana','Page')), ['action' => 'add'], ['icon' => 'add']);
$this->Toolbar->addLink(__d('banana','Repair'), ['action' => 'repair'], ['icon' => 'configure']);

// HEADING
$this->assign('heading', __d('banana','Pages'));

// CONTENT
?>
<div class="pages index">

    <!-- Quick Search -->
    <div class="ui segment">
        <div class="ui form">
            <?= $this->Form->create(null, ['id' => 'quickfinder', 'action' => 'quick']); ?>
            <?= $this->Form->input('page_id', [
                'options' => $pagesTree,
                'label' => false,
                'empty' => '- Quick Search -'
            ]); ?>
            <?= $this->Form->button('Go'); ?>
            <?= $this->Form->end() ?>
        </div>
    </div>

    <table class="ui sortable compact table" data-sort-url="<?= $this->Url->build(['action' => 'tree_sort']) ?>">
        <thead>
        <tr>
            <th><?= h('id') ?></th>
            <th><?= h('title') ?></th>
            <th><?= h('type') ?></th>
            <th><?= h('is_published') ?></th>
            <th class="actions"><?= __d('banana','Actions') ?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($contents as $content): ?>
            <tr data-id="<?= h($content->id) ?>">
                <td><?= h($content->id); ?></td>
                <td><?= $this->Html->link(
                        $pagesTree[$content->id],
                        ['action' => 'view', $content->id],
                        ['title' => $this->Url->build($content->url)]);
                    ?></td>
                <td><?= h($content->type); ?></td>
                <td><?= $this->Ui->statusLabel($content->is_published) ?></td>
                <td class="actions">
                    <div class="ui basic mini buttons">

                        <div class="ui button">
                            <?= $this->Html->link(__d('banana','View'), ['action' => 'view', $content->id]) ?>
                        </div>
                        <div class="ui floating dropdown icon button">
                            <i class="dropdown icon"></i>
                            <div class="menu">
                                <?= $this->Ui->link(
                                    __d('banana','Edit'),
                                    ['action' => 'preview', $content->id],
                                    ['class' => 'item', 'icon' => 'edit']
                                ) ?>
                                <?= $this->Ui->link(
                                    __d('banana','Preview'),
                                    ['action' => 'preview', $content->id],
                                    ['class' => 'item', 'icon' => 'eye', 'target' => 'preview']
                                ) ?>
                                <?= $this->Ui->link(
                                    __d('banana','Copy'),
                                    ['action' => 'duplicate', $content->id],
                                    ['class' => 'item', 'icon' => 'edit']
                                ) ?>
                                <?= $this->Ui->link(
                                    __d('banana','Move Up'),
                                    ['action' => 'moveUp', $content->id],
                                    ['class' => 'item', 'icon' => 'arrow up']
                                ) ?>
                                <?= $this->Ui->link(
                                    __d('banana','Move Down'),
                                    ['action' => 'moveDown', $content->id],
                                    ['class' => 'item', 'icon' => 'arrow down']
                                ) ?>
                                <?= $this->Ui->deleteLink(
                                    __d('banana','Delete'),
                                    ['action' => 'delete', $content->id],
                                    ['class' => 'item', 'icon' => 'trash', 'confirm' => __d('banana','Are you sure you want to delete # {0}?', $content->id)]
                                ) ?>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>