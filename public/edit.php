<?php
session_start();
require_once('../lib/init.php');
$name = App::get_requeset_page_name();
$text = '';

if(Item::count(['conditions' => ['name = ?', $name]]) > 0) {
    $item = Item::find_by_name($name);
    $text = $item->text;
}
if(isset($item)) {
    LockManager::lock_update($item->id, session_id());
}

?>
<!DOCTYPE html>
<html>
<head>
<?php include('../templates/html_header.php'); ?>
</head>
<body>

<?php include('../templates/sidebar.php'); ?>

<div id="main">

    <form id="submit_data" action="./save.php?n=<?php echo $name; ?>" method="post">
    <div class="uk-navbar-container" uk-navbar>
        <div class="uk-navbar-left">
            <div class="uk-navbar-item">
                <?php if(isset($item) && LockManager::is_editable($item->id, session_id())) { ?> 
                <span>他の人が編集しているため保存できません</span>
                <?php }else{ ?>
                <button class="uk-button uk-button-primary" id="save" type="submit">保存</button>
                <?php } ?>
            </div>
        </div>
        <div class="uk-navbar-right">
            <div class="uk-navbar-item">
                <button class="uk-button uk-button-default" id="no_save" name="no_save" value="1" type="submit">編集終了</button>
            </div>
        </div>
    </div>
    
    
    <h1 class="title">[<?php echo $name; ?>]を編集中</h1>
    
    <!-- ckeditor -->
    <div id="toolbar-container"></div>
    <div id="editor"><?php echo $text; ?></div>
    <!-- /ckeditor -->
    
    <input type="hidden" id="text" name="text">
    </form>
</div>

<script src="ckeditor/ckeditor.js"></script>
<script src="ckeditor/translations/ja.js"></script>

<script>
let base_url =  location.href.split("/").slice(0,-1).join("/");
let ed;
let before_text = document.getElementById('editor').innerHTML;
DecoupledEditor
    .create( document.querySelector( '#editor' ), {
        cloudServices: {
            tokenUrl:  base_url + '/token.php',
            uploadUrl: base_url + '/upload.php'
        },
        language: 'ja'
    } )
    .then( editor => {
        const toolbarContainer = document.querySelector( '#toolbar-container' );

        toolbarContainer.appendChild( editor.ui.view.toolbar.element );
        ed = editor;
        editor.model.document.on('change:data', () => {
            if(editor.getData() != before_text) {
                document.getElementById('no_save').className = 'uk-button uk-button-danger';
            }
        });
    } )
    .catch( error => {
        console.error( error );
    } );

    document.querySelector('#save').addEventListener('click', () => {
        const body = ed.getData();
        document.getElementById('text').value = body;
        document.getElementById('submit_data').submit();
    });

    document.getElementById('no_save').addEventListener('click', (e) => {
        if(ed.getData() != before_text) {
            let close_ok = confirm('編集中のデータは失われますがよろしいですか？');
            if(!close_ok) {
                e.preventDefault();
            }
        }
    });

</script>
</body>
</html>
