<?php

use app\helpers\PostHelper;
use app\modules\Users\helpers\UserHelper;
use yii\helpers\Url;
?>

<nav id="sidebar">
    <?php echo Yii::$app->controller->renderPartial( "/default/sidebar" ); ?>
</nav>

<div class="content with-sidebar">
    <?php
    if( ! empty( $practice_details ) && ! ( empty( $user_details ) ) && ! ( empty( $company_details ) ) )
    {
    ?>
        <div class="my-practice">
            <h1>
                <?php echo Yii::t( "app", "My_practice" ); ?>
            </h1>

            <div class="practice-details">
                <h3>
                    <?php echo Yii::t( "app", "Practice_details" ); ?>
                </h3>

                <div>
                    <span>
                        <?php echo Yii::t( "app", "Date" ) . ": "; ?>
                    </span>
                    <span>
                        <?php echo $practice_details->start_date . " - " . $practice_details->end_date; ?>
                    </span>
                </div>

                <div>
                    <span>
                        <?php echo Yii::t( "app", "Mark" ) . ": "; ?>
                    </span>
                    <span>
                        <?php echo ( ! empty( $practice_details->assn_details->mark ) ? $practice_details->assn_details->mark : "-" ); ?>
                    </span>
                </div>

                <div>
                    <span>
                        <?php echo Yii::t( "app", "Company_rating" ) . ": "; ?>
                    </span>
                    <span>
                        <?php echo ( ! empty( $practice_details->assn_details->company_rating ) ? $practice_details->assn_details->company_rating: "-" ); ?>
                    </span>
                </div>

                <div>
                    <span>
                        <?php echo Yii::t( "app", "My_rating" ) . ": "; ?>
                    </span>
                    <span>
                        <?php echo ( ! empty( $practice_details->assn_details->user_rating ) ? $practice_details->assn_details->user_rating: "-" ); ?>
                    </span>
                </div>
            </div>

            <div class="student-details">
                <h3>
                    <?php echo Yii::t( "app", "Student_details" ); ?>
                </h3>

                <div>
                    <span>
                        <?php echo Yii::t( "app", "Student_name" ) . ": "; ?>
                    </span>
                    <span>
                        <?php echo UserHelper::Get_user_name(); ?>
                    </span>
                </div>

                <div>
                    <span>
                        <?php echo Yii::t( "app", "Department_name" ) . ": "; ?>
                    </span>
                    <span>
                        <?php echo ! empty(  $user_details->department_details->name ) ? $user_details->department_details->name : ""; ?>
                    </span>
                </div>

                <div>
                    <span>
                        <?php echo Yii::t( "app", "Year_name" ) . ": "; ?>
                    </span>
                    <span>
                        <?php echo $user_details->year_details->name; ?>
                    </span>
                </div>
            </div>

            <div class="company-details">
                <h3>
                    <?php echo Yii::t( "app", "Company_details" ); ?>
                </h3>

                <div>
                    <span>
                        <?php echo Yii::t( "app", "Company_name" ) . ": "; ?>
                    </span>
                    <span>
                        <?php echo $company_details->name; ?>
                    </span>
                </div>

                <div>
                    <span>
                        <?php echo Yii::t( "app", "Address" ) . ": "; ?>
                    </span>
                    <span>
                        <?php echo $practice_details->city . " " . $practice_details->address; ?>
                    </span>
                </div>

                <div>
                    <span>
                        <?php echo Yii::t( "app", "Leader" ) . ": "; ?>
                    </span>
                    <span>
                        <?php echo UserHelper::Get_user_name_by_id( $practice_details->user_id ); ?>
                    </span>
                </div>
            </div>

            <div class="practice-actions">
                <h3>
                    <?php echo Yii::t( "app", "Practice_actions" ); ?>
                </h3>

                <div>
                    <?php
                    if( ! empty( $practice_details->assn_details->report ) )
                    {
                    ?>
                        <div>
                            <span>
                                <?php echo Yii::t( "app", "Report" ) . ": "; ?>
                            </span>
                            <a href="<?php echo Yii::getAlias( "@imgUrl" ) . $practice_details->assn_details->report ?>"
                                data-fancybox="gallery">
                                <?php echo Yii::t( "app", "Uploaded_file" ); ?>
                            </a>
                        </div>
                    <?php
                    }
                    ?>

                    <form action="" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="<?php echo Yii::$app->request->csrfParam; ?>" value="<?php echo Yii::$app->request->csrfToken; ?>" />
                        <div class="form-group">
                            <div>
                                <label for="report">
                                    <?php echo Yii::t( "app", "Upload" ); ?>
                                </label>
                            </div>
                            <div>
                                <input type="file" name="file" id="report"
                                    value="<?php echo PostHelper::Get( "report", $practice_details->assn_details ); ?>"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <div>
                                <label for="rating">
                                    <?php echo Yii::t( "app", "Rating" ); ?>
                                </label>
                            </div>
                            <div class="select">
                                <div>
                                    <select name="rating" id="rating">
                                        <option value="0"></option>
                                        <?php
                                        for( $index = 1; $index <= 10; $index++ )
                                        {
                                        ?>
                                            <option value="<?php echo $index ?>"
                                                <?php echo $practice_details->assn_details->user_rating == $index ? 'selected="selected"' : "" ?>>
                                                <?php echo $index; ?>
                                            </option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div>
                            <input type="submit" class="btn btn-success" value="<?php echo Yii::t( "app", "Save" ); ?>">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php
    }
    ?>
</div>