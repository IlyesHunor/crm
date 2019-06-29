<div style="margin-top: 30px; font-size: 0; display: block">
    <div style="display: inline-block; font-size: 16px; width: 50%">
        <div>
            <span>
                <?php echo Yii::t( "app", "Universitate" ); ?>
            </span>
        </div>
        <?php
        if( ! empty( $data->teacher_sign ) )
        {
        ?>
            <img src="<?php echo Yii::getAlias( "@imgPath" ) . $data->teacher_sign; ?>" alt=""/>
        <?php
        }
        ?>
    </div>
    <div style="display: inline-block; font-size: 16px; width: 50%; text-align: right;">
        <div>
            <span>
                <?php echo Yii::t( "app", "Compania" ); ?>
            </span>
        </div>
        <?php
        if( ! empty( $data->company_sign ) )
        {
        ?>
            <img src="<?php echo Yii::getAlias( "@imgPath" ) . $data->company_sign; ?>" alt=""/>
        <?php
        }
        ?>
    </div>
</div>
