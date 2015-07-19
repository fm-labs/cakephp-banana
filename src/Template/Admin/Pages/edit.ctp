<?php
$this->extend('/Admin/Content/edit');

// EXTEND: TOOLBAR
$toolbarMenu = [
    'delete' => [
        'title' => __('Delete'),
        'url' => ['action' => 'delete', $content->id],
        'attr' => ['icon' => 'remove', 'confirm' => __('Are you sure you want to delete # {0}?', $content->id)],
        '_children' => [],
    ],
    'list' => [
        'title' => __('List {0}', __('Pages')),
        'url' => ['action' => 'index'],
        'attr' => ['icon' => 'list']
    ],
    'add' => [
        'title' => __('Add'),
        'url' => false,
        'attr' => [],

        '_children' => [
            [
                'title' => __('New {0}', __('Content Module')),
                'url' => ['action' => 'add_module'],
                'attr' => ['icon' => 'add']
            ]
        ]

    ]
];
$this->set('toolbarMenu', $toolbarMenu);

// EXTEND: HEADING
$this->assign('heading', __('Edit {0}', __('Page')));
?>
<div class="pages">
    <?= $this->Form->create($content); ?>
    <div class="ui grid">
        <div class="twelve wide column">
            <div class="ui form">
                <?php
                echo $this->Form->input('title');
                echo $this->Form->input('slug');
                echo $this->Form->input('type');
                echo $this->Form->input('redirect_status');
                echo $this->Form->input('redirect_location');
                ?>
            </div>
            <div class="ui divider"></div>

            <h4 class="ui header">Contents</h4>
            <?php echo $this->element(
                'Banana.Admin/Content/list_content_modules_editable',
                ['contentModules' => $content->content_modules]);
            ?>
        </div>
        <div class="four wide column">
            <div class="ui attached basic right aligned segment form">
                <?= $this->Form->button(__('Save Changes'), ['class' => 'ui positive fluid button']) ?>
            </div>
            <h5 class="ui attached header">Publish</h5>
            <div class="ui attached secondary segment form">
                <?php
                echo $this->Form->input('is_published');
                echo $this->Form->input('publish_start_date');
                echo $this->Form->input('publish_end_date');
                ?>
            </div>
            <h5 class="ui attached header">Layout</h5>
            <div class="ui attached secondary segment form">
                <?php
                echo $this->Form->input('parent_id', ['options' => $treeList, 'empty' => 'Website Root']);
                echo $this->Form->input('layout_template', ['options' => $layoutsAvailable]);
                echo $this->Form->input('page_template');
                ?>
            </div>
        </div>
    </div>
    <?= $this->Form->end() ?>
</div>