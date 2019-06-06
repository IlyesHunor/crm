<?php
use \app\helpers\PostHelper;
use app\modules\Theses\helpers\ThesisHelper;
use yii\helpers\Url;

$thesis_details   = ( ! empty( $thesis_details ) ? $thesis_details : "" );
$page_title         = ( ! empty( $thesis_details ) ? "Modify_thesis" : "Add_new_thesis" );
$is_public          = ( ! empty( $thesis_details->is_public ) ? 1 : 0 );
$last_url           = ThesisHelper::Get_url();
$delete_file_url    = ThesisHelper::Get_file_delete_url( $thesis_details );
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

        <div id="tabs">
            <ul>
                <li>
                    <a href="#tab1">
                        <?php echo Yii::t( "app", "Details" ); ?>
                    </a>
                </li>
                <li>
                    <a href="#tab2">
                        <?php echo Yii::t( "app", "Description" ); ?>
                    </a>
                </li>
                <li>
                    <a href="#tab3">
                        <?php echo Yii::t( "app", "Files" ); ?>
                    </a>
                </li>
            </ul>

            <div id="tab1">
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
                        <select id="department" name="department_id">
                            <option value=""></option>
                            <?php
                            if( ! empty( $departments ) )
                            {
                                foreach( $departments as $department )
                                {
                                    ?>
                                    <option value="<?php echo $department->id; ?>"
                                        <?php echo ( $department->id == PostHelper::Get_integer( "department_id", $thesis_details ) ? 'selected="selected"' : "" ); ?>>
                                        <?php echo Yii::t( "app", $department->name ); ?>
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
                        <label for="with_company">
                            <?php echo Yii::t( "app", "Include_company" ); ?>
                        </label>
                    </div>
                    <div>
                        <input type="checkbox" id="with_company" name="with_company" class="include-company"
                            <?php echo ( ! empty( $thesis_details->company_id ) ? 'checked="checked"' : "" ); ?>>
                    </div>
                </div>

                <div class="form-group select-company <?php echo ( empty( $thesis_details->company_id ) ? "hidden" : "" ) ?>">
                    <div>
                        <label for="company">
                            <?php echo Yii::t( "app", "Company" ); ?>
                        </label>
                    </div>
                    <div class="select">
                        <select id="company" name="company_id">
                            <option value=""></option>
                            <?php
                            if( ! empty( $companies ) )
                            {
                                foreach( $companies as $company )
                                {
                                    ?>
                                    <option value="<?php echo $company->id; ?>"
                                        <?php echo ( $company->id == PostHelper::Get_integer( "company_id", $thesis_details ) ? 'selected="selected"' : "" ); ?>>
                                        <?php echo Yii::t( "app", $company->name ); ?>
                                    </option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <div id="tab2">
                <div class="form-group">
                    <div>
                        <label for="description">
                            <?php echo Yii::t( "app", "Description" ); ?>
                        </label>
                    </div>
                    <div class="textarea">
                        <textarea name="description" id="description"><?php echo PostHelper::Get("description", $thesis_details ) ?></textarea>
                    </div>
                </div>
            </div>
            <div id="tab3">
                <?php
                if( ! empty( $thesis_details ) )
                {
                    ?>
                    <div class="form-group">
                        <div>
                            <label for="file">
                                <?php echo Yii::t( "app", "Document" ); ?>
                            </label>
                        </div>
                        <div>
                            <input type="file" name="file" id="file"
                                   value="<?php echo PostHelper::Get( "file", $thesis_details ); ?>"/>
                        </div>
                    </div>

                    <?php
                    if( ! empty( $thesis_details->file ) )
                    {
                        ?>
                        <div class="form-group">
                            <div>
                                <label for="file">
                                    <?php echo Yii::t( "app", "Actions" ); ?>
                                </label>
                            </div>
                            <div>
                                <a href="<?php echo Yii::getAlias( "@imgUrl" ).$thesis_details->file; ?>"
                                   class="btn btn-primary fancybox" data-fancybox="gallery">
                                    <i class="icon-view">&nbsp;</i>
                                    <?php echo Yii::t( "app", "View" ); ?>
                                </a>
                                <a href="<?php echo $delete_file_url; ?>" class="btn btn-danger confirm-delete">
                                    <i class="icon-delete">&nbsp;</i>
                                    <?php echo Yii::t( "app", "Delete" ); ?>
                                </a>
                            </div>
                        </div>
                    <?php
                    }
                }
                ?>
            </div>
        </div>

        <div>
            <input type="submit" class="btn btn-success" value="<?php echo Yii::t( "app", "Save" ); ?>">
            <a href="<?php echo $last_url; ?>" class="btn btn-danger">
                <?php echo Yii::t( "app", "Back" ); ?>
            </a>
        </div>
    </form>
</div>