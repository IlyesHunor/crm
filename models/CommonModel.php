<?php

namespace app\models;

use Yii;

class CommonModel extends \yii\db\ActiveRecord
{
    public function Xss_clean( $attribute, $params, $validator )
    {
        if( empty( $this->$attribute ) )
        {
            return;
        }

        if( ! $this->Php_clean( $this->$attribute ) )
        {
            $this->addError( $attribute, Yii::t( "app", "Validation_xss" ) );
        }

        if( ! $this->Javascript_clean( $this->$attribute ) )
        {
            $this->addError( $attribute, Yii::t( "app", "Validation_xss" ) );
        }

        if( ! $this->Html_clean( $this->$attribute ) )
        {
            $this->addError( $attribute, Yii::t( "app", "Validation_xss" ) );
        }
    }

    private function Php_clean( $value )
    {
        if( strpos( $value, "<?" ) !== false )
        {
            return false;
        }

        if( preg_match( "/(.*)\<\?(.*)/", $value ) )
        {
            return false;
        }

        if( preg_match( "/(.*)\?\>(.*)/", $value ) )
        {
            return false;
        }

        return true;
    }

    private function Javascript_clean( $value )
    {
        if( strpos( $value, "<script" ) !== false )
        {
            return false;
        }

        if( preg_match( "/(.*)\<script(.*)\>(.*)/", $value ) )
        {
            return false;
        }

        if( preg_match( "/(.*)\<\/script\>(.*)/", $value ) )
        {
            return false;
        }

        if( preg_match( "/onclick|onmouse|onload\=/", $value ) )
        {
            return false;
        }

        return true;
    }

    private function Html_clean( $value )
    {
        if( preg_match( "/(.*)\<\!\-\-(.*)/", $value ) )
        {
            return false;
        }

        if( preg_match( "/(.*)\-\-\>(.*)/", $value ) )
        {
            return false;
        }

        return true;
    }
}
