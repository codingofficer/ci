jQuery(document).ready(function($){

  let frame;

  $('#upload_author_avatar').on('click', function(e){
    e.preventDefault();

    if (frame) {
      frame.open();
      return;
    }

    frame = wp.media({
      title: 'Select or Upload Avatar',
      button: { text: 'Use this image' },
      multiple: false
    });

    frame.on('select', function(){
      const attachment = frame.state().get('selection').first().toJSON();

      $('#author_avatar_id').val(attachment.id);

      $('#author-avatar-preview').html(
        '<img src="'+attachment.sizes.thumbnail.url+'" style="max-width:120px;border-radius:8px;" />'
      );
    });

    frame.open();
  });

  $('#remove_author_avatar').on('click', function(){
    $('#author_avatar_id').val('');
    $('#author-avatar-preview').html('');
  });

});