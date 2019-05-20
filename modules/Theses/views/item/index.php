<?php
use \app\helpers\PostHelper;
use app\modules\Events\helpers\EventHelper;
use yii\helpers\Url;

$thesis_details   = ( ! empty( $thesis_details ) ? $thesis_details : "" );
$page_title         = ( ! empty( $thesis_details ) ? "Modify_thesis" : "Add_new_thesis" );
$is_public          = ( ! empty( $thesis_details->is_public ) ? 1 : 0 );
$last_url           = "";//ThesisHelper::Get_url();
$delete_image_url   = "";//ThesisHelper::Get_image_delete_url( $thesis_details );
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
                    value="<?php echo PostHelper::Get( "name", $thesis_details ); ?>"/>
            </div>
        </div>

        <div class="form-group">
            <div>
                <label for="department">
                    <?php echo Yii::t( "app", "Department" ); ?>
                </label>
            </div>
            <div class="select">
                <select id="department">
                    <option value=""></option>
                    <?php
                    if( ! empty( $departments ) )
                    {
                        foreach( $departments as $department )
                        {
                        ?>
                            <option value="<?php echo $department->id; ?>">
                                <?php echo Yii::t( "app", $department->name ); ?>
                            </option>
                        <?php
                        }
                    }
                    ?>
                </select>
            </div>
        </div>

        <?php
        if( ! empty( $thesis_details ) )
        {
        ?>
            <div class="form-group">
                <div>
                    <label for="file">
                        <?php echo Yii::t( "app", "Image" ); ?>
                    </label>
                </div>
                <div>
                    <input type="file" name="file" id="file"
                        value="<?php echo PostHelper::Get( "file", $thesis_details ); ?>"/>
                </div>
            </div>
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
                <textarea name="description" id="description"><?php echo PostHelper::Get("description", $thesis_details ) ?></textarea>
            </div>
        </div>

        <div class="form-group">
            <div>
                <label for="with_company">
                    <?php echo Yii::t( "app", "With_company" ); ?>
                </label>
            </div>
            <div>
                <input type="checkbox" id="with_company" name="with_company">
            </div>
        </div>

        <div class="form-group">
            <div>
                <label for="company">
                    <?php echo Yii::t( "app", "Department" ); ?>
                </label>
            </div>
            <div class="select">
                <select id="company">
                    <option value=""></option>
                    <?php
                    if( ! empty( $companies ) )
                    {
                        foreach( $companies as $company )
                        {
                        ?>
                            <option value="<?php echo $company->id; ?>">
                                <?php echo Yii::t( "app", $company->name ); ?>
                            </option>
                        <?php
                        }
                    }
                    ?>
                </select>
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