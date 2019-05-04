<?php
use yii\helpers\Url;

$item_url = Url::toRoute( ["item"] );
?>
<nav id="sidebar">
    <?php echo Yii::$app->controller->renderPartial( "/default/sidebar" ); ?>
</nav>

<div class="content with-sidebar">
    <h1>
        <?php echo Yii::t( "app", "Practices" ); ?>
    </h1>

    <table class="table">
        <thead class="thead-dark">
            <tr>
                <th scope="col">
                    <?php echo Yii::t( "app", "Nr" ); ?>
                </th>
                <th scope="col">
                    <?php echo Yii::t( "app", "Name" ); ?>
                </th>
                <th scope="col">
                    <?php echo Yii::t( "app", "Is_enabled" ); ?>
                </th>
                <th scope="col">
                    <?php echo Yii::t( "app", "Actions" ); ?>
                </th>
            </tr>
        </thead>
        <tbody>
        <?php
        if( ! empty( $templates ) )
        {
            foreach( $templates as $index => $template )
            {
                ?>
                <tr>
                    <th scope="row">
                        <?php echo $index + 1; ?>
                    </th>
                    <td>
                        <?php echo $template->name; ?>
                    </td>
                    <td>
                        <?php echo $template->is_enabled; ?>
                    </td>
                    <td>
                        <a href="<?php echo $item_url . "?item_id=" . $template->id; ?>" class="btn btn-default">
                            <?php echo Yii::t( "app", "Modify" ); ?>
                        </a>
                    </td>
                </tr>
                <?php
            }
        }
        ?>
        </tbody>
    </table>

    <a href="<?php echo $item_url; ?>" class="btn btn-primary">
        <?php echo Yii::t( "app", "Add_new" ); ?>
    </a>
</div>