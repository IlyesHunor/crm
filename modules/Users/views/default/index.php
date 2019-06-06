<nav id="sidebar">
    <?php echo Yii::$app->controller->renderPartial( "/default/sidebar" ); ?>
</nav>

<div class="content with-sidebar">
    <h3>
        <?php echo Yii::t( "app", "Users" ); ?>
    </h3>

    <div id="tabs">
        <ul>
            <?php
            if( ! empty( $user_types ) )
            {
                foreach( $user_types as $user_type )
                {
                ?>
                    <li>
                        <a href="#tab-<?php echo $user_type->id ?>">
                            <?php echo Yii::t( "app", $user_type->name ); ?>
                        </a>
                    </li>
                <?php
                }
            }
            ?>
        </ul>
        <?php
        if( ! empty( $user_types ) )
        {
            foreach( $user_types as $user_type )
            {
            ?>
                <div id="tab-<?php echo $user_type->id; ?>">
                    <?php
                    echo Yii::$app->controller->renderPartial(
                        "users_listing",
                        array( "user_type" => $user_type )
                    );
                    ?>
                </div>
            <?php
            }
        }
        ?>
    </div>
</div>
