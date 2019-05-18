$(document).ready(function ()
{
    init_notifications();
    init_mark_notification_as_read();

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

    $( "#overlay" ).show();

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