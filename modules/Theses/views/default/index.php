<nav id="sidebar">
    <?php echo Yii::$app->controller->renderPartial( "/default/sidebar" ); ?>
</nav>

<div class="content with-sidebar">
    <h1>
        <?php echo Yii::t( "app", "Theses" ); ?>
    </h1>

    <div id="tabs">
        <ul>
            <?php
            if( ! empty( $departments ) )
            {
                foreach( $departments as $department )
                {
                ?>
                    <li>
                        <a href="#tab-<?php echo $department->id ?>">
                            <?php echo Yii::t( "app", $department->name ); ?>
                        </a>
                    </li>
                <?php
                }
            }
            ?>
        </ul>
        <?php
        if( ! empty( $departments ) )
        {
            foreach( $departments as $department )
            {
            ?>
                <div id="tab-<?php echo $department->id; ?>">
                    <?php
                    echo Yii::$app->controller->renderPartial(
                            "theses_listing",
                            array( "department" => $department )
                    );
                    ?>
                </div>
            <?php
            }
        }
        ?>
    </div>
</div>
