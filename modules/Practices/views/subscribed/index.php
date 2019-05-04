<nav id="sidebar">
    <?php echo Yii::$app->controller->renderPartial( "sidebar" ); ?>
</nav>

<div class="content with-sidebar">
    <h1>
        <?php echo Yii::t( "app", "Subscribed_practices" ); ?>
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
                    <?php echo Yii::t( "app", "Subscription_date" ); ?>
                </th>
                <th scope="col">
                    <?php echo Yii::t( "app", "Is_accepted" ); ?>
                </th>
            </tr>
        </thead>
        <tbody>
            <?php
            if( ! empty( $practices ) )
            {
                foreach( $practices as $index => $practice )
                {
                ?>
                    <tr>
                        <th scope="row">
                            <?php echo $index + 1; ?>
                        </th>
                        <td>
                            <?php echo $practice->practice_name; ?>
                        </td>
                        <td>
                            <?php echo $practice->insert_date; ?>
                        </td>
                        <td>
                            <?php echo "ok"; ?>
                        </td>
                    </tr>
                <?php
                }
            }
            ?>
        </tbody>
    </table>
</div>