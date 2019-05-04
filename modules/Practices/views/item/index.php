<?php
use \app\helpers\PostHelper;
use app\modules\Events\helpers\EventHelper;
use app\modules\Practices\helpers\PracticeHelper;
use yii\helpers\Url;

$practice_details   = ( ! empty( $practice_details ) ? $practice_details : "" );
$page_title         = ( ! empty( $practice_details ) ? "Modify_practice" : "Add_new_practice" );
$is_public          = ( ! empty( $practice_details->is_public ) ? 1 : 0 );
$last_url           = PracticeHelper::Get_url();
$delete_image_url   = PracticeHelper::Get_image_delete_url( $practice_details );
?>
<nav id="sidebar">
    <?php echo Yii::$app->controller->renderPartial( "/default/sidebar" ); ?>
</nav>

<div class="content with-sidebar">
    <h1>
        <?php echo Yii::t( "app", $page_title ); ?>
    </h1>

    <form action="" method="post" enctype="multipart/form-data">
        <input type="hidden" name="<?php echo Yii::$app->request->csrfParam; ?>" value="<?php echo Yii::$app->request->csrfToken; ?>" />
        <div class="form-group">
            <div>
                <label for="name">
                    <?php echo Yii::t( "app", "Name" ); ?>
                </label>
            </div>
            <div class="input-text">
                <input type="text" name="name" id="name"
                    value="<?php echo PostHelper::Get( "name", $practice_details ); ?>"/>
            </div>
        </div>

        <div class="form-group">
            <div>
                <label for="country">
                    <?php echo Yii::t( "app", "Country" ); ?>
                </label>
            </div>
            <div class="input-text">
                <input type="text" name="country" id="country"
                    value="<?php echo PostHelper::Get( "country", $practice_details ); ?>"/>
            </div>
        </div>

        <div class="form-group">
            <div>
                <label for="city">
                    <?php echo Yii::t( "app", "City" ); ?>
                </label>
            </div>
            <div class="input-text">
                <input type="text" name="city" id="city"
                    value="<?php echo PostHelper::Get( "city", $practice_details ); ?>"/>
            </div>
        </div>

        <div class="form-group">
            <div>
                <label for="address">
                    <?php echo Yii::t( "app", "Address" ); ?>
                </label>
            </div>
            <div class="input-text">
                <input type="text" name="address" id="address"
                    value="<?php echo PostHelper::Get( "address", $practice_details ); ?>"/>
            </div>
        </div>

        <div class="form-group">
            <div>
                <label for="start_date">
                    <?php echo Yii::t( "app", "Start_date" ); ?>
                </label>
            </div>
            <div class="input-text">
                <input type="text" name="start_date" id="start_date"
                    value="<?php echo PostHelper::Get( "start_date", $practice_details ); ?>"/>
            </div>
        </div>

        <div class="form-group">
            <div>
                <label for="end_date">
                    <?php echo Yii::t( "app", "End_date" ); ?>
                </label>
            </div>
            <div class="input-text">
                <input type="text" name="end_date" id="end_date"
                    value="<?php echo PostHelper::Get( "end_date", $practice_details ); ?>"/>
            </div>
        </div>

        <div class="form-group">
            <div>
                <label for="deadline_date">
                    <?php echo Yii::t( "app", "Deadline_date" ); ?>
                </label>
            </div>
            <div class="input-text">
                <input type="text" name="deadline_date" id="deadline_date"
                    value="<?php echo PostHelper::Get( "deadline_date", $practice_details ); ?>"/>
            </div>
        </div>

        <div class="form-group">
            <div>
                <label for="max_participants">
                    <?php echo Yii::t( "app", "Max_participants" ); ?>
                </label>
            </div>
            <div>
                <input type="text" name="max_participants" id="max_participants"
                    value="<?php echo PostHelper::Get( "max_participants", $practice_details ); ?>"/>
            </div>
        </div>

        <?php
        if( ! empty( $practice_details ) )
        {
        ?>
            <div class="form-group">
                <div>
                    <label for="image">
                        <?php echo Yii::t( "app", "Image" ); ?>
                    </label>
                </div>
                <div>
                    <input type="file" name="image" id="image"
                        value="<?php echo PostHelper::Get( "image", $practice_details ); ?>"/>
                </div>
            </div>

            <?php
            if( ! empty( $practice_details->image ) )
            {
            ?>
                <div class="form-group">
                    <div class="image-preview">
                        <img src="<?php echo Yii::getAlias( "@imgUrl" ) . $practice_details->image; ?>" alt />
                    </div>
                    <div>
                        <a href="<?php echo $delete_image_url; ?>" class="btn btn-danger">
                            <?php echo Yii::t( "app", "Delete_image" ); ?>
                        </a>
                    </div>
                </div>
            <?php
            }
            ?>
        <?php
        }
        ?>

        <div class="form-group">
            <div>
                <label for="description">
                    <?php echo Yii::t( "app", "Description" ); ?>
                </label>
            </div>
            <div>
                <textarea name="description" id="description"><?php echo PostHelper::Get("description", $practice_details ) ?></textarea>
            </div>
        </div>

        <div>
            <input type="submit" class="btn btn-primary" value="<?php echo Yii::t( "app", "Save" ); ?>">
            <a href="<?php echo $last_url; ?>" class="btn btn-danger">
                <?php echo Yii::t( "app", "Back" ); ?>
            </a>
        </div>
    </form>
</div>