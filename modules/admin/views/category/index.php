<?php
	use nirvana\showloading\ShowLoadingAsset;
	
	$this->registerCssFile('/js/libs/jstree/themes/default/style.min.css');
	$this->registerJsFile('/js/libs/jstree/jstree.js');
	
    ShowLoadingAsset::register($this);

$this->title = 'Категории';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $this->beginJs(); ?>
<script>
    $(function () {

        var categories = <?= $categories ?>;
        
        var el_tree = $('#html1');
        var searchTimeout = null;

        function processChangesInTree(url, node, fail_message) {
            el_tree.parent().css({'min-height': el_tree.height() + 'px'});
            $.ajax({
                url       : url,
                method    : 'post',
                dataType  : 'json',
                data      : node,
                beforeSend: function () {
                    el_tree.showLoading();
                },
                success   : function (data) {
                    if (!data.success) {
                        alert(data['response']);
                    } else {
                        if (data.data.id) {
                            el_tree.jstree(true).set_id(node.node, data.data.id);
                        }
                        categories = el_tree.jstree(true).get_json('#', {include_original: true});
                    }
                    
                    init_jstree(el_tree, categories, data["success"] ? false : true);
                    el_tree.hideLoading();
                },
                error     : function () {
                    alert(fail_message)
                    init_jstree(el_tree, categories, true);
                    el_tree.hideLoading();
                }
            });
        }

        $('#node_search').keydown(function () {
            if (searchTimeout) {
                clearTimeout(searchTimeout);
            }
            searchTimeout = setTimeout(function () {
                var v = $('#node_search').val();
                el_tree.jstree(true).search(v);
            }, 250);
        });


        // handle click on node create button
        $('#node_create').click(function (e) {
            e.defaultPrevented;
            var ref = el_tree.jstree(true);
            var sel = ref.get_selected();

            if (!sel.length) {
                return false;
            }

            sel = sel[0];
            sel = ref.create_node(sel, {'type': 'category'});

            if (sel) {
                ref.edit(sel);
            }
        });

        // handle click on node rename button
        $('#node_rename').click(function (e) {
            e.defaultPrevented;
            var ref = el_tree.jstree(true);
            var sel = ref.get_selected();
            if (!sel.length) {
                return false;
            }
            
            sel = sel[0];
            ref.edit(sel);
        });

        // handle click on node delete button
        $('#node_delete').click(function (e) {
            e.defaultPrevented;
            var ref = el_tree.jstree(true);
            var sel = ref.get_selected();
            if (!sel.length) {
                return false;
            }
            ref.delete_node(sel);
        });

         // handle click on node edit button
         $('#node_options').click(function (e) {
            e.defaultPrevented;
            var ref = el_tree.jstree(true);
            var sel = ref.get_selected();
            if (!sel.length) {
                return false;
            }
            
            sel = sel[0];

            window.location = '/admin/category/edit/' + sel
        });

         // handle click on node view button
         $('#node_view').click(function (e) {
            e.defaultPrevented;
            var ref = el_tree.jstree(true);
            var sel = ref.get_selected();
            if (!sel.length) {
                return false;
            }
            
            sel = sel[0];

            window.location = '/jobs?VacancySearch[category_list]=' + sel
        });

        /**
         * Js tree initialization.
         * @param el        element, which'll contain tree
         * @param data      categories (json)
         * @param refresh
         */
        function init_jstree(el, data, refresh) {
            // destroy old jstree
            el.jstree('destroy');

            // init jstree
            el.jstree({
                'core'   : {
                    'check_callback': true,
                    'data'          : data
                },
                'rules'  : {'draggable': 'all'},
                'plugins': ['contextmenu', 'dnd', 'search', 'state', 'types', 'wholerow'],
                'types'  : {
                    'category': {},
                    'file'    : {'valid_children': [], 'icon': 'file'}
                }
            });

            // handle node removing
            el_tree.on('delete_node.jstree', function (e, obj) {
                processChangesInTree('/admin/category/delete', {node: obj.node}, 'Не удалось удалить категорию.\nПроверьте соединение с интернетом и повторите попытку.');
            })

            // handle node renaming
            el_tree.on('rename_node.jstree', function (e, obj) {
                processChangesInTree('/admin/category/update', {node: obj.node}, 'Не удалось переименовать категорию.\nПроверьте соединение с интернетом и повторите попытку.');
            });

            // handle node cration
            el_tree.on('create_node.jstree', function (e, obj) {
                processChangesInTree('/admin/category/create', {node: obj.node}, 'Не удалось добавить категорию.\nПроверьте соединение с интернетом и повторите попытку.');
            });

            // handle node moving
            el_tree.on('move_node.jstree', function (e, obj) {

                var parent = el_tree.jstree(true).get_node(obj.parent);

                // inside as default
                var node = {
                    action: 'inside',
                    moving_id: obj.parent,
                    anchor_id: obj.node.id
                };

                // obj has been moved
                if (parent.children.length > 1) {
                    if (obj.position == parent.children.length - 1) {
                        // move after
                        node.action = "after";
                        node.moving_id = parent.children[obj.position - 1];
                    } else {
                        // move before
                        node.action = "before";
                        node.moving_id = parent.children[obj.position + 1];
                    }
                }

                processChangesInTree('/admin/category/move', {node: node}, 'Не удалось переместить категорию.\nПроверьте соединение с интернетом и повторите попытку');
            });

            // expand tree if jstree loaded
            el_tree.on('loaded.jstree', function () {
                el.jstree('open_all');
            });

            // expand tree if jstree has been refreshed
            el_tree.on('refresh.jstree', function () {
                el.jstree('open_all');
            });

            // do refresh or not
            if (refresh) {
                el.jstree('refresh');
            }
        };

        // Init jstree

        init_jstree(el_tree, categories, true);

    });
</script>
<?php $this->endJs(); ?>
    <div class="shop-main mgt40 mgb60 fullwidth" style="min-height: 320px;">
    	<div class="row">
			<div class="col-xs-5">
				<!-- Add node button -->
				<a id="node_create" href="#" class="btn btn-success btn-sm">
					<i class="fa fa-plus"></i> Добавить
				</a>

				<!-- Rename node button -->
				<a id="node_rename" href="#" class="btn btn-warning btn-sm">
					<i class="glyphicon glyphicon-pencil"></i> Переименовать
				</a>

				<!-- Remove node button -->
				<a id="node_delete" href="#" class="btn btn-danger btn-sm">
					<i class="fa fa-remove"></i> Удалить
				</a>

                <!-- edit node button -->
				<a id="node_options" href="#" class="btn btn-info btn-sm">
					<i class="glyphicon glyphicon-wrench"></i> Управление
				</a>

                <!-- edit node button -->
				<a id="node_view" href="#" class="btn btn-default btn-sm">
					<i class="glyphicon glyphicon-eye-open"></i> Просмотр
				</a>
			</div>

			<div class="col-xs-7">
				<!-- Search field -->
				<input id="node_search" class="form-control input-sm pull-left" type="text" placeholder="Поиск" value=""/>
			</div>
		</div>

		<div class="row">
			<div class="col-xs-12">
				<div id="html1">
					<!-- empty -->
				</div>
			</div>
			
		</div>
    </div>