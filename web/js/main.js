$(document).ready(function ()
{
    init_notifications();
    init_mark_notification_as_read();
    main_handlers_for_digital_signing();
    init_download_pdf();
    init_save_mark();

    $('#sidebarCollapse').on('click', function () {
        $('#sidebar').toggleClass('active');
    });
});

function send_post_request( url, parameters, callback_function, post_type )
{
    if( url == null || url == "" )
    {
        return false;
    }

    parameters              = get_parameters( parameters );
    parameters["post_type"] = post_type;

    $.post( url, parameters, function(data){
        if( post_type == "json" )
        {
            data = $.parseJSON( data )
        }

        if( callback_function != null && callback_function != "" )
        {
            callback_function( data );
        }

        /*$( "#overlay" ).hide();*/
    });
}

function get_parameters( parameters )
{
    let new_parameters = {};

    if( parameters != null && parameters != "" )
    {
        new_parameters = parameters;
    }

    new_parameters["whit_ajax"] = 1;

    return new_parameters;
}

function init_notifications()
{
    $( "#notification-button" ).click(function(){
        let list = $( ".notifications-listing" );

        if( $( list ).hasClass( "opened" ) )
        {
            $( list ).hide( "slow" );
            $( list ).removeClass( "opened" );

            return false;
        }

        $( list ).show( "slow" );
        $( list ).addClass( "opened" );
    });
}

function init_mark_notification_as_read()
{
    $( ".notifications-listing .read" ).click(function(){
        let parameters = get_parameters_for_notification( this );

        mark_notification_as_read( parameters, this );
    });
}

function get_parameters_for_notification( item )
{
    let parameters                  = {};
    parameters["notification_id"]   = $( item ).attr( "data-id" );
    parameters["readed"]            = ( $( item ).parent().hasClass( "readed" ) ? 1 : 0 );

    return parameters;
}

function mark_notification_as_read( parameters, item )
{
    $( "#overlay" ).show();

    send_post_request(
        base_url + "/ajax/set_notification_read",
        parameters,
        function( result ){
            if( result.status != "success" )
            {
                return;
            }

            if( $( item ).parent().hasClass( "unreaded" ) )
            {
                $( item ).parent().removeClass( "unreaded" );
                $( item ).parent().addClass( "readed" );
            }

            function go_to()
            {
                window.location.href = result.link;

                $( "#overlay" ).hide();
            };

            window.setTimeout( go_to, 500 );
        },
        "json"
    );
}

let mouse_pressed = false;
let last_x;
let last_y;

function main_handlers_for_digital_signing()
{
    let signing = $( "#sign" );

    if( signing.length == 0 )
    {
        return false;
    }

    let context = signing[0].getContext( "2d" );

    init_signing( signing, context );
    init_clear_signing_box( context );
}

function init_signing( signing, context )
{
    let parent = signing.parent();

    signing[0].width    = parent.width();
    signing[0].height   = 150;

    $( signing ).mousedown( function ( event ){
        event.preventDefault();
        event.stopPropagation();

        mouse_pressed = true;

        Draw( event.pageX - $( this ).offset().left, event.pageY - $( this ).offset().top, false, context );
    });

    $( signing ).mousemove( function ( event ){
        event.preventDefault();
        event.stopPropagation();

        if( ! mouse_pressed )
        {
            return false;
        }

        Draw( event.pageX - $( this ).offset().left, event.pageY - $( this ).offset().top, true, context );
    });

    $( signing ).mouseup( function ( event ){
        event.preventDefault();
        event.stopPropagation();

        mouse_pressed = false;

        save_signing_image();
    });

    $( signing ).mouseleave( function ( event ){
        event.preventDefault();
        event.stopPropagation();

        mouse_pressed = false;

        save_signing_image();
    });
}

function Draw( x, y, is_down, context )
{
    if( is_down )
    {
        context.beginPath();

        context.strokeStyle = "#000";
        context.lineWidth   = "1";
        context.lineJoin    = "round";

        context.moveTo( last_x, last_y );
        context.lineTo( x, y );
        context.closePath();
        context.stroke();
    }

    last_x  = x;
    last_y  = y;

    $( ".clear-signing-box" ).show( "slow" );
}

function init_clear_signing_box( context )
{
    $( ".clear-signing-box" ).click(function(){
        context.setTransform( 1, 0, 0, 1, 0, 0 );
        context.clearRect( 0, 0, context.canvas.width, context.canvas.height );

        $( this ).hide( "slow" );
    });
}

function save_signing_image()
{
    let input       = $( 'input[name="signing-image"]' );
    let image       = $( "#sign" )[0].toDataURL( "image/png" );
    let input_type  = $( 'input[name="type"]' );

    input.val( image );
}

function init_download_pdf()
{
    $( ".download_pdf" ).click(function( event ){
        let parameters = get_parameters_for_download_pdf( this );

        generate_and_download_pdf( parameters, this );
    });
}

function get_parameters_for_download_pdf( item )
{
    let parameters                  = {};
    parameters["practice_assn_id"]  = $( item ).attr( "data-practice-assn-id" );

    return parameters;
}

function generate_and_download_pdf( parameters, item )
{
    send_post_request(
        base_url + "/ajax/generate_pdf",
        parameters,
        function( result ){
            if( result.status != "success" )
            {
                return;
            }

            init_download( result.download_url );
        },
        "json"
    );
}

function init_download( url )
{
    let element = document.createElement( "a" );

    $( element ).attr( 'href', url );
    $( element ).attr( 'download', "Contract.pdf" );
    $( element ).css( "display", "none" );

    document.body.appendChild( element );

    element.click();

    document.body.removeChild( element );
}

function init_save_mark()
{
    $( ".save-mark" ).click( function(){
        $( "#overlay" ).show();
        let parameters = get_parameters_for_add_mark( this );

        save_mark( parameters );
    });
}

function get_parameters_for_add_mark( item )
{
    let parameters                  = {}
    parameters["practice_assn_id"]  = $( item ).attr( "data-practice-assn-id" );
    parameters["department_id"]     = $( item ).attr( "data-department-id" );
    parameters["mark"]              = $( item ).parent().find( 'input[name="mark"]' ).val();

    return parameters;
}

function save_mark( parameters )
{
    send_post_request(
        base_url + "/ajax/save_mark",
        parameters,
        function( result ){
            $( "#overlay" ).hide();

            if( result.status != "success" )
            {
                return;
            }
        },
        "json"
    );
}