jQuery( document ).ready( function ( $ ) {
  if( $( '.settings_page_noo-member-settings' ).length ) {
    var $subscribe_type = $( '#noo_subscribe_type' );
    $subscribe_type.bind('toggle_children', function() {
      $this = $(this);

      var value = $this.find( 'input[type=radio]:checked' ).val();
      
      if( value == 'subscribe' ) {
        $('#amount').closest('tr').show();
        $('#period').closest('tr').show();
        $('#trial').closest('tr').show();
        $('#trial').trigger('toggle_children');
        $('#subscribe_page').closest('tr').show();
      } else {
        $('#amount').closest('tr').hide();
        $('#period').closest('tr').hide();
        $('#trial').closest('tr').hide();
        $('#trial').trigger('toggle_children');
        $('#subscribe_page').closest('tr').hide();
      }
    });

    $subscribe_type.trigger('toggle_children');
    $subscribe_type.find('input').click( function() {
      $subscribe_type.trigger("toggle_children");
    });

    var $subscribe_trial = $( '#trial' );
    $subscribe_trial.bind('toggle_children', function(){
      $this = $(this);
      if(!$this.is(':visible')) {
        $('#trial_period').closest('tr').hide();

        return;
      }

      var value = $this.is(':checked');
      if( value ) {
        $('#trial_period').closest('tr').show();
      } else {
        $('#trial_period').closest('tr').hide();
      }
    });

    $subscribe_trial.trigger('toggle_children');
    $subscribe_trial.click( function() {
      $subscribe_trial.trigger("toggle_children");
    });
  }
} );

