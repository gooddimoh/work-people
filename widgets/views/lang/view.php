<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>
<? /*
<ul class="navbar-laguages mb-0">
    <?php foreach ($langs as $lang):?>
        <li>
            <?= Html::a(
                '<div class="flag flags-'. $lang['flag'] .'" title="'. $lang['label'] .'"></div> ',
                Url::to('/'.$lang['url'], true))
            ?>
        </li>
    <?php endforeach;?>
</ul>
*/ ?>

<select name="" class="change-translation-widget j-select header__select select">
    <?php foreach ($langs as $lang):?>
        <option value="<?= '/' . $lang['url'] ?>" <?= $current['url'] != $lang['url'] ?: 'selected' ?> ><?= $lang['label'] ?></option>
            <?
            /* Html::a(
                '<div class="flag flags-'. $lang['flag'] .'" title="'. $lang['label'] .'"></div> ',
                Url::to('/'.$lang['url'], true))
            */
            ?>
    <?php endforeach;?>
</select>

<?php $this->beginJs(); ?>
<script>
    $(document).ready(function() {
        $( ".change-translation-widget" ).change(function() {
            document.location.href = $( this ).val();
        });
    });
</script>
<?php $this->endJs(); ?>
