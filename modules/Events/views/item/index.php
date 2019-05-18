<?php
use \app\helpers\PostHelper;
use app\modules\Events\helpers\EventHelper;
use yii\helpers\Url;

$event_details      = ( ! empty( $event_details ) ? $event_details : "" );
$page_title         = ( ! empty( $event_details ) ? "Modify_event" : "Add_new_event" );
$is_public          = ( ! empty( $event_details->is_public ) ? 1 : 0 );
$last_url           = EventHelper::Get_url();
$delete_image_url   = EventHelper::Get_image_delete_url( $event_details );
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
                    value="<?php echo PostHelper::Get( "name", $event_details ); ?>"/>
            </div>
        </div>

        <div class="form-group">
            <div>
                <label for="event_category">
                    <?php echo Yii::t( "app", "Name" ); ?>
                </label>
            </div>
            <div>
                <select name="event_category_id" id="event_category">
                    <?php
                    if( ! empty( $event_categories ) )
                    {
                        $selected_category = PostHelper::Get_integer( "event_category_id", $event_details );

                        foreach( $event_categories as $event_category )
                        {
                        ?>
                            <option value="<?php echo $event_category->id; ?>"
                                <?php echo ( ( $selected_category == $event_category->id ) ? 'selected="selected"' : "" ); ?>>
                                <?php echo Yii::t( "app", $event_category->name ); ?>
                            </option>
                        <?php
                        }
                    }
                    ?>
                </select>
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
                    value="<?php echo PostHelper::Get( "country", $event_details ); ?>"/>
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
                    value="<?php echo PostHelper::Get( "city", $event_details ); ?>"/>
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
                    value="<?php echo PostHelper::Get( "address", $event_details ); ?>"/>
            </div>
        </div>

        <div class="form-group">
            <div>
                <label for="institution">
                    <?php echo Yii::t( "app", "Institution" ); ?>
                </label>
            </div>
            <div class="input-text">
                <input type="text" name="institution" id="institution"
                    value="<?php echo PostHelper::Get( "institution", $event_details ); ?>"/>
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
                    value="<?php echo PostHelper::Get( "start_date", $event_details ); ?>"/>
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
                    value="<?php echo PostHelper::Get( "end_date", $event_details ); ?>"/>
            </div>
        </div>

        <div class="form-group">
            <div>
                <label for="is_public">
                    <?php echo Yii::t( "app", "Is_public" ); ?>
                </label>
            </div>
            <div>
                <input type="checkbox" name="is_public" id="is_public" value="1"
                    <?php echo ( ! empty( $is_public ) ? 'checked="checked"' : "" ); ?>/>
            </div>
        </div>

        <?php
        if( ! empty( $event_details ) )
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
                        value="<?php echo PostHelper::Get( "image", $event_details ); ?>"/>
                </div>
            </div>

            <?php
            if( ! empty( $event_details->image ) )
            {
            ?>
                <div class="form-group">
                    <div class="image-preview">
                        <img src="<?php echo Yii::getAlias( "@imgUrl" ) . $event_details->image; ?>" alt />
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
                <textarea name="description" id="description"><?php echo PostHelper::Get("description", $event_details ) ?></textarea>
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